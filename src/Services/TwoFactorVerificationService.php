<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Services;

use Assghard\Laravel2fa\Enums\TwoFactorVerificationMethodsEnum;
use Assghard\Laravel2fa\Providers\EmailProvider;
use Assghard\Laravel2fa\Providers\SmsApiPlProvider;
use Illuminate\Database\Eloquent\Model;
use Assghard\Laravel2fa\Models\TwoFactorVerificationCode;
use Illuminate\Support\Str;

class TwoFactorVerificationService
{
    public function sendUserTwoFactorVerificationCode(Model $user, TwoFactorVerificationMethodsEnum $verificationMethod, int $length = 6, bool $useLetters = false): bool
    {
        $twoFactorCodeEntity = $this->createUserVerificationCode($user, $length, $useLetters);
        if (empty($twoFactorCodeEntity)) {
            return false;
        }

        return $this->sendUserNotification($verificationMethod, $user, $twoFactorCodeEntity->code);
    }

    public function findUser2faCode(Model $user, string|int $code): TwoFactorVerificationCode|null
    {
        return TwoFactorVerificationCode::where([
                'user_id' => $user->id,
                'code' => $code,
        ])->first();
    }

    public function findUserLast2faCode(Model $user): TwoFactorVerificationCode|null
    {
        return TwoFactorVerificationCode::where([
            'user_id' => $user->id
        ])->orderBy('id', 'DESC')->first();
    }

    protected function createUserVerificationCode(Model $user, int $length, bool $useLetters = false): TwoFactorVerificationCode|bool
    {
        $user->loadMissing('two_factor_verification_codes');
        if ($this->userCanCreateVerificationCode($user) === false) {
            return false;
        }
        
        if ($user->two_factor_verification_codes->isEmpty()) {
            $code = $this->generateCode($length, $useLetters);
        } else {
            while (true) { // who knows
                $code = $this->generateCode($length, $useLetters);
                $notUniqueCount = $user->two_factor_verification_codes->where('code', $code)->count();
                if ($notUniqueCount == 0) {
                    break;
                }
            }
        }

        $entity = $user->two_factor_verification_codes()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(config('2fa.user_code_valid_time')),
        ]);

        return $entity;
    }

    protected function generateCode(int $length = 6, bool $useLetters = false): string
    {
        if ($useLetters === true) {
            return Str::random($length);
        }

        $digits = [];
        for ($i = 0; $i < $length; $i++) {
            $digits[] = mt_rand(0, 9);
        }

        return implode('', $digits);
    }

    protected function sendUserNotification(TwoFactorVerificationMethodsEnum $verificationMethod, Model $user, string|int $code): bool
    {
        if ($verificationMethod == TwoFactorVerificationMethodsEnum::Email) {
            return (new EmailProvider)->sendNotification($user->email, __('2fa.messages.2fa_code').$code);
        } elseif($verificationMethod == TwoFactorVerificationMethodsEnum::Sms) {
            return (new SmsApiPlProvider(config('2fa.sms.api_token')))->sendNotification($user->phone_number, __('2fa.messages.2fa_code').$code);
        } else {
            return false;
        }
    }

    /**
     * In case of too much tries to resend verification code.
     * Limits:
     *  - max 1 code per 1 minute
     *  - max quantity of verification codes per day (from 2fa.php config)
     */
    protected function userCanCreateVerificationCode(Model $user, bool $resend = false): bool
    {
        if ($resend === true) {
            $result = $user->two_factor_verification_codes()->where('created_at', '>', now()->subMinutes(1))->count();
            if($result > 0){ // last 1 minute limit
                return false;
            }
        }

        $result = $user->two_factor_verification_codes()->whereDate('created_at', today())->count();
        if ($result > config('2fa.daily_user_codes_limit')) { // daily limit per uesr
            return false;
        }

        return true;
    }
}
