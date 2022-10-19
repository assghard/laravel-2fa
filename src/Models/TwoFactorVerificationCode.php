<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Models;

use Assghard\Laravel2fa\Enums\TwoFactorVerificationMethodsEnum;

class TwoFactorVerificationCode
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'type' => TwoFactorVerificationMethodsEnum::class,
    ];
}
