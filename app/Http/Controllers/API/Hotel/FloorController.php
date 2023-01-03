<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Models\HotelFloor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $floors = HotelFloor::select('id','name','code')->get();

        return response()->json([
            'data' => $floors
        ]);
    }
}
