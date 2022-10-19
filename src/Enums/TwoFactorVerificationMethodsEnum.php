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
     * Get case description
     *
     * @return string
     */
    public function description(): string
    {
        return __('2fa.descriptions.'.$this->name);
    }

    // private static function mapCaseNameToValue($name)
    // {
    //     foreach (self::cases() as $case) {
    //         if ($case->name == $name) {
    //             return $case->value;
    //         }
    //     }

    //     return null;
    // }

    /**
     * Get key-value array with translated values for frontend select
     *
     * @return array
     */
    // public static function translatedArray($exeptNames = []): array
    // {
    //     $data = [];
    //     foreach (self::names() as $name) {
    //         if (in_array($name, $exeptNames)) {
    //             continue;
    //         }

    //         $data[self::mapCaseNameToValue($name)] = __('2fa.methods.'.$name);
    //     }

    //     return $data;
    // }
}
