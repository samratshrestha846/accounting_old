<?php

namespace App\Http\Controllers\Hotel;

use App\Actions\CreateNewHotelTable;
use App\Actions\DeleteHotelTable;
use App\Actions\UpdateHotelTable;
use App\FormDatas\HotelTableFormdata;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRoomStoreRequest;
use App\Http\Requests\HotelTableStoreRequest;
use App\Http\Requests\HotelTableUpdateRequest;
use App\Models\CabinType;
use App\Models\HotelRoom;
use App\Models\HotelTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('hotel-table-view')) {
            return view('backend.permission.permission');
        }

        $tables = HotelTable::filters($request->all())->paginate(10);

        return view('backend.hotel.table.index')
            ->with('tables', $tables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('hotel-table-create')) {
            return view('backend.permission.permission');
        }

        $rooms = HotelRoom::with('floor')->get();
        $cabinTypes = CabinType::get();

        return view('backend.hotel.table.create')
            ->with('rooms', $rooms)
            ->with('cabin_types', $cabinTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelTableStoreRequest $request, CreateNewHotelTable $createNewHotelTable)
    {
        if (!$request->user()->can('hotel-table-create')) {
            return view('backend.permission.permission');
        }

        $room = HotelRoom::findOrFail($request->room);

        $data = new HotelTableFormdata(
            $room->floor_id,
            $room->id,
            $request->table_name,
            $request->table_code,
            $request->max_capacity,
            (bool) $request->is_cabin,
            $request->cabin_type ? (int) $request->cabin_type : null,
            $request->cabin_charge ? (float) $request->cabin_charge : null
        );

        $createNewHotelTable->execute($data);

        return redirect()->route('hotel-table.index')->with('success', "Hotel Table created successfully");
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
    public function edit(Request $request, HotelTable $hotelTable)
    {
        if (!$request->user()->can('hotel-table-edit')) {
            return view('backend.permission.permission');
        }

        $rooms = HotelRoom::with('floor')->get();
        $cabinTypes = CabinType::get();

        return view('backend.hotel.table.edit')
            ->with('table', $hotelTable)
            ->with('rooms', $rooms)
            ->with('cabin_types', $cabinTypes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HotelTableUpdateRequest $request, HotelTable $hotelTable, UpdateHotelTable $updateHotelTable)
    {
        if (!$request->user()->can('hotel-table-edit')) {
            return view('backend.permission.permission');
        }

        $room = HotelRoom::findOrFail($request->room);

        $data = new HotelTableFormdata(
            $room->floor_id,
            $room->id,
            $request->table_name,
            $request->table_code,
            $request->max_capacity,
            (bool) $request->is_cabin,
            $request->cabin_type ? (int) $request->cabin_type : null,
            $request->cabin_charge ? (float) $request->cabin_charge : null
        );

        $updateHotelTable->execute($hotelTable, $data);

        return redirect()->route('hotel-table.index')->with('success', "Hotel Table updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HotelTable $hotelTable, DeleteHotelTable $deleteHotelTable)
    {
        if (!$request->user()->can('hotel-table-delete')) {
            return view('backend.permission.permission');
        }

        try {
            $deleteHotelTable->execute($hotelTable);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-table.index')->with('success', "Hotel Table deleted successfully");
    }
}
