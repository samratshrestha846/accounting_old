<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Models\HotelRoom;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = HotelRoom::select('id','name','code','table_capacity','floor_id')
            ->filters($request->all())
            ->get();

        return response()->json([
            'data' => $rooms
        ]);
    }
}
