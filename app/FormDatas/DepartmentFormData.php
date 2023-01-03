<?php
namespace App\FormDatas;

class DepartmentFormData {

    public string $name;
    public string $code;
    public string $location;
    public ?int $deviceId;
    public ?int $parentDept;

    public function __construct(
        string $name,
        string $code,
        string $location,
        ?int $deviceId = null,
        ?int $parentDept = null
    )
    {
        $this->name = $name;
        $this->code = $code;
        $this->location = $location;
        $this->deviceId = $deviceId;
        $this->parentDept = $parentDept;
    }
}
