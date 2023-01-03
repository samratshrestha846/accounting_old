<?php

namespace App\Http\Controllers;

use App\Models\ProductNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductNotificationController extends Controller
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
    public function index()
    {
        // $pronotis = ProductNotification::latest()->paginate(10);
        // return view('backend.product_service.productnotification', compact('pronotis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-product-report' ) ) {
            $pronotis = ProductNotification::where('status', 0)->latest()->get();
            foreach($pronotis as $pronoti){
                $productnotification = ProductNotification::findorFail($pronoti->id);
                $date = date('Y/m/d H:i:s');
                $productnotification->update([
                    'status'=> 1,
                    'read_at'=> $date,
                    'read_by'=>Auth::user()->id,
                ]);
                $productnotification->save();
            }
            return redirect()->back()->with('success', 'All Marked as Read');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductNotification  $productNotification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productnotification = ProductNotification::findorFail($id);
        $date = date('Y/m/d H:i:s');
        $productnotification->update([
            'status'=> 1,
            'read_at'=> $date,
            'read_by'=>Auth::user()->id,
        ]);
        $productnotification->save();
        return redirect()->route('product.show', $productnotification->product_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductNotification  $productNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductNotification $productNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductNotification  $productNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductNotification  $productNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductNotification $productNotification)
    {
        //
    }

    public function productnotificationtype($type)
    {
        $pronotis = ProductNotification::where('noti_type', $type)->where('status', 0)->paginate(15);
        return view('backend.product_service.productnotification', compact('pronotis', 'type'));
    }

    public function readproductnotification($type)
    {
        $pronotis = ProductNotification::where('noti_type', $type)->where('status', 0)->get();
        foreach($pronotis as $pronoti){
            $productnotification = ProductNotification::findorFail($pronoti->id);
            $date = date('Y/m/d H:i:s');
            $productnotification->update([
                'status'=> 1,
                'read_at'=> $date,
                'read_by'=>Auth::user()->id,
            ]);
            $productnotification->save();
        }
        return redirect()->back()->with('success', 'All Marked as Read');
    }
}
