<?php
namespace App\FormDatas;

class PositionFormData {

    public string $name;
    public bool $status;

    public function __construct(
        string $name,
        bool $status = false
    )
    {
        $this->name = $name;
        $this->status = $status;
    }
}
