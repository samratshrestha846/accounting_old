<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Http\Request;

class BrandController extends Controller
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
        if($request->user()->can('manage-brand-information'))
        {
            $brands = Brand::latest()->paginate(10);
            return view('backend.brand.index', compact('brands'));
        }else{
            return view('backend.permission.permission');
        }
    }


    public function deletedbrand(Request $request)
    {
        if($request->user()->can('manage-brand-information')){
            $brands = Brand::onlyTrashed()->latest()->paginate(10);
            return view('backend.trash.brandtrash', compact('brands'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request){
        $search = $request->input('search');

        $brands = Brand::query()
            ->where('brand_name', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);
        return view('backend.brand.search', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('manage-brand-information'))
        {
            $brands = Brand::all();
            $allbrandcodes = [];
            foreach($brands as $brand){
                array_push($allbrandcodes, $brand->brand_code);
            }
            $branch_code = 'BD'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            return view('backend.brand.create', compact('allbrandcodes', 'branch_code'));
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
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $this->validate($request, [
            'brand_name' => 'required',
            'brand_code' => 'required|unique:brands',
            'brand_logo' => 'mimes:png,jpg,jpeg'
        ]);

        if($request->hasfile('brand_logo')) {
            $image = $request->file('brand_logo');
            $imagename = $image->store('brand_logo', 'uploads');
        } else
        {
            $imagename = 'favicon.png';
        }

        Brand::create([
            'brand_name' => $request['brand_name'],
            'brand_code' => $request['brand_code'],
            'brand_logo' => $imagename
        ]);

        if(isset($_POST['brand_modal_button'])){
            return redirect()->back()->with('success', 'Brand information successfully inserted.');
        }elseif($saveandcontinue == 1){
            return redirect()->route('brand.create')->with('success', 'Brand information is saved successfully.');
        } else {
            return redirect()->route('brand.index')->with('success', 'Brand information is saved successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->user()->can('manage-brand-information'))
        {
            $existing_brand = Brand::findorFail($id);
            return view('backend.brand.edit', compact('existing_brand'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existing_brand = Brand::findorFail($id);
        $this->validate($request, [
            'brand_name' => 'required',
            'brand_code' => 'required|unique:brands,brand_code,'.$existing_brand->id,
            'brand_logo' => 'mimes:png,jpg,jpeg'
        ]);

        if($request->hasfile('brand_logo'))
        {
            $image = $request->file('brand_logo');
            $imagename = $image->store('brand_logo', 'uploads');
            $existing_brand->update([
                'brand_name' => $request['brand_name'],
                'brand_code' => $request['brand_code'],
                'brand_logo' => $imagename
            ]);
        }
        else
        {
            $existing_brand->update(['brand_name' => $request['brand_name'], 'brand_code' => $request['brand_code']]);
        }
        return redirect()->route('brand.index')->with('success', 'Brand information is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-brand-information'))
        {
            $existing_brand = Brand::findorFail($id);
            $products = Product::where('brand_id', $existing_brand->id)->get();

            if(count($products) > 0){
                return redirect()->back()->with('error', 'Products are available in brand. Cant delete.');
            }

            $series = Series::where('brand_id', $existing_brand->id)->get();

            foreach ($series as $serie) {
                $serie->delete();
            }
            $existing_brand->delete();
            return redirect()->route('brand.index')->with('success', 'Brand information is deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restorebrand(Request $request, $id)
    {
        if($request->user()->can('manage-trash')){
            $deleted_brand = Brand::onlyTrashed()->findorFail($id);
            $deleted_brand->restore();
            return redirect()->route('brand.index')->with('success', 'Brand information is restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function getSeries($id)
    {
        $series = Series::where('brand_id', $id)->latest()->get();
        return response()->json($series);
    }
}
