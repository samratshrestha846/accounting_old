<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelKitchenStoreRequest;
use App\Http\Requests\HotelKitchenUpdateRequest;
use App\Models\HotelKitchen;
use App\Models\HotelRoom;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-kitchen-view')) {
            return view('backend.permission.permission');
        }
        $kitchens = HotelKitchen::filters($request->all())->with('floor', 'room')->paginate(10);
        return view('backend.hotel.kitchen.index')
            ->with('kitchens', $kitchens);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('hotel-kitchen-create')) {
            return view('backend.permission.permission');
        }
        $rooms = HotelRoom::get();
        return view('backend.hotel.kitchen.create')
            ->with('rooms', $rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelKitchenStoreRequest $request)
    {

        if (!$request->user()->can('hotel-kitchen-create')) {
            return view('backend.permission.permission');
        }

        $room = HotelRoom::findOrFail($request->room);
        HotelKitchen::create([
            'floor_id' => $room->floor_id,
            'room_id' => $room->id,
            'kitchen_name' => $request->kitchen_name,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('hotel-kitchen.index')->with('success', 'Hotel Kitchen created successfully');
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
    public function edit(Request $request, HotelKitchen $hotelKitchen)
    {
        if (!$request->user()->can('hotel-kitchen-edit')) {
            return view('backend.permission.permission');
        }

        $rooms = HotelRoom::get();
        return view('backend.hotel.kitchen.edit')
            ->with('rooms', $rooms)
            ->with('kitchen', $hotelKitchen);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelKitchenUpdateRequest $request, HotelKitchen $hotelKitchen)
    {
        if (!$request->user()->can('hotel-kitchen-edit')) {
            return view('backend.permission.permission');
        }
        $room = HotelRoom::findOrFail($request->room);

        $hotelKitchen->update([
            'floor_id' => $room->floor_id,
            'room_id' => $room->id,
            'kitchen_name' => $request->kitchen_name,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('hotel-kitchen.index')->with('success', 'Hotel Kitchen updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HotelKitchen $hotelKitchen)
    {
        if (!$request->user()->can('hotel-kitchen-delete')) {
            return view('backend.permission.permission');
        }

        try {
            $hotelKitchen->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-kitchen.index')->with('success', 'Hotel Kitchen deleted successfully');
    }
}
