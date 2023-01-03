<?php
namespace App\Actions\Vendor;

use App\Http\Requests\StoreVendorRequest;
use App\Models\Vendor;

class CreateVendorAction {

    public function execute(StoreVendorRequest $request): Vendor
    {
        return Vendor::create($request->validated());
    }
}
