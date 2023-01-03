<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\ProductStock;
use App\Models\Product;

use App\Models\Province;
use Illuminate\Http\Request;

class GodownController extends Controller {
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
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $godowns = Godown::latest()->paginate(10);
            return view( 'backend.godown.index', compact( 'godowns' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $godowns = Godown::query()
        ->where( 'godown_name', 'LIKE', "%{$search}%" )
        ->orWhere( 'godown_code', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.godown.search', compact( 'godowns' ) );
    }

    public function deletedGodownInfo( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $godowns = Godown::onlyTrashed()->latest()->paginate( 10 );
            return view( 'backend.trash.godownTrash', compact( 'godowns' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $provinces = Province::latest()->get();
            $godowns = Godown::latest()->get();
            // dd($godowns[0]->godownproduct[0]->allserialnumbers);
            $allgodowncodes = [];
            foreach ( $godowns as $godown ) {
                array_push( $allgodowncodes, $godown->godown_code );
            }
            $godown_code = 'GO'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.godown.create', compact( 'provinces', 'allgodowncodes', 'godown_code' ) );
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
        $this->validate( $request, [
            'godown_name' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'local_address' => 'required',
            'godown_code' => 'required|unique:godowns',
        ] );

        Godown::create( [
            'godown_name' => $request['godown_name'],
            'province_id' => $request['province_id'],
            'district_id' => $request['district_id'],
            'local_address' => $request['local_address'],
            'godown_code' => $request['godown_code'],
        ] );

        return redirect()->route( 'godown.index' )->with( 'success', 'Godown information inserted successfully.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Godown  $godown
    * @return \Illuminate\Http\Response
    */

    public function show( Godown $godown ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Godown  $godown
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $godown = Godown::findorFail( $id );
            $provinces = Province::latest()->get();
            $districts = District::where( 'id', $godown->district_id )->latest()->get();
            return view( 'backend.godown.edit', compact( 'godown', 'provinces', 'districts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Godown  $godown
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $godown = Godown::findorFail( $id );
        $this->validate( $request, [
            'godown_name' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'local_address' => 'required',
            'godown_code' => 'required|unique:godowns,godown_code,'.$godown->id,
        ] );

        $godown->update( [
            'godown_name' => $request['godown_name'],
            'province_id' => $request['province_id'],
            'district_id' => $request['district_id'],
            'local_address' => $request['local_address'],
            'godown_code' => $request['godown_code']
        ] );

        return redirect()->route( 'godown.index' )->with( 'success', 'Godown information updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Godown  $godown
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            Godown::findorFail( $id )->delete();
            return redirect()->route( 'godown.index' )->with( 'success', 'Godown information deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restoreGodownInfo( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $godown_info = Godown::onlyTrashed()->findorFail( $id );
            $godown_info->restore();
            return redirect()->route( 'godown.index' )->with( 'success', 'Godown information is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function markasdefault(Request $request){
        if ( $request->user()->can( 'manage-godown-information' ) ) {
            $existingcount= Godown::where('is_default', 1)->count();
            if($existingcount > 0){
                $existing_default = Godown::where('is_default', 1)->first();
                $existing_default->update([
                    'is_default'=>'0'
                ]);
            }

            $new_default = Godown::where('id', $request['id'])->first();
            $new_default->update([
                'is_default'=>'1'
            ]);

            $response = array('status' => 'success','message' => 'Mark as Default Added');
            echo(json_encode($response));
        }
        else{
            return view('backend.permission.permission');
        }
    }

    public function getGodown(){
      return response()->json(['data'=>Godown::select('id','godown_name')->get()]);
    }

    public function saveGodownProductStock(Request $request){


            $godown_ids = $request->godown_id;
            $stocks = $request->stock;
            $product_id = $request->product_id;
            $total_stock = 0;
            if(!empty($product_id)){
            foreach($godown_ids as $key=>$godown){
                $total_stock += $stocks[$key] ?? 0;
                $godownProduct = GodownProduct::where('product_id',$product_id)->where('godown_id',$godown)->first();
                if(!empty($godownProduct)){
                $godownProduct->update(['stock'=>$godownProduct->stock + $stocks[$key] ?? 0]);
                }else{
                    GodownProduct::create([
                        'product_id'=>$product_id,
                        'godown_id'=>$godown,
                        'stock'=> $stocks[$key] ?? 0,
                        'opening_stock'=>0,
                        'alert_on'=>0,
                    ]);
                }

                ProductStock::create([
                    'product_id'=>$product_id,
                    'godown_id'=>$godown,
                    'added_stock'=>$stocks[$key] ?? 0,
                    'added_date'=>date('Y-m-d'),
                ]);


            }


          Product::where('id',$product_id)->increment('total_stock',$total_stock);


            return $total_stock;
        }

    }

    public function godownserialnumber(Request $request){

        $serialnumber = GodownSerialNumber::where('serial_number',$request->number)->first();
        if($serialnumber){
            return 'exist';
        }
        // print_r($serialnumber);exit;
    }
}
