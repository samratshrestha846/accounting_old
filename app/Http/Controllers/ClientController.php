<?php

namespace App\Http\Controllers;

use App\Helpers\HashPinNumber;
use App\Models\Billing;
use App\Models\BillingCredit;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\Clientconcern;
use App\Models\Credit;
use App\Models\DealerType;
use App\Models\DealerUser;
use App\Models\DealerUserCompany;
use App\Models\District;
use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use App\Models\OpeningBalance;
use App\Models\Province;
use App\Models\PaymentInfo;
use App\Models\SuperSetting;
use App\Models\Setting;
use App\Models\SubAccount;
use App\Models\UserCompany;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function App\NepaliCalender\datenep;
use PDF;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class ClientController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'view-customer' ) ) {
            $clients = Client::latest()->paginate( 10 );
            // dd($clients);
            return view( 'backend.client.index', compact('clients') );
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
        if ( $request->user()->can( 'create-customer' ) ) {
            $provinces = Province::latest()->get();
            $clients = Client::latest()->get();
            $dealerTypes = DealerType::latest()->get();
            $allclientcodes = [];
            foreach ( $clients as $client ) {
                array_push( $allclientcodes, $client->client_code );
            }
            $client_code = 'CL'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            return view( 'backend.client.create', compact( 'allclientcodes', 'provinces', 'client_code', 'dealerTypes' ) );
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
        if ( $request->user()->can( 'create-customer' ) ) {
            $data = $this->validate( $request, [
                'client_type'=>'required',
                'dealer_type_id'=>'required',
                'name'=>'required',
                'client_code'=>'nullable|unique:clients',
                'pan_vat'=>'',
                'phone'=>'',
                'province'=>'',
                'district'=>'',
                'local_address'=>'',
                'concerned_name'=>'',
                'concerned_email'=>'',
                'concerned_phone'=>'',
                'designation'=>'',
                'email'=>'nullable|string|email|max:255',
                'password' => 'nullable|min:8',
                'logo' => 'mimes:png,jpg,jpeg',
                'opening_balance'=> 'required',
                'behaviour'=>'required',
                'is_vendor'=>'',
            ]);
            $user = Auth::user()->id;
            $dealerType = DealerType::find($data['dealer_type_id']);
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
            if($dealerType->make_user == 1){
                $similar_email_count = DB::table('dealer_users')->where('email', $data['email'])->count();
                // dd($similar_email_count);
                if($similar_email_count > 0){
                    return redirect()->back()->with('error', 'Email already Exists');
                }
            }
            if($request->hasfile('logo'))
            {
                $image = $request->file('logo');
                $imagename = $image->store('clients_logo', 'uploads');
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
            DB::beginTransaction();
            try{
                // Child Account
                $date = date("Y-m-d");
                $nepalidate = datenep($date);
                $exploded_date = explode("-", $nepalidate);

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
                $client = Client::create( [
                    'client_type'=>$data['client_type'],
                    'dealer_type_id'=>$data['dealer_type_id'],
                    'name'=>$data['name'],
                    'client_code'=>$data['client_code'],
                    'pan_vat'=>$data['pan_vat'],
                    'phone'=>$data['phone'],
                    'email'=>$data['email'],
                    'province'=>$data['province'],
                    'district'=>$data['district'],
                    'local_address'=>$data['local_address'],
                    'logo' => $imagename,
                    'is_vendor'=>$data['is_vendor'] ?? 0,
                    'child_account_id' => $childAccount['id']
                ]);

                $client->save();
                if(isset($data['is_vendor']) && $data['is_vendor'] == 1){
                    Vendor::create([
                        'company_name' => $data['name'],
                        'company_email' => $data['email'],
                        'company_phone' => $data['phone'],
                        'province_id' => $data['province'],
                        'district_id' => $data['district'],
                        'company_address' => $data['local_address'],
                        'pan_vat' => $data['pan_vat'],
                        'supplier_code'=>$data['client_code'],
                        'logo' => $imagename,
                        'is_client'=>1,
                        'child_account_id' => $childAccount['id']
                    ]);
                }

                $concern_names = $request['concerned_name'];
                $concern_phones = $request['concerned_phone'];
                $concern_emails = $request['concerned_email'];
                $concern_designation = $request['designation'];
                $concerncount = count($request['concerned_name']);
                $clientconcerns = [];
                $default = 0;
                for($x = 0; $x<$concerncount; $x++){
                    if($x == 0){
                        $default = 1;
                    }else{
                        $default = 0;
                    }
                    $clientconcerns[] = [
                        'client_id' => $client['id'],
                        'concerned_name' => $concern_names[$x],
                        'concerned_phone'=>$concern_phones[$x],
                        'concerned_email'=>$concern_emails[$x],
                        'designation'=>$concern_designation[$x],
                        'default'=>$default
                    ];
                }

                Clientconcern::insert($clientconcerns);

                $supersetting = SuperSetting::first();

                $client_credit = Credit::create([
                    'customer_id' => $client->id,
                    'allocated_days' => $supersetting->allocated_days,
                    'allocated_bills' => $supersetting->allocated_bills,
                    'allocated_amount' => $supersetting->allocated_amount,
                ]);
                $client_credit->save();
                if(!$request['email'] == null){
                    if($dealerType->make_user == 1){
                        $similar_email_count = DB::table('dealer_users')->where('email', $data['email'])->count();
                        $dealeruser = DealerUser::create([
                            'client_id' => $client['id'],
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                            'pin_number' => (new HashPinNumber)->make(mt_rand(1111,9999)),
                        ]);
                        $dealerusercompany = DealerUserCompany::create([
                            'dealer_user_id' => $dealeruser['id'],
                            'company_id' => $currentcomp->company_id,
                            'branch_id'=>$currentcomp->branch_id,
                            'is_selected'=>'1',
                        ]);

                        $dealerusercompany->save();
                    }
                }
                DB::commit();
                if($saveandcontinue == 1){
                    return redirect()->back()->with( 'success', 'Client created successfullly with desired credit account.' );
                }else{
                    return redirect()->route( 'client.index' )->with( 'success', 'Client created successfullly with desired credit account.' );
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
    * @param  \App\Models\Client  $client
    * @return \Illuminate\Http\Response
    */

    public function show( Request $request, $id ) {
        if($request->ajax()){
            $client = Client::find($id);
            $pan_vat = $client->pan_vat ?? "0";
            $total_receiveable_credit = BillingCredit::where('client_id',$id)->sum('credit_amount');

            $vendor = Vendor::where('child_account_id',$client->child_account_id)->first();
            $total_payable_amount = (!empty($vendor)) ? BillingCredit::where('vendor_id',$vendor->id)->sum('credit_amount'): 0 ;
            return response()->json([
                'pan_vat' => $pan_vat,
                'total_credit' => $total_receiveable_credit,
                'total_payable_amount'=>$total_payable_amount,
            ]);
        }
        if ( $request->user()->can( 'view-customer' ) ) {
            $client = Client::findorFail( $id );
            $dealerTypes = DealerType::latest()->get();
            $journalvouchers = JournalVouchers::latest()->where('client_id', $id)->where('status', 1)->paginate(15);
            $salesBillings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 1 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            $creditNoteBillings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 6 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            $clientproducts = Billing::where('client_id', $id)->leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                            ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                            ->leftJoin('products', 'particulars', '=', 'products.id')
                            ->leftJoin('billingtypes', 'billings.billing_type_id', '=', 'billingtypes.id')
                            ->select('billings.id as billing_id', 'reference_no', 'quantity', 'eng_date', 'nep_date', 'godown_name','product_name', 'rate', 'billing_types', 'billings.status as status')->paginate(10);
            return view( 'backend.client.show', compact( 'client', 'journalvouchers', 'salesBillings', 'creditNoteBillings', 'clientproducts', 'dealerTypes') );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function clientsales( Request $request, $id ) {
        if ( $request->user()->can( 'view-customer' ) ) {
            $client = Client::findorFail( $id );
            $billings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 1 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            // dd( $billings );
            return view( 'backend.client.clientsales', compact( 'client', 'billings' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function clientcreditnote( Request $request, $id ) {
        if ( $request->user()->can( 'view-customer' ) ) {
            $client = Client::findorFail( $id );
            $billings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 6 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
            return view( 'backend.client.clientcreditnote', compact( 'client', 'billings' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Client  $client
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {

        if ( $request->user()->can( 'edit-customer' ) ) {
            $clt = Client::findorFail( $id );
            $provinces = Province::latest()->get();
            $allclientcodes = [];
            $dealerTypes = DealerType::latest()->get();
            $clients = Client::latest()->get();
            foreach ( $clients as $client ) {
                array_push( $allclientcodes, $client->client_code );
            }
            $district = District::where( 'id', $client->district )->first();


            $districts = District::all();
            if ( $district == null ) {
                return view( 'backend.client.edit', compact( 'clt', 'provinces','districts','district', 'allclientcodes', 'dealerTypes') );
            } else {
                $district_group = District::where( 'province_id', $district->province_id )->get();
                return view( 'backend.client.edit', compact( 'clt', 'provinces','districts' ,'district',  'district_group', 'allclientcodes','dealerTypes' ) );
            }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Client  $client
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        // dd($request->all());
        if ( $request->user()->can( 'edit-customer' ) ) {
            // dd( );
            DB::beginTransaction();
            try{
                $client = Client::findorFail( $id );
                $data = $this->validate( $request, [
                    'client_type'=>'required',
                    'dealer_type_id'=>'required',
                    'name'=>'required',
                    'client_code'=>'nullable|unique:clients,client_code,'.$client->id,
                    'pan_vat'=>'',
                    'phone'=>'',
                    'email'=>'nullable|string|email',
                    'province'=>'',
                    'district'=>'',
                    'local_address'=>'',
                    'concerned_name'=>'',
                    'concerned_email'=>'',
                    'concerned_phone'=>'',
                    'designation'=>'',
                    'logo' => 'mimes:png,jpg,jpeg',
                    'is_vendor'=>"",
                ] );

                if($request->hasfile('logo'))
                {
                    $image = $request->file('logo');
                    $imagename = $image->store('supplier_logo', 'uploads');
                }
                else
                {
                    $imagename = $client->logo;
                }

                $client->update( [
                    'client_type'=>$data['client_type'],
                    'dealer_type_id'=>$data['dealer_type_id'],
                    'name'=>$data['name'],
                    'client_code'=>$data['client_code'],
                    'pan_vat'=>$data['pan_vat'],
                    'phone'=>$data['phone'],
                    'email'=>$data['email'],
                    'province'=>$data['province'],
                    'district'=>$data['district'],
                    'local_address'=>$data['local_address'],
                    'logo' => $imagename,
                    'is_vendor'=>$data['is_vendor'] ?? 0,
                ] );

                $concerns = Clientconcern::where('client_id', $id)->delete();

                $concern_names = $request['concerned_name'];
                $concern_phones = $request['concerned_phone'];
                $concern_emails = $request['concerned_email'];
                $concern_designation = $request['designation'];
                $concerncount = count($request['concerned_name']);
                $clientconcerns = [];
                $default = 0;
                for($x = 0; $x<$concerncount; $x++){
                    if($x == 0){
                        $default = 1;
                    }else{
                        $default = 0;
                    }
                    $clientconcerns[] = [
                        'client_id' => $client['id'],
                        'concerned_name' => $concern_names[$x],
                        'concerned_phone'=>$concern_phones[$x],
                        'concerned_email'=>$concern_emails[$x],
                        'designation'=>$concern_designation[$x],
                        'default'=>$default
                    ];
                }
                Clientconcern::insert($clientconcerns);
                if($data['dealer_type_id'] == 4 || $data['dealer_type_id'] == 5 || $data['dealer_type_id'] == 6){
                    $dealerusers = DealerUser::where('client_id', $client['id'])->get();
                    foreach($dealerusers as $dealeruser){
                        $dealeruser->delete();
                    }
                }
                DB::commit();

                //if is vendor


                if(isset($data['is_vendor']) && $data['is_vendor'] == 1){
                    $vendor = Vendor::where('child_account_id',$client->child_account_id)->first();
                    if(!empty($vendor)){

                        $vendor->update([
                            'company_name' => $data['name'],
                            'company_email' => $data['email'],
                            'company_phone' => $data['phone'],
                            'province_id' => $data['province'],
                            'district_id' => $data['district'],
                            'company_address' => $data['local_address'],
                            'pan_vat' => $data['pan_vat'],
                            'supplier_code'=>$data['client_code'],
                            'logo' => $imagename,
                            'is_client'=>1,
                        ]);

                    }else{

                        Vendor::create([
                            'company_name' => $data['name'],
                            'company_email' => $data['email'],
                            'company_phone' => $data['phone'],
                            'province_id' => $data['province'],
                            'district_id' => $data['district'],
                            'company_address' => $data['local_address'],
                            'pan_vat' => $data['pan_vat'],
                            'supplier_code'=>$data['client_code'],
                            'logo' => $imagename,
                            'is_client'=>1,
                            'child_account_id' => $client->child_account_id,
                        ]);
                     }
                }

                return redirect()->route( 'client.index' )->with( 'success', 'Client Successfully Updated' );
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
    * @param  \App\Models\Client  $client
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'remove-customer' ) ) {
            $client = Client::findorFail( $id );
            $client->delete();
            return redirect()->route( 'client.index' )->with( 'success', 'Client Successfully Deleted' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function makedefault(Request $request){
        if ( $request->user()->can( 'view-customer' ) ) {
            $current_default = Clientconcern::where('client_id', $request['client_id'])->where('default',1)->first();
            $current_default->update([
                'default'=>0,
            ]);

            $new_default = Clientconcern::where('id', $request['concern_id'])->first();
            $new_default->update([
                'default'=>1,
            ]);

            $response = array('status' => 'success','message' => 'Default concerned person updated');
            echo(json_encode($response));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function clientproducts(Request $request, $id){
        if ( $request->user()->can( 'view-customer' ) ) {
            $client = Client::findorFail( $id );

            $clientproducts = Billing::where('client_id', $id)->leftJoin('billing_extras', 'billings.id', '=', 'billing_extras.billing_id')
                                        ->leftJoin('godowns', 'billings.godown', '=', 'godowns.id')
                                        ->leftJoin('products', 'particulars', '=', 'products.id')
                                        ->leftJoin('billingtypes', 'billings.billing_type_id', '=', 'billingtypes.id')
                                        ->select('billings.id as billing_id', 'reference_no', 'quantity', 'eng_date', 'nep_date', 'godown_name','product_name', 'rate', 'billing_types', 'billings.status as status')->paginate(15);
            // dd($clientproducts);
            return view('backend.client.clientproduct', compact('client', 'clientproducts'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function customersLedgers(Request $request)
    {
        $number = $request['number_to_filter'] != null ? $request['number_to_filter'] : 100;
        $customers = Client::with(['salesBillings', 'salesReturnBillings'])->get();
        if($request->ajax()){
            return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('bill_amount',function ($row){

                $serviceSaleTotal = $row->serviceSalebillgrandtotal($row->id);
                // $servicePurchasepaidamount = $row->servicePurchasebillpaidamount($row->id);
                // $servicePurchaseRemaining = $servicePurchaseTotal - $servicePurchasepaidamount;
                $total_amt = $row->salesBillings->sum('grandtotal') - $row->salesReturnBillings->sum('grandtotal') + $serviceSaleTotal;
                return 'Rs '.$total_amt;
            })

            ->addColumn('paid_amount',function($row){
                $serviceSalepaidamount = $row->serviceSalebillpaidamount($row->id);

                $total_paid_amount = 0;
                $total_received_amount = 0;
                foreach ($row->salesBillings as $billing)
                {
                    $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                    $total_paid_amount += $paid_amount_sum;
                }
                foreach ($row->salesReturnBillings as $returnedBilling)
                {
                    $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                    $total_received_amount += $received_amount_sum;
                }
               $total_paid_amount =   $total_paid_amount - $total_received_amount + $serviceSalepaidamount;
               return 'Rs '.$total_paid_amount;
            })
            ->addColumn('remaining_amount',function($row){
                $serviceSaleTotal = $row->serviceSalebillgrandtotal($row->id);
                $serviceSalepaidamount = $row->serviceSalebillpaidamount($row->id);
                $serviceSaleRemaining = $serviceSaleTotal - $serviceSalepaidamount;
                $total_paid_amount = 0;
                $total_received_amount = 0;
                foreach ($row->salesBillings as $billing)
                {
                    $paid_amount_sum = $billing->payment_infos->sum('total_paid_amount');
                    $total_paid_amount += $paid_amount_sum;
                }
                foreach ($row->salesReturnBillings as $returnedBilling)
                {
                    $received_amount_sum = $returnedBilling->payment_infos->sum('total_paid_amount');
                    $total_received_amount += $received_amount_sum;
                }
                $remaining_amt = ($row->salesBillings->sum('grandtotal') - $total_paid_amount) - ($row->salesReturnBillings->sum('grandtotal') - $total_received_amount) + $serviceSaleRemaining;
                return 'Rs '.$remaining_amt;
            })
            ->addColumn('action',function($row){
                $showurl = route('client.show',$row->id);
                $btn = "<div class='btn-bulk justify-content-center'>
                  <a href='$showurl' class='btn btn-primary icon-btn' title='View Supplier'><i class='fas fa-eye'></i></a>
                </div>";
            return $btn;
            })
            ->rawColumns(['bill_amount','paid_amount','remaining_amount','action'])
            ->make(true);
        };
        return view('backend.customersLedgers', compact('customers', 'number'));
    }

    public function customersUnpaidLedgers(Request $request){
        $unpaidpaymentinfo = Billing::with(['payment_infos'=>function($query){
            return $query->where('payment_type','unpaid');
        }])->where('billing_type_id',1)->get();

        if($request->ajax()){
            return DataTables::of($unpaidpaymentinfo)
            ->editColumn('client_name',function($row){
                $client_name = $row->client->name ?? "";
                return $client_name;

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

            ->rawColumns(['client_name','remaining_amount','billing_id','total_paid_amount'])
            ->make(true);
        }
     return view('backend.customerUnpaidLedgers');
    }

    public function clientsalesPdf($id){
        $client = Client::find($id);
        $billings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'client_id', $client->id )->where( 'billing_type_id', 1 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->get();
        $created_at = date("Y-m-d", strtotime($client->created_at));
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
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.client.downloadclientsale', compact('currentcomp', 'created_nepali_date', 'setting', 'path_img','client','billings'));
        return $pdf->download('Journals ('.$client->name.').pdf');
    }

    public function customerLedgersgeneratepdf(Request $request){
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
        $customers = Client::with(['salesBillings', 'salesReturnBillings'])->get();
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.client.downloadclientlegder', compact('currentcomp', 'setting', 'path_img','customers'));
        return $pdf->download('ClientLedger.pdf');
    }
}
