<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\Credit;
use App\Models\District;
use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use App\Models\OpeningBalance;
use App\Models\PaymentInfo;
use App\Models\Province;
use App\Models\SalesBills;
use App\Models\Vendor;
use App\Models\Vendorconcern;
use App\Models\Setting;
use App\Models\SubAccount;
use App\Models\SuperSetting;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use function App\NepaliCalender\datenep;
use PDF;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class VendorController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'view-supplier' ) ) {
            $vendors = Vendor::with('vendorconcerns')->latest()->paginate(15);
            // dd($vendors);
            return view( 'backend.vendors.index', compact( 'vendors' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $vendors = Vendor::query()
        ->where( 'company_name', 'LIKE', "%{$search}%" )
        ->orWhere( 'company_email', 'LIKE', "%{$search}%" )
        ->orWhere( 'supplier_code', 'LIKE', "%{$search}%" )
        ->orWhere( 'company_phone', 'LIKE', "%{$search}%" )
        ->orWhere( 'pan_vat', 'LIKE', "%{$search}%" )
        ->orWhere( 'concerned_name', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.vendors.search', compact( 'vendors' ) );
    }

    public function deletedvendor( Request $request ) {
        if ( $request->user()->can( 'view-supplier' ) ) {
            $vendors = Vendor::onlyTrashed()->latest()->paginate( 10 );
            return view( 'backend.trash.supplierstrash', compact( 'vendors' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'create-supplier' ) ) {
            $provinces = Province::latest()->get();
            $suppliers = Vendor::latest()->get();
            $allsuppliercodes = [];
            foreach ( $suppliers as $supplier ) {
                array_push( $allsuppliercodes, $supplier->supplier_code );
            }
            $supplier_code = 'SU'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.vendors.create', compact( 'provinces', 'allsuppliercodes', 'supplier_code' ) );
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

    public function store( Request $request ) {

        $saveandcontinue = $request->saveandcontinue ?? 0;
        if ( $request->user()->can( 'create-supplier' ) ) {
            $this->validate( $request, [
                'company_name' => 'required',
                'company_email' => '',
                'company_phone' => '',
                'province' => '',
                'district' => '',
                'company_address' => '',
                'pan_vat' => '',
                'concerned_name' => '',
                'concerned_phone' => '',
                'concerned_email' => '',
                'designation' => '',
                'supplier_code' => 'nullable|unique:vendors',
                'logo' => 'mimes:png,jpg,jpeg',
                'opening_balance'=> 'required',
                'behaviour'=>'required',
            ]);

            if($request->hasfile('logo'))
            {
                $image = $request->file('logo');
                $imagename = $image->store('supplier_logo', 'uploads');
            }
            else
            {
                $imagename = 'favicon.png';
            }

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

            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);
            // Child Account
            DB::beginTransaction();
            try{
                $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
                // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
                $current_fiscal_year = FiscalYear::first();

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
                // Vendor
                $new_vendor = Vendor::create( [
                    'company_name' => $request['company_name'],
                    'company_email' => $request['company_email'],
                    'company_phone' => $request['company_phone'],
                    'province_id' => $request['province'],
                    'district_id' => $request['district'],
                    'company_address' => $request['company_address'],
                    'pan_vat' => $request['pan_vat'],
                    'supplier_code'=>$request['supplier_code'],
                    'logo' => $imagename,
                    'is_client'=>$request['is_client'] ?? 0,
                    'child_account_id' => $childAccount['id']
                ]);

                $new_vendor->save();
                if($request['is_client'] ==  1){//store to client also
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
                    'logo' => $imagename,
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

                $concern_names = $request['concerned_name'];
                $concern_phones = $request['concerned_phone'];
                $concern_emails = $request['concerned_email'];
                $concern_designation = $request['designation'];
                $concerncount = count($request['concerned_name']);
                $vendorconcerns = [];
                $default = 0;
                for($x = 0; $x<$concerncount; $x++){
                    if($x == 0){
                        $default = 1;
                    }else{
                        $default = 0;
                    }
                    $vendorconcerns[] = [
                        'vendor_id' => $new_vendor['id'],
                        'concerned_name' => $concern_names[$x],
                        'concerned_phone'=>$concern_phones[$x],
                        'concerned_email'=>$concern_emails[$x],
                        'designation'=>$concern_designation[$x],
                        'default'=>$default
                    ];
                }

                Vendorconcern::insert($vendorconcerns);
                DB::commit();
                if($saveandcontinue ==1){
                    return redirect()->back()->with( 'success', 'Supplier information successfully inserted.' );
                }

                if ( isset( $_POST['modal_button'] ) ) {
                    return redirect()->back()->with( 'success', 'Supplier information successfully inserted.' );
                } else {
                    return redirect()->route( 'vendors.index' )->with( 'success', 'Supplier information successfully inserted.' );
                }

            }
                catch(\Exception $e)
            {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Vendor  $vendor
    * @return \Illuminate\Http\Response
    */

    public function show( $id, Request $request ) {
        if($request->ajax()){
            return  Vendor::find( $id )->pan_vat ?? "";
        }
        if ( $request->user()->can( 'view-supplier' ) ) {
            $vendor = Vendor::find( $id );
            $journalvouchers = JournalVouchers::latest()->where('vendor_id', $id)->where('status', 1)->paginate(15);
            $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $vendor->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            $servicebillings = SalesBills::latest()->with( 'suppliers' )->where( 'vendor_id', $vendor->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            $debitNoteBillings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $vendor->id )->where( 'billing_type_id', 5 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            $vendorproducts = Billing::where('vendor_id', $id)->leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                            ->leftJoin('products', 'particulars', '=', 'products.id')
                            ->leftJoin('billingtypes', 'billings.billing_type_id', '=', 'billingtypes.id')
                            ->select('billings.id as billing_id', 'reference_no', 'quantity', 'eng_date', 'nep_date','product_name', 'rate', 'billing_types', 'billings.status as status')->paginate(10);
            return view( 'backend.vendors.view', compact( 'vendor', 'journalvouchers', 'billings','servicebillings', 'debitNoteBillings', 'vendorproducts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function supplierpurchase( Request $request, $id ) {
        if ( $request->user()->can( 'view-supplier' ) ) {
            $supplier = Vendor::findorFail( $id );
            $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $supplier->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            // dd( $billings );
            return view( 'backend.vendors.supplierpurchase', compact( 'supplier', 'billings' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function supplierServicepurchase(Request $request,$id){
        if ( $request->user()->can( 'view-supplier' ) ) {
            $supplier = Vendor::findorFail( $id );
            $billings = SalesBills::latest()->with( 'suppliers' )->where( 'vendor_id', $supplier->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            // dd( $billings );
            return view( 'backend.vendors.supplierServicepurchase', compact( 'supplier', 'billings' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function supplierdebitnote( Request $request, $id ) {
        if ( $request->user()->can( 'view-supplier' ) ) {
            $supplier = Vendor::findorFail( $id );
            $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $supplier->id )->where( 'billing_type_id', 5 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            return view( 'backend.vendors.supplierdebitnote', compact( 'supplier', 'billings' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Vendor  $vendor
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'edit-supplier' ) ) {
            $vendor = Vendor::findorFail( $id );
            $provinces = Province::latest()->get();
            $district = District::where( 'id', $vendor->district_id )->first();
            if ( $district == null ) {

                return view('backend.vendors.edit', compact( 'vendor', 'provinces', 'district' ) );
            } else {
                $district_group = District::where( 'province_id', $district->province_id )->get();
                return view('backend.vendors.edit', compact( 'vendor', 'provinces', 'district',  'district_group' ) );
            }
        } else {

            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Vendor  $vendor
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        if ( $request->user()->can( 'edit-supplier' ) ) {
            $vendor = Vendor::findorFail( $id );
            $this->validate( $request, [
                'company_name' => 'required',
                'company_email' => '',
                'company_phone' => '',
                'province' => '',
                'district' => '',
                'company_address' => '',
                'pan_vat' => '',
                'concerned_name' => '',
                'concerned_phone' => '',
                'concerned_email' => '',
                'designation' => '',
                'supplier_code' => 'nullable|unique:vendors,supplier_code,'.$vendor->id,
                'logo' => 'mimes:png,jpg,jpeg'
            ] );

            if($request->hasfile('logo'))
            {
                $image = $request->file('logo');
                $imagename = $image->store('supplier_logo', 'uploads');
            }
            else
            {
                $imagename = $vendor->logo;
            }
            DB::beginTransaction();
            try{
                $vendor->update( [
                    'company_name' => $request['company_name'],
                    'company_email' => $request['company_email'],
                    'company_phone' => $request['company_phone'],
                    'province_id' => $request['province'],
                    'district_id' => $request['district'],
                    'company_address' => $request['company_address'],
                    'pan_vat' => $request['pan_vat'],
                    'supplier_code' => $request['supplier_code'],
                    'logo' => $imagename,
                    'is_client'=>$request['is_client'] ?? 0,
                ] );

                $concerns = Vendorconcern::where('vendor_id', $id)->delete();

                $concern_names = $request['concerned_name'];
                $concern_phones = $request['concerned_phone'];
                $concern_emails = $request['concerned_email'];
                $concern_designation = $request['designation'];
                $concerncount = count($request['concerned_name']);
                $vendorconcerns = [];
                $default = 0;
                for($x = 0; $x<$concerncount; $x++){
                    if($x == 0){
                        $default = 1;
                    }else{
                        $default = 0;
                    }
                    $vendorconcerns[] = [
                        'vendor_id' => $vendor['id'],
                        'concerned_name' => $concern_names[$x],
                        'concerned_phone'=>$concern_phones[$x],
                        'concerned_email'=>$concern_emails[$x],
                        'designation'=>$concern_designation[$x],
                        'default'=>$default
                    ];
                }

                Vendorconcern::insert($vendorconcerns);

                //if is client
                $client = Client::where('child_account_id',$vendor->child_account_id)->first();

                if($request['is_client'] ==  1){//store to client also
                    if(!empty($client)){
                        $client->update([
                            'name'=>$request['company_name'],
                            'client_code'=>$request['supplier_code'],
                            'pan_vat'=>$request['pan_vat'],
                            'phone'=>$request['company_phone'],
                            'email'=>$request['company_email'],
                            'province'=>$request['province'],
                            'district'=>$request['district'],
                            'local_address'=>$request['company_address'],
                        ]);

                    }else{
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
                        'logo' => $imagename,
                        'is_vendor'=>1,
                        'child_account_id' => $vendor->child_account_id,
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
                }
                DB::commit();
                return redirect()->route( 'vendors.index' )->with( 'success', 'Supplier information successfully updated.' );
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Vendor  $vendor
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if ( $request->user()->can( 'remove-supplier' ) ) {
            $vendor = Vendor::findorFail( $id );
            $vendor->delete();

            return redirect()->route( 'vendors.index' )->with( 'success', 'Supplier information successfully deleted.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function getdistricts( $id ) {
        $districts = District::where( 'province_id', $id )->get();
        return response()->json( $districts );
    }

    public function restorevendor( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $deleted_vendor = Vendor::onlyTrashed()->findorFail( $id );
            $deleted_vendor->restore();
            return redirect()->route( 'vendors.index' )->with( 'success', 'Supplier information is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function makedefault(Request $request){
        DB::beginTransaction();
        try{
            $current_default = Vendorconcern::where('vendor_id', $request['vendor_id'])->where('default',1)->first();
            $current_default->update([
                'default'=>0,
            ]);

            $new_default = Vendorconcern::where('id', $request['concern_id'])->first();
            $new_default->update([
                'default'=>1,
            ]);
            DB::commit();
            $response = array('status' => 'success','message' => 'Default concerned person updated');
            echo(json_encode($response));

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    public function vendorproducts(Request $request, $id){
        if ( $request->user()->can( 'view-supplier' ) ) {
            $vendor = Vendor::findorFail( $id );

            $vendorproducts = Billing::where('vendor_id', $id)->leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                                        ->leftJoin('products', 'particulars', '=', 'products.id')
                                        ->leftJoin('billingtypes', 'billings.billing_type_id', '=', 'billingtypes.id')
                                        ->select('billings.id as billing_id', 'reference_no', 'quantity', 'eng_date', 'nep_date','product_name', 'rate', 'billing_types', 'billings.status as status')->paginate(15);
            // dd($vendorproducts);
            return view('backend.vendors.vendorproduct', compact('vendor', 'vendorproducts'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function supplierLedgers(Request $request)
    {

        $number = $request['number_to_filter'] != null ? $request['number_to_filter'] : 100;
        $vendors = Vendor::with(['purchaseBillings', 'purchaseReturnBillings'])->get();
        // dd($vendors);
        if($request->ajax()){
            return DataTables::of($vendors)
            ->addIndexColumn()
            ->addColumn('bill_amount',function ($row){

                $servicePurchaseTotal = $row->servicePurchasebillgrandtotal($row->id);
                // $servicePurchasepaidamount = $row->servicePurchasebillpaidamount($row->id);
                // $servicePurchaseRemaining = $servicePurchaseTotal - $servicePurchasepaidamount;
                $total_amt = $row->purchaseBillings->sum('grandtotal') - $row->purchaseReturnBillings->sum('grandtotal') + $servicePurchaseTotal;
                return 'Rs '.$total_amt;
            })

            ->addColumn('paid_amount',function($row){
                $servicePurchasepaidamount = $row->servicePurchasebillpaidamount($row->id);

                $total_paid_amount = 0;
                $total_returned_amount = 0;
                foreach ($row->purchaseBillings as $billing)
                {
                    $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                    $total_paid_amount += $paid_amount_sum;
                }
                foreach ($row->purchaseReturnBillings as $returnedBilling)
                {
                    $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                    $total_returned_amount += $received_amount_sum;
                }
               $total_paid_amount =  $total_paid_amount - $total_returned_amount + $servicePurchasepaidamount;
               return 'Rs '.$total_paid_amount;
            })
            ->addColumn('remaining_amount',function($row){
                $servicePurchaseTotal = $row->servicePurchasebillgrandtotal($row->id);
                $servicePurchasepaidamount = $row->servicePurchasebillpaidamount($row->id);
                $servicePurchaseRemaining = $servicePurchaseTotal - $servicePurchasepaidamount;
                $total_paid_amount = 0;
                $total_returned_amount = 0;
                foreach ($row->purchaseBillings as $billing)
                {
                    $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                    $total_paid_amount += $paid_amount_sum;
                }
                foreach ($row->purchaseReturnBillings as $returnedBilling)
                {
                    $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                    $total_returned_amount += $received_amount_sum;
                }
                $remaining_amt =  ($row->purchaseBillings->sum('grandtotal') - $total_paid_amount) - ($row->purchaseReturnBillings->sum('grandtotal') - $total_returned_amount + $servicePurchaseRemaining);
                return 'Rs '.$remaining_amt;
            })
            ->addColumn('action',function($row){
                $showurl = route('vendors.show',$row->id);
                $btn = "<div class='btn-bulk justify-content-center'>
                  <a href='$showurl' class='btn btn-primary icon-btn' title='View Supplier'><i class='fas fa-eye'></i></a>
                </div>";
            return $btn;
            })
            ->rawColumns(['bill_amount','paid_amount','remaining_amount','action'])
            ->make(true);
        };
        // dd($vendors);
        return view('backend.supplierLedgers', compact('vendors', 'number'));
    }

    public function supplierLedgersunpaid(Request $request, $paidStatus){
        $unpaidpaymentinfo = Billing::with(['payment_infos'=>function($query){
            return $query->where('payment_type','unpaid');
        }])->where('billing_type_id',2)->get();

        if($request->ajax()){
            return DataTables::of($unpaidpaymentinfo)
            ->editColumn('vendor_name',function($row){
                $vendor_name = $row->suppliers->company_name;
                return $vendor_name;

            })
            ->editColumn('remaining_amount',function($row){
                if(isset($row->payment_infos[0])){
                    return $row->grandtotal - $row->payment_infos[0]->total_paid_amount;
                }else{
                    return $row->grandtotal;
                }

            })
            ->editColumn('billing_id',function($row){
                return "<a href=". route('billings.show',$row->id).">".$row->reference_no."</a>";
            })
            ->editColumn('total_paid_amount',function($row){
                if(isset($row->payment_infos[0])){
                    return $row->payment_infos[0]->total_paid_amount;
                }else{
                    return 0;
                }

            })

            ->rawColumns(['vendor_name','remaining_amount','billing_id','total_paid_amount'])
            ->make(true);
        }

        return view('backend.supplierUnpaidLedgers', compact('unpaidpaymentinfo'));
    }

    public function supplierpurchasepdf($id){

        $supplier = Vendor::findorFail( $id );
        $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'vendor_id', $supplier->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->get();

        $created_at = date("Y-m-d", strtotime($supplier->created_at));
            $created_nepali_date = datenep($created_at);
            $setting = Setting::first();
            $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            $opciones_ssl=array(
                "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
                ),
            );
            $img_path = 'uploads/' . $currentcomp->company->company_logo;
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($data);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.vendors.downloadsupplierpurchase', compact('currentcomp', 'created_nepali_date', 'setting', 'path_img','supplier','billings'));
        return $pdf->download('Journals ('.$supplier->company_name.').pdf');

    }
    public function supplierPdf(){
        $suppliers = Vendor::latest()->get();

            $setting = Setting::first();
            $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            $opciones_ssl=array(
                "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
                ),
            );
            $img_path = 'uploads/' . $currentcomp->company->company_logo;
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($data);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.vendors.supplierspdf', compact('currentcomp', 'setting', 'path_img','suppliers'));
        return $pdf->download('Suppliers.pdf');
    }

    public function supplierLedgersgeneratepdf(Request $request){
        $number = $request['number_to_filter'] != null ? $request['number_to_filter'] : 100;
        $vendors = Vendor::with(['purchaseBillings', 'purchaseReturnBillings'])->get();
        $setting = Setting::first();
        $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
        $opciones_ssl=array(
            "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
        );
        $img_path = 'uploads/' . $currentcomp->company->company_logo;
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($data);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.vendors.downloadsupplierledger', compact('vendors','currentcomp', 'setting', 'path_img'));
        return $pdf->download('SupplierLedger.pdf');
    }
}
