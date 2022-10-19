<?php

use Assghard\Laravel2fa\Enums\TwoFactorVerificationMethodsEnum;

return [
    
    /**
     * Enable 2FA for Users. After enabling this feature 2FA will be required
     */
    'enable' => env('2FA_ENABLE', false),

    /**
     * Default 2FA method
     * TwoFactorVerificationMethodsEnum::cases()
     */
    'default_method' => TwoFactorVerificationMethodsEnum::Email,

    /**
     * Allow user to change 2FA method
     */
    // 'allow_change_2fa_method' => env('ALLOW_CHANGE_2FA_METHOD', false),

    /**
     * After successful login all user codes are deleting, so user will have limit reseted
     */
    'daily_user_codes_limit' => 25,

    /**
     * Single code valid time in minutes.
     * expires_at = now() + user_code_valid_time
     */
    'user_code_valid_time' => 10,

];