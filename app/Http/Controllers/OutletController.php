<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Outlet;
use App\Models\Province;
use Illuminate\Http\Request;

class OutletController extends Controller
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
        if ( $request->user()->can( 'manage-pos' ) ) {
            $outlets = Outlet::latest()->paginate(10);
            return view('backend.outlet.index', compact('outlets'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $outlets = Outlet::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.outlet.search', compact( 'outlets' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $provinces = Province::latest()->get();
            return view( 'backend.outlet.create', compact( 'provinces') );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( $request, [
            'name' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'local_address' => 'required'
        ] );

        Outlet::create( [
            'name' => $request['name'],
            'province_id' => $request['province_id'],
            'district_id' => $request['district_id'],
            'local_address' => $request['local_address']
        ] );

        return redirect()->route( 'outlet.index' )->with( 'success', 'Outlet information inserted successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $outlet = Outlet::findorFail($id);
            $provinces = Province::latest()->get();
            $districts = District::where( 'id', $outlet->district_id )->latest()->get();
            return view('backend.outlet.edit', compact('outlet', 'provinces', 'districts'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $outlet = Outlet::findorFail( $id );
        $this->validate( $request, [
            'name' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'local_address' => 'required'
        ] );

        $outlet->update( [
            'name' => $request['name'],
            'province_id' => $request['province_id'],
            'district_id' => $request['district_id'],
            'local_address' => $request['local_address']
        ] );

        return redirect()->route( 'outlet.index' )->with( 'success', 'Outlet information updated successfully.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            Outlet::findorFail( $id )->delete();
            return redirect()->route( 'outlet.index' )->with( 'success', 'Outlet information deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
