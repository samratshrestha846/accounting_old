<?php
namespace App\Helpers;

class HashPinNumber {

    public function make(string $q)
    {
        return base64_encode($this->getAttributeKey($q));
    }

    public function decryptIt(string $q): string
    {
        return base64_decode($this->getAttributeKey($q));
    }

    private function getAttributeKey(string $q): string
    {
        return md5($q);
    }
}
