<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvienceResource;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{

    protected Province $province;

    public function __construct()
    {
        $this->province = new Province();
    }

    public function index(Request $request)
    {
        $provinces = $this->province->getAll();

        return ProvienceResource::collection($provinces);
    }
}
