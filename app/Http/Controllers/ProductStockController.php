<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id, Request $request)
    {
        if ( $request->user()->can( 'manage-product-report' ) ) {
            $product = Product::findorfail($id);
            $productstocks = ProductStock::where( 'product_id', $id )->latest()->get();
            return view('backend.product_service.addstockindex', compact('product', 'productstocks'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create($id, Request $request)
    {
        if ( $request->user()->can( 'manage-product-report' ) ) {
            $product = Product::findorfail($id);
            $godowns = Godown::all();
            return view('backend.product_service.addstock', compact('product', 'godowns'));
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

    public function store( Request $request )
    {
        $count = count( $request['godown'] );
        $stock = $request['stock'];
        $godown = $request['godown'];

        for ( $x = 0; $x < $count; $x++ ) {
            $productStock = ProductStock::create( [
                'product_id'=>$request['product_id'],
                'godown_id'=>$godown[$x],
                'added_stock'=>$stock[$x],
                'added_date'=>date( 'Y-m-d' ),
            ] );
            $productStock->save();
        }

        $godowns = [];

        foreach($request->godown as $key => $value) {
            $godowns[] = [
                'godown_id' => $value,
                'serial_numbers' => $request['serial_numbers_'.($key+1)] ?? [],
            ];
        }
        DB::beginTransaction();
        try{
            for ( $y = 0; $y < $count; $y++ ) {
                // dd($godown[$y]);
                $godownproduct = GodownProduct::where('godown_id', $godown[$y])->where('product_id', $request['product_id'] )->first();
                // dd($godownproduct);
                if ( !$godownproduct == null ) {
                    $oldstock = $godownproduct['stock'];
                    $newstock = $stock[$y];
                    $godostock = $oldstock + $newstock;
                    $godownproduct->update( [
                        'product_id'=>$request['product_id'],
                        'godown_id'=>$request['godown'][$y],
                        'stock'=>$godostock,
                    ] );
                    $godownproduct->save();
                } else {
                    $newstock = $stock[$y];
                    $gdproduct = GodownProduct::create( [
                        'product_id'=>$request['product_id'],
                        'godown_id'=>$request['godown'][$y],
                        'opening_stock'=>$newstock,
                        'stock'=>$newstock,
                    ] );
                    $gdproduct->save();
                }
            }

            $godownserialnumbers = [];
            foreach($godowns as $indvgdns){
                $godownproduct_id = GodownProduct::where('product_id', $request['product_id'])->where('godown_id', $indvgdns['godown_id'])->first()->id;
                // dd($godownproduct);
                foreach($indvgdns['serial_numbers'] as $serialnumber){
                    $godownserialnumbers[]=[
                        'godown_product_id'=>$godownproduct_id,
                        'serial_number'=>$serialnumber,
                        'addstock_id'=>$productStock['id']
                    ];
                }
            }
            GodownSerialNumber::insert($godownserialnumbers);
            $product = Product::findorfail( $request['product_id'] );
            $total_stock = array_sum( $request['stock'] ) + $product->total_stock;
            $product->update( [
                'total_stock'=>$total_stock,
            ] );
            DB::commit();
            return redirect()->route( 'product.show', $request['product_id'] )->with( 'success', 'Stock Successfully Added' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\ProductStock  $productStock
    * @return \Illuminate\Http\Response
    */

    public function show( ProductStock $productStock ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\ProductStock  $productStock
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request )
    {
        if ( $request->user()->can( 'manage-product-report' ) ) {
            $productStock = ProductStock::findorFail( $id );
            $product = Product::where('id', $productStock->product_id)->first();
            $godownproduct_id = GodownProduct::where('product_id',$productStock->product_id)->where('godown_id', $productStock->godown_id)->first()->id;
            $serialnumbers = GodownSerialNumber::where('godown_product_id', $godownproduct_id)->where('addstock_id', $id)->where('billing_id', null)->get();
            // dd($serialnumbers);
            return view( 'backend.product_service.editaddedstock', compact( 'productStock', 'product', 'serialnumbers'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\ProductStock  $productStock
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        DB::beginTransaction();
        try{
            $productStock = ProductStock::findorfail( $id );
            $godownproduct = GodownProduct::where( 'godown_id', $request['godown'] )->where( 'product_id', $request['product_id'] )->first();
            $product = Product::findorfail( $request['product_id'] );
            $oldstock = $godownproduct['stock'] - $productStock->added_stock;
            $productstocks = $product->total_stock - $productStock->added_stock;
            $productStock->update([
                'product_id'=>$request['product_id'],
                'godown_id'=>$request['godown'],
                'added_stock'=>$request['added_stock'],
                'added_date'=>date( 'Y-m-d' ),
            ] );
            $productStock->save();
            $newstock = $request['added_stock'];
            $godostock = $oldstock + $newstock;
            $godownproduct->update( [
                'product_id'=>$request['product_id'],
                'godown_id'=>$request['godown'],
                'stock'=>$godostock,
            ] );
            $godownproduct->save();

            $total_stock = $request['added_stock'] + $productstocks;
            $product->update( [
                'total_stock'=>$total_stock,
            ]);
            $oldserials = GodownSerialNumber::where('godown_product_id', $godownproduct['id'])->where('addstock_id', $id)->where('billing_id', null)->get();
            foreach($oldserials as $oldsn){
                $oldsn->delete();
            }
            $newserialnumbers = [];
            foreach($request['serial_number'] as $newsn){
                $newserialnumbers[] = [
                    'godown_product_id'=>$godownproduct['id'],
                    'addstock_id'=> $id,
                    'serial_number'=>$newsn
                ];
            }
            GodownSerialNumber::insert($newserialnumbers);
            DB::commit();
            return redirect()->route( 'product.show', $request['product_id'] )->with( 'success', 'Stock Successfully Updated' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\ProductStock  $productStock
    * @return \Illuminate\Http\Response
    */

    public function destroy( ProductStock $productStock ) {
        //
    }
}
