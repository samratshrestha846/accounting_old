<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CabinTypeStoreRequest;
use App\Http\Requests\CabinTypeUpdateRequest;
use App\Models\CabinType;
use Illuminate\Http\Request;

class CabinTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->can('cabin-type-view')) {
            $cabinTypes = CabinType::paginate(10);
            return view('backend.hotel.cabin_type.index')
                ->with('cabin_types', $cabinTypes);
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->can('cabin-type-create')) {
            return view('backend.hotel.cabin_type.create');
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CabinTypeStoreRequest $request)
    {
        if ($request->user()->can('cabin-type-create')) {

            $cabin = CabinType::create([
                'name' => $request->name,
                'remarks' => $request->remarks
            ]);
            if (request()->wantsJson()) {
                return response()->json([
                    'data' => $cabin
                ]);
            }

            return redirect()->route('cabintype.index')->with('success', 'Cabin Type created successfully');
        } else {
            return view('backend.permission.permission');
        }
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
    public function edit(Request $request, CabinType $cabintype)
    {
        if ($request->user()->can('cabin-type-edit')) {
            return view('backend.hotel.cabin_type.edit')
                ->with('cabintype', $cabintype);
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CabinTypeUpdateRequest $request, CabinType $cabintype)
    {
        if ($request->user()->can('cabin-type-edit')) {
            $cabintype->update([
                'name' => $request->name,
                'remarks' => $request->remarks
            ]);

            return redirect()->route('cabintype.index')->with('success', 'Cabin Type updated successfully');
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CabinType $cabintype)
    {
        if ($request->user()->can('cabin-type-delete')) {
            try {

                // if($hotelFloor->hasRooms())
                //     throw new \Exception("You cannot delete floor since it has some rooms");

                $cabintype->delete();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect()->route('cabintype.index')->with('success', 'Cabin type deleted successfully');
        } else {
            return view('backend.permission.permission');
        }
    }
}
