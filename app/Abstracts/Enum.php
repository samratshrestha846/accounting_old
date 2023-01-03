<?php
namespace App\Abstracts;

use ReflectionClass;

abstract class Enum {

    public static function getAllValues(): array
    {
        $refl = new ReflectionClass(get_called_class());
        $consts = $refl->getConstants();

        $data = [];

        foreach($consts as $constant) {
            $data[] = $constant;
        }

        return $data;
    }
}
