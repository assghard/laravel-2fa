<?php

declare(strict_types=1);

namespace Assghard\Laravel2fa\Enums;

use Assghard\Laravel2fa\Traits\BaseEnumTrait;

enum TwoFactorVerificationMethodsEnum: int
{
    use BaseEnumTrait;

    case Email = 1;
    case Sms = 2;

    /**
     * Get case name translation
     *
     * @return string
     */
    public function label(): string
    {
        return __('2fa.methods.'.$this->name);
    }

    /**
     * Get case description translation
     *
     * @return string
     */
    public function description(): string
    {
        return __('2fa.descriptions.'.$this->name);
    }
}
