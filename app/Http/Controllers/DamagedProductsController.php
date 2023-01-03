<?php

namespace App\Http\Controllers;

use App\Models\DamagedProducts;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;

class DamagedProductsController extends Controller
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
        if($request->user()->can('manage-damaged-product')){
            $damagedproducts = DamagedProducts::latest()->paginate(10);

            $totalproductvalidation =  DamagedProducts::all()->sum(function($query) {
                $product = Product::where('id',$query->product_id)->first();
                return  $query->stock * $product->product_price;
            });
            // $totalproductvalidation = DamagedProducts::with(['product'=>function($q){

            //     return $q->sum('product_price');
            // }])->get();

            return view('backend.product_service.damaged_products.index', compact('damagedproducts','totalproductvalidation'));
        }
        else{
            return view('backend.permission.permission');
        }
    }

    public function deletedDamagedProducts(Request $request)
    {
        if($request->user()->can('manage-trash')){
            $damagedproducts = DamagedProducts::onlyTrashed()->latest()->paginate(10);
            return view('backend.trash.damagedproductstrash', compact('damagedproducts'));
        }
        else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $damagedproducts = DamagedProducts::with('product')->where(function($query) use ($search)
        {
            $query->where('stock', 'LIKE', '%' . $search . '%');
            $query->orWhereHas('product', function($q) use ($search) {
                $q->where(function($q) use ($search) {
                    $q->where('product_name', 'LIKE', '%' . $search . '%');
                    $q->orWhere('product_code', 'LIKE', '%' . $search . '%');
                });
            });

            $query->orWhereHas('godown', function($q) use ($search) {
                $q->where(function($q) use ($search) {
                    $q->where('godown_name', 'LIKE', '%' . $search . '%');
                });
            });

        })->latest()->paginate(10);

        return view('backend.product_service.damaged_products.search', compact('damagedproducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        if($request->user()->can('manage-damaged-product'))
        {
            $products = Product::with('godownproduct', 'godownproduct.godown')->latest()->get();
            return view('backend.product_service.damaged_products.create', compact('products'));
        }
        else{
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
        $this->validate($request, [
            'product' => 'required',
            'godown' => 'required',
            'stock' => '',
            'serial_numbers' => '',
            'document' => '',
            'document.*' => 'mimes:png,jpg,jpeg',
            'reason' => 'required'
        ]);

        $product = Product::findorFail($request['product']);

        if($product->has_serial_number == 1)
        {
            $stock = count($request['serial_numbers']);
        }
        elseif ($product->has_serial_number == 0)
        {
            $stock = $request['stock'];
        }

        if($request->hasfile('document')) {
            $image = $request->file('document');
            $imagename = $image->store('document', 'uploads');
        } else
        {
            $imagename = 'favicon.png';
        }

        $godown_stock = GodownProduct::where('godown_id', $request['godown'])->where('product_id', $request['product'])->first();

        if($stock > $godown_stock->stock)
        {
            return redirect()->back()->with('error', 'Damaged products can\'t be more than available stock.');
        }
        else
        {
            DamagedProducts::create([
                'product_id' => $request['product'],
                'godown_id' => $request['godown'],
                'stock' => $stock,
                'document' => $imagename,
                'reason' => $request['reason']
            ]);

            $remaining_stock_in_godown = $godown_stock->stock - $stock;
            $remaining_stock_in_product = $product->total_stock - $stock;

            $godown_stock->update(['stock' => $remaining_stock_in_godown]);
            $product->update(['total_stock' => $remaining_stock_in_product]);

            if($product->has_serial_number == 1)
            {
                for($i = 0; $i < $stock; $i++)
                {
                    $godown_serial_number = GodownSerialNumber::where('id', $request['serial_numbers'][$i])->first();
                    $godown_serial_number->update(['is_damaged' => 1]);
                }
            }
        }

        return redirect()->route('damaged_products.index')->with('success', 'Damaged products is inserted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DamagedProducts  $damagedProducts
     * @return \Illuminate\Http\Response
     */
    public function show(DamagedProducts $damagedProducts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DamagedProducts  $damagedProducts
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->user()->can('manage-damaged-product'))
        {
            $damaged_product = DamagedProducts::findorFail($id);
            $products = Product::latest()->get();

            $godown_product = GodownProduct::where('product_id', $damaged_product->product_id)->where('godown_id', $damaged_product->godown_id)->first();
            $all_serial_numbers = GodownSerialNumber::where('godown_product_id', $godown_product->id)->get();
            $damaged_serial_numbers = GodownSerialNumber::where('godown_product_id', $godown_product->id)->where('is_damaged', 1)->get();
            $damaged_numbers = [];
            foreach ($damaged_serial_numbers as $damaged_number) {
                array_push($damaged_numbers, $damaged_number->id);
            }

            $godown_array = [];
            foreach ($damaged_product->product->godownproduct as $godown_product)
            {
                array_push($godown_array, $godown_product->godown_id);
            }
            $related_godowns = Godown::whereIn('id', $godown_array)->get();
            return view('backend.product_service.damaged_products.edit', compact('damaged_product', 'products', 'related_godowns', 'all_serial_numbers', 'damaged_numbers'));
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DamagedProducts  $damagedProducts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $damaged_product = DamagedProducts::findorFail($id);
        $related_product = Product::where('id', $damaged_product->product_id)->first();
        $existing_stock_in_godown = GodownProduct::where('product_id', $damaged_product->product_id)->where('godown_id', $damaged_product->godown_id)->first();

        $previous_damaged_stock = $damaged_product->stock;

        $total_stock_in_godown = $existing_stock_in_godown->stock + $previous_damaged_stock;
        $total_stock_in_product = $related_product->total_stock + $previous_damaged_stock;

        $existing_stock_in_godown->update(['stock' => $total_stock_in_godown]);
        $related_product->update(['total_stock' => $total_stock_in_product]);

        $damaged_product->forceDelete();

        $this->validate($request, [
            'product' => '',
            'godown' => 'required',
            'stock' => '',
            'serial_numbers' => '',
            'document' => '',
            'document.*' => 'mimes:png,jpg,jpeg',
            'reason' => 'required'
        ]);

        if($related_product->has_serial_number == 1)
        {
            $stock = count($request['serial_numbers']);
        }
        elseif ($related_product->has_serial_number == 0)
        {
            $stock = $request['stock'];
        }

        if($request->hasfile('document')) {
            $image = $request->file('document');
            $imagename = $image->store('document', 'uploads');
        } else
        {
            $imagename = $damaged_product->document;
        }

        $godown_stock = GodownProduct::where('godown_id', $request['godown'])->where('product_id', $request['product'])->first();

        if($stock > $godown_stock->stock)
        {
            return redirect()->back()->with('error', 'Damaged products cant be more than available stock.');
        }
        else
        {
            $damaged_product = DamagedProducts::create([
                'product_id' => $request['product'],
                'godown_id' => $request['godown'],
                'stock' => $stock,
                'document' => $imagename,
                'reason' => $request['reason']
            ]);

            $damaged_product->save();

            $product = Product::where('id', $godown_stock->product_id)->first();
            $remaining_stock_in_godown = $godown_stock->stock - $stock;
            $remaining_stock_in_product = $product->total_stock - $stock;
            $godown_stock->update(['stock' => $remaining_stock_in_godown]);
            $product->update(['total_stock' => $remaining_stock_in_product]);

            if($product->has_serial_number == 1)
            {
                foreach($existing_stock_in_godown->damagedserialnumbers as $damaged)
                {
                    $damaged->update(['is_damaged' => 0]);
                }
                for($i = 0; $i < $stock; $i++)
                {
                    $godown_serial_number = GodownSerialNumber::where('id', $request['serial_numbers'][$i])->first();
                    $godown_serial_number->update(['is_damaged' => 1]);
                }
            }
        }
        return redirect()->route('damaged_products.index')->with('success', 'Damaged products is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DamagedProducts  $damagedProducts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-damaged-product'))
        {
            $damaged_product = DamagedProducts::findorFail($id);

            $related_product = Product::where('id', $damaged_product->product_id)->first();

            $existing_stock_in_godown = GodownProduct::where('product_id', $damaged_product->product_id)->where('godown_id', $damaged_product->godown_id)->first();

            $previous_damaged_stock = $damaged_product->stock;

            $total_stock_in_godown = $existing_stock_in_godown->stock + $previous_damaged_stock;
            $total_stock_in_product = $related_product->total_stock + $previous_damaged_stock;
            $existing_stock_in_godown->update(['stock' => $total_stock_in_godown]);
            $related_product->update(['total_stock' => $total_stock_in_product]);

            if ($damaged_product->product->has_serial_number == 1)
            {
                foreach($existing_stock_in_godown->damagedserialnumbers as $damaged)
                {
                    $damaged->update(['is_damaged' => 0]);
                }
            }

            $damaged_product->delete();
            return redirect()->route('damaged_products.index')->with('success', 'Damaged Product is deleted successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function restoreDamagedProducts($id, Request $request)
    {
        if($request->user()->can('manage-trash'))
        {
            $deletedDamagedProducts = DamagedProducts::onlyTrashed()->findorFail($id);

            $godown_product = GodownProduct::where('product_id', $deletedDamagedProducts->product_id)->where('godown_id', $deletedDamagedProducts->godown_id)->first();
            $product = Product::where('id', $godown_product->product_id)->first();

            $godown_stock = $godown_product->stock - $deletedDamagedProducts->stock;
            $product_stock = $product->total_stock - $deletedDamagedProducts->stock;

            $godown_product->update(['stock' => $godown_stock]);
            $product->update(['total_stock' => $product_stock]);

            $deletedDamagedProducts->restore();
            return redirect()->route('damaged_products.index')->with('success', 'Damaged Product is restored successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
