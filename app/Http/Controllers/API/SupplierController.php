<?php

namespace App\Http\Controllers\API;

use App\Helpers\HashPinNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnlinePaymentRequest;
use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Brand;
use App\Models\Budget;
use App\Models\Category;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\Clientconcern;
use App\Models\Credit;
use App\Models\DealerType;
use App\Models\DealerUser;
use App\Models\DealerUserCompany;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use App\Models\Outlet;
use App\Models\OutletBiller;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SubAccount;
use App\Models\SuperSetting;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vendor;
use App\Models\Vendorconcern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function app\NepaliCalender\datenep;

class SupplierController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Vendor::latest()->get();

        return response()->json($suppliers);
    }

    public function clientindex()
    {
        $clients = Client::with('dealertype')->latest()->get();


        return response()->json($clients);
    }

    public function brandindex()
    {
        $brands = Brand::latest()->get();
        return response()->json($brands);
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
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
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

            if($request['behaviour'] == 'debit'){
                $subaccount = SubAccount::where('slug', 'sundry-debtors')->first();
                if($subaccount == null){
                    $newsubaccount = SubAccount::create([
                        'title' => 'Sundry Debtors',
                        'slug' => Str::slug('Sundry Debtors'),
                        'account_id' => '1'
                    ]);
                    $newsubaccount->save();
                }

                $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
            }elseif($request['behaviour'] == 'credit'){
                $subaccount = SubAccount::where('slug', 'sundry-creditors')->first();
                if($subaccount == null){
                    $newsubaccount = SubAccount::create([
                        'title' => 'Sundry creditors',
                        'slug' => Str::slug('Sundry creditors'),
                        'account_id' => '2'
                    ]);
                    $newsubaccount->save();
                }
                $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
            }

            $childAccount = ChildAccount::create([
                'title' => $request['company_name'],
                'slug' => Str::slug($request['title']),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $subaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);
            $openingbalance->save();
            // vendor
            $new_vendor = Vendor::create([
                'company_name' => $request['company_name'],
                'supplier_code' => $request['company_code'],
                'company_email' => $request['company_email'],
                'company_phone' => $request['company_phone'],
                'province_id' => $request['province_id'],
                'district_id' => $request['district_id'],
                'company_address' => $request['company_address'],
                'pan_vat' => $request['pan_vat'],
                'is_client'=>$request['is_client'] ?? 0,
                'child_account_id' => $childAccount['id']
            ]);
            $new_vendor->save();
            if(isset($request['is_client']) && $request['is_client'] ==  1){//store to client also
                $client = Client::create([
                'client_type'=>1,
                'dealer_type_id'=>6,
                'name'=>$request['company_name'],
                'client_code'=>$request['supplier_code'],
                'pan_vat'=>$request['pan_vat'],
                'phone'=>$request['company_phone'],
                'email'=>$request['company_email'],
                'province'=>$request['province'],
                'district'=>$request['district'],
                'local_address'=>$request['company_address'],
                'is_vendor'=>1,
                'child_account_id' => $childAccount['id']
                ]);
                $supersetting = SuperSetting::first();

                $client_credit = Credit::create([
                    'customer_id' => $client->id,
                    'allocated_days' => $supersetting->allocated_days,
                    'allocated_bills' => $supersetting->allocated_bills,
                    'allocated_amount' => $supersetting->allocated_amount,
                ]);
                $client_credit->save();
            }

            Vendorconcern::create([
                'vendor_id' => $new_vendor['id'],
                'concerned_name' => $request['concerned_name'],
                'concerned_phone' => $request['concerned_phone'],
                'concerned_email' => $request['concerned_email'],
                'designation' => $request['designation'],
                'default' => 1,
            ]);

            $new_vendor->save();

                $new_vendor->child_acc_id=$childAccount->id;
                $new_vendor->child_acc_title=$childAccount->title;
                $new_vendor->sub_acc_title=$subaccount->title;


            DB::commit();
            return response()->json($new_vendor, 201);
        }
        catch(\Exception $e)
        {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function clientstore(Request $request)
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);
        DB::beginTransaction();
        try{
            // child Account
            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
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

            if($request['behaviour'] == 'debit'){
                $subaccount = SubAccount::where('slug', 'sundry-debtors')->first();
                if($subaccount == null){
                    $newsubaccount = SubAccount::create([
                        'title' => 'Sundry Debtors',
                        'slug' => Str::slug('Sundry Debtors'),
                        'account_id' => '1'
                    ]);
                    $newsubaccount->save();
                }

                $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
            }elseif($request['behaviour'] == 'credit'){
                $subaccount = SubAccount::where('slug', 'sundry-creditors')->first();
                if($subaccount == null){
                    $newsubaccount = SubAccount::create([
                        'title' => 'Sundry Creditors',
                        'slug' => Str::slug('Sundry creditors'),
                        'account_id' => '2'
                    ]);
                    $newsubaccount->save();
                }
                $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;
            }

            $childAccount = ChildAccount::create([
                'title' => $request['name'],
                'slug' => Str::slug($request['title']),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $subaccount_id,
            ]);

            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);
            $openingbalance->save();

            // Client
            $new_client = Client::create([
                'client_type' =>$request['client_type'],
                'dealer_type_id'=>$request['dealer_type_id'],
                'name' => $request['name'],
                'client_code' => $request['client_code'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'province' => $request['province'],
                'district' => $request['district'],
                'local_address' => $request['local_address'],
                'is_vendor' => $request['is_vendor'] ?? 0,
                'pan_vat' => $request['pan_vat'],
                'is_vendor'=>$request['is_vendor'] ?? 0,
                'child_account_id' => $childAccount['id']
            ]);

            $new_client->save();
            if($request['is_vendor'] == 1){
                Vendor::create([
                    'company_name' => $request['name'],
                    'company_email' => $request['email'],
                    'company_phone' => $request['phone'],
                    'province_id' => $request['province'],
                    'district_id' => $request['district'],
                    'company_address' => $request['local_address'],
                    'pan_vat' => $request['pan_vat'],
                    'supplier_code'=>$request['client_code'],
                    'is_client'=>1,
                    'child_account_id' => $childAccount['id']
                ]);
            }


            Clientconcern::create([
                'client_id' => $new_client['id'],
                'concerned_name' => $request['concerned_name'],
                'concerned_phone' => $request['concerned_phone'],
                'concerned_email' => $request['concerned_email'],
                'designation' => $request['designation'],
                'default' => 1
            ]);

            $supersetting = SuperSetting::first();

            Credit::create([
                'customer_id' => $new_client->id,
                'allocated_days' => $supersetting->allocated_days,
                'allocated_bills' => $supersetting->allocated_bills,
                'allocated_amount' => $supersetting->allocated_amount,
            ]);

            $new_client->save();
            $new_client->child_acc_id = $childAccount->id;
            $new_client->child_acc_title = $childAccount->title;
            $new_client->sub_acc_title = $subaccount->title;
            DB::commit();
            return response()->json($new_client, 201);
        }
        catch(\Exception $e)
        {

            DB::rollBack();
            throw new \Exception($e->getMessage());
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

    public function getinfo($id)
    {
        $budgetinfo = Budget::latest()->where('child_account_id', $id)->first();
        return response()->json($budgetinfo);
    }

    public function apiproductImage($id)
    {
        $productImage = ProductImages::latest()->where('product_id', $id)->first();
        return response()->json($productImage);
    }

    public function godown_stock($id)
    {
        $godown_stock = GodownProduct::latest()->where('product_id', $id)->get();
        return response()->json($godown_stock);
    }

    public function godown($id)
    {
        $godown = Godown::latest()->where('id', $id)->first();
        return response()->json($godown);
    }

    public function filterGodown($id)
    {
        $godowns = Godown::latest()->where('id', '!=', $id)->get();
        return response()->json($godowns);
    }

    public function allSerialNumbers($godown_id, $product_id)
    {
        $godownProduct = GodownProduct::latest()->with('serialnumbers')->where('godown_id', $godown_id)->where('product_id', $product_id)->first();
        return response()->json($godownProduct->allserialnumbers);
    }

    public function serialNumbers($godown_id, $product_id)
    {
        $godownProduct = GodownProduct::latest()->with('serialnumbers')->where('godown_id', $godown_id)->where('product_id', $product_id)->first();
        return response()->json($godownProduct->serialnumbers);
    }

    public function outletSerialNumbers($product_id)
    {
        $godownProduct = GodownProduct::latest()->with('serialnumbers')->where('product_id', $product_id)->first();
        return response()->json($godownProduct->outletserialnumbers);
    }

    public function damagedSerialNumbers($godown_id, $product_id)
    {
        $godownProduct = GodownProduct::latest()->with('serialnumbers')->where('godown_id', $godown_id)->where('product_id', $product_id)->first();
        return response()->json($godownProduct->damagedserialnumbers);
    }

    public function apiunits()
    {
        $units = Unit::all();
        return response()->json($units);
    }

    public function apiSecondaryUnits($id)
    {
        $units = Unit::where('id', '!=', $id)->get();
        return response()->json($units);
    }

    public function unitstore(Request $request)
    {
        $new_unit = Unit::create([
            'unit' => $request['unit'],
            'short_form' => $request['short_form'],
            'unit_code' => $request['unit_code']
        ]);

        $new_unit->save();

        return response()->json($new_unit, 201);
    }

    public function storeProductCategory(Request $request)
    {
        $user = Auth::user()->id;
        $currentcomp = UserCompany::where( 'user_id', $user )->where( 'is_selected', 1 )->first();
        $category_latest = Category::orderBy( 'in_order', 'desc' )->first();
        if($category_latest)
        {
            $category_order = $category_latest->in_order + 1;
        }
        else
        {
            $category_order = 1;
        }

        $new_category = Category::create([
            'company_id' => $currentcomp->company_id,
            'branch_id' => $currentcomp->branch_id,
            'category_name' => $request['category_name'],
            'category_code' => $request['category_code'],
            'category_image' => 'favicon.png',
            'in_order' => $category_order
        ]);

        return response()->json($new_category, 201);
    }

    public function storeServices(Request $request)
    {
        DB::beginTransaction();

        try{

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
            $newServices = Service::create([
                'service_name' => $request['service_name'],
                'service_code' => $request['service_code'],
                'service_category_id' => $request['category'],
                'cost_price' => $request['cost_price'],
                'sale_price' => $request['selling_price'],
                'description' => $request['description'],
                'status' => $request['status'],
                'opening_balance'=> $request['opening_balance'],
                'behaviour'=> $request['behaviour'],
                'child_account_id'=>$childAccount['id'],
            ]);
            $newServices->save();
            DB::commit();
            return response()->json($newServices, 201);
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    public function apibankinfo()
    {
        $bank_info = Bank::all();
        return response()->json($bank_info);
    }

    public function apiBankAccountType()
    {
        $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
        return response()->json($bankAccountTypes);
    }

    public function apiOnlinePortal()
    {
        $online_portals = OnlinePayment::all();
        return response()->json($online_portals);
    }

    public function apiServiceCategories()
    {
        $serviceCategories = ServiceCategory::orderBy('in_order', 'ASC')->get();
        return response()->json($serviceCategories);
    }

    public function apiServiceFromCategories($id)
    {
        $serviceFromCategory = Service::latest()->where('service_category_id', $id)->where('status', 1)->get();
        return response()->json($serviceFromCategory);
    }

    public function apiServices()
    {
        $services = Service::latest()->where('status', 1)->get();
        return response()->json($services);
    }

    public function apiService($id)
    {
        $service = Service::findorFail($id);
        return response()->json($service);
    }

    public function bankinfostore(Request $request)
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();

        DB::beginTransaction();
        try{
            // Child Account
            $account_type = BankAccountType::where('id', $request['account_type_id'])->first();
            // dd($account_type);

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
            $bankAccount = SubAccount::where('slug', 'bank')->first();
            if($bankAccount == null){
                $newbankaccount = SubAccount::create([
                    'title'=>'Bank',
                    'slug'=>Str::slug('bank'),
                    'account_id'=>1,
                    'sub_account_id'=>1,
                ]);
                $newbankaccount->save();
                $bankaccount_id = $newbankaccount['id'];
            }else{
                $bankaccount_id = $bankAccount->id;
            }
            $childAccount = ChildAccount::create([
                'title' => $request['bank_name'].'('.$account_type->account_type_name.')',
                'slug' => Str::slug($request['title']),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $bankaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);
            $openingbalance->save();

            // bank
            $bank_info = Bank::create([
                'bank_name' => $request['bank_name'],
                'head_branch' => $request['head_branch'],
                'bank_province_no' => $request['bank_province_no'],
                'bank_district_no' => $request['bank_district_no'],
                'bank_local_address' => $request['bank_local_address'],
                'account_no' => $request['account_no'],
                'account_name' => $request['account_name'],
                'account_type_id' => $request['account_type_id'],
                'child_account_id' => $childAccount['id'],
            ]);

            $bank_info->save();
            DB::commit();
            return response()->json($bank_info, 201);
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function bankAccountTypeStore(Request $request)
    {
        $bankAccountType = BankAccountType::create([
            'account_type_name' => $request['account_type_name']
        ]);

        return response()->json($bankAccountType, 201);
    }

    public function onlinePortalstore(StoreOnlinePaymentRequest $request)
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        DB::beginTransaction();
        try{
            // child Account
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
            $bankAccount = SubAccount::where('slug', 'bank')->first();
            if($bankAccount == null){
                $newbankaccount = SubAccount::create([
                    'title'=>'Bank',
                    'slug'=>Str::slug('bank'),
                    'account_id'=>1,
                    'sub_account_id'=>1,
                ]);
                $newbankaccount->save();
                $bankaccount_id = $newbankaccount['id'];
            }else{
                $bankaccount_id = $bankAccount->id;
            }
            $childAccount = ChildAccount::create([
                'title' => $request['name'].'('.$request['payment_id'].')',
                'slug' => Str::slug($request['name'].'('.$request['payment_id'].')'),
                'opening_balance' => $opening_balance,
                'sub_account_id' => $bankaccount_id,
            ]);
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childAccount['id'],
                'fiscal_year_id' => $current_fiscal_year->id,
                'opening_balance' => $opening_balance,
                'closing_balance' => $opening_balance
            ]);
            $openingbalance->save();
            $online_payment = OnlinePayment::create([
                'name'=>$request['name'],
                'payment_id'=>$request['payment_id'],
                'child_account_id'=>$childAccount['id']
            ]);
            $online_payment->save();
            DB::commit();
            return response()->json($online_payment, 201);
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function apiproduct($id)
    {
        $product = Product::findorFail($id);
        return response()->json($product);
    }

    public function getCreditInfo($id)
    {
        $credit_info = Credit::where('customer_id', $id)->where('converted', 0)->get();
        $credited_total_amount = Credit::where('customer_id', $id)->where('converted', 0)->get()->sum('credited_amount');
        $credited_bills = Credit::where('customer_id', $id)->where('converted', 0)->get()->count();

        return response()->json([
            'credits' => $credit_info,
            'credited_total_amount' => $credited_total_amount,
            'credited_bills' => $credited_bills
        ]);
    }

    public function billers()
    {
        $billers = OutletBiller::pluck('user_id');
        $user = Auth::user()->id;
        $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
        $users = User::with('users_roles', 'users_roles.role')->whereNotIn('id', $billers)->where(function ($query) use ($currentcomp){
            $query->whereHas('usercompany', function($q) use ($currentcomp){
                $q->where('company_id', $currentcomp->company_id);
                $q->where('branch_id', $currentcomp->branch_id);
            })
            ->whereHas('users_roles',function($q) {
                $q->where('role_id', 3);
            });
        })->latest()->get();

        return response()->json($users);
    }

    public function allbillers()
    {
        $user = Auth::user()->id;
        $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
        $users = User::with('users_roles', 'users_roles.role')->where(function ($query) use ($currentcomp){
            $query->whereHas('usercompany', function($q) use ($currentcomp){
                $q->where('company_id', $currentcomp->company_id);
                $q->where('branch_id', $currentcomp->branch_id);
            })
            ->whereHas('users_roles',function($q) {
                $q->where('role_id', 3);
            });
        })->latest()->get();

        return response()->json($users);
    }

    public function getBillers($id)
    {
        $billers = OutletBiller::where('user_id', '!=', $id)->get();
        return response()->json($billers);
    }

    public function outlets()
    {
        $outlets = Outlet::latest()->get();
        return response()->json($outlets);
    }
}
