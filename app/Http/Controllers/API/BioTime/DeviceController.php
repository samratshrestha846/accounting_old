<?php

namespace App\Http\Controllers\API\BioTime;

use App\Actions\CreateDeviceAction;
use App\Actions\UpdateDeviceAction;
use App\FormDatas\DeviceFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\BioTime\UpdateDeviceRequest;
use App\Http\Requests\BioTime\StoreDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Get a list of devices
     *
     * @queryParam per_page integer The perpage number. Example: 10
     * @queryParam page integer The page number. Example: 1
     *
     * @responseFile  responses/Device/devices.get.json
     * @responseFile 401 responses/401.get.json
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page;

        $devices = Device::query()->select('id','uuid','name','serial_number','ip_address','area','last_activity');

        if($perPage) {
            $devices = $devices->paginate($perPage);
        }else {
            $devices = $devices->get();
        }

        return DeviceResource::collection($devices);
    }

    /**
     * Create a New Device
     *
     * @bodyParam  uuid string required The uuid of the device. Example: 001
     * @bodyParam  name string required The name of the device. Example: Device 1
     * @bodyParam  serial_number string required The serial number of the device. Example: 202-123-232
     * @bodyParam  ip_address string required The ip address of the device. Example: 127.0.0.1:8000
     * @bodyParam  area string required The area of the device. Example: 127.0.0.1:8000
     * @bodyParam  last_activity string nullable The last activity date of the device. Example: 2021-03-03
     * @bodyParam  status boolean The status of the device. Example: true
     *
     * @responseFile  responses/Device/created.get.json
     * @responseFile 401 responses/401.get.json
     */
    public function store(StoreDeviceRequest $request)
    {
        $deviceFormData = new DeviceFormData(
            $request->uuid,
            $request->name,
            $request->serial_number,
            $request->ip_address,
            $request->area,
            $request->last_activity,
        );

        $device = (new CreateDeviceAction)->execute($deviceFormData);

        return $this->responseOk(
            "Device created successfully",
            DeviceResource::make($device),
            201,
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update Device
     *
     * @bodyParam  name string required The name of the device. Example: Device 1
     * @bodyParam  serial_number string required The serial number of the device. Example: 202-123-232
     * @bodyParam  ip_address string required The ip address of the device. Example: 127.0.0.1:8000
     * @bodyParam  area string required The area of the device. Example: 127.0.0.1:8000
     * @bodyParam  last_activity string required The last activity date of the device. Example: 2021-03-03
     * @bodyParam  status boolean The status of the device. Example: true
     *
     * @responseFile  responses/Device/updated.get.json
     * @responseFile 401 responses/401.get.json
     * @responseFile 404 responses/404.get.json
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $deviceFormData = new DeviceFormData(
            $request->uuid,
            $request->name,
            $request->serial_number,
            $request->ip_address,
            $request->area,
            $request->last_activity,
        );

        (new UpdateDeviceAction)->execute($device, $deviceFormData);

        return $this->responseSuccessMessage("Device updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
