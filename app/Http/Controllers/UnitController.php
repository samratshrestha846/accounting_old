<?php

namespace App\Http\Controllers;

use App\Models\BillingExtra;
use App\Models\ServiceSalesExtra;
use App\Models\Unit;
use Illuminate\Http\Request;
use PDO;

class UnitController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request )
    {
        if ( $request->user()->can( 'manage-units' ) )
        {
            $units = Unit::latest()->paginate( 10 );
            return view( 'backend.units.index', compact( 'units' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $units = Unit::query()
        ->where( 'unit', 'LIKE', "%{$search}%" )
        ->orWhere( 'short_form', 'LIKE', "%{$search}%" )
        ->orWhere( 'unit_code', 'LIKe', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.units.search', compact( 'units' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-units' ) ) {
            $units = Unit::all();
            $allunitcodes = [];
            foreach ( $units as $unit ) {
                array_push( $allunitcodes, $unit->unit_code );
            }
            $unit_code = 'UT'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.units.create', compact( 'allunitcodes', 'unit_code' ) );
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

    public function store( Request $request ) {
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $this->validate( $request, [
            'unit' => 'required',
            'unit_code' => 'required|unique:units',
            'short_form' => 'required'
        ] );

        Unit::create( [
            'unit' => $request['unit'],
            'unit_code' => $request['unit_code'],
            'short_form' => $request['short_form'],
        ] );
        if($saveandcontinue == 1){
            return redirect()->back()->with( 'success', 'Unit information successfully inserted.' );
        }

        return redirect()->route( 'unit.index' )->with( 'success', 'Unit information successfully inserted.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-units' ) ) {
            $existingUnit = Unit::findorFail( $id );
            return view( 'backend.units.edit', compact( 'existingUnit' ) );
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

    public function update( Request $request, $id ) {
        $existingUnit = Unit::findorFail( $id );

        $this->validate( $request, [
            'unit' => 'required',
            'unit_code' => 'required|unique:units,unit_code,'.$existingUnit->id,
            'short_form' => 'required'
        ] );

        $existingUnit->update( [
            'unit' => $request['unit'],
            'unit_code' => $request['unit_code'],
            'short_form' => $request['short_form'],
        ] );

        return redirect()->route( 'unit.index' )->with( 'success', 'Unit information successfully updated.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        $unit = Unit::find($id);
        $unitname = $unit->unit;
        $usedunit = BillingExtra::where('unit',$unitname)->count();
        if($usedunit > 0){
            return redirect()->back()->with('error','This Unit is already in use.You cannot Delete');
        }
        $usedunit = ServiceSalesExtra::where('unit',$unitname)->count();
        if($usedunit > 0){
            return redirect()->back()->with('error','This Unit is already in use.You cannot Delete');
        }
        $unit->delete();
        return redirect()->back()->with('success','Successfully Deleted');
    }
}
