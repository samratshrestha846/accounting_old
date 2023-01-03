<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRoomStoreRequest;
use App\Http\Requests\HotelRoomUpdateRequest;
use App\Models\HotelFloor;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Floor;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-room-view')) {
            return view('backend.permission.permission');
        }

        $rooms = HotelRoom::filters($request->all())->paginate(10);
        return view('backend.hotel.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('hotel-room-create')) {
            return view('backend.permission.permission');
        }

        $floors = HotelFloor::select('id', 'name', 'code')->get();
        return view('backend.hotel.room.create')
            ->with('floors', $floors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelRoomStoreRequest $request)
    {
        if (!$request->user()->can('hotel-room-view')) {
            return view('backend.permission.permission');
        }

        HotelRoom::create([
            'name' => $request->room_name,
            'code' => $request->room_code,
            'floor_id' => $request->floor,
            'table_capacity' => $request->table_capacity,
        ]);

        return redirect()->route('hotel-room.index')->with('success', 'Hotel Room created successfully');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, HotelRoom $hotelRoom)
    {
        if (!$request->user()->can('hotel-room-edit')) {
            return view('backend.permission.permission');
        }

        $floors = HotelFloor::select('id', 'name', 'code')->get();
        return view('backend.hotel.room.edit')
            ->with('floors', $floors)
            ->with('room', $hotelRoom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelRoomUpdateRequest $request, HotelRoom $hotelRoom)
    {
        if (!$request->user()->can('hotel-room-edit')) {
            return view('backend.permission.permission');
        }

        $hotelRoom->update([
            'name' => $request->room_name,
            'code' => $request->room_code,
            'floor_id' => $request->floor,
            'table_capacity' => $request->table_capacity,
        ]);

        return redirect()->route('hotel-room.index')->with('success', 'Hotel Room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HotelRoom $hotelRoom)
    {
        if (!$request->user()->can('hotel-room-delete')) {
            return view('backend.permission.permission');
        }

        try {
            $hotelRoom->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-room.index')->with('success', 'Hotel Room deleted successfully');
    }
}
