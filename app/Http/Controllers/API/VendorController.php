<?php

namespace App\Http\Controllers\API;

use App\Actions\Vendor\CreateVendorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::select('*');

        if($request['per_page']){
            $vendors = $vendors->paginate($request['per_page']);
            return VendorResource::collection($vendors);
        }

        $vendors = $vendors->get();

        return $this->responseOk($message = "Vendor Fetched successfully", VendorResource::collection($vendors));
    }

    public function store(StoreVendorRequest $request)
    {
        $vendor = (new CreateVendorAction())->execute($request);
        return $this->responseOk($message = "Vendor created successfully", VendorResource::make($vendor), 201);
    }
}
