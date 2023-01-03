<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Billing;
use App\Models\ChildAccount;
use App\Models\District;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\Setting;
use App\Models\Province;
use App\Models\SubAccount;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use PDF;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

use function app\NepaliCalender\datenep;

class BankController extends Controller {
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
        if ( $request->user()->can( 'view-bank-info' ) ) {
            $banks = Bank::latest()->paginate( 10 );
            return view( 'backend.bank_info.index', compact( 'banks' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        if ( $request->user()->can( 'view-bank-info' ) ) {
            $search = $request->input( 'search' );
            $banks = Bank::query()
            ->where( 'bank_name', 'LIKE', "%{$search}%" )
            ->orWhere( 'bank_local_address', 'LIKE', "%{$search}%" )
            ->orWhere( 'account_name', 'LIKE', "%{$search}%" )
            ->orWhere( 'account_no', 'LIKE', "%{$search}%" )
            ->latest()
            ->paginate( 10 );

            return view( 'backend.bank_info.search', compact( 'banks' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function deletedBankInfo( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $banks = Bank::onlyTrashed()->latest()->paginate();
            return view( 'backend.trash.bankinfotrash', compact( 'banks' ) );
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
        if ( $request->user()->can( 'create-bank-info' ) ) {
            $provinces = Province::latest()->get();
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view( 'backend.bank_info.create', compact( 'provinces', 'bankAccountTypes' ) );
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
        $this->validate( $request, [
            'bank_name' => 'required',
            'head_branch' => 'required',
            'bank_province_no' => 'required',
            'bank_district_no' => 'required',
            'bank_local_address' => 'required',
            'account_no' => 'required|unique:banks',
            'account_name' => 'required',
            'account_type' => 'required',
            'opening_balance'=> 'required',
            'behaviour'=>'required',
        ] );

        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        DB::beginTransaction();
        try{
            // Child Account
            $account_type = BankAccountType::where('id', $request['account_type'])->first();

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
                    'sub_account_id' => 1
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

            // Bank
            Bank::create( [
                'bank_name' => $request['bank_name'],
                'head_branch' => $request['head_branch'],
                'bank_province_no' => $request['bank_province_no'],
                'bank_district_no' => $request['bank_district_no'],
                'bank_local_address' => $request['bank_local_address'],
                'account_no' => $request['account_no'],
                'account_name' => $request['account_name'],
                'account_type_id' => $request['account_type'],
                'child_account_id' => $childAccount['id'],
            ] );
            DB::commit();
            return redirect()->route( 'bank.index' )->with( 'success', 'Bank information successfully inserted.' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Bank  $bank
    * @return \Illuminate\Http\Response
    */

    public function show( Bank $bank )
    {
        $salesBillings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 1 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        $creditNoteBillings = Billing::latest()->with( 'client' )->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 6 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        $billings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 2 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        $debitNoteBillings = Billing::latest()->with( 'suppliers' )->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 5 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        $receiptBillings = Billing::latest()->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 3 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        $paymentBillings = Billing::latest()->with( 'payment_infos' )->where( 'bank_id', $bank->id )->where( 'billing_type_id', 4 )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( 10 );
        return view('backend.bank_info.show', compact('bank', 'salesBillings', 'creditNoteBillings', 'billings', 'debitNoteBillings', 'receiptBillings', 'paymentBillings'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Bank  $bank
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'edit-bank-info' ) ) {
            $bank_info = Bank::findorFail( $id );
            $provinces = Province::latest()->get();
            $districts = District::where( 'province_id', $bank_info->bank_province_no )->latest()->get();
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();

            return view( 'backend.bank_info.edit', compact( 'bank_info', 'provinces', 'districts', 'bankAccountTypes' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Bank  $bank
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $bank_info = Bank::findorFail( $id );

        $this->validate( $request, [
            'bank_name' => 'required',
            'head_branch' => 'required',
            'bank_province_no' => 'required',
            'bank_district_no' => 'required',
            'bank_local_address' => 'required',
            'account_no' => 'required',
            'account_name' => 'required',
            'account_type' => 'required',
        ] );

        $bank_info->update( [
            'bank_name' => $request['bank_name'],
            'head_branch' => $request['head_branch'],
            'bank_province_no' => $request['bank_province_no'],
            'bank_district_no' => $request['bank_district_no'],
            'bank_local_address' => $request['bank_local_address'],
            'account_no' => $request['account_no'],
            'account_name' => $request['account_name'],
            'account_type_id' => $request['account_type']
        ] );

        return redirect()->route( 'bank.index' )->with( 'success', 'Bank information successfully updated.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Bank  $bank
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id ) {
        if ( $request->user()->can( 'delete-bank-info' ) ) {
            $bank_info = Bank::findorFail( $id );
            $bank_info->delete();

            return redirect()->route( 'bank.index' )->with( 'success', 'Bank information successfully deleted.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restoreBankInfo( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $deleted_bank_info = Bank::onlyTrashed()->findorFail( $id );
            $deleted_bank_info->restore();
            return redirect()->route( 'bank.index' )->with( 'success', 'Bank information is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function bankLedgers(Request $request)
    {
        $number = $request['number_to_filter'] != null ? $request['number_to_filter'] : 100;
        $banks = Bank::with(['purchaseBillings', 'purchaseReturnBillings', 'salesBillings', 'salesReturnBillings', 'receiptBillings', 'paymentBillings'])->get();
        if($request->ajax()){
            return DataTables::of($banks)
                ->addIndexColumn()
                ->addColumn('received_amount',function($row){
                    $received_amount = $row->salesBillings->sum('grandtotal') + $row->purchaseReturnBillings->sum('grandtotal') + $row->receiptBillings->sum('grandtotal');
                    return 'Rs '.$received_amount;
                })
                ->addColumn('paid_amount',function($row){
                    $paid_amount = $row->purchaseBillings->sum('grandtotal') + $row->salesReturnBillings->sum('grandtotal') + $row->paymentBillings->sum('grandtotal');
                    return 'Rs '.$paid_amount;
                })
                ->addColumn('action',function($row){
                    $showurl = route('bank.show',$row->id);
                    $btn = "<div class='btn-bulk justify-content-center'>
                      <a href='$showurl' class='btn btn-primary icon-btn' title='View Supplier'><i class='fas fa-eye'></i></a>
                    </div>";
                return $btn;
                })
                ->rawColumns(['received_amount','paid_amount','action'])
                ->make(true);
        }
        return view('backend.bankLedgers', compact('banks', 'number'));
    }

    public function bankLedgersPdf(){
        $banks = Bank::with(['purchaseBillings', 'purchaseReturnBillings', 'salesBillings', 'salesReturnBillings', 'receiptBillings', 'paymentBillings'])->get();
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
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.bank_info.downloadbanklegder', compact('currentcomp', 'setting', 'path_img','banks'));
        return $pdf->download('BankLedger.pdf');
    }
}
