<?php
namespace App\FormDatas;

class DeviceFormData {

    public string $uuid;
    public string $name;
    public string $serialNumber;
    public string $ipAddress;
    public ?string $area;
    public ?string $lastActivity;
    public bool $status;
    
    public function __construct(
        string $uuid,
        string $name,
        string $serialNumber,
        string $ipAddress,
        string $area = null,
        string $lastActivity = null,
        bool $status = false
    )
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->serialNumber = $serialNumber;
        $this->ipAddress = $ipAddress;
        $this->area = $area;
        $this->lastActivity = $lastActivity;
        $this->status = $status;
    }
}
