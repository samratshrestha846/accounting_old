<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    protected District $district;

    public function __construct()
    {
        $this->district = new District();
    }

    public function index(Request $request)
    {
        $districts = $this->district->getAll([
            'filters' => $request->all(),
        ]);

        return DistrictResource::collection($districts);
    }
}
