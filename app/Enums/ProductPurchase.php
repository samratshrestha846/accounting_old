<?php
namespace App\Enums;

class ProductPurchase {

    public const PRIMARY_UNIT = "primary_unit";
    public const SECONDARY_UNIT = "secondary_unit";

    public static function getAllValues(): array
    {
        return [
            self::PRIMARY_UNIT,
            self::SECONDARY_UNIT,
        ];
    }
}
