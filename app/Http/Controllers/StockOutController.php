<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\Product;
use App\Models\ProductNotification;
use App\Models\Province;
use App\Models\StockOutProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->can('manage-product-report')){
            $stockouts = StockOut::latest()->where('status', 1)->paginate(15);
            return view('backend.stockout.index', compact('stockouts'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovedindex(Request $request){
        if($request->user()->can('manage-product-report')){
            $stockouts = StockOut::latest()->where('status', 0)->paginate(15);
            return view('backend.stockout.unapprovedindex', compact('stockouts'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function stockoutsearch (Request $request){
        if($request->user()->can('manage-product-report')){
            $search = $request->input('search');
            $stockouts = StockOut::latest()->where('status', 1)->with('client')->where(function($query) use ($search){
                $query->where('stock_out_date', 'LIKE', "%{$search}%");
                $query->orWhereHas('client', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                });
            })->paginate(15);

            return view('backend.stockout.searchindex', compact('stockouts', 'search'));
        }
        else{
            return view('backend.permission.permission');
        }
    }

    public function stockoutunapprovedsearch(Request $request){
        if($request->user()->can('manage-product-report')){
            $search = $request->input('search');
            $stockouts = StockOut::latest()->where('status', 0)->with('client')->where(function($query) use ($search){
                $query->where('stock_out_date', 'LIKE', "%{$search}%");
                $query->orWhereHas('client', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                });
            })->paginate(15);

            return view('backend.stockout.searchunapprovedindex', compact('stockouts', 'search'));
        }
        else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if($request->user()->can('manage-product-report')){
            $clients = Client::all();
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->get();
            $provinces = Province::all();
            return view('backend.stockout.create', compact('clients', 'godowns', 'client_code', 'provinces'));
        }else{
            return view('backend.permission.permission');
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
        if($request->user()->can('manage-product-report'))
        {
            $data = $this->validate($request, [
                'client_id'=>'required',
                'stock_out_date'=>'required',
                'godown_id'=>'required',
                'product_id'=>'required',
                'quantity'=>'required',
                'status'=>'required',
            ]);

            DB::beginTransaction();
            try{
            $stockout = StockOut::create([
                'client_id'=>$data['client_id'],
                'stock_out_date'=>$data['stock_out_date'],
                'godown_id'=>$data['godown_id'],
                'status'=>$data['status'],
                'user_id'=>Auth::user()->id
            ]);

            $product_id  = $data['product_id'];
            $total_stock_out  = $data['quantity'];
            $product_count = count($product_id);
            $stockoutproducts = [];
            for($x = 0; $x < $product_count; $x++){
                $stockoutproducts[] = [
                    'stock_out_id' => $stockout['id'],
                    'product_id'=>$product_id[$x],
                    'total_stock_out'=>$total_stock_out[$x],
                ];

                if($request['status'] == 1){
                    $product = Product::findorfail($product_id[$x]);
                    $stock = $product->total_stock;
                    $remstock = $stock - $total_stock_out[$x];
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $product_id[$x])->where('godown_id', $request['godown_id'])->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock - $total_stock_out[$x];
                    $alert_on = $godownproduct->alert_on;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();

                    if($remgostock <= $alert_on){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$product_id[$x],
                            'godown_id'=>$request['godown_id'],
                            'noti_type'=>'low_stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }elseif($remgostock <= 0){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$product_id[$x],
                            'godown_id'=>$request['godown_id'],
                            'noti_type'=>'stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }
                }
            }

            StockOutProduct::insert($stockoutproducts);
            $stockout->save();
            DB::commit();
            return redirect()->route('stockout.index')->with('success', 'Stock Out Successfully Created');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->user()->can('manage-product-report')){
            $stockout = StockOut::findorfail($id);
            $clients = Client::all();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->get();
            return view('backend.stockout.show', compact('stockout', 'clients', 'godowns'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOut $stockOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockOut $stockOut)
    {
        //
    }

    public function approve(Request $request, $id){
        if($request->user()->can('manage-product-report')){
            $stockout = StockOut::findorfail($id);
            $stockout->update([
                'status'=> 1,
            ]);
            DB::beginTransaction();
            try{
            foreach($stockout->stockoutproducts as $stockoutproduct){
                $thisqty = $stockoutproduct->total_stock_out;
                $thisproduct_id = $stockoutproduct->product_id;
                $product = Product::findorfail($thisproduct_id);
                $stock = $product->total_stock;
                $remstock = $stock - $thisqty;
                $product->update([
                    'total_stock' => $remstock
                ]);
                $product->save();
                $godownproduct = GodownProduct::where('product_id', $thisproduct_id)->where('godown_id', $stockout->godown_id)->first();
                $gostock = $godownproduct->stock;
                $remgostock = $gostock - $thisqty;
                $alert_on = $godownproduct->alert_on;
                $godownproduct->update([
                    'stock' => $remgostock
                ]);
                $godownproduct->save();

                if($remgostock <= $alert_on){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$thisproduct_id,
                        'godown_id'=>$stockout->godown_id,
                        'noti_type'=>'low_stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }elseif($remgostock <= 0){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$thisproduct_id,
                        'godown_id'=>$stockout->godown_id,
                        'noti_type'=>'stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Successfully Approved');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprove(Request $request,$id){
        if($request->user()->can('manage-product-report')){
            $stockout = StockOut::findorfail($id);
            $stockout->update([
                'status'=> 0,
            ]);
            DB::beginTransaction();
            try{
            foreach($stockout->stockoutproducts as $stockoutproduct){
                $thisqty = $stockoutproduct->total_stock_out;
                $thisproduct_id = $stockoutproduct->product_id;
                $product = Product::findorfail($thisproduct_id);
                $stock = $product->total_stock;
                $remstock = $stock + $thisqty;
                $product->update([
                    'total_stock' => $remstock
                ]);
                $product->save();

                $godownproduct = GodownProduct::where('product_id', $thisproduct_id)->where('godown_id', $stockout->godown_id)->first();
                $gostock = $godownproduct->stock;
                $remgostock = $gostock + $thisqty;
                $alert_on = $godownproduct->alert_on;
                $godownproduct->update([
                    'stock' => $remgostock
                ]);
                $godownproduct->save();

                if($remgostock <= $alert_on){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$thisproduct_id,
                        'godown_id'=>$stockout->godown_id,
                        'noti_type'=>'low_stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }elseif($remgostock <= 0){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$thisproduct_id,
                        'godown_id'=>$stockout->godown_id,
                        'noti_type'=>'stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Successfully Unapprove');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        }else{
            return view('backend.permission.permission');
        }
    }
}
