<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Http\Controllers\Controller;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function App\NepaliCalender\datenep;

class SchemeController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if($request->user()->can('scheme-management')){
            $schemes = Scheme::latest()->paginate( 10 );
            return view( 'backend.schemes.index', compact( 'schemes' ) );
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create(Request $request) {
        if($request->user()->can('scheme-management')){
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            $schemes = Scheme::latest()->get();
            $allschemecodes = [];
            foreach ( $schemes as $scheme ) {
                array_push( $allschemecodes, $scheme->code );
            }
            $scheme_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.schemes.create', compact( 'nepali_today', 'scheme_code', 'allschemecodes' ) );
        }else{
            return view('backend.permission.permission');
        }
    }

    public function searchscheme( Request $request ) {
        $search = $request->input( 'search' );
        $schemes = Scheme::query()
        ->where( 'name', 'LIKE', "%{$search}%" )
        ->orWhere( 'code', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );
        $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
        return view( 'backend.schemes.search', compact( 'schemes', 'currentcomp' ) );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $this->validate( $request, [
            'scheme_name' => 'required',
            'code' => 'required|unique:schemes',
            'starting_date' => 'required',
            'ending_date' => 'required',
            'percent_fixed' => 'required',
            'based_on' => 'required',
            'amount' => 'required',
            'status' => '',
        ] );

        if ( $request['status'] == null ) {
            $status = 0;
        } else {
            $status = 1;
        }

        Scheme::create( [
            'name' => $request['scheme_name'],
            'code' => $request['code'],
            'start_date' => $request['starting_date'],
            'end_date' => $request['ending_date'],
            'percent_fixed' => $request['percent_fixed'],
            'based_on' => $request['based_on'],
            'amount' => $request['amount'],
            'status' => $status
        ] );

        return redirect()->route( 'scheme.index' )->with( 'success', 'Scheme information is saved successfully.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Scheme  $scheme
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Scheme  $scheme
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if($request->user()->can('scheme-management')){
            $scheme = Scheme::findorFail( $id );
            return view( 'backend.schemes.edit', compact( 'scheme' ) );
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Scheme  $scheme
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $scheme = Scheme::findorFail( $id );
        $this->validate( $request, [
            'scheme_name' => 'required',
            'code' => 'required|unique:schemes,code,'.$scheme->id,
            'starting_date' => 'required',
            'ending_date' => 'required',
            'percent_fixed' => 'required',
            'based_on' => 'required',
            'amount' => 'required',
            'status' => '',
        ] );

        if ( $request['status'] == null ) {
            $status = 0;
        } else {
            $status = 1;
        }

        $scheme->update( [
            'name' => $request['scheme_name'],
            'code' => $request['code'],
            'start_date' => $request['starting_date'],
            'end_date' => $request['ending_date'],
            'percent_fixed' => $request['percent_fixed'],
            'based_on' => $request['based_on'],
            'amount' => $request['amount'],
            'status' => $status
        ] );

        return redirect()->route( 'scheme.index' )->with( 'success', 'Scheme information is updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Scheme  $scheme
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if($request->user()->can('scheme-management')){
            $scheme = Scheme::findorFail($id);
            $scheme->delete();

            return redirect()->route( 'scheme.index' )->with( 'success', 'Scheme information is delete successfully.' );
        }else{
            return view('backend.permission.permission');
        }
    }
}
