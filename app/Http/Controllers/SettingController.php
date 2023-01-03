<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller {
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
        if ( Auth::user()->id == 1 ) {
            $setting = Setting::first();
            $provinces = Province::latest()->get();
            $district = District::where( 'id', $setting->district_id )->first();
            $district_group = District::where( 'province_id', $district->province_id )->latest()->get();
            return view( 'backend.setting', compact( 'setting', 'provinces', 'district_group' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function update( Request $request, $id ) {
        // dd($request->all());
        $setting = Setting::findorFail( $id );

        $this->validate( $request, [
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required',
            'province' => 'required',
            'district' => 'required',
            'address' => 'required',
            'pan_vat' => 'required',
            'registration_no' => 'required',
            'company_logo' => 'mimes:jpeg,jpg,png',
        ] );

        $imagename = '';
        if ( $request->hasfile( 'company_logo' ) ) {
            $image = $request->file( 'company_logo' );
            $imagename = $image->store( 'company_logo', 'uploads' );
            $setting->update( [
                'logo' => $imagename,
            ] );
        }
        DB::beginTransaction();
        try{

            $setting->update( [
                'company_name' => $request['company_name'],
                'company_email' => $request['company_email'],
                'company_phone' => $request['company_phone'],
                'province_id' => $request['province'],
                'district_id' => $request['district'],
                'address' => $request['address'],
                'pan_vat' => $request['pan_vat'],
                'registration_no' => $request['registration_no'],
                'invoice_color'=>$request['invoice_color'],
            ] );
            DB::commit();
            return redirect()->back()->with( 'success', 'Setting information successfully updated.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Setting  $setting
    * @return \Illuminate\Http\Response
    */

    public function destroy( Setting $setting ) {
        //
    }
}
