<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Assghard\Laravel2fa\Models\TwoFactorVerificationCode;

trait UserTwoFactorVerificationTrait
{
    public function two_factor_verification_codes(): HasMany
    {
        return $this->hasMany(TwoFactorVerificationCode::class);
    }

    /**
     * Delete all user 2FA codes after successful login
     *
     * @return void
     */
    public function clear2faCodes()
    {
        $this->two_factor_verification_codes()->delete();
    }
}
