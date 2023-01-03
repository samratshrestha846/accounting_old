<?php

namespace App\Http\Controllers;

use App\Models\Paymentmode;
use Illuminate\Http\Request;

class PaymentmodeController extends Controller {
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
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            $paymentmodes = Paymentmode::latest()->paginate( 10 );
            return view( 'backend.payment_mode.index', compact( 'paymentmodes' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );
        $paymentmodes = Paymentmode::query()
        ->where( 'payment_mode', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.payment_mode.search', compact( 'paymentmodes' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            return view( 'backend.payment_mode.create' );
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
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            $data = $this->validate( $request, [
                'payment_mode'=>'required',
                'status'=>'required',
            ] );
            $paymentmode = Paymentmode::create( [
                'payment_mode'=>$data['payment_mode'],
                'status'=>$data['status'],
            ] );
            $paymentmode->save();
            return redirect()->route( 'paymentmode.index' )->with( 'success', 'Payment Mode Successfully created' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Paymentmode  $paymentmode
    * @return \Illuminate\Http\Response
    */

    public function show( Paymentmode $paymentmode ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Paymentmode  $paymentmode
    * @return \Illuminate\Http\Response
    */

    public function edit( Paymentmode $paymentmode, Request $request ) {
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            return view( 'backend.payment_mode.edit', compact( 'paymentmode' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Paymentmode  $paymentmode
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Paymentmode $paymentmode ) {
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            $data = $this->validate( $request, [
                'payment_mode'=>'required',
                'status'=>'required',
            ] );
            $paymentmode->update( [
                'payment_mode'=>$data['payment_mode'],
                'status'=>$data['status'],
            ] );
            $paymentmode->save();
            return redirect()->route( 'paymentmode.index' )->with( 'success', 'Payment Mode Successfully Updated' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Paymentmode  $paymentmode
    * @return \Illuminate\Http\Response
    */

    public function destroy( Paymentmode $paymentmode, Request $request ) {
        if ( $request->user()->can( 'manage-payment-mode' ) ) {
            $paymentmode->delete();
            return redirect()->route( 'paymentmode.index' )->with( 'success', 'Successfully Deleted' );

        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
