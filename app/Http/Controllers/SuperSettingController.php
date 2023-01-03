<?php

namespace App\Http\Controllers;

use App\Models\SuperSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuperSettingController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit( Request $request, $id )
    {
        if ( Auth::user()->id == 1 ) {
            $supersetting = SuperSetting::findorFail( $id );
            return view( 'backend.supersetting', compact( 'supersetting' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\SuperSetting  $superSetting
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id )
    {
        $supersetting = SuperSetting::findorfail( $id );
        if(isset($_POST['supersetting']))
        {
            $this->validate( $request, [
                'user_limit'=>'required',
                'company_limit'=>'required',
                'branch_limit'=>'required',
                'expire_date'=>'required',
                'attendance'=>'required',
                'journal_edit_number'=>'required',
                'journal_edit_days_limit'=>'required'
            ] );

            $newExpireDateInString = strtotime( $request['expire_date'] );
            $todayInString = strtotime( date( 'Y-m-d h:i:s a' ) );

            if ( $todayInString > $newExpireDateInString ) {
                return redirect()->route( 'supersetting.edit', $id )->with( 'error', 'Expiration date should be greater than today.' );
            } else {
                $supersetting->update( [
                    'user_limit' => $request['user_limit'],
                    'company_limit' => $request['company_limit'],
                    'branch_limit' => $request['branch_limit'],
                    'expire_date' => $request['expire_date'],
                    'attendance' => $request['attendance'],
                    'notified' => 0,
                    'journal_edit_number' => $request['journal_edit_number'],
                    'journal_edit_days_limit' => $request['journal_edit_days_limit']
                ] );

                return redirect()->route( 'supersetting.edit', $id )->with( 'success', 'Settings updated successfully.' );
            }
        }
        else if(isset($_POST['discount']))
        {
            $this->validate($request, [
                'before_after' => 'required',
                'discount_type' => 'required',
            ]);

            $supersetting->update( [
                'before_after' => $request['before_after'],
                'discount_type' => $request['discount_type']
            ] );
            return redirect()->route( 'discountSetting' )->with( 'success', 'Discount settings updated successfully.' );
        }
        else if(isset($_POST['creditSetting']))
        {
            $this->validate( $request, [
                'allocated_days'=>'required',
                'allocated_bills'=>'required',
                'allocated_amount'=>'required',
            ] );

            $supersetting->update( [
                'allocated_days'=> $request['allocated_days'],
                'allocated_bills'=> $request['allocated_bills'],
                'allocated_amount'=> $request['allocated_amount'],
            ] );
            return redirect()->route( 'creditSettings' )->with( 'success', 'Credit Settings updated successfully.' );
        }
    }

    public function discountSetting()
    {
        $supersetting = SuperSetting::first();
        return view('backend.discountsettings', compact('supersetting'));
    }

    public function creditSettings()
    {
        $supersetting = SuperSetting::first();
        return view('backend.creditSetting', compact('supersetting'));
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\SuperSetting  $superSetting
    * @return \Illuminate\Http\Response
    */

    public function destroy( SuperSetting $superSetting )
    {
        //
    }
}
