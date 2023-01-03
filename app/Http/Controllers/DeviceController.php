<?php

namespace App\Http\Controllers;

use App\Actions\DeleteDeviceAction;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-device' ) ) {
            $devices = Device::latest()->withCount('departments')->paginate( 10 );
            return view( 'backend.device.index', compact( 'devices' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }


    public function search(Request $request)
    {
        $search = $request->input( 'search' );
        if ( $request->user()->can( 'manage-device' ) ) {
            $devices = Device::latest()
            ->where( 'name', 'LIKE', "%{$search}%" )
            ->orWhere( 'serial_number', 'LIKE', "%{$search}%" )
            ->orWhere( 'ip_address', 'LIKE', "%{$search}%" )
            ->orWhere( 'area', 'LIKE', "%{$search}%" )
            ->withCount('departments')->paginate( 10 );
            return view( 'backend.device.index', compact( 'devices' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        try{
            (new DeleteDeviceAction)->execute($device);
        } catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }

        return redirect()->back()->with('success', "Device deleted successfully");
    }
}
