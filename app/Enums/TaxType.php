<?php
namespace App\Enums;

class TaxType {

    public const INCLUSIVE = "Inclusive";
    public const EXCLUSIVE = "Exclusive";
    public const NONE = "None";

    public static function getAllValues(): array
    {
        return [
            self::INCLUSIVE,
            self::EXCLUSIVE,
            self::NONE,
        ];
    }
}
