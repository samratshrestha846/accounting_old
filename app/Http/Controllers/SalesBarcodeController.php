<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class SalesBarcodeController extends Controller
{
    public const TYPE = [
        1 => 'godown_serial_numbers',
        2 => 'products',
        3 => 'all',
        4 => 'product Name'
    ];
    public function getProduct(Request  $request)
    {
        //validation and fallback if validation fails
        $validation = Validator::make($request->all(), [
            'godown' => ['required', 'exists:godowns,id'],
            'type' => ['required', 'in:1,2,3,4'],
            'code' => ['required'],
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                "status_code" => 422,
                "message" => $validation->errors()->all()
            ], 422);
        }
        $data = $validation->validated();
        //search products according to criteria
        $products = Product::join('godown_products', 'godown_products.product_id', 'products.id')
            ->join('godown_serial_numbers', 'godown_serial_numbers.godown_product_id', 'godown_products.id')
            ->when($data['type'] == 1, fn ($query) => $query->where('godown_serial_numbers.serial_number', $data['code']))
            ->when($data['type'] == 2, fn ($query) => $query->where('products.product_code', $data['code']))
            ->when($data['type']  == 3, fn ($query) =>
            $query->where('godown_serial_numbers.serial_number', explode('/', $data['code'])[1] ?? $data['code'])
                ->orWhere('products.product_code', explode('/', $data['code'])[0]))
            ->where('godown_products.godown_id', $data['godown'])
            ->first();
        if (!$products) {
            return response()->json([
                'status' => false,
                'message' => 'No Products Found',
            ], 404);
        }
        $serialNumbers = $products->has_serial_number ? DB::table('godown_serial_numbers')->where('godown_product_id', $products->godown_product_id)->get() : null;
        return response()->json([
            'status' => true,
            'products' => $products,
            'serialNumbers' => $serialNumbers,
        ], 200);
    }
    public function productSearch(Request $request)
    {
        $products =  Product::select('products.*')
            ->join('godown_products', 'godown_products.product_id', 'products.id')
            ->where(function ($query) {
                $query->where('products.product_name', 'like', "%" . request('name') . "%")
                    ->orWhere('products.product_code', 'like', "%" . request('name') . "%");
            })
            ->where('godown_products.godown_id', request('godown'))
            ->limit(10)
            ->get();
        $view = view('backend.billings._includes.searchedProducts', compact('products'))->render();
        return response()->json($view, 200);
    }
}
