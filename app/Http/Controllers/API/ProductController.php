<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\PosProductResource;
use App\Models\Outlet;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function getPosProductListByOuletId(Outlet $outlet, Request $request)
    {
        $perPage = $request->per_page;

        if(!auth()->user()->canAccessOutlet($outlet->id)){
            return response()->json([
                'error' => true,
                'status' => 401,
                'message' => "You are not authorized to view this outlet products"
            ], 401);
        }

        $products = $outlet->products()
            ->with('primaryUnit','secondaryUnit')
            ->wherePivot('primary_stock','>', 0)
            ->when($request->has('product_code'), function($q, $request){
                $q->where('products.product_code','LIKE','%'.$request->product_code.'%');
            })
            ->when($request->category_id, function($q, $value) {
                $q->where('products.category_id', $value);
            })
            ->active();


        // $products = QueryBuilder::for(Product::class)
        // ->allowedIncludes(['category','brand','series','vendor','product_images'])
        // ->with('outletProduct')
        // ->filters($request->all())
        // ->HasOutletsNotOfStock('outlets')
        // ->active();

        if($perPage && $perPage > 0){
            $products = $products->paginate($perPage);

            return PosProductResource::collection($products);
        }else{
            $products = $products->get();
        }

        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => 'Product fetched successfully',
            'data' =>  PosProductResource::collection($products)
        ]);
    }

    /**
     * Get product by outlet id & product code
     *
     * @
     */
    public function findPosProductByOutletIdAndProductCode(Outlet $outlet, $product_code)
    {
        $product = $outlet->products()
            ->with('primaryUnit','secondaryUnit')
            ->wherePivot('secondary_stock','>', 0)
            ->where('product_code', $product_code)
            ->orWhere('secondary_code', $product_code)
            ->active()
            ->firstOrFail();

        return $this->responseOk("Product fetched successfully", PosProductResource::make($product));
    }
}
