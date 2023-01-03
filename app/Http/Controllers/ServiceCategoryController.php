<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceCategoryController extends Controller {
    protected $category;

    public function __construct( ServiceCategory $category )
    {
        $this->category = $category;
        $this->middleware( 'auth' );
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $categories = ServiceCategory::orderBy( 'in_order', 'asc' )->get();
            return view( 'backend.service.service_category.index', compact( 'categories' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $categories = ServiceCategory::query()
        ->where( 'category_name', 'LIKE', "%{$search}%" )
        ->orWhere( 'category_code', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.service.service_category.search', compact( 'categories' ) );
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request )
    {
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $categories = ServiceCategory::latest()->get();
            $allcategorycodes = [];
            foreach ( $categories as $category ) {
                array_push( $allcategorycodes, $category->category_code );
            }
            $category_code = 'CT'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.service.service_category.create', compact( 'allcategorycodes', 'category_code' ) );
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
        $saveandcontinue = $request->saveandcontinue ?? 0;
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $category_latest = ServiceCategory::orderBy( 'in_order', 'desc' )->first();
            if ( $category_latest ) {
                $category_order = $category_latest->in_order + 1;
            } else {
                $category_order = 1;
            }
            $this->validate( $request, [
                'category_name' => 'required',
                'category_code' => 'required|unique:categories',
                'category_image' => 'mimes:png,jpg,jpeg'
            ] );

            $user = Auth::user()->id;
            $currentcomp = UserCompany::where( 'user_id', $user )->where( 'is_selected', 1 )->first();

            if ( $request->hasfile( 'category_image' ) ) {
                $image = $request->file( 'category_image' );
                $imagename = $image->store( 'category_images', 'uploads' );
            } else
            {
                $imagename = 'favicon.png';
            }

            $new_category = ServiceCategory::create( [
                'company_id' => $currentcomp->company_id,
                'branch_id' => $currentcomp->branch_id,
                'category_name' => $request['category_name'],
                'category_code' => $request['category_code'],
                'category_image' => $imagename,
                'in_order' => $category_order
            ] );

            $new_category->save();
            if($saveandcontinue == 1){
                return redirect()->back()->with( 'success', 'Category information successfully inserted.' );
            }

            if ( isset( $_POST['modal_button'] ) ) {
                return redirect()->back()->with( 'success', 'Category information successfully inserted.' );
            } else {
                return redirect()->route( 'service_category.index' )->with( 'success', 'Category information is saved successfully.' );
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $category = ServiceCategory::findorFail( $id );
            return view( 'backend.service.service_category.edit', compact( 'category' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $category = ServiceCategory::findorFail( $id );

            $this->validate( $request, [
                'category_name' => 'required',
                'category_code' => 'required|unique:categories,category_code,'.$category->id,
                'category_image' => 'mimes:png,jpg,jpeg'
            ] );

            if ( $request->hasfile( 'category_image' ) ) {
                $image = $request->file( 'category_image' );
                $imagename = $image->store( 'category_images', 'uploads' );
                $category->update( [
                    'category_image' => $imagename
                ] );
            }

            $category->update( [
                'category_name' => $request['category_name'],
                'category_code' => $request['category_code']
            ] );

            return redirect()->route( 'service_category.index' )->with( 'success', 'Category information is updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if ( $request->user()->can( 'manage-service-categories' ) ) {
            $existing_category = ServiceCategory::findorFail( $id );
            $services = Service::where( 'service_category_id', $id )->get();
            if ( count( $services ) > 0 ) {
                return redirect()->route( 'service_category.index' )->with( 'error', 'Category cannot delete. There are services under this.' );
            }
            $existing_category->delete();
            return redirect()->route( 'service_category.index' )->with( 'success', 'Category information is deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function updateServiceCategoryOrder( Request $request )
    {
        parse_str( $request->sort, $arr );
        $order = 1;
        if ( isset( $arr['menuItem'] ) ) {
            foreach ( $arr['menuItem'] as $key => $value ) {
                //id //parent_id
                $this->category->where( 'id', $key )
                ->update( [
                    'in_order' => $order,
                ] );
                $order++;
            }
        }
        return true;
    }
}
