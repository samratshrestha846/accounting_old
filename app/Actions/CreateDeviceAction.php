<?php
namespace App\Actions;

use App\FormDatas\DeviceFormData;
use App\Models\Device;

class CreateDeviceAction {

    public function execute(DeviceFormData $data): Device
    {
        return Device::create([
            'uuid' => $data->uuid,
            'name' => $data->name,
            'serial_number' => $data->serialNumber,
            'ip_address' => $data->ipAddress,
            'area' => $data->area,
            'last_activity' => $data->lastActivity,
            'status' => $data->status
        ]);
    }
}
