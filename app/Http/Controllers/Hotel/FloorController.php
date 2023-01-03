<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelFloorStoreRequest;
use App\Http\Requests\HotelFloorUpdateRequest;
use App\Models\HotelFloor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-floor-view')) {
            return view('backend.permission.permission');
        }
        $floors = HotelFloor::filters($request->all())->paginate(10);
        return view('backend.hotel.floor.index', compact('floors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('hotel-floor-create')) {
            return view('backend.permission.permission');
        }
        return view('backend.hotel.floor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelFloorStoreRequest $request)
    {
        if (!$request->user()->can('hotel-floor-create')) {
            return view('backend.permission.permission');
        }

        HotelFloor::create([
            'name' => $request->floor_name,
            'code' => $request->floor_code,
        ]);

        return redirect()->route('hotel-floor.index')->with('Hotel Floor created successfully');
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
    public function edit(Request $request, HotelFloor $hotelFloor)
    {
        if (!$request->user()->can('hotel-floor-edit')) {
            return view('backend.permission.permission');
        }

        return view('backend.hotel.floor.edit')
            ->with('floor', $hotelFloor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelFloorUpdateRequest $request, HotelFloor $hotelFloor)
    {
        if (!$request->user()->can('hotel-floor-edit')) {
            return view('backend.permission.permission');
        }

        $hotelFloor->update([
            'name' => $request->floor_name,
            'code' => $request->floor_code,
        ]);

        return redirect()->route('hotel-floor.index')->with('Hotel Floor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HotelFloor $hotelFloor)
    {
        if (!$request->user()->can('hotel-floor-edit')) {
            return view('backend.permission.permission');
        }

        try {

            if ($hotelFloor->hasRooms())
                throw new \Exception("You cannot delete floor since it has some rooms");

            $hotelFloor->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-floor.index')->with('success', 'Hotel Floor deleted successfully');
    }
}
