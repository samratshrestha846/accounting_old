<?php
namespace App\Enums;

class ClientType {

    public const COMPANY = "Company";
    public const PERSON = "Person";

    public static function getAllValues(): array
    {
        return [
            self::COMPANY,
            self::PERSON
        ];
    }
}
