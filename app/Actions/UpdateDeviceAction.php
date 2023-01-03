<?php
namespace App\Actions;

use App\FormDatas\DeviceFormData;
use App\Models\Device;

class UpdateDeviceAction {

    public function execute(Device $device, DeviceFormData $data): bool
    {
        return $device->update([
            'name' => $data->name,
            'serial_number' => $data->serialNumber,
            'ip_address' => $data->ipAddress,
            'area' => $data->area,
            'last_activity' => $data->lastActivity,
            'status' => $data->status
        ]);
    }
}
