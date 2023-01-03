<?php
namespace App\Enums;

class DiscountType {

    public const FIXED = "Fixed";
    public const PERCENTAGE = "Percentage";
    public const INDIVIDUAL = "0";
    public const BULK = "1";
    public const BOTH = "2";
    public const NONE = "None";

    public static function getAllValues(): array
    {
        return [
            self::FIXED,
            self::PERCENTAGE,
            self::INDIVIDUAL,
            self::BULK,
            self::BOTH,
            self::NONE,
        ];
    }
}
