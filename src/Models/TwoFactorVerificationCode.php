<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorVerificationCode extends Model
{
    protected $table = 'user_2fa_codes';
    protected $fillable = [
        'user_id',
        'code',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function hasExpired(): bool
    {
        return ($this->expires_at->isFuture()) ? false : true;
    }
}
