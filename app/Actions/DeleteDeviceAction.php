<?php
namespace App\Actions;

use App\Models\Device;

class DeleteDeviceAction {

    public function execute(Device $device)
    {
        if($device->hasDepartments()) {
            throw new \Exception("You cannot delete this device");
        }

        return $device->delete();
    }
}
