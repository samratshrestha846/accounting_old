<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaxController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'manage-tax' ) ) {
            $taxes = Tax::latest()->paginate( 10 );
            return view( 'backend.tax.index', compact( 'taxes' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $taxes = Tax::query()
        ->where( 'title', 'LIKE', "%{$search}%" )
        ->orWhere( 'percent', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.tax.search', compact( 'taxes' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-tax' ) ) {
            return view( 'backend.tax.create' );
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
        if ( $request->user()->can( 'manage-tax' ) ) {
            $data = $this->validate( $request, [
                'title'=>'required',
                'percent'=>'required',
            ] );
            $slug = Str::slug( $data['title'] );

            $tax = Tax::create( [
                'title'=>$data['title'],
                'slug'=>$slug,
                'percent'=>$data['percent'],
            ] );
            $tax->save();
            return redirect()->route( 'tax.index' )->with( 'success', 'Tax Successfully Created' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Tax  $tax
    * @return \Illuminate\Http\Response
    */

    public function show( Tax $tax ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Tax  $tax
    * @return \Illuminate\Http\Response
    */

    public function edit( Tax $tax, Request $request ) {
        if ( $request->user()->can( 'manage-tax' ) ) {
            return view( 'backend.tax.edit', compact( 'tax' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Tax  $tax
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Tax $tax ) {
        if ( $request->user()->can( 'manage-tax' ) ) {
            $data = $this->validate( $request, [
                'title'=>'required',
                'percent'=>'required',
            ] );
            $slug = Str::slug( $data['title'] );

            $tax->update( [
                'title'=>$data['title'],
                'slug'=>$slug,
                'percent'=>$data['percent'],
            ] );
            $tax->save();
            return redirect()->route( 'tax.index' )->with( 'success', 'Tax Successfully Updated' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Tax  $tax
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, Tax $tax ) {
        if ( $request->user()->can( 'manage-tax' ) ) {
            $tax->delete();
            return redirect()->route( 'tax.index' )->with( 'success', 'Tax Successfully Deleted' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function mark_as_default(Request $request)
    {
        $tax_default = Tax::where('is_default',1)->first();
        if($tax_default){
            $tax_default->is_default = 0;
            $tax_default->save();
        }

        if($request->id != 0){
            $tax = Tax::find($request->id);
            $tax->is_default = 1;
            $tax->save();
        }

        $response = array('status' => 'success','message' => 'Mark as Default Added');
        echo(json_encode($response));
    }
}
