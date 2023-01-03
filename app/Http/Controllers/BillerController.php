<?php

namespace App\Http\Controllers;

use App\Models\OutletBiller;
use Illuminate\Http\Request;

class BillerController extends Controller
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
            $billers = OutletBiller::latest()->paginate(10);
            return view('backend.biller.index', compact('billers'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            return view('backend.biller.create');
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
        $this->validate($request, [
            'biller' => 'required',
            'outlet' => 'required'
        ]);

        $biller = OutletBiller::create([
            'outlet_id' => $request['outlet'],
            'user_id' => $request['biller']
        ]);

        $biller->save();
        return redirect()->route('biller.index')->with('success', 'Outlet Biller information saved successfully.');
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
    public function edit(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $outlet_biller = OutletBiller::findorFail($id);
            return view('backend.biller.edit', compact('outlet_biller'));
        } else {
            return view( 'backend.permission.permission' );
        }
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
        $outlet_biller = OutletBiller::findorFail($id);
        $this->validate($request, [
            'biller' => 'required',
            'outlet' => 'required'
        ]);

        $outlet_biller->update([
            'outlet_id' => $request['outlet'],
            'user_id' => $request['biller']
        ]);

        return redirect()->route('biller.index')->with('success', 'Outlet Biller information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos' ) ) {
            $outlet_biller = OutletBiller::findorFail($id);
            $outlet_biller->delete();

            return redirect()->route('biller.index')->with('success', 'Outlet Biller information deleted successfully.');
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
