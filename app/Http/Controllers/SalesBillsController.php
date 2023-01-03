<?php

namespace App\Http\Controllers;

use App\Actions\CreateServiceSaleBillsAction;
use App\Actions\UpdateServiceSaleBillAction;
use App\FormDatas\ServiceSaleBillFormData;
use App\Models\SalesBills;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceSaleBillRequest;
use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\BillingCredit;
use App\Models\CancelledServiceBills;
use App\Models\Category;
use App\Models\Client;
use App\Models\DealerType;
use App\Models\FiscalYear;
use App\Models\OnlinePayment;
use App\Models\Paymentmode;
use App\Models\Province;
use App\Models\Quotationsetting;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSalesExtra;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Models\UserCompany;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Yajra\Datatables\Datatables;

use function app\NepaliCalender\dateeng;
use function app\NepaliCalender\datenep;
use Illuminate\Support\Facades\DB;

class SalesBillsController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    public function index(Request $request, $billing_type_id=NULL)
    {

        if ( $request->user()->can( 'manage-sales' ) ) {
            $fiscal_years = FiscalYear::latest()->get();
            $today = date("Y-m-d");
            $nepali_today = datenep($today);
            $exploded_date = explode("-", $nepali_today);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $starting_date = null;
            $ending_date = null;
            $start_date = null;
            $end_date = null;
            if ($request['number_to_filter'] == null) {
                $number = 10;
            }
            else
            {
                $number = $request['number_to_filter'];
            }
            $billing_type_id = '';
            if($request->billing_type_id){

                $billing_type_id = $request->get('billing_type_id');
                $billings = SalesBills::with('billing_types','suppliers','client','journalvoucher')->where('sales_bills.status', '1')->where('is_cancelled', '0')->where('billing_type_id', $request->get('billing_type_id'))->select('sales_bills.*');
                $total_sum = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->where('billing_type_id', $request->get('billing_type_id'))->sum('grandtotal');
            }else{

                $billing_type_id = 1;
                $billings = SalesBills::with('billing_types','suppliers','client','journalvoucher')->where('sales_bills.status', '1')->where('is_cancelled', '0')->where('billing_type_id',$request->get('billing_type_id'))->select('sales_bills.*');
                $total_sum = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->where('billing_type_id', $request->get('billing_type_id'))->sum('grandtotal');
            }
            if($request->ajax()){

                // $this->ajaxBillingdatatable($billings);

                return Datatables::of($billings)
                ->addIndexColumn()
                ->editColumn('select',function($row){
                    $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                    return $select;
                })
                ->editColumn('related_jv_no',function($row){

                    if($row->related_jv_no != null){
                    return '<a href="'.route("journals.show",$row->journalvoucher->id).'">'.$row->related_jv_no.'</a>';
                    }
                })
                ->editColumn('billing_type_id',function($row){
                    if($row->billing_type_id == 1 || $row->billing_type_id == 6 || $row->billing_type_id == 7){
                        return ($row->client_id == null) ? '-' : $row->client->name;
                    }else if($row->billing_type_id == 2 || $row->billing_type_id == 3 || $row->billing_type_id == 4 || $row->billing_type_id == 5){
                        return ($row->vendor_id == null) ? '-' : $row->suppliers->company_name;
                    }
                })
                ->editColumn('action',function($row){

                    $showurl = route('service_sales.show', $row->id);
                    // $editurl = route('billings.edit', $row->id);
                    $billingtype = $row->billing_type_id;

                    if($row->status == 1){
                        $statusurl = route('unapproveSingleServiceBill', $row->id);
                    }else{
                        $statusurl = route('approveSingleServiceBill', $row->id);
                    }

                    $cancellationurl = route('cancelSingleServiceBill', $row->id);
                    if ($row->status == 1) {
                        $btnname = 'fa fa-thumbs-down';
                        $btnclass = 'btn icon-btn';
                        $title = 'Disapprove';
                    } else {
                        $btnname = 'fa fa-thumbs-up';
                        $btnclass = 'btn icon-btn';
                        $title = 'Approve';
                    }
                    $csrf_token = csrf_token();
                    $btn ='<div class="btn-bulk justify-content-center">';
                    if ($billingtype == 2) {
                        $debitnoteurl = route('serviceSaleDebitNote', $row->id);
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$debitnoteurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Debit Note'><i class='fas fa-credit-card'></i></a>
                                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$row->id' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Please give reason for Cancellation</p>
                                                    <hr>
                                                    <form action='$cancellationurl' method='POST'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                        <input type='hidden' name='billing_id' value='$row->id'>
                                                        <div class='form-group'>
                                                            <label for='reason'>Reason:</label>
                                                            <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='description'>Description: </label>
                                                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                        </div>
                                                        <button type='submit' class='btn btn-danger'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    ";
                    }elseif ($billingtype == 1) {
                        $creditnoteurl = route('serviceSaleCreditNote', $row->id);
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$creditnoteurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Credit Note'><i class='fas far fa-credit-card'></i></a>
                                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                    <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Please give reason for Cancellation</p>
                                                    <hr>
                                                    <form action='$cancellationurl' method='POST'>
                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                        <input type='hidden' name='billing_id' value='$row->id'>
                                                        <div class='form-group'>
                                                            <label for='reason'>Reason:</label>
                                                            <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='description'>Description: </label>
                                                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                        </div>
                                                        <button type='submit' class='btn btn-danger'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    ";
                    }else {
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                    <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Please give reason for Cancellation</p>
                                                    <hr>
                                                    <form action='$cancellationurl' method='POST'>
                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                        <input type='hidden' name='billing_id' value='$row->id'>
                                                        <div class='form-group'>
                                                            <label for='reason'>Reason:</label>
                                                            <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='description'>Description: </label>
                                                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                        </div>
                                                        <button type='submit' class='btn btn-danger'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    ";
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['select','billing_type_id','action','related_jv_no'])
                ->make(true);
            }

            return view('backend.service_sales.index', compact('billing_type_id','total_sum','fiscal_years', 'start_date', 'end_date', 'starting_date', 'ending_date', 'number', 'nepali_today', 'current_fiscal_year', 'actual_year'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function serviceSaleCreditNote(Request $request, $id){

        if ( $request->user()->can( 'manage-sales' ) ) {

            $fiscal_years = FiscalYear::latest()->get();
            $serviceSale = SalesBills::with('serviceSalesExtra')->where('id', $id)->first();
            // if($serviceSale->status == 1)
            // {
            //     return redirect()->back()->with('error', 'Approved bills cant be edited.');
            // }
            $clients = Client::latest()->get();
            $payment_methods = Paymentmode::latest()->get();
            $taxes = Tax::latest()->get();
            $online_portals = OnlinePayment::latest()->get();
            $banks = Bank::latest()->get();
            $categories = ServiceCategory::with('services')->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );

            return view('backend.service_sales.creditnote', compact('serviceSale', 'ird_sync', 'service_code', 'clients', 'payment_methods', 'categories', 'taxes', 'fiscal_years', 'online_portals', 'banks'));
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    public function serviceSaleDebitNote(Request $request, $id){
        if ( $request->user()->can( 'manage-sales' ) ) {

            $fiscal_years = FiscalYear::latest()->get();
            $serviceSale = SalesBills::with('serviceSalesExtra')->where('id', $id)->first();
            // if($serviceSale->status == 1)
            // {
            //     return redirect()->back()->with('error', 'Approved bills cant be edited.');
            // }
            $suppliers = Vendor::latest()->get();
            $payment_methods = Paymentmode::latest()->get();
            $taxes = Tax::latest()->get();
            $online_portals = OnlinePayment::latest()->get();
            $banks = Bank::latest()->get();
            $categories = ServiceCategory::with('services')->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );

            return view('backend.service_sales.debitnote', compact('serviceSale', 'ird_sync', 'service_code', 'suppliers', 'payment_methods', 'categories', 'taxes', 'fiscal_years', 'online_portals', 'banks'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ( $request->user()->can( 'manage-sales' ) ) {
            $fiscal_years = FiscalYear::latest()->get();
            $today = date("Y-m-d");
            $nepali_today = datenep($today);
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $dealerTypes = DealerType::latest()->get();
            $clients = Client::latest()->get();
            $allclientcodes = [];
            foreach($clients as $client){
                array_push($allclientcodes, $client->client_code);
            }
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $categories = ServiceCategory::with('services')->get();



            $services = Service::latest()->get();
            $allservicecodes = [];
            foreach ( $services as $service ) {
                array_push( $allservicecodes, $service->service_code );
            }
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view('backend.service_sales.create', compact('bankAccountTypes', 'categories', 'allservicecodes', 'service_code', 'nepali_today', 'fiscal_years', 'allclientcodes', 'payment_methods', 'dealerTypes', 'vendors', 'provinces', 'taxes', 'clients', 'client_code', 'ird_sync'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function purchasecreate(Request $request)
    {
        if ( $request->user()->can( 'manage-sales' ) ) {
            $fiscal_years = FiscalYear::latest()->get();
            $today = date("Y-m-d");
            $nepali_today = datenep($today);
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $suppliers = Vendor::latest('id','company_name')->get();

            $allsuppliercodes = [];
            foreach($vendors as $supplier){
                array_push($allsuppliercodes, $supplier->supplier_code);
            }
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);

            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $categories = ServiceCategory::with('services')->get();



            $services = Service::latest()->get();
            $allservicecodes = [];
            foreach ( $services as $service ) {
                array_push( $allservicecodes, $service->service_code );
            }
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view('backend.service_sales.purchasecreate', compact('bankAccountTypes', 'categories', 'allservicecodes', 'service_code', 'nepali_today', 'fiscal_years', 'payment_methods', 'vendors', 'provinces', 'taxes', 'suppliers', 'supplier_code', 'allsuppliercodes', 'ird_sync'));
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

     public function updateServiceQtyOnCreditNote($reference_invoice_no,$particulars,$qty){

        $refrence_sale = SalesBills::where('reference_no',$reference_invoice_no)->first();
            $refrence_sale_id = $refrence_sale->id;
            $services_sales_refrence = ServiceSalesExtra::where('sales_bills_id',$refrence_sale_id)->get();
            $particularWithQty = [];

            foreach($particulars as $p=>$particular){

                $particularWithQty[$particular] = $qty[$p];
            }

            foreach($services_sales_refrence as $servicesale){
                $servicesale->quantity = $servicesale->quantity - $particularWithQty[$servicesale->particulars];
                $servicesale->save();
            }
     }


    public function store(ServiceSaleBillRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $billing_type_id = $request->billing_type_id;
            // if($billing_type_id == 5){//creditnote
            //     $this->updateServiceQtyOnCreditNote($request->reference_invoice_no,$request->particulars,$request->quantity);
            // }

            //TAX INFO
            $request->servicediscounttype = $request->alldiscounttypeservice;
            $request->servicediscountamount = $request->servicediscountamount;
            $serviceSaleBillFormData = (new ServiceSaleBillFormData(
                $request->fiscal_year_id,
                $request->nep_date,
                $request->eng_date,
                $request->client_id ?? null,
                $request->ledger_no,
                $request->file_no,
                $request->payment_method,
                $request->online_portal,
                $request->customer_portal_id,
                $request->bank_id,
                $request->cheque_no,
                $request->particulars,
                $request->quantity,
                $request->unit,
                $request->rate,
                $request->total,
                $request->subtotal,
                $request->discountamount,
                $request->alldiscounttype,
                $request->alldtamt,
                $request->taxamount,
                $request->alltaxtype,
                $request->alltax,
                $request->shipping,
                $request->grandtotal,
                $request->vat_refundable,
                $request->sync_ird,
                $request->status,
                $request->payment_type,
                $request->payment_amount,
                $request->remarks,
                $request->vendor_id,
                $request->billing_type_id,
                $request->reference_invoice_no,
                $request->discountamt,
                $request->discounttype,
                $request->dtamt,
                $request->taxamt,
                $request->itemtax,
                $request->taxtype,
                $request->tax,
                $request->due_date_nep,
                $request->due_date_eng,
                $request->service_charge,
                $request->servicediscounttype,
                $request->servicediscountamount,
            ));

            (new CreateServiceSaleBillsAction())->execute($serviceSaleBillFormData);

            DB::commit();
            if(!empty($billing_type_id)){
                return redirect()->route('service_sales.index',['billing_type_id'=>$billing_type_id])->with('success', 'Service Bills is successfully saved.');
            }
            return redirect()->route('service_sales.index')->with('success', 'Service Sales Bills is successfully saved.');

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function taxinfoCreate($taxinfoarray,$returnnotes = Null){
        $current_fiscal_year = FiscalYear::where('id', $taxinfoarray['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $taxinfoarray['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];


        $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
        $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
        $unpaids = [];
        foreach($unpaidtaxes as $unpaidtax){
            array_push($unpaids, $unpaidtax->total_tax);
        }
        $duetax = array_sum($unpaids);
        if($taxcount < 1){
            if($returnnotes == 'creditnote'){
              $taxcolumn = 'salesreturn_tax';
              $total_tax = -1 * $taxinfoarray['taxamount'];
            }
            if($returnnotes == 'serviceSalepurchase'){
                $taxcolumn = 'purchase_tax';
                $total_tax = -1 * $taxinfoarray['taxamount'];
              }
            if($returnnotes == 'debitnote'){
            $taxcolumn = 'purchasereturn_tax';
            $total_tax =  $taxinfoarray['taxamount'];
            }
            if($returnnotes == ''){
            $taxcolumn = 'sales_tax';
            $total_tax = $taxinfoarray['taxamount'];
            }
            $salestax = TaxInfo::create([
                'fiscal_year'=> $current_fiscal_year->fiscal_year,
                'nep_month'=> $nepmonth,
                $taxcolumn=>$taxinfoarray['taxamount'],
                'total_tax'=>$total_tax,
                'is_paid'=>0,
                'due'=>$duetax,
            ]);
            $salestax->save();
        }else{
            $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();


                if($returnnotes == 'creditnote'){
                    $sales_tax = $salestax->salesreturn_tax + (float)$taxinfoarray['taxamount'];
                    $total_tax = $salestax->total_tax - (float)$taxinfoarray['taxamount'];
                    $salestax->update([
                        'salesreturn_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }else if($returnnotes == 'serviceSalepurchase'){
                    $purchase_tax = $salestax->purchase_tax + (float)$taxinfoarray['taxamount'];
                    $total_tax = $salestax->total_tax - (float)$taxinfoarray['taxamount'];
                    $salestax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }else if($returnnotes == 'debitnote'){
                    $purchasereturn_tax = $salestax->purchasereturn_tax + (float)$taxinfoarray['taxamount'];
                    $total_tax = $salestax->total_tax + (float)$taxinfoarray['taxamount'];
                    $salestax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }else{
                    $sales_tax = $salestax->sales_tax + (float)$taxinfoarray['taxamount'];
                    $total_tax = $salestax->total_tax + (float)$taxinfoarray['taxamount'];
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }

            $salestax->save();
        }
    }

    public function billingCredit($billing,$onupdate = null){
        // $payment_info =  PaymentInfo::where('billing_id',$billing->id)->latest()->first();
// dd($billing);

        if($onupdate){
           //  dd('ff');
           BillingCredit::where('billing_id',$billing->id)->update([

               'due_date_eng'=>$billing->due_date_eng ?? '',
               'due_date_nep'=>$billing->due_date_nep ?? '',
               'credit_amount'=>$billing->grandtotal - $billing->payment_amount,
               'vendor_id'=>$billing->vendor_id,
               'client_id'=>$billing->client_id,
               'notified'=>0,
               'is_read'=>0,
               'billing_type_id'=>$billing->billing_type_id,
               'is_sale_service'=>1,
           ]);
        }else{
           //  dd('gg');
           BillingCredit::create([
               'billing_id'=>$billing->id,
               'due_date_eng'=>$billing->due_date_eng ?? '',
               'due_date_nep'=>$billing->due_date_nep ?? '',
               'credit_amount'=>$billing->grandtotal - $billing->payment_amount,
               'vendor_id'=>$billing->vendor_id,
               'client_id'=>$billing->client_id,
               'notified'=>0,
               'is_read'=>0,
               'billing_type_id'=>$billing->billing_type_id,
               'is_sale_service'=>1,
           ]);
        }



       }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesBills  $salesBills
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $serviceSaleBill = SalesBills::findorFail($id);
        if ( $request->user()->can( 'manage-sales' ) ) {
            return view('backend.service_sales.show', compact('serviceSaleBill'));
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesBills  $salesBills
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {

        if ( $request->user()->can( 'manage-sales' ) ) {

            $fiscal_years = FiscalYear::latest()->get();
            $serviceSale = SalesBills::with('serviceSalesExtra')->where('id', $id)->first();
            if($serviceSale->status == 1)
            {
                return redirect()->back()->with('error', 'Approved bills cant be edited.');
            }
            $clients = Client::latest()->get();
            $payment_methods = Paymentmode::latest()->get();
            $taxes = Tax::latest()->get();
            $online_portals = OnlinePayment::latest()->get();
            $banks = Bank::latest()->get();
            $categories = ServiceCategory::with('services')->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $service_code = 'SC'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
            $vendors = Vendor::latest()->get();

            return view('backend.service_sales.edit', compact('serviceSale', 'ird_sync', 'service_code', 'clients', 'payment_methods', 'categories', 'taxes', 'fiscal_years', 'online_portals', 'banks', 'vendors'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesBills  $salesBills
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceSaleBillRequest $request, $id)
    {

        DB::beginTransaction();
        try{
            $serviceSale = SalesBills::with('serviceSalesExtra')->where('id', $id)->first();

            foreach ($serviceSale->serviceSalesExtra as $billExtra)
            {

                $billExtra->delete();
            }


            $request->servicediscounttype = $request->alldiscounttypeservice;
            $request->servicediscountamount = $request->servicecharge;
            $serviceSaleBillFormData = (new ServiceSaleBillFormData(
                $request->fiscal_year_id,
                $request->nep_date,
                $request->eng_date,
                $request->client_id,
                $request->ledger_no,
                $request->file_no,
                $request->payment_method,
                $request->online_portal,
                $request->customer_portal_id,
                $request->bank_id,
                $request->cheque_no,
                $request->particulars,
                $request->quantity,
                $request->unit,
                $request->rate,
                $request->total,
                $request->subtotal,
                $request->discountamount,
                $request->alldiscounttype,
                $request->alldtamt,
                $request->taxamount,
                $request->alltaxtype,
                $request->alltax,
                $request->shipping,
                $request->grandtotal,
                $request->vat_refundable,
                $request->sync_ird,
                $request->status,
                $request->payment_type,
                $request->payment_amount,
                $request->remarks,
                $request->vendor_id,
                $request->billing_type_id,
                $request->reference_invoice_no,
                $request->discountamt,
                $request->discounttype,
                $request->dtamt,
                $request->taxamt,
                $request->itemtax,
                $request->taxtype,
                $request->tax,
                $request->due_date_nep,
                $request->due_date_eng,
                $request->service_charge,
                $request->servicediscounttype,
                $request->servicediscountamount,
            ));

            (new UpdateServiceSaleBillAction())->execute($serviceSale, $serviceSaleBillFormData);


            DB::commit();
            return redirect()->route('service_sales.index')->with('success', 'Service Sales Bills is successfully updated.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesBills  $salesBills
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generateSalesBillingReport(Request $request)
    {
        $starting_date = $request['starting_date'];
        $ending_date = $request['ending_date'];
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);
        $billing_type_id = $request['billing_type_id'];

        if ($start_date > $end_date) {
            return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
        }

        $start_date_explode = explode("-", $starting_date);
        $end_date_explode = explode("-", $ending_date);

        if(($end_date_explode[0] - $start_date_explode[0]) > 1)
        {
            return redirect()->back()->with('error', 'Select dates within a fiscal year.');
        }

        $today = date("Y-m-d");
        $nepali_today = datenep($today);
        $exploded_date = explode("-", $nepali_today);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::all();
        if ($request['number_to_filter'] == null) {
            $number = 10;
        }
        else
        {
            $number = $request['number_to_filter'];
        }
        $billings = SalesBills::latest()->where('billing_type_id', $billing_type_id)->where('status', '1')->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->where('is_cancelled', '0')->paginate($number);
        $total_sum = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->where('billing_type_id', $request->get('billing_type_id'))->sum('grandtotal');
        return view('backend.service_sales.generateindex', compact('fiscal_years', 'nepali_today', 'start_date', 'end_date', 'number', 'billings', 'current_fiscal_year', 'actual_year', 'starting_date', 'ending_date','billing_type_id', 'total_sum'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if($search == ""){
            $billings = SalesBills::where('status', 3)->paginate(10);
        }
        else
        {
            $billings = SalesBills::query()
                ->where('reference_no', 'LIKE', "%{$search}%")
                ->orWhere('transaction_no', 'LIKE', "%{$search}%")
                ->orWhere('nep_date', 'LIKE', "%{$search}%")
                ->where('status', '1')
                ->where('is_cancelled', '0')
                ->paginate(10);
        }

        return view('backend.service_sales.search', compact('billings'));
    }

    public function multiunapprove(Request $request)
    {
        DB::beginTransaction();
        try{
            foreach($request['id'] as $id){
                $billing = SalesBills::findorFail($id);
                $fiscal_year = FiscalYear::where('id',$billing->fiscal_year_id)->first()->fiscal_year ?? '';

                if(!empty($fiscal_year)){
                    $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();

                    if($billing->billing_type_id == 6){
                        $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax - (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 2){
                        $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax - (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 5){
                        $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax - (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 1){
                        $taxinfo->sales_tax = (float)$taxinfo->sales_tax - (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
                    }
                    $taxinfo->save();
                }
                // if($billing->billing_type_id == 5){
                //     $this->updateQtyOnapprovalStatus($id,'unapprove');
                // }
                $billing->update([
                    'status' => '0',
                    'approved_by' => null,
                ]);
            }
            DB::commit();
            return response()->json(['success'=>'Selected Service Sales Bills are successfully Unapproved']);
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function multiapprove(Request $request)
    {
        DB::beginTransaction();
        try{
            foreach($request['id'] as $id){
                $billing = SalesBills::findorFail($id);
                $fiscal_year = FiscalYear::where('id',$billing->fiscal_year_id)->first()->fiscal_year ?? '';

                if(!empty($fiscal_year)){
                    $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();

                    if($billing->billing_type_id == 6){
                        $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax + (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 2){
                        $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax + (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 5){
                        $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax + (float)$billing->taxamount;
                        $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
                    }
                    if($billing->billing_type_id == 1){
                    $taxinfo->sales_tax = (float)$taxinfo->sales_tax + (float)$billing->taxamount;
                    $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
                    }
                    $taxinfo->save();
                }
                // if($billing->billing_type_id == 5){
                //     $this->updateQtyOnapprovalStatus($id,'approve');
                // }
                $billing->update([
                    'status' => '1',
                    'approved_by' => Auth::user()->id,
                ]);
            }
            DB::commit();
            return response()->json(['success'=>'Selected Service Sales Bills are successfully Approved']);
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function unapprovedServiceBills(Request $request)
    {
        if ($request['number_to_filter'] == null) {
            $number = 10;
        }
        else
        {
            $number = $request['number_to_filter'];
        }
        $billing_type_id = $request['billing_type_id'];
        $billing_type = '';
        if($request->get('billing_type_id')){
            $billing_type = $request->get('billing_type_id');
            $billings = SalesBills::with('client')->latest()->where('status', '0')->where('is_cancelled', '0')->where('billing_type_id',$request->get('billing_type_id'))->get();
            // print_r($billings);exit;
            $total_sum = SalesBills::latest()->where('status', '0')->where('is_cancelled', '0')->where('billing_type_id',$request->get('billing_type_id'))->sum('grandtotal');
        }else{
            $billings = SalesBills::with('client')->latest()->where('status', '0')->where('is_cancelled', '0')->where('billing_type_id',1)->get();
            $total_sum = SalesBills::latest()->where('status', '0')->where('is_cancelled', '0')->where('billing_type_id',1)->sum('grandtotal');
        }
        if($request->ajax()){

        return Datatables::of($billings)
                ->addIndexColumn()
                ->addColumn('select',function($row){
                    $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                    return $select;
                })
                ->addColumn('client_name',function($row){
                    $client_name="";
                    if(!empty($row->client)){
                        $client_name = $row->client->name;
                    }
                   return $client_name;
                })
                ->addColumn('action',function($row){
                    $btn = '<div class="btn-bulk justify-content-center">';
                    $printurl = route('serviceSalesBillPrint', $row->id);
                    $showurl = route('service_sales.show', $row->id);
                    $editurl = route('service_sales.edit', $row->id);
                    $approveurl = route('approveSingleServiceBill', $row->id);
                    $cancelurl = route('cancelSingleServiceBill', $row->id);
                    $csrf_token = csrf_token();
                    $btn.='<a href='.$printurl.' class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                    <a href='.$showurl.' class="btn btn-primary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
                    <a href='.$editurl.' class="btn btn-secondary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                    <button type="button" class="btn btn-primary icon-btn btn-sm" data-toggle="modal" data-target="#cancellation" data-toggle="tooltip" data-placement="top" title="Cancel"><i class="fa fa-ban"></i></button>
                    <a href='.$approveurl.' class="btn icon-btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Approve"><i class="fas fa-thumbs-up"></i></a>
                    <!-- Modal -->
                        <div class="modal fade text-left" id="cancellation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Billing Cancellation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <p>Please give reason for Cancellation</p>
                                    <hr>
                                    <form action='.$cancelurl.' method="POST">
                                    <input type="hidden" name="_token" value='.$csrf_token.'>

                                        <div class="form-group">
                                            <label for="reason">Reason:</label>
                                            <input type="text" name="reason" id="reason" class="form-control" placeholder="Enter Reason for Cancellation" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description: </label>
                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter Detailed Reason" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>';
                    $btn .='</div>';

                    return $btn;

                })
                ->rawColumns(['client_name','select','billing_type_id','action'])
                ->make(true);
        }

        return view('backend.service_sales.unapproved', compact('billing_type','total_sum','number', 'billings', 'billing_type_id'));

    }

    public function cancelledServiceBills(Request $request)
    {
        $billing_type_id = $request->get('billing_type_id');
        if ($request['number_to_filter'] == null) {
            $number = 10;
        }
        else
        {
            $number = $request['number_to_filter'];
        }
        $billing_type_id = $request['billing_type_id'];
        if(!empty($billing_type_id)){
            $billings = SalesBills::with('client')->latest()->where('is_cancelled', '1')->where('billing_type_id', $billing_type_id)->get();
            $total_sum = SalesBills::latest()->where('status', '1')->where('is_cancelled', '1')->where('billing_type_id', $billing_type_id)->sum('grandtotal');
        }else{
            $billings = SalesBills::with('client')->latest()->where('is_cancelled', '1')->get();
            $total_sum = SalesBills::latest()->where('status', '1')->where('is_cancelled', '1')->sum('grandtotal');
        }


        if($request->ajax()){
            return Datatables::of($billings)
                ->addIndexColumn()
                ->addColumn('select',function($row){
                    $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                    return $select;
                })
                ->addColumn('client_name',function($row){
                    $client_name="";
                    if(!empty($row->client)){
                        $client_name = $row->client->name;
                    }
                   return $client_name;
                })
                ->addColumn('action',function($row){
                    $btn = '<div class="btn-bulk justify-content-center">';
                    $printurl =  route('serviceSalesBillPrint', $row->id);
                    $showurl =  route('service_sales.show', $row->id);
                    $reviveurl = route('reviveSingleServiceBill',$row->id);
                    $btn .='<a href='.$printurl.' class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                    <a href='.$showurl.' class="btn btn-primary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
                    <a href='.$reviveurl.' class="btn btn-secondary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="Revive"><i class="fa fa-smile-beam"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['client_name','select','action'])
                ->make(true);
        }
        return view('backend.service_sales.cancelled', compact('total_sum','number', 'billings', 'billing_type_id'));

    }

    public function unapproveSingleServiceBill($id)
    {

        $serviceSaleBill = SalesBills::findorFail($id);

        $fiscal_year = FiscalYear::where('id',$serviceSaleBill->fiscal_year_id)->first()->fiscal_year ?? '';

        if(!empty($fiscal_year)){
            $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();
            if($serviceSaleBill->billing_type_id == 6){
                $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax - (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 2){
                $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax - (float)$serviceSaleBill->taxamount;

                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 5){
                $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax - (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 1){
                $taxinfo->sales_tax = (float)$taxinfo->sales_tax - (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$serviceSaleBill->taxamount;
            }
            $taxinfo->save();
        }


        // if($serviceSaleBill->billing_type_id == 5){
        //     $this->updateQtyOnapprovalStatus($id,'unapprove');
        // }
        $serviceSaleBill->update([
            'status' => '0',
            'approved_by' => null,
        ]);

        return redirect()->back()->with('success', 'Service bill is successfully unapproved.');
    }

    public function updateQtyOnapprovalStatus($id,$approvestat){
        $serviceSaleBill = SalesBills::findorFail($id);
        $refrenceService = SalesBills::where('reference_no',$serviceSaleBill->reference_invoice_no)->first();
        $refrenceExtraService =  ServiceSalesExtra::where('sales_bills_id',$refrenceService->id)->get();

            foreach($refrenceExtraService as $refrenceservice){
                $qty = ServiceSalesExtra::where('sales_bills_id',$id)->where('particulars',$refrenceservice->particulars)->first()->quantity ?? 0;
                if($approvestat == 'unapprove'){
                    $refrenceservice->quantity = $refrenceservice->quantity + $qty;
                }else{
                    $refrenceservice->quantity = $refrenceservice->quantity - $qty;
                }
                $refrenceservice->save();
            }

    }

    public function approveSingleServiceBill($id)
    {
        $serviceSaleBill = SalesBills::findorFail($id);
        $fiscal_year = FiscalYear::where('id',$serviceSaleBill->fiscal_year_id)->first()->fiscal_year ?? '';

        if(!empty($fiscal_year)){
            $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();
            if($serviceSaleBill->billing_type_id == 6){
                $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax + (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 2){
                $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax + (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 5){
                $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax + (float)$serviceSaleBill->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$serviceSaleBill->taxamount;
            }
            if($serviceSaleBill->billing_type_id == 1){
            $taxinfo->sales_tax = (float)$taxinfo->sales_tax + (float)$serviceSaleBill->taxamount;
            $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$serviceSaleBill->taxamount;
            }
            $taxinfo->save();
        }
        // if($serviceSaleBill->billing_type_id == 5){
        //     $this->updateQtyOnapprovalStatus($id,'approve');
        // }

        $serviceSaleBill->update([
            'status' => '1',
            'approved_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Service bill is successfully approved.');
    }

    public function cancelSingleServiceBill(Request $request, $id)
    {
        $billing = SalesBills::findorFail($id);
        $fiscal_year = FiscalYear::where('id',$billing->fiscal_year_id)->first()->fiscal_year ?? '';

        if(!empty($fiscal_year) && $billing->status == 1){
            $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();
            if($billing->billing_type_id == 6){
                $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax - (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 2){
                $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax - (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 5){
                $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax - (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 1){
                $taxinfo->sales_tax = (float)$taxinfo->sales_tax - (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
            }
            $taxinfo->save();
        }
        // if($billing->billing_type_id == 5 && $billing->status == 1){
        //     $this->updateQtyOnapprovalStatus($id,'unapprove');
        // }
        $this->validate($request, [
            'reason' => 'required',
            'description' => 'required'
        ]);

        CancelledServiceBills::create([
            'sales_bills_id' => $billing->id,
            'reason' => $request['reason'],
            'description' => $request['description'],
        ]);

        $billing->update([
            'is_cancelled' => '1',
            'cancelled_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Service bill is successfully cancelled.');
    }

    public function reviveSingleServiceBill($id)
    {

        $billing = SalesBills::findorFail($id);
        $fiscal_year = FiscalYear::where('id',$billing->fiscal_year_id)->first()->fiscal_year ?? '';

        if(!empty($fiscal_year) && $billing->status == 1){
            $taxinfo = TaxInfo::where('fiscal_year',$fiscal_year)->first();
            if($billing->billing_type_id == 6){
                $taxinfo->salesreturn_tax = (float)$taxinfo->salesreturn_tax + (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 2){
                $taxinfo->purchase_tax = (float)$taxinfo->purchase_tax + (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax - (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 5){
                $taxinfo->purchasereturn_tax = (float)$taxinfo->purchasereturn_tax + (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
            }
            if($billing->billing_type_id == 1){
                $taxinfo->sales_tax = (float)$taxinfo->sales_tax + (float)$billing->taxamount;
                $taxinfo->total_tax = (float)$taxinfo->total_tax + (float)$billing->taxamount;
            }
            $taxinfo->save();
        }
        // if($billing->billing_type_id == 5){
        //     $this->updateQtyOnapprovalStatus($id,'approve');
        // }
        CancelledServiceBills::where('sales_bills_id', $id)->first()->delete();

        $billing->update([
            'is_cancelled' => '0',
            'cancelled_by' => null,
        ]);

        return redirect()->back()->with('success', 'Service bill is successfully revived.');
    }

    public function printpreview($id, Request $request)
    {

        $billing = SalesBills::findorfail($id);
        $setting = Setting::first();

        $user = Auth::user()->id;
        $quotationsetting = Quotationsetting::first();
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
        return view('backend.service_sales.printpreview', compact('path_img','billing', 'setting', 'user', 'quotationsetting', 'currentcomp'));
    }

    public function printBill(Request $request, $bill_id)
    {
        // echo 'gg';exit;
        $billing = SalesBills::findorfail($bill_id);
        $billing->update([
            'printcount' => $request['nprintcount'],
            'is_printed' => 1,
        ]);

        return response()->json("Successfully changes", 201);
    }

    public function generateServiceSalesBillingPDF($id)
    {
        $billing = SalesBills::findorfail($id);
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

        $img_address = 'img/address.png';
        $extencionadd = pathinfo($img_address, PATHINFO_EXTENSION);
        $dataaddress = file_get_contents($img_address, false, stream_context_create($opciones_ssl));
        $img_base_64_address = base64_encode($dataaddress);
        $path_img_address = 'data:image/' . $extencionadd . ';base64,' . $img_base_64_address;

        $img_phone = 'img/phone.png';
        $extencionphn = pathinfo($img_phone, PATHINFO_EXTENSION);
        $dataphn = file_get_contents($img_phone, false, stream_context_create($opciones_ssl));
        $img_base_64_phn = base64_encode($dataphn);
        $path_img_phn = 'data:image/' . $extencionphn . ';base64,' . $img_base_64_phn;

        $img_web = 'img/web.png';
        $extencionweb = pathinfo($img_web, PATHINFO_EXTENSION);
        $dataweb = file_get_contents($img_web, false, stream_context_create($opciones_ssl));
        $img_base_64_web = base64_encode($dataweb);
        $path_img_web = 'data:image/' . $extencionweb . ';base64,' . $img_base_64_web;

        $user =  Auth::user();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.service_sales.generatePdf', compact('path_img_web','path_img_phn','path_img_address','user','billing', 'setting', 'path_img', 'currentcomp'));
        $ref_no = $billing->reference_no;
        $predlcount = $billing->downloadcount;
        $newdownloadcount = $predlcount +1;
        $billing->update([
            'downloadcount' => $newdownloadcount,
        ]);

        return $pdf->download($ref_no.'.pdf');
    }

    public function sendServiceSaleEmail($id)
    {
        $billing = SalesBills::findorfail($id);
        $client_data['email'] = $billing->client->email;
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.service_sales.generatePdf', compact('billing', 'setting', 'path_img', 'currentcomp'));

        Mail::send('emails.billingsMail', $client_data, function($message)use($client_data, $pdf)
        {
            $message->to($client_data["email"])
                    ->subject("Billing Information")
                    ->attachData($pdf->output(), "service_sales_bills.pdf");
        });

        return redirect()->back()->with('success', 'Bill PDF is sent successfully.');
    }

    public function serviceSalesBillingsPDF(Request $request)
    {
        if($request['selectedid'] == null)
        {
            $billings = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->get();
        }
        else
        {
            $selectedid = explode(',', $request['selectedid']);
            $billings = SalesBills::latest()->whereIn('id', $selectedid)->where('status', '1')->where('is_cancelled', '0')->get();
        }

        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.service_sales.multipleBillingsPdf', compact(
            'billings',
            'currentcomp',
            'current_fiscal_year',
            'setting',
            'path_img'));

        return $pdf->download($current_fiscal_year->fiscal_year.'_Service_Sales.pdf');
    }

    public function serviceSalesBillingsReportPDF(Request $request, $id, $starting_date, $ending_date)
    {
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);

        if($request['selectedid'] == null)
        {
            $billings = SalesBills::latest()->where('status', '1')->where('is_cancelled', '0')->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->get();
        }
        else
        {
            $selectedid = explode(',', $request['selectedid']);
            $billings = SalesBills::latest()->whereIn('id', $selectedid)->where('status', '1')->where('is_cancelled', '0')->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->get();
        }
        $current_fiscal_year = FiscalYear::findorFail($id);
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.service_sales.multipleBillingsPdf',
        compact(
            'billings',
            'currentcomp',
            'current_fiscal_year',
            'setting',
            'start_date',
            'end_date',
            'starting_date',
            'ending_date',
            'path_img'
        ));

        return $pdf->download($start_date . ' to '. $end_date.'_Service_Sales.pdf');
    }
}
