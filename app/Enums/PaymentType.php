<?php
namespace App\Enums;

class PaymentType {

    public const CASH = "1";
    public const CHEUQUE = "2";
    public const BANK_DEPOSITE = "3";

    public static function getAllValues(): array
    {
        return [
            self::CASH,
            self::CHEUQUE,
            self::BANK_DEPOSITE
        ];
    }
}
