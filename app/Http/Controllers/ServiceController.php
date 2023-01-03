<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\ProductImages;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SubAccount;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function app\NepaliCalender\datenep;

class ServiceController extends Controller
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
        if($request->user()->can('manage-services')) {
            $services = Service::latest()->paginate( 10 );
            $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
            return view( 'backend.service.index', compact( 'currentcomp', 'services' ) );
        }else {
            return view('backend.permission.permission');
        }
    }

    public function deletedservice(Request $request)
    {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $services = Service::onlyTrashed()->latest()->paginate( 10 );
            return view( 'backend.trash.servicetrash', compact( 'services' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function searchservice( Request $request ) {
        $search = $request->input( 'search' );
        $services = Service::query()
        ->where( 'service_name', 'LIKE', "%{$search}%" )
        ->orWhere( 'service_code', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );
        $currentcomp = UserCompany::where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();
        return view( 'backend.service.search', compact( 'services', 'currentcomp' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('manage-services')) {
            $service_categories = ServiceCategory::orderBy('in_order', 'asc')->get();
            $services = Service::latest()->get();
            $allservicecodes = [];
            foreach ( $services as $service ) {
                array_push( $allservicecodes, $service->service_code );
            }
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.service.create', compact( 'service_categories', 'allservicecodes', 'service_code' ) );
        }else {
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
        if($request->user()->can('manage-services')) {
            $this->validate($request, [
                'service_name' => 'required',
                'service_code' => 'required|unique:services',
                'category' => 'required',
                'cost_price' => 'required',
                'selling_price' => 'required',
                'description' => 'required',
                'status' => '',
                'service_image' => '',
                'service_image.*' => 'image|mimes:jpg,jpeg,png',
                'opening_balance'=> 'required',
                'behaviour'=>'required',
            ]);

            if ($request['status'] == null) {
                $status = 0;
            } else {
                $status = 1;
            }
            DB::beginTransaction();
            try{
                // Child Account
                if($request['opening_balance'] == null){
                    $opening_balance = 0;
                }else{
                    if($request['behaviour'] == "credit")
                    {
                        $opening_balance = '-'.$request['opening_balance'];
                    }
                    elseif($request['behaviour'] == "debit")
                    {
                        $opening_balance = $request['opening_balance'];
                    }
                }

                $subaccount = SubAccount::where('slug', 'service')->first();
                if($subaccount == null){
                    $newsubaccount = SubAccount::create([
                        'title' => 'Service',
                        'slug' => Str::slug('Service'),
                        'account_id' => '1',
                        'sub_account_id' => '8'
                    ]);
                    $newsubaccount->save();
                }

                $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;

                $childAccount = ChildAccount::create([
                    'title' => $request['service_name'],
                    'slug' => Str::slug($request['service_name']),
                    'opening_balance' => $opening_balance,
                    'sub_account_id' => $subaccount_id
                ]);
                $date = date("Y-m-d");
                $nepalidate = datenep($date);
                $exploded_date = explode("-", $nepalidate);

                $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
                // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
                $current_fiscal_year = FiscalYear::first();
                OpeningBalance::create([
                    'child_account_id' => $childAccount['id'],
                    'fiscal_year_id' => $current_fiscal_year->id,
                    'opening_balance' => $opening_balance,
                    'closing_balance' => $opening_balance
                ]);

                // Service

                $service = Service::create([
                    'service_name' => $request['service_name'],
                    'service_code' => $request['service_code'],
                    'service_category_id' => $request['category'],
                    'cost_price' => $request['cost_price'],
                    'sale_price' => $request['selling_price'],
                    'description' => $request['description'],
                    'status' => $status,
                    'child_account_id' => $childAccount['id'],
                ]);

                $imagename = '';
                if($request->hasfile('service_image')) {
                    $images = $request->file('service_image');
                    foreach($images as $image){
                        $imagename = $image->store('item_images', 'uploads');
                        $service_image = ProductImages::create([
                            'service_id' => $service['id'],
                            'location' => $imagename,
                        ]);
                        $service_image->save();
                    }
                } else {
                    $service_image = ProductImages::create([
                        'service_id' => $service['id'],
                        'location' => 'favicon.png',
                    ]);
                    $service_image->save();
                }

                $service->save();
                DB::commit();
                return redirect()->route('service.index')->with('success', 'Service information is successfully inserted.');

            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if($request->user()->can('manage-services')) {
            $service = Service::findorFail($id);
            $service_images = ProductImages::where( 'service_id', $service->id )->latest()->get();
            return view('backend.service.view', compact('service', 'service_images'));
        }else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if($request->user()->can('manage-services')) {
            $service = Service::findorFail($id);
            $service_images = ProductImages::where('service_id', $service->id)->get();
            $service_categories = ServiceCategory::latest()->get();
            return view('backend.service.edit', compact('service', 'service_images', 'service_categories'));
        }else {
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->can('manage-services')){
            if(isset($_POST['submit'])){
                $service = Service::findorFail($id);

                $this->validate($request, [
                    'service_name' => 'required',
                    'service_code' => 'required|unique:services,service_code,'.$service->id,
                    'category' => 'required',
                    'cost_price' => 'required',
                    'selling_price' => 'required',
                    'description' => 'required',
                    'service_status' => ''
                ]);

                if ($request['service_status'] == null)
                {
                    $status = 0;
                }
                else
                {
                    $status = 1;
                }

                $service->update([
                    'service_name' => $request['service_name'],
                    'service_code' => $request['service_code'],
                    'service_category_id' => $request['category'],
                    'cost_price' => $request['cost_price'],
                    'sale_price' => $request['selling_price'],
                    'description' => $request['description'],
                    'status' => $status
                ]);

                return redirect()->route('service.index')->with('success', 'Service information is successfully updated.');
            }
            elseif(isset($_POST['update']))
            {
                $service = Service::findorFail($id);

                $this->validate($request, [
                    'service_image' => '',
                    'service_image.*' => 'mimes:png,jpg,jpeg',
                ]);

                $imagename = '';

                if($request->hasfile('service_image')) {

                    $images = $request->file('service_image');
                    foreach($images as $image){
                        $imagename = $image->store('item_images', 'uploads');
                        $service_images = ProductImages::create([
                            'service_id' => $service->id,
                            'location' => $imagename,
                        ]);
                        $service_images->save();
                    }
                }
                return redirect()->back()->with('success', 'Service images is successfully updated.');
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if($request->user()->can('manage-services'))
        {
            $service = Service::findorFail($id);
            $service->delete();
            return redirect()->route('service.index')->with('success', 'Service information is successfully deleted.');
        }
        else
        {
            return view('backend.permission.permission');
        }

    }

    public function deleteserviceimage($id,Request $request)
    {
        if($request->user()->can('manage-services'))
        {
            $service_image = ProductImages::findorfail($id);
            $images = ProductImages::where('product_id', $service_image->product_id)->get();
            if(count($images) < 2){
                return redirect()->back()->with('error', 'Only one image cannot be deleted.');
            }

            $service_image->delete();
            return redirect()->back()->with('success', 'Image Removed Successfully');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function restoreservice($id, Request $request)
    {
        if($request->user()->can('manage-trash'))
        {
            $deleted_service = Service::onlyTrashed()->findorFail($id);
            $category = Category::onlyTrashed()->where('id', $deleted_service->category_id)->first();
            if($category)
            {
                return redirect()->back()->with('error', 'Category is not present or is soft deleted. Check Category Module.');
            }
            $deleted_service->restore();
            return redirect()->route('service.index')->with('success', 'Service information is restored successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
