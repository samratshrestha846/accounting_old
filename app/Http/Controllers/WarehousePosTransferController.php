<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\Outlet;
use App\Models\OutletProduct;
use App\Models\Product;
use App\Models\WarehousePosTransfer;
use App\Models\WarehouseTransferProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehousePosTransferController extends Controller
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

    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $outletproducts = OutletProduct::latest()
            ->when($request->get('stock') == 'low', function($q) {
                return $q->whereRaw('primary_stock <= primary_stock_alert')
                ->orWhereRaw('secondary_stock <= secondary_stock_alert');
            })->paginate(10);

            return view('backend.warehousePosTransfer.index', compact('outletproducts'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.allserialnumbers')->latest()->get();
            $outlets = Outlet::all();
            $products = Product::all();
            return view('backend.warehousePosTransfer.create',compact('godowns', 'outlets', 'products'));
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
        $this->validate($request, [
            'godown_id' => 'required',
            'outlet_id' => 'required',
            'product_id.*' => 'integer',
            'product_id' => '',
            'primary_alert.*' => 'integer',
            'primary_alert' => '',
            'stock' => '',
            'stock.*' => 'integer',
            'transfer_eng_date' => 'required',
            'transfer_nep_date' => 'required',
            'remarks'=>''
        ]);
        DB::beginTransaction();
        try{
            $warehousePosTransfer = WarehousePosTransfer::create([
                'godown_id'=>$request['godown_id'],
                'outlet_id'=>$request['outlet_id'],
                'transfer_eng_date'=>$request['transfer_eng_date'],
                'transfer_nep_date'=>$request['transfer_nep_date'],
                'transfered_by'=>Auth::user()->id,
                'remarks'=>$request['remarks'],
            ]);

            $count = count($request['product_id']);
            $product_id = $request['product_id'];
            $stock = $request['stock'];
            $warehousePosTransferproducts = [];

            $products = Product::whereIn('id', $product_id)->select('id','secondary_number', 'total_stock')->get();

            for($x = 0; $x<$count; $x++ ){
                if($product_id[$x] == $products[$x]->id)
                {
                    //product stocks
                    $product = Product::findorFail($products[$x]->id);
                    $product->update([
                        'total_stock' => $products[$x]->total_stock - $stock[$x],
                    ]);

                    //godown product stocks
                    $godownproduct = GodownProduct::where('product_id', $products[$x]->id)->where('godown_id', $request['godown_id'])->first();
                    if(count($godownproduct->serialnumbers) > 0)
                    {
                        foreach ($godownproduct->serialnumbers as $productSerial)
                        {
                            if(in_array($productSerial->id, $request['serial_numbers_'.($x+1)]))
                            {
                                $productSerial->update(['is_pos_product' => 1]);
                            }
                        }
                    }
                    $godownproduct->update([
                        'stock' => $godownproduct->stock - $stock[$x]
                    ]);
                }
            }

            $existingoutletproducts = OutletProduct::whereIn('product_id', $products->pluck('id'))->get();
            $existingproduct_id = [];
            foreach($existingoutletproducts as $existing){
                array_push($existingproduct_id, $existing->product_id);
            }

            for($x = 0; $x < $count; $x++)
            {
                $warehousePosTransferproducts[] = [
                    'warehouse_pos_transfer_id' => $warehousePosTransfer['id'],
                    'product_id' => $product_id[$x],
                    'stock' =>$stock[$x],
                ];

                $outletProduct =  OutletProduct::where(['outlet_id' => $request['outlet_id'], 'product_id' => $product_id[$x]])->first();

                if($outletProduct)
                {
                    $outletProduct->update([
                        'primary_stock' => $outletProduct->primary_stock + $stock[$x],
                    ]);
                }
                else
                {
                    OutletProduct::create([
                        'product_id'=>$product_id[$x],
                        'outlet_id'=>$request['outlet_id'],
                        'primary_stock'=>$stock[$x],
                        'primary_opening_stock'=>$stock[$x],
                        'primary_stock_alert' => $request['primary_alert'][$x],
                    ]);
                }
            }

            WarehouseTransferProduct::insert($warehousePosTransferproducts);
            DB::commit();
            return redirect()->route('outlettransfer.index')->with('success, "Warehouse to Outlet Successfully Transferred');

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function transferrecords($product_id, Request $request)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $product = Product::where('id', $product_id)->select('product_name')->first()->product_name;
            $transferrecords = WarehouseTransferProduct::where('product_id', $product_id)
                                ->leftJoin('warehouse_pos_transfers', 'warehouse_pos_transfers.id', '=', 'warehouse_transfer_products.warehouse_pos_transfer_id')
                                ->paginate(15);
            return view('backend.warehousePosTransfer.transferrecords', compact('product', 'transferrecords'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function outletrecords($outlet_id, Request $request)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $outlet = Outlet::where('id', $outlet_id)->select('name')->first()->name;
            $outletproducts = WarehousePosTransfer::where('outlet_id', $outlet_id)
                                ->leftJoin('warehouse_transfer_products', 'warehouse_transfer_products.warehouse_pos_transfer_id', '=', 'warehouse_pos_transfers.id')
                                ->paginate(15);
            return view('backend.warehousePosTransfer.productrecords', compact('outlet', 'outletproducts'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function outletStockEdit(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {
            $outletproducts = OutletProduct::findorFail($id);
            $outletproducts->update([
                'primary_stock_alert' => $request['primary_stock_alert'],
            ]);

            return redirect()->back()->with('success', 'Alert stock is successfully updated.');
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function stockTransferToGodown(Request $request, $id)
    {
        if ( $request->user()->can( 'manage-pos-setting' ) ) {$outletProduct = OutletProduct::findorFail($id);
            $godownProduct = GodownProduct::where('godown_id', $request['godown'])->where('product_id', $outletProduct->product_id)->first();
            $product = Product::where('id', $outletProduct->product_id)->first();

            $standard_stock_unit = $outletProduct->secondary_opening_stock / $outletProduct->primary_opening_stock;

            DB::beginTransaction();
            try{
                if ($product->has_serial_number == 0)
                {
                    $stock_back = $request['stock_to_transfer'];
                }
                else if($product->has_serial_number == 1)
                {
                    $stock_back = count($request['serial_numbers']);
                    foreach ($product->godownproduct as $product_in_godown)
                    {
                        foreach($product_in_godown->outletserialnumbers as $serial_number)
                        {
                            if(in_array($serial_number->id, $request['serial_numbers']))
                            {
                                $serial_number->update(['is_pos_product' => 0]);
                            }
                        }
                    }
                }

                if($stock_back > $outletProduct->primary_stock)
                {
                    return redirect()->back()->with('error', 'Cannot transfer more stock than available.');
                }
                else if($stock_back == $outletProduct->primary_stock)
                {
                    $outlet_primary_stock = 0;
                    $outlet_secondary_stock = 0;
                }
                else
                {
                    $outlet_primary_stock = $outletProduct->primary_stock - $stock_back;
                    $secondary_stock_to_deduct = $stock_back * $standard_stock_unit;
                    $outlet_secondary_stock = $outletProduct->secondary_stock - $secondary_stock_to_deduct;
                }

                $godown_stock = $godownProduct->stock + $stock_back;
                $product_stock = $product->total_stock + $stock_back;

                $outletProduct->update([
                    'primary_stock' => $outlet_primary_stock,
                    'secondary_stock' => $outlet_secondary_stock,
                ]);

                $godownProduct->update([
                    'stock' => $godown_stock
                ]);

                $product->update([
                    'total_stock' => $product_stock
                ]);

                DB::commit();
                return redirect()->back()->with('success', 'Product stock successfully transfered.');

            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
