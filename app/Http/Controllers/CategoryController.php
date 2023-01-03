<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoryImport;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->can('manage-product-categories')) {
            $currentcomp = UserCompany::where('user_id', auth()->user()->id)
                ->where('is_selected', 1)
                ->first();
            $categories = Category::whereNull('category_id')
                ->with('childrenCategories')
                ->orderBy('in_order', 'asc')
                ->where('company_id', $currentcomp->company_id)
                ->where('branch_id', $currentcomp->branch_id)
                ->get();

            return view('backend.category.index2', compact('categories'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function deletedcategory(Request $request)
    {
        if ($request->user()->can('manage-trash')) {
            $categories = Category::onlyTrashed()->latest()->paginate(10);
            return view('backend.trash.categorytrash', compact('categories'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::query()
            ->where('category_name', 'LIKE', "%{$search}%")
            ->orWhere('category_code', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('backend.category.search', compact('categories'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        if ($request->user()->can('manage-product-categories')) {
            $categories = Category::latest()->get();
            $allcategorycodes = [];
            foreach ($categories as $category) {
                array_push($allcategorycodes, $category->category_code);
            }
            $category_code = 'CT' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);

            return view('backend.category.create', compact('allcategorycodes', 'category_code'));
        } else {
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
        if ($request->user()->can('manage-product-categories')) {
            $saveandcontinue = $request->saveandcontinue ?? 0;
            $category_latest = Category::orderBy('in_order', 'desc')->first();
            if ($category_latest) {
                $category_order = $category_latest->in_order + 1;
            } else {
                $category_order = 1;
            }
            $this->validate($request, [
                'category_name' => 'required',
                'category_code' => 'required|unique:categories',
                'category_image' => 'mimes:png,jpg,jpeg'
            ]);

            $user = Auth::user()->id;
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();

            if ($request->hasfile('category_image')) {
                $image = $request->file('category_image');
                $imagename = $image->store('category_images', 'uploads');
            } else {
                $imagename = 'favicon.png';
            }

            $category = Category::create([
                'company_id' => $currentcomp->company_id,
                'branch_id' => $currentcomp->branch_id,
                'category_name' => $request['category_name'],
                'category_code' => $request['category_code'],
                'category_image' => $imagename,
                'in_order' => $category_order
            ]);

            $category->save();

            if (isset($_POST['modal_button'])) {
                return redirect()->back()->with('success', 'Category information successfully inserted.');
            }elseif($saveandcontinue == 1){
                return redirect()->route('category.create')->with('success', 'Category information is saved successfully.');
            } else {
                return redirect()->route('category.index')->with('success', 'Category information is saved successfully.');
            }
        } else {
            return view('backend.permission.permission');
        }
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

    public function edit($id, Request $request)
    {
        if ($request->user()->can('manage-product-categories')) {
            $category = Category::findorFail($id);
            return view('backend.category.edit', compact('category'));
        } else {
            return view('backend.permission.permission');
        }
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
        if ($request->user()->can('manage-product-categories')) {
            $category = Category::findorFail($id);

            $this->validate($request, [
                'category_name' => 'required',
                'category_code' => 'required|unique:categories,category_code,' . $category->id,
                'category_image' => 'mimes:png.jpg,jpeg'
            ]);

            if ($request->hasfile('category_image')) {
                $image = $request->file('category_image');
                $imagename = $image->store('category_images', 'uploads');
                $category->update([
                    'category_name' => $request['category_name'],
                    'category_code' => $request['category_code'],
                    'category_image' => $imagename
                ]);
            } else {
                $category->update([
                    'category_name' => $request['category_name'],
                    'category_code' => $request['category_code']
                ]);
            }

            return redirect()->route('category.index')->with('success', 'Category information is updated successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, Request $request)
    {
        if ($request->user()->can('manage-product-categories')) {
            $existing_category = Category::findorFail($id);
            $products = Product::where('category_id', $id)->get();
            if (count($products) > 0) {
                return redirect()->route('category.index')->with('error', 'There are products under this category. Cannot delete.');
            }
            $existing_category->delete();
            return redirect()->route('category.index')->with('success', 'Category information is deleted successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }

    public function restorecategory(Request $request, $id)
    {
        if ($request->user()->can('manage-trash')) {
            $deleted_category = Category::onlyTrashed()->findorFail($id);
            $deleted_category->restore();
            return redirect()->route('category.index')->with('success', 'Category information is restored successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }

    public function updateCategoryOrder(Request $request)
    {
        parse_str($request->sort, $arr);
        $order = 1;
        if (isset($arr['menuItem'])) {
            foreach ($arr['menuItem'] as $key => $value) {
                //id //parent_id
                $this->category->where('id', $key)
                    ->update([
                        'in_order' => $order,
                    ]);
                $order++;
            }
        }
        return true;
    }


    /**
     * @param Request $request
     */
    public function set_order(Request $request)
    {

        $categories = new Category();
        $list_order = $request['list_order'];

        $this->saveList($list_order, $request->parent_id);
        $data = array('status' => 'success');
        echo json_encode($data);
        exit;
    }

    /**
     * @param $list
     * @param int $parent_id
     * @param int $child
     * @param int $m_order
     */
    function saveList($list, $parent_id = 0, &$m_order = 0)
    {

        foreach ($list as $item) {
            $m_order++;
            $updateData = array("category_id" => $parent_id, "in_order" => $m_order);
            Category::where('id', $item['id'])->update($updateData);

            if (array_key_exists("children", $item)) {
                $this->saveList($item["children"], $item['id'], $m_order);
            }
        }
    }

    public function categoryImporter(){
        try {
            Excel::import(new CategoryImport, request()->file('csv_file'));
        } catch (\Throwable $th) {
           $error_message =  $th->getMessage();
            return redirect()->back()->with('error', $error_message);
        }
        return redirect()->back()->with('success', 'Uploaded successfully.');
    }
}
