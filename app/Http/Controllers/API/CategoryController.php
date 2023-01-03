<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('childrenCategories')
            ->orderBy('in_order', 'asc')
            ->get();
        return response()->json([
            'error' => false,
            'status' => 200,
            'message' => 'Category fetched successfully',
            'data' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'category_name' => 'required',
        //     'category_code' => 'required|unique:categories',
        //     'category_image' => 'required|mimes:png,jpg,jpeg'
        // ]);

        // if($request->hasfile('category_image')) {

        //     dd("What");
        //     $image = $request->file('category_image');
        //     $imagename = $image->store('category_images', 'uploads');
        //     $new_category = Category::create([
        //         'category_name' => $request['category_name'],
        //         'category_code' => $request['category_code'],
        //         'category_image' => $imagename
        //     ]);

        //     $new_category->save();

        //     return response()->json($new_category, 201);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
