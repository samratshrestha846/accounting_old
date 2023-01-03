<?php

namespace App\Http\Controllers;

use App\Models\Quotationsetting;
use Illuminate\Http\Request;

class QuotationSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function quotationsetting( $id, Request $request )
    {
        if ( $request->user()->can( 'manage-quotation-setting' ) ) {
            $quotation = Quotationsetting::find( $id );
            return view( 'backend.quotationsetting', compact( 'quotation' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function quotationsettingupdate( Request $request, $id )
    {
        if ( $request->user()->can( 'manage-quotation-setting' ) ) {
            $quotation = Quotationsetting::find( $id );
            if ( $request['show_brand'] == null ) {
                $show_brand = 0;
            } else {
                $show_brand = 1;
            }
            if ( $request['show_model'] == null ) {
                $show_model = 0;
            } else {
                $show_model = 1;
            }
            if ( $request['show_picture'] == null ) {
                $show_picture = 0;
            } else {
                $show_picture = 1;
            }
            $imagename = '';
            if ( $request->hasfile( 'letterhead' ) ) {
                $image = $request->file( 'letterhead' );
                $imagename = $image->store( 'letterhead', 'uploads' );
            } else {
                $imagename = $quotation->letterhead;
            }
            $quotation->update( [
                'show_brand'=>$show_brand,
                'show_model'=>$show_model,
                'show_picture'=>$show_picture,
                'letterhead'=>$imagename,
            ] );
            $quotation->save();
            return redirect()->route( 'quotation.setting', $quotation )->with( 'success', 'Quotation Setting Successfully Updated' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
