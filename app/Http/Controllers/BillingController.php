<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Billing;
use App\Models\BillingExtra;
use App\Models\Billingtype;
use App\Models\BillingCredit;
use App\Models\Billprint;
use App\Models\CancelledBilling;
use App\Models\Category;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\Credit;
use App\Models\DealerType;
use App\Models\DebitCreditNote;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\JournalExtra;
use App\Models\JournalVouchers;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use App\Models\PaymentInfo;
use App\Models\Paymentmode;
use App\Models\Product;
use App\Models\ProductNotification;
use App\Models\Province;
use App\Models\ProductStock;

use App\Models\PurchaseOrder;
use App\Models\Quotationsetting;
use App\Models\Reconciliation;
use App\Models\ReturnReference;
use App\Models\SalesBills;
use App\Models\SalesRecord;
use App\Models\SalesReturnRecord;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SubAccount;
use App\Models\Tax;
use App\Models\TaxInfo;
use App\Models\UserCompany;
use App\Models\Unit;
use App\Models\Vendor;
use App\Services\ProductSaleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use stdClass;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;


class BillingController extends Controller
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
        //
        // if ($request->ajax())
        //     {
        //         // $current_year = FiscalYear::latest()->first();
        //         $data = Billing::latest()->with('billing_types')->with('suppliers')->get();
        //         return Datatables::of($data)
        //                 ->addIndexColumn()
        //                 ->addColumn('billing_type', function($row){
        //                     $billing_type = $row->billing_types->billing_types;
        //                     return $billing_type;
        //                 })
        //                 ->addColumn('supplier', function($row){
        //                     if($row->vendor_id == null){
        //                         $supplier = 'Not Indicated';
        //                     }else{
        //                         $supplier = $row->suppliers->company_name;
        //                     }
        //                     return $supplier;
        //                 })
        //                 ->addColumn('grandtotal', function($row){
        //                     $grandtotal = 'Rs. '.$row->grandtotal;
        //                     return $grandtotal;
        //                 })
        //                 ->addColumn('action', function($row)
        //                 {
        //                     $showurl = route('billings.show', $row->id);
        //                     $editurl = route('billings.edit', $row->id);
        //                     $statusurl = route('billings.status', $row->id);
        //                     $cancellationurl = route('billings.cancel', $row->id);
        //                     if($row->status == 1){
        //                         $btnname = 'fa fa-thumbs-down';
        //                         $btnclass = 'btn-info';
        //                         $title = 'Disapprove';
        //                     }else{
        //                         $btnname = 'fa fa-thumbs-up';
        //                         $btnclass = 'btn-info';
        //                         $title = 'Approve';
        //                     }
        //                     $csrf_token = csrf_token();
        //                     $btn = "<a href='$showurl' class='edit btn btn-success btn-sm mt-1'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
        //                         <a href='$editurl' class='edit btn btn-primary btn-sm mt-1' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
        //                         <button type='button' class='btn btn-danger btn-sm mt-1' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
        //                         <form action='$statusurl' method='POST' style='display:inline-block'>
        //                         <input type='hidden' name='_token' value='$csrf_token'>
        //                             <button type='submit' name = '$title' class='btn $btnclass btn-sm mt-1' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
        //                         </form>
        //                         <!-- Modal -->
        //                             <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        //                                 <div class='modal-dialog' role='document'>
        //                                 <div class='modal-content'>
        //                                     <div class='modal-header'>
        //                                     <h5 class='modal-title' id='exampleModalLabel'>Billing Cancellation</h5>
        //                                     <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
        //                                         <span aria-hidden='true'>&times;</span>
        //                                     </button>
        //                                     </div>
        //                                     <div class='modal-body'>
        //                                         <p>Please give reason for Cancellation</p>
        //                                         <hr>
        //                                         <form action='$cancellationurl' method='POST'>
        //                                         <input type='hidden' name='_token' value='$csrf_token'>
        //                                             <input type='hidden' name='journalvoucher_id' value='$row->id'>
        //                                             <div class='form-group'>
        //                                                 <label for='reason'>Reason:</label>
        //                                                 <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
        //                                             </div>
        //                                             <div class='form-group'>
        //                                                 <label for='description'>Description: </label>
        //                                                 <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
        //                                             </div>
        //                                             <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
        //                                         </form>
        //                                     </div>
        //                                 </div>
        //                                 </div>
        //                             </div>
        //                         ";
        //                     return $btn;
        //                 })
        //                 ->rawColumns(['billing_type', 'supplier', 'grandtotal', 'action'])
        //                 ->make(true);
        //     }
        // return view('backend.billings.index');
    }

    public function billingsreport(Request $request, $billing_type_id)
    {


        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $fiscal_years = FiscalYear::latest()->get();
            // $date = date("Y-m-d");
            // $nepalidate = datenep($date);
            // $exploded_date = explode("-", $nepalidate);

            // $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            // $current_fiscal_year = FiscalYear::first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);

            if ($request['number_to_filter'] == null) {
                $number = 10;
            }
            else
            {
                $number = $request['number_to_filter'];
            }
            $query = new Billing;

            if(isset($request->start_date) && !empty($request->start_date)){
                $query = $query->where('eng_date', '>=', $request->start_date)->where('eng_date', '<=', $request->end_date);
            }
            // if($billing_type_id == 7 || $billing_type_id == 4 || $billing_type_id == 3)
            // {

            //     $billings = $query->latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('outlet_id', null)->where('is_cancelled', '0')->get();
            // }
            // else
            // {
                // dd($query);
                $billings = $query->with('billing_types','suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('outlet_id', null)->where('is_cancelled', '0')->select('billings.*');
            // }
            if($request->ajax()){
                // dd($request->start_date);
                // $this->ajaxBillingdatatable($billings);
                $datatable = new Datatables;
                return $datatable->eloquent($billings)

                ->addColumn('actions',function($row){

                    $showurl = route('billings.show', $row->id);
                    $editurl = route('billings.edit', $row->id);
                    $billingtype = $row->billing_type_id;
                    $statusurl = route('billings.status', [$row->id, $billingtype]);
                    $cancellationurl = route('billings.cancel', $billingtype);
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
                    $btn ='<div class="btn-bulk justify-content-start">';
                    if ($billingtype == 2) {
                        $debitnoteurl = route('billings.debitnotecreate', $row->id);
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
                        $creditnoteurl = route('billings.creditnotecreate', $row->id);
                        $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$creditnoteurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Credit Note'><i class='fas far fa-credit-card'></i></a>
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
                    }else {
                        $btn .= "<a href='$showurl' class='edit btn btn-secondary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                 <a href='$editurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$row->id' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>

                                    <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                                    <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
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
                    }
                    $btn .= '</div>';
                    return $btn;
                })

                ->addColumn('select',function($row){
                    $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                    return $select;
                })
                ->editColumn('related_jv_no',function($row){

                    $jv = JournalVouchers::where('journal_voucher_no',$row->related_jv_no)->first();
                    if(!$jv){
                        return '';
                    }else{
                        return '<a href='.route('journals.show',$jv->id).'>'.$row->related_jv_no.'</a>';
                    }

                })
                ->editColumn('billing_type_id',function($row){

                    if($row->billing_type_id == 1 || $row->billing_type_id == 6 || $row->billing_type_id == 7){
                        return ($row->client_id == null) ? '-' : $row->client->name;
                    }else if($row->billing_type_id == 2 || $row->billing_type_id == 3 || $row->billing_type_id == 4 || $row->billing_type_id == 5){
                        return ($row->vendor_id == null) ? '-' : $row->suppliers->company_name;
                    }
                })
                ->rawColumns(['select','billing_type_id','actions','related_jv_no'])
                ->make(true);
            }
            // dd($billings);
            $total_sum = array_sum(array_column($billings->get()->toArray(), 'grandtotal'));

            $start_date = "";
            $end_date ="";


            return view('backend.billings.index', compact('total_sum','start_date','end_date','number', 'fiscal_years', 'billing_type_id', 'actual_year', 'current_fiscal_year', 'billingtype', 'billings'));
        }else{
            return view('backend.permission.permission');
        }
    }



    public function salesinvoice(Request $request, $billing_type_id)
    {
        if($request->user()->can('manage-sales-invoices'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);

            if ($request['number_to_filter'] == null) {
                $number = 10;
            }
            else
            {
                $number = $request['number_to_filter'];
            }
            $billings = Billing::latest()->whereHas('payment_infos', function ($q) { $q->where('is_sales_invoice', 1); })->paginate($number);
            return view('backend.billings.salesinvoice', compact('billings', 'number', 'fiscal_years', 'billing_type_id', 'actual_year', 'current_fiscal_year', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request, $id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-sales-invoices') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $id)->first();
            if($search == ""){
                $billings = Billing::where('billing_type_id', 8)->paginate(10);
            }
            else if(isset($_POST['POS_generate']))
            {
                $billings = Billing::query()
                    ->where('billing_type_id', $id)
                    ->where('reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('transaction_no', 'LIKE', "%{$search}%")
                    ->orWhere('nep_date', 'LIKE', "%{$search}%")
                    ->where('status', '1')
                    ->where('is_cancelled', '0')
                    ->where('is_pos_data', 1)
                    ->paginate(10);
            }
            else
            {
                $billings = Billing::query()
                    ->where('billing_type_id', $id)
                    ->where('reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('transaction_no', 'LIKE', "%{$search}%")
                    ->orWhere('nep_date', 'LIKE', "%{$search}%")
                    ->where('status', '1')
                    ->where('is_cancelled', '0')
                    ->paginate(10);
            }
            return view('backend.billings.search', compact('billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovedbillingsreport(Request $request, $billing_type_id)
    {


        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            if ($request['number_to_filter'] == null) {
                $number = 10;
            }
            else
            {
                $number = $request['number_to_filter'];
            }

            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '0')->where('is_cancelled', '0')->get();
           if($request->ajax()){
            return Datatables::of($billings)
            ->addIndexColumn()
            ->addColumn('select',function($row){
                $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                return $select;
            })
            ->addColumn('action',function($row){
                $showurl = route('billings.show', $row->id);
                $editurl = route('billings.edit', $row->id);
                $printurl = route('billing.print',$row->id);
                $billingtype = $row->billing_type_id;
                $statusurl = route('billings.status', [$row->id, $billingtype]);
                $cancellationurl = route('billings.cancel', $billingtype);
                if ($row->status == 1) {
                    $btnname = 'fa fa-thumbs-down';
                    $btnclass = 'btn btn-secondary icon-btn';
                    $title = 'Disapprove';
                } else {
                    $btnname = 'fa fa-thumbs-up';
                    $btnclass = 'btn btn-secondary icon-btn';
                    $title = 'Approve';
                }
                $csrf_token = csrf_token();
                $btn ='<div class="btn-bulk justify-content-start"><a href='.$printurl.' class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i></a>';
                $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                    <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$row->id' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                    <form action='$statusurl' method='POST' style='display:inline-block;padding:0;' class='btn'>
                    <input type='hidden' name='_token' value='$csrf_token'>
                        <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
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
                                        <button type='submit' class='btn btn-secondary'>Submit</button>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>
                    ";
                    $btn .= '</div>';
                return $btn;
            })
            ->editColumn('billing_type_id',function($row){
                if($row->billing_type_id == 1 || $row->billing_type_id == 6 || $row->billing_type_id == 7){
                    return ($row->client_id == null) ? '-' : $row->client->name;
                }else if($row->billing_type_id == 2 || $row->billing_type_id == 3 || $row->billing_type_id == 4 || $row->billing_type_id == 5){
                    return ($row->vendor_id == null) ? '-' : $row->suppliers->company_name;
                }
            })

            ->rawColumns(['select','billing_type_id','action'])
            ->make(true);
           }
           $total_sum = array_sum(array_column($billings->toArray(), 'grandtotal'));
            return view('backend.billings.unapprovedindex', compact('total_sum','number', 'billings','billing_type_id', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovesearch(Request $request, $id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $id)->first();

            $billings = Billing::query()
                ->where('billing_type_id', $id)
                ->where('reference_no', 'LIKE', "%{$search}%")
                ->orWhere('transaction_no', 'LIKE', "%{$search}%")
                ->orWhere('nep_date', 'LIKE', "%{$search}%")
                ->where('status', '0')
                ->where('is_cancelled', '0')
                ->latest()
                ->paginate(10);
            // dd($billings);

            return view('backend.billings.unapprovesearch', compact('billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function cancelledbillingsreport(Request $request, $billing_type_id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::latest('billings.created_at')->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('is_cancelled', '1')->get();
            if($request->ajax()){

                    return Datatables::of($billings)
                    ->addIndexColumn()
                    ->editColumn('billing_type_id',function($row){
                        if($row->billing_type_id == 1 || $row->billing_type_id == 6 || $row->billing_type_id == 7){
                            return ($row->client_id == null) ? '-' : $row->client->name;
                        }else if($row->billing_type_id == 2 || $row->billing_type_id == 3 || $row->billing_type_id == 4 || $row->billing_type_id == 5){
                            return ($row->vendor_id == null) ? '-' : $row->suppliers->company_name;
                        }
                    })
                    ->addColumn('action',function($row){
                        $showurl = route('billings.show', $row->id);
                        $editurl = route('billings.edit', $row->id);
                        $editprint = route('billing.print',$row->id);
                        $billingtype = $row->billing_type_id;
                        $statusurl = route('billings.status', [$row->id, $billingtype]);
                        $reviveurl = route('billings.revive', $billingtype);
                        $csrf_token = csrf_token();
                        $btn = "<div class='btn-bulk justify-content-start'><a href='$editprint' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>";
                        if ($row->status == 1) {
                            $btnname = 'fa fa-thumbs-down';
                            $btnclass = 'btn-secondary';
                            $title = 'Disapprove';
                            $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm icon-btn' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                        </form>
                                        <form action='$reviveurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                        <input type='hidden' name='billing_id' value='$row->id'>
                                            <button type='submit' class='btn btn-primary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                        </form>
                                        ";
                        } else {
                            $btnname = 'fa fa-thumbs-up';
                            $btnclass = 'btn-primary';
                            $title = 'Approve';
                            $btn .= "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                        <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' name = '$title' class='btn $btnclass btn-sm icon-btn' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                        </form>
                                        <form action='$reviveurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                        <input type='hidden' name='billing_id' value='$row->id'>
                                            <button type='submit' class='btn btn-secondary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                        </form>
                                        ";
                        }
                        $btn .= "</div>";
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $total_sum = array_sum(array_column($billings->toArray(), 'grandtotal'));
            return view('backend.billings.cancelledindex', compact('total_sum','billings', 'billing_type_id', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function cancelledsearch(Request $request, $id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $id)->first();
            $billings = Billing::query()
                ->where('billing_type_id', $id)
                ->where('reference_no', 'LIKE', "%{$search}%")
                ->orWhere('transaction_no', 'LIKE', "%{$search}%")
                ->orWhere('nep_date', 'LIKE', "%{$search}%")
                ->where('is_cancelled', '1')
                ->latest()
                ->paginate(10);
            // dd($billings);

            return view('backend.billings.cancelledsearch', compact('billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function materialized(Request $request, $billing_type_id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->paginate(10);
            $total_sum = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->sum('subtotal');


            return view('backend.billings.materializedindex', compact('total_sum','billings', 'billing_type_id', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function materializedsearch(Request $request, $id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $id)->first();

            if($id == 1 || $id == 6)
            {
                $billings = Billing::with('client')->where('billing_type_id', $id)->where('status', '1')->where('is_cancelled', '0')->where(function($query) use ($search)
                {
                    $query->where('reference_no', 'LIKE', "%{$search}%");
                    $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                    $query->orWhere('nep_date', 'LIKE', "%{$search}%");
                    $query->orWhereHas('client', function($q) use ($search) {
                            $q->where(function($q) use ($search) {
                                $q->where('name', 'LIKE', "%{$search}%");
                                $q->orWhere('pan_vat', 'LIKE', '%' . $search . '%');
                            });
                        });

                })->latest()->paginate(10);
            }
            else
            {
                $billings = Billing::with('suppliers')->where('billing_type_id', $id)->where('status', '1')->where('is_cancelled', '0')->where(function($query) use ($search)
                {
                    $query->where('reference_no', 'LIKE', "%{$search}%");
                    $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                    $query->orWhere('nep_date', 'LIKE', "%{$search}%");
                    $query->orWhereHas('suppliers', function($q) use ($search) {
                            $q->where(function($q) use ($search) {
                                $q->where('company_name', 'LIKE', "%{$search}%");
                                $q->orWhere('pan_vat', 'LIKE', '%' . $search . '%');
                            });
                        });

                })->latest()->paginate(10);
            }

            return view('backend.billings.searchmaterialized', compact('billings', 'billingtype'));
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function cbms(Request $request, $billing_type_id)
    {
        if($request->user()->can('manage-quotations')  || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->paginate(10);
            $total_sum = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->sum('subtotal');
            return view('backend.billings.cbmsindex', compact('total_sum','billings', 'billing_type_id', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function cbmssearch(Request $request, $id)
    {
        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $id)->first();
            $billings = Billing::with('suppliers')->where('billing_type_id', $id)->where('status', '1')->where('is_cancelled', '0')->where(function($query) use ($search)
            {
                $query->where('reference_no', 'LIKE', "%{$search}%");
                $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                $query->orWhere('nep_date', 'LIKE', "%{$search}%");
                $query->orWhereHas('suppliers', function($q) use ($search) {
                        $q->where(function($q) use ($search) {
                            $q->where('company_name', 'LIKE', "%{$search}%");
                            $q->orWhere('pan_vat', 'LIKE', '%' . $search . '%');
                        });
                    });

            })->latest()->paginate(10);

            return view('backend.billings.searchcbms', compact('billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function multiunapprove(Request $request)
    {

        foreach($request['id'] as $id)
        {
            $billing = Billing::find($id);
            $billing->update([
                'status'=>'0',
                'approved_by'=>null,
            ]);

            $current_fiscal_year = FiscalYear::where('id', $billing->fiscal_year_id)->first();
            $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
            $engtoday = $billing->eng_date;
            $date_in_nepali = datenep($engtoday);
            $explodenepali = explode('-', $date_in_nepali);

            $nepdate = (int)$explodenepali[1] - 1;
            $nepmonth = $monthname[$nepdate];

            if($billing->billing_type_id == 2){
                //cancel stock from gowdon
                $product_stock = ProductStock::where('billing_id',$billing->id)->get();
                $added_stock = 0;

                foreach($product_stock as $stock){

                    $added_stock += $stock->added_stock;
                    GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->decrement('stock',$stock->added_stock);
                    Product::where('id',$stock->product_id)->decrement('total_stock',$stock->added_stock);

                }

                //end cancel stock from gowdon
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax - (float)$billing->taxamount;
                    $total_tax = $purchasetax->total_tax + (float)$billing->taxamount;
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasetax->save();
                }
            }
            elseif($billing->billing_type_id == 1)
            {
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $sales_tax = $salestax->sales_tax + (float)$billing->taxamount;
                    $total_tax = $salestax->total_tax - (float)$billing->taxamount;
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salestax->save();

                    foreach($billing->billingextras as $extra){
                        $product = Product::findorfail($extra->particulars);
                        $stock = $product->total_stock;
                        $remstock = $stock + $extra->quantity;
                        $product->update([
                            'total_stock' => $remstock
                        ]);
                        $product->save();

                        $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                        $gostock = $godownproduct->stock;
                        $remgostock = $gostock + $extra->quantity;
                        $alert_on = $godownproduct->alert_on;
                        $godownproduct->update([
                            'stock' => $remgostock
                        ]);
                        $godownproduct->save();

                        if($remgostock <= $alert_on){
                            $productnotification = ProductNotification::create([
                                'product_id'=>$extra->particulars,
                                'godown_id'=>$billing->godown,
                                'noti_type'=>'low_stock',
                                'status'=>0,
                                'read_at'=>null,
                                'read_by'=>null,
                            ]);
                            $productnotification->save();
                        }elseif($remgostock <= 0){
                            $productnotification = ProductNotification::create([
                                'product_id'=>$extra->particulars,
                                'godown_id'=>$billing->godown,
                                'noti_type'=>'stock',
                                'status'=>0,
                                'read_at'=>null,
                                'read_by'=>null,
                            ]);
                            $productnotification->save();
                        }
                    }
                }
                $serialnumbers = GodownSerialNumber::where('billing_id',$request['id'])->get();
                foreach($serialnumbers as $serialnumber){
                    $serialnumber->update([
                        'sales_approved'=>0,
                    ]);
                }
            }
            elseif($billing->billing_type_id == 5)
            {
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchasereturn_tax = $purchasereturntax->purchasereturn_tax + (float)$billing->taxamount;
                    $total_tax = $purchasereturntax->total_tax - (float)$billing->taxamount;
                    $purchasereturntax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasereturntax->save();
                }
            }
            elseif($billing->billing_type_id == 6)
            {
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $salesreturn_tax = $salesreturntax->salesreturn_tax - (float)$billing->taxamount;
                    $total_tax = $salesreturntax->total_tax + (float)$billing->taxamount;
                    $salesreturntax->update([
                        'salesreturn_tax'=>$salesreturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salesreturntax->save();
                }

                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock - $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock - $extra->quantity;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();
                }
            }
            $billing->save;
        }
        return response()->json(['success'=>'Selected Billings Successfully Unapproved']);
    }

    public function multiapprove(Request $request)
    {

        foreach($request['id'] as $id){
            // print_r(json_decode($id));exit;
            $billing = Billing::find($id);
                $billing->update([
                    'status'=>'1',
                    'approved_by'=>Auth::user()->id,
                ]);
                $current_fiscal_year = FiscalYear::where('id', $billing->fiscal_year_id)->first();
                $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
                $engtoday = $billing->eng_date;
                $date_in_nepali = datenep($engtoday);
                $explodenepali = explode('-', $date_in_nepali);

                $nepdate = (int)$explodenepali[1] - 1;
                $nepmonth = $monthname[$nepdate];
                if($billing->billing_type_id == 2){
                    //revive stock from gowdon
                    $product_stock = ProductStock::where('billing_id',$billing->id)->get();
                    $added_stock = 0;

                    foreach($product_stock as $stock){

                        $added_stock += $stock->added_stock;
                        GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->increment('stock',$stock->added_stock);
                        Product::where('id',$stock->product_id)->increment('total_stock',$stock->added_stock);

                    }

                    //end revive stock from gowdon
                    if($billing->sync_ird == 1 && $billing->status == 1){
                        $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                        $purchase_tax = $purchasetax->purchase_tax + (float)$billing->taxamount;
                        $total_tax = $purchasetax->total_tax - (float)$billing->taxamount;
                        $purchasetax->update([
                            'purchase_tax'=>$purchase_tax,
                            'total_tax'=>$total_tax,
                        ]);
                        $purchasetax->save();
                    }
                }elseif($billing->billing_type_id == 1){
                    if($billing->sync_ird == 1 && $billing->status == 1){
                        $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                        $sales_tax = $salestax->sales_tax - (float)$billing->taxamount;
                        $total_tax = $salestax->total_tax + (float)$billing->taxamount;
                        $salestax->update([
                            'sales_tax'=>$sales_tax,
                            'total_tax'=>$total_tax,
                        ]);
                        $salestax->save();
                    }

                    foreach($billing->billingextras as $extra){
                        $product = Product::findorfail($extra->particulars);
                        $stock = $product->total_stock;
                        $remstock = $stock - $extra->quantity;
                        $product->update([
                            'total_stock' => $remstock
                        ]);
                        $product->save();

                        $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                        $gostock = $godownproduct->stock;
                        $remgostock = $gostock - $extra->quantity;
                        $alert_on = $godownproduct->alert_on;
                        $godownproduct->update([
                            'stock' => $remgostock
                        ]);
                        $godownproduct->save();

                        if($remgostock <= $alert_on){
                            $productnotification = ProductNotification::create([
                                'product_id'=>$extra->particulars,
                                'godown_id'=>$billing->godown,
                                'noti_type'=>'low_stock',
                                'status'=>0,
                                'read_at'=>null,
                                'read_by'=>null,
                            ]);
                            $productnotification->save();
                        }elseif($remgostock <= 0){
                            $productnotification = ProductNotification::create([
                                'product_id'=>$extra->particulars,
                                'godown_id'=>$billing->godown,
                                'noti_type'=>'stock',
                                'status'=>0,
                                'read_at'=>null,
                                'read_by'=>null,
                            ]);
                            $productnotification->save();
                        }
                    }
                    $serialnumbers = GodownSerialNumber::where('billing_id',$request['id'])->get();
                    foreach($serialnumbers as $serialnumber){
                        $serialnumber->update([
                            'sales_approved'=>1,
                        ]);
                    }
                }
                elseif($billing->billing_type_id == 5){
                    if($billing->sync_ird == 1 && $billing->status == 1){
                        $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                        $purchasereturn_tax = $purchasereturntax->purchasereturn_tax - (float)$billing->taxamount;
                        $total_tax = $purchasereturntax->total_tax + (float)$billing->taxamount;
                        $purchasereturntax->update([
                            'purchasereturn_tax'=>$purchasereturn_tax,
                            'total_tax'=>$total_tax,
                        ]);
                        $purchasereturntax->save();
                    }
                }elseif($billing->billing_type_id == 6){
                    if($billing->sync_ird == 1 && $billing->status == 1){
                        $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                        $salesreturn_tax = $salesreturntax->salesreturn_tax + (float)$billing->taxamount;
                        $total_tax = $salesreturntax->total_tax - (float)$billing->taxamount;
                        $salesreturntax->update([
                            'salesreturn_tax'=>$salesreturn_tax,
                            'total_tax'=>$total_tax,
                        ]);
                        $salesreturntax->save();
                    }
                    foreach($billing->billingextras as $extra){
                        $product = Product::findorfail($extra->particulars);
                        $stock = $product->total_stock;
                        $remstock = $stock + $extra->quantity;
                        $product->update([
                            'total_stock' => $remstock
                        ]);
                        $product->save();
                        $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                        $gostock = $godownproduct->stock;
                        $remgostock = $gostock + $extra->quantity;
                        $godownproduct->update([
                            'stock' => $remgostock
                        ]);
                        $godownproduct->save();
                    }
                }
                $billing->save;
        }
        return response()->json(['success'=>'Selected Billings Successfully Unapproved']);
    }

    public function status($id, $billingtype)
    {
        // dd('HI');
        // dd($_POST);
        $billing = Billing::findorfail($id);

        $current_fiscal_year = FiscalYear::where('id', $billing->fiscal_year_id)->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $billing->eng_date;
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        if(isset($_POST['Disapprove']))
        {

            $billing->update([
                'status'=>'0',
            ]);

            if($billing->billing_type_id == 2){
                //cancel stock from gowdon
                $product_stock = ProductStock::where('billing_id',$billing->id)->get();
                $added_stock = 0;

                foreach($product_stock as $stock){

                    $added_stock += $stock->added_stock;
                    GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->decrement('stock',$stock->added_stock);
                    Product::where('id',$stock->product_id)->decrement('total_stock',$stock->added_stock);

                }

                //end cancel stock from gowdon
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax - (float)$billing->taxamount;
                    $total_tax = $purchasetax->total_tax + (float)$billing->taxamount;
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasetax->save();
                }
            }elseif($billing->billing_type_id == 1)
            {
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $sales_tax = $salestax->sales_tax + (float)$billing->taxamount;
                    $total_tax = $salestax->total_tax - (float)$billing->taxamount;
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salestax->save();
                }
                $serialnumbers = GodownSerialNumber::where('billing_id',$id)->get();
                foreach($serialnumbers as $serialnumber){
                    $serialnumber->update([
                        'sales_approved'=>0,
                    ]);
                }
                //Stock on Disapproving
                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock + $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);

                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock + $extra->quantity;
                    $alert_on = $godownproduct->alert_on;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();

                    if($remgostock <= $alert_on){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$extra->particulars,
                            'godown_id'=>$billing->godown,
                            'noti_type'=>'low_stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }elseif($remgostock <= 0){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$extra->particulars,
                            'godown_id'=>$billing->godown,
                            'noti_type'=>'stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }
                }

            }elseif($billing->billing_type_id == 5){


                if($billing->sync_ird == 1 && $billing->status == 0){

                    $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchasereturn_tax = $purchasereturntax->purchasereturn_tax + (float)$billing->taxamount;
                    $total_tax = $purchasereturntax->total_tax - (float)$billing->taxamount;
                    $purchasereturntax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasereturntax->save();
                    //AS.K
                    $debitnotes = DebitCreditNote::where('billing_id',$billing->id)->get();

                    foreach($debitnotes as $notes){
                        GodownProduct::where('product_id',$notes->product_id)->where('godown_id',$notes->godown_id)->increment('stock',$notes->amount);
                        $gdp =  GodownProduct::where('product_id',$notes->product_id)->where('godown_id',$notes->godown_id)->first();
                        Product::where('id',$notes->product_id)->increment('total_stock',$notes->amount);
                        if(!empty($notes->serial_number)){
                            foreach(json_decode($notes->serial_number) as $numb){
                                GodownSerialNumber::create(
                                        [
                                            'godown_product_id'=> $gdp->id,
                                            'serial_number'=>$numb,
                                            'purchase_billing_id'=>ReturnReference::where('billing_id',$billing->id)->first()->reference_billing_id,
                                        ]
                                    );
                            }


                        }

                    }
                    //as.k
                }

            }elseif($billing->billing_type_id == 6){
                if($billing->sync_ird == 1 && $billing->status == 0){
                    $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $salesreturn_tax = $salesreturntax->salesreturn_tax - (float)$billing->taxamount;
                    $total_tax = $salesreturntax->total_tax + (float)$billing->taxamount;
                    $salesreturntax->update([
                        'salesreturn_tax'=>$salesreturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salesreturntax->save();
                }
                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock - $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock - $extra->quantity;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();
                }
            }
            $billing->save;
            // $creditnotes = DebitCreditNote::where('billing_id',$billing->id)->get();
            // foreach($creditnotes as $notes){

            //     GodownProduct::where('godown_id',$notes->godown_id)->where('product_id',$notes->product_id)->decrement('stock',$notes->amount);
            //     Product::where('id',$notes->godown_id)->decrement('total_stock',$notes->amount);
            // }

            return redirect()->route('billings.unapproved',$billingtype)->with('success', 'Status Updated Successfully');
        }
        else if(isset($_POST['Approve']))
        {

            $user = Auth::user()->id;
            $billing = Billing::findorfail($id);
            $billing->update([
                'status'=>'1',
                'approved_by'=>$user,
            ]);
            if($billing->billing_type_id == 2){
                 //revive stock from gowdon
                 $product_stock = ProductStock::where('billing_id',$billing->id)->get();
                 $added_stock = 0;

                 foreach($product_stock as $stock){

                     $added_stock += $stock->added_stock;
                     GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->increment('stock',$stock->added_stock);
                     Product::where('id',$stock->product_id)->increment('total_stock',$stock->added_stock);

                 }

                 //end revive stock from gowdon
                if($billing->sync_ird == 1 && $billing->status == 1){
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax + (float)$billing->taxamount;
                    $total_tax = $purchasetax->total_tax - (float)$billing->taxamount;
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasetax->save();
                }
            }elseif($billing->billing_type_id == 1){
                if($billing->sync_ird == 1 && $billing->status == 1){
                    $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $sales_tax = $salestax->sales_tax - (float)$billing->taxamount;
                    $total_tax = $salestax->total_tax + (float)$billing->taxamount;
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salestax->save();
                }
                $serialnumbers = GodownSerialNumber::where('billing_id',$id)->get();
                foreach($serialnumbers as $serialnumber){
                    $serialnumber->update([
                        'sales_approved'=>1,
                    ]);
                }
                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock - $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();

                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock - $extra->quantity;
                    $alert_on = $godownproduct->alert_on;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();

                    if($remgostock <= $alert_on){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$extra->particulars,
                            'godown_id'=>$billing->godown,
                            'noti_type'=>'low_stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }elseif($remgostock <= 0){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$extra->particulars,
                            'godown_id'=>$billing->godown,
                            'noti_type'=>'stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }
                }
            }
            elseif($billing->billing_type_id == 5){
                if($billing->sync_ird == 1 && $billing->status == 1){
                    $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchasereturn_tax = $purchasereturntax->purchasereturn_tax - (float)$billing->taxamount;
                    $total_tax = $purchasereturntax->total_tax + (float)$billing->taxamount;
                    $purchasereturntax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasereturntax->save();

                     //AS.K
                     $debitnotes = DebitCreditNote::where('billing_id',$billing->id)->get();

                     foreach($debitnotes as $notes){

                        GodownProduct::where('product_id',$notes->product_id)->where('godown_id',$notes->godown_id)->decrement('stock',$notes->amount);
                        $gdp = GodownProduct::where('product_id',$notes->product_id)->where('godown_id',$notes->godown_id)->first();
                         Product::where('id',$notes->product_id)->decrement('total_stock',$notes->amount);
                         if(!empty($notes->serial_number)){
                             foreach(json_decode($notes->serial_number) as $numb){
                                 GodownSerialNumber::where('godown_product_id',$gdp->id)->where('serial_number',$numb)->delete();

                             }


                         }
                         // $notes->delete();
                     }
                     //as.k
                }
            }elseif($billing->billing_type_id == 6){
                if($billing->sync_ird == 1 && $billing->status == 1){
                    $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $salesreturn_tax = $salesreturntax->salesreturn_tax + (float)$billing->taxamount;
                    $total_tax = $salesreturntax->total_tax - (float)$billing->taxamount;
                    $salesreturntax->update([
                        'salesreturn_tax'=>$salesreturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salesreturntax->save();
                }
                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock + $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock + $extra->quantity;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();
                }
            }
            $billing->save;
            return redirect()->route('billings.report',$billingtype)->with('success', 'Status Updated Successfully');
        }
    }

    public function cancel(Request $request, $billingtype)
    {

        $this->validate($request,[
            'billing_id'=> 'required',
            'reason'=>'required',
            'description'=>'required',
        ]);
        $billing = Billing::findorfail($request['billing_id']);
        $user = Auth::user()->id;
        $current_fiscal_year = FiscalYear::where('id', $billing->fiscal_year_id)->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $billing->eng_date;
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $cancellation = CancelledBilling::create([
            'billing_id'=>$request['billing_id'],
            'reason'=>$request['reason'],
            'description'=>$request['description'],
        ]);

        $billing->update([
            'is_cancelled'=>'1',
            'cancelled_by'=>$user,
        ]);

        if($billing->billing_type_id == 2){
            //cancel stock from gowdon
            $product_stock = ProductStock::where('billing_id',$billing->id)->get();
            $added_stock = 0;

            foreach($product_stock as $stock){

                $added_stock += $stock->added_stock;
                GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->decrement('stock',$stock->added_stock);
                Product::where('id',$stock->product_id)->decrement('total_stock',$stock->added_stock);

            }

            //end cancel stock from gowdon
            if($billing->sync_ird == 1 && $billing->status == 1){
                $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                $purchase_tax = $purchasetax->purchase_tax - (float)$billing->taxamount;
                $total_tax = $purchasetax->total_tax + (float)$billing->taxamount;
                $purchasetax->update([
                    'purchase_tax'=>$purchase_tax,
                    'total_tax'=>$total_tax,
                ]);
                $purchasetax->save();
            }
        }
        if($billing->billing_type_id == 1 && $billing->status == 1){
            foreach($billing->billingextras as $extra){
                $product = Product::findorfail($extra->particulars);
                $stock = $product->total_stock;
                $remstock = $stock + $extra->quantity;
                $product->update([
                    'total_stock' => $remstock
                ]);
                $product->save();

                $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                $gostock = $godownproduct->stock;
                $remgostock = $gostock + $extra->quantity;
                $alert_on = $godownproduct->alert_on;
                $godownproduct->update([
                    'stock' => $remgostock
                ]);
                $godownproduct->save();

                if($remgostock <= $alert_on){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$extra->particulars,
                        'godown_id'=>$billing->godown,
                        'noti_type'=>'low_stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }elseif($remgostock <= 0){
                    $productnotification = ProductNotification::create([
                        'product_id'=>$extra->particulars,
                        'godown_id'=>$billing->godown,
                        'noti_type'=>'stock',
                        'status'=>0,
                        'read_at'=>null,
                        'read_by'=>null,
                    ]);
                    $productnotification->save();
                }
            }
        }
        if($billing->billing_type_id == 1){
            $serialnumbers = GodownSerialNumber::where('billing_id',$request['billing_id'])->get();
            foreach($serialnumbers as $serialnumber){
                $serialnumber->update([
                    'is_sold'=>0,
                    'billing_id'=>null,
                    'sales_approved'=>0,
                ]);
            }
        }
        if($billing->billing_type_id == 6 && $billing->status == 1){
            foreach($billing->billingextras as $extra){
                $product = Product::findorfail($extra->particulars);
                $stock = $product->total_stock;
                $remstock = $stock - $extra->quantity;
                $product->update([
                    'total_stock' => $remstock
                ]);
                $product->save();
                $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                $gostock = $godownproduct->stock;
                $remgostock = $gostock - $extra->quantity;
                $godownproduct->update([
                    'stock' => $remgostock
                ]);
                $godownproduct->save();
            }
        }
        $cancellation->save;
        $billing->save;
        return redirect()->route('billings.cancelled', $billingtype)->with('success', 'Billing Successfully Cancelled');
    }

    public function revive(Request $request, $billingtype)
    {

        // if($billingtype == 1){
        //     return redirect()->back()->with('error', 'Cannot Restore Sales Billing');
        // }else{
            $this->validate($request, [
                'billing_id' =>'required',
            ]);
            $billing = Billing::findorfail($request['billing_id']);
            $cancelledbilling = CancelledBilling::where('billing_id', $request['billing_id'])->first();
            $cancelledbilling->delete();
            $billing->update([
                'is_cancelled'=>'0',
                'cancelled_by'=>null,
            ]);
             //revive stock from gowdon
             $product_stock = ProductStock::where('billing_id',$billing->id)->get();
             $added_stock = 0;
             foreach($product_stock as $stock){
                 $added_stock += $stock->added_stock;
                 GodownProduct::where('product_id',$stock->product_id)->where('godown_id',$stock->godown_id)->increment('stock',$stock->added_stock);
                 Product::where('id',$stock->product_id)->increment('total_stock',$stock->added_stock);
             }
             //end revive stock from gowdon
            $current_fiscal_year = FiscalYear::where('id', $billing->fiscal_year_id)->first();
            $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
            $engtoday = $billing->eng_date;
            $date_in_nepali = datenep($engtoday);
            $explodenepali = explode('-', $date_in_nepali);

            $nepdate = (int)$explodenepali[1] - 1;
            $nepmonth = $monthname[$nepdate];
            if($billing->billing_type_id == 2){
                if($billing->sync_ird == 1 && $billing->status == 1){
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax + (float)$billing->taxamount;
                    $total_tax = $purchasetax->total_tax - (float)$billing->taxamount;
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasetax->save();
                }
            }
            $billing->save();
            // if($billing->billing_type_id == 1 && $billing->status == 1){
            //     foreach($billing->billingextras as $extra){
            //         $product = Product::findorfail($extra->particulars);
            //         $stock = $product->total_stock;
            //         $remstock = $stock - $extra->quantity;
            //         $product->update([
            //             'total_stock' => $remstock
            //         ]);
            //         $product->save();

            //         $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
            //         $gostock = $godownproduct->stock;
            //         $remgostock = $gostock - $extra->quantity;
            //         $alert_on = $godownproduct->alert_on;
            //         $godownproduct->update([
            //             'stock' => $remgostock
            //         ]);
            //         $godownproduct->save();

            //         if($remgostock <= $alert_on){
            //             $productnotification = ProductNotification::create([
            //                 'product_id'=>$extra->particulars,
            //                 'godown_id'=>$billing->godown,
            //                 'noti_type'=>'low_stock',
            //                 'status'=>0,
            //                 'read_at'=>null,
            //                 'read_by'=>null,
            //             ]);
            //             $productnotification->save();
            //         }elseif($remgostock <= 0){
            //             $productnotification = ProductNotification::create([
            //                 'product_id'=>$extra->particulars,
            //                 'godown_id'=>$billing->godown,
            //                 'noti_type'=>'stock',
            //                 'status'=>0,
            //                 'read_at'=>null,
            //                 'read_by'=>null,
            //             ]);
            //             $productnotification->save();
            //         }
            //     }
            // }
            if($billing->billing_type_id == 6 && $billing->status == 1){
                foreach($billing->billingextras as $extra){
                    $product = Product::findorfail($extra->particulars);
                    $stock = $product->total_stock;
                    $remstock = $stock + $extra->quantity;
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $extra->particulars)->where('godown_id', $billing->godown)->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock + $extra->quantity;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();
                }
            }
            return redirect()->route('billings.report', $billingtype)->with('success', 'Billing Successfully Revived');
        // }
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

    public function salescreate(Request $request)
    {

        if($request->user()->can('manage-sales'))
        {
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 1;
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.serialnumbers','godownproduct.product.brand')->latest()->get();
            $clients = Client::with('dealertype')->latest()->get();
            $dealerTypes = DealerType::latest()->get();
            $allclientcodes = [];
            foreach($clients as $client){
                array_push($allclientcodes, $client->client_code);
            }
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $services = Service::latest()->where('status', 1)->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            return view('backend.billings.salescreate', compact('currentcomp','services', 'bankAccountTypes', 'date', 'fiscal_years', 'current_fiscal_year', 'allclientcodes', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'clients', 'client_code', 'ird_sync', 'dealerTypes'));
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function purchasecreate(Request $request)
    {
        if($request->user()->can('manage-purchases')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $godowns = Godown::all();
            $products = Product::all();
            $billing_type_id = 2;

            $categories = Category::with('products','products.brand')->get();
            $addproductCategory = Category::select('id','category_name')->get();
            $suppliers = Vendor::latest('id','company_name')->get();
            $units = Unit::all();

            // print_r($categories);exit;
            $taxes = Tax::latest()->get();
            $allsuppliercodes = [];
            foreach($vendors as $supplier){
                array_push($allsuppliercodes, $supplier->supplier_code);
            }
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            return view('backend.billings.purchasecreate', compact('units','suppliers','addproductCategory','fiscal_years', 'bankAccountTypes', 'allsuppliercodes', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'supplier_code', 'ird_sync', 'godowns', 'products', 'currentcomp'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function debitnotecreate(Request $request, $id)
    {
        // dd('hi');
        if($request->user()->can('manage-debit-note')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 5;
            $categories = Category::with('products','products.brand')->get();
            $taxes = Tax::latest()->get();
            $purchase_inv = Billing::findorfail($id);
            $vendors = Vendor::latest()->get();
            $billingextras = BillingExtra::where('billing_id', $id)->get();
            $billing = Billing::find($id);
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            $godowns = Godown::all();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            return view('backend.billings.debitnotecreate', compact('currentcomp','godowns','billing','fiscal_years', 'current_fiscal_year', 'bankAccountTypes', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'purchase_inv', 'billingextras', 'ird_sync'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function creditnotecreate(Request $request, $id)
    {
        if($request->user()->can('manage-credit-note')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 6;
            $purchase_inv = Billing::findorfail($id);
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $billingextras = BillingExtra::where('billing_id', $id)->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->latest()->get();
            $selgodown = Godown::with('godownproduct', 'godownproduct.product')->where('id', $purchase_inv->godown)->first();
            $clients = Client::with('dealertype')->latest()->get();
            $allclientcodes = [];
            foreach($clients as $client){
                array_push($allclientcodes, $client->client_code);
            }
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            return view('backend.billings.creditnotecreate', compact('currentcomp','purchase_inv','fiscal_years', 'bankAccountTypes', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'purchase_inv', 'billingextras', 'godowns','selgodown', 'clients','allclientcodes', 'ird_sync'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function paymentcreate(Request $request)
    {
        if($request->user()->can('manage-payment')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 4;
            $taxes = Tax::latest()->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $dealerTypes = DealerType::latest()->get();
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view('backend.billings.paymentcreate', compact('fiscal_years', 'bankAccountTypes', 'client_code', 'supplier_code', 'dealerTypes', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'taxes', 'ird_sync'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function receiptcreate(Request $request)
    {
        if($request->user()->can('manage-receipts')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 3;
            $taxes = Tax::latest()->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $dealerTypes = DealerType::latest()->get();
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view('backend.billings.receiptcreate', compact('fiscal_years', 'dealerTypes', 'bankAccountTypes', 'client_code', 'supplier_code', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'taxes', 'ird_sync'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function quotationcreate(Request $request)
    {
        if($request->ajax()){
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series')->latest()->get();
            return $godowns;
        }
        if($request->user()->can('manage-quotations')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 1;
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series')->latest()->get();
            // dd($godowns->);
            $quotationsetting = Quotationsetting::first();
            $dealerTypes = DealerType::latest()->get();
            $clients = Client::with('dealertype')->latest()->get();
            $allclientcodes = [];
            foreach($clients as $client){
                array_push($allclientcodes, $client->client_code);
            }
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            // dd($godowns);
            // $products = Product::with('godownproduct', 'godownproduct.godown')->latest()->get();
            // dd($transaction_no);
            $addproductCategory = Category::select('id','category_name')->get();
            $suppliers = Vendor::latest('id','company_name')->get();
            $units = Unit::all();
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            $products = Product::all();
            return view('backend.billings.quotationcreate', compact('products','units','suppliers','addproductCategory','fiscal_years', 'bankAccountTypes', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'quotationsetting','clients','allclientcodes', 'client_code', 'dealerTypes'));
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

    public function saveGodownProductStock($godown_ids, $godown_qty,$particulars,$billingid,$update=Null)
    {
            $stocks = $godown_qty;
            $product_ids = $particulars;

            if(!empty($product_ids)){
            foreach($product_ids as $pkey=>$product_id){
                $total_stock = 0;
                if(!empty($product_id)){
                    foreach(array_unique($godown_ids) as $key=>$godown){

                        foreach($godown_qty[$godown] as $qtykey=>$qty){

                            if($qtykey == $pkey && $qty != 0){
                            if($update){

                                ProductStock::create([
                                    'product_id'=>$product_id,
                                    'godown_id'=>$godown,
                                    'added_stock'=>$qty ?? 0,
                                    'billing_id'=>$billingid,
                                    'added_date'=>date('Y-m-d'),
                                ]);
                            }else{
                                $total_stock += $qty ?? 0;
                                $godownProduct = GodownProduct::where('product_id',$product_id)->where('godown_id',$godown)->first();
                                if(!empty($godownProduct)){
                                $godownProduct->increment('stock', $qty ?? 0);

                                }else{
                                    GodownProduct::create([
                                        'product_id'=>$product_id,
                                        'godown_id'=>$godown,
                                        'stock'=> $qty ?? 0,
                                        'opening_stock'=>0,
                                        'alert_on'=>0,
                                    ]);
                                }

                                ProductStock::create([
                                    'product_id'=>$product_id,
                                    'godown_id'=>$godown,
                                    'added_stock'=>$qty ?? 0,
                                    'billing_id'=>$billingid,
                                    'added_date'=>date('Y-m-d'),
                                ]);

                            }
                        }

                        }

                    }
                }
            Product::where('id',$product_id)->increment('total_stock',$total_stock);
        }

            // return $total_stock;
        }

    }

    public function addserialNumberProduct($serial_products,$billingid){
        foreach($serial_products as $godownProduct=>$ser){
            if(!empty($ser)){
                $godown_pro = explode('-',$godownProduct);
                $godown_id = current($godown_pro);
                $product_id = end($godown_pro);
                $serial_product = explode(',',$ser);
                if(!empty($serial_product)){
                    $godownproduct_id=GodownProduct::where('product_id',$product_id)->where('godown_id',$godown_id)->first()->id ?? '';
                    if(!empty($godownproduct_id)){
                        foreach($serial_product as $serialnumber){
                        GodownSerialNumber::create([
                            'godown_product_id'=>$godownproduct_id,
                            'serial_number'=>$serialnumber,
                            'status'=>1,
                            'is_damaged'=>0,
                            'is_sold'=>0,
                            'purchase_billing_id'=>$billingid,
                            'sales_approved'=>0,
                            'is_pos_product'=>0,
                        ]);
                        }

                    }

                }

            }
        }
    }

    public function billingCredit($billing,$onupdate = null){
     $payment_info =  PaymentInfo::where('billing_id',$billing->id)->latest()->first();


     if($onupdate){
        //  dd('ff');
        BillingCredit::where('billing_id',$billing->id)->update([

            'due_date_eng'=>$billing->due_date_eng,
            'due_date_nep'=>$billing->due_date_nep,
            'credit_amount'=>$billing->grandtotal - $payment_info->total_paid_amount,
            'vendor_id'=>$billing->vendor_id ?? null,
            'client_id'=>$billing->client_id,
            'notified'=>0,
            'is_read'=>0,
            'billing_type_id'=>$billing->billing_type_id,
        ]);
     }else{
        //  dd('gg');
        BillingCredit::create([
            'billing_id'=>$billing->id,
            'due_date_eng'=>$billing->due_date_eng,
            'due_date_nep'=>$billing->due_date_nep,
            'credit_amount'=>$billing->grandtotal - $payment_info->payment_amount,
            'vendor_id'=>$billing->vendor_id ?? null,
            'client_id'=>$billing->client_id,
            'notified'=>0,
            'is_read'=>0,
            'billing_type_id'=>$billing->billing_type_id,
        ]);
     }



    }

    public function purchasestore(Request $request){
        // dd($request->all());
        $saveandcontinue = $request->saveandcontinue ?? 0;

        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $request['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        //Purchase Bill
        $billscount = Billing::where('billing_type_id', 2)->count();

        if($billscount == 0)
        {
            $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = 'PB-'.$reference_no;
        }
        else
        {

            $lastbill = Billing::where('billing_type_id', 2)->latest()->first();
            $newtransaction_no = $lastbill->transaction_no+1;
            $newreference_no = $lastbill->reference_no;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            $reference_no = 'PB-'.$reference_no;
        }

        $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method' => 'required',
            'bank_id' => '',
            'online_portal' => '',
            'cheque_no' => '',
            'customer_portal_id' => '',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'cheque_no'=>'',
            'quantity'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
            'selected_filter_option'=> '',
            'original_vendor_price',
            'charging_rate',
            'final_vendor_price',
            'carrying_cost',
            'transportation_cost',
            'miscellaneous_percent',
            'other_cost',
            'product_cost',
            'custom_duty',
            'after_custom',
            'product_tax',
            'total_cost',
            'margin_type',
            'margin_value',
            'product_price',
        ]);


        if($request['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }

        $thisday = date('Y-m-d');

        if($request['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $user_id = Auth::user()->id;

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $request->fiscal_year_id)->get();
        if(count($journals) == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }

        $jvno = "JV-".$jvnumber;
        //Purchase Bill
        DB::beginTransaction();
        try{
            $billing = Billing::create([
                'vendor_id'=>$request['vendor_id'],
                'billing_type_id'=>$request['billing_type_id'],
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'ledger_no'=>$request['ledger_no'],
                'file_no'=>$request['file_no'],
                'remarks'=>$request['remarks'],
                'eng_date'=>$request['eng_date'],
                'nep_date'=>$request['nep_date'],
                'payment_method' => $request['payment_method'],
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'godown'=>$godown,
                'entry_by'=>$user_id,
                'status'=>$request['status'],
                'fiscal_year_id'=>$request['fiscal_year_id'],
                'alltaxtype'=>$request['alltaxtype'],
                'alltax'=>$request['alltax'],
                'taxamount'=>$request['taxamount'],
                'alldiscounttype'=>$request['alldiscounttype'],
                'discountamount'=>$request['discountamount'],
                'alldtamt'=>$request['alldtamt'],
                'subtotal'=>$request['subtotal'],
                'shipping'=>$request['shipping'],
                'grandtotal'=>$request['grandtotal'],
                'approved_by'=>$approval_by,
                'vat_refundable'=>$request['vat_refundable'],
                'sync_ird'=>$ird_sync,
                'is_realtime'=>$is_realtime,
                'selected_filter_option'=>$request->selected_filter_option,
                'declaration_form_no'=>$request->declaration_form_no,
                'related_jv_no'=>$jvno,
            ]);

            //ashish stockchange
            if($request->stock_change){
                $billing->update(['enable_stock_change'=>1]);
                $godown_qty = $request->godown_qty;

                if(count(array_unique($request->godown_id)) == 1){
                    $prostockarray = array();

                    foreach($request->quantity as $key=>$qty){
                        $prostockarray[$request->godown_id[0]][] = $qty;
                    }
                    $godown_qty = $prostockarray;
                }


                if(!empty($request->godown_id) && !empty($godown_qty) && !empty($request->particulars)){
                    $this->saveGodownProductStock($request->godown_id,$godown_qty,$request->particulars,$billing->id);
                }
                if($request->serial_product){
                $this->addserialNumberProduct($request->serial_product,$billing->id);
                }
            }
            //end ashish stock

            if(isset($_POST['convertToBill']))
            {
                $purchaseOrder = PurchaseOrder::where('id', $request['purchaseOrder'])->first();
                $purchaseOrder->update(['converted' => 1]);
            }

            if($request['sync_ird'] == 1 && $request['status'] == 1)
            {
                $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
                $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
                $unpaids = [];
                foreach($unpaidtaxes as $unpaidtax){
                    array_push($unpaids, $unpaidtax->total_tax);
                }
                $duetax = array_sum($unpaids);
                if($taxcount < 1)
                {
                    $purchasetax = TaxInfo::create([
                        'fiscal_year'=> $current_fiscal_year->fiscal_year,
                        'nep_month'=> $nepmonth,
                        'purchase_tax'=>$request['taxamount'],
                        'total_tax'=>-$request['taxamount'],
                        'is_paid'=>0,
                        'due'=>$duetax,
                    ]);
                    $purchasetax->save();
                }
                else
                {
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchase_tax = $purchasetax->purchase_tax + (float)$request['taxamount'];
                    $total_tax = $purchasetax->total_tax - (float)$request['taxamount'];
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                }
            }
            $payment_info = PaymentInfo::create([
                'billing_id'=>$billing['id'],
                'payment_type'=>$request['payment_type'],
                'payment_amount'=>$request['payment_amount'],
                'payment_date'=>$request['eng_date'],
                'total_paid_amount'=>$request['payment_amount'],
                'paid_to'=>Auth::user()->id,
            ]);
            $payment_info->save();

            if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid"){
                $billing->due_date_eng = $request->due_date_eng;
                $billing->due_date_nep = $request->due_date_nep;
                $this->billingCredit($billing);
            }
            //as.k

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            // Product Data
            $original_vendor_price = $request['original_vendor_price'];
            $charging_rate = $request['charging_rate'];
            $final_vendor_price = $request['final_vendor_price'];
            $carrying_cost = $request['carrying_cost'];
            $transportation_cost = $request['transportation_cost'];
            $miscellaneous_percent = $request['miscellaneous_percent'];
            $other_cost = $request['other_cost'];
            $product_cost = $request['product_cost'];
            $custom_duty = $request['custom_duty'];
            $after_custom = $request['after_custom'];
            $vat_select = $request['product_tax'];
            $total_cost = $request['total_cost'];
            $margin_type = $request['margin_type'];
            $margin_value = $request['margin_value'];
            $product_price = $request['product_price'];

            for($x=0; $x<$count; $x++)
            {
                $product = Product::where('id', $particulars[$x])->first();
                if($margin_type[$x] == 'percent'){
                    $profit_margin = $total_cost[$x] * $margin_value[$x]/100;
                }else{
                    $profit_margin =  $margin_value[$x];
                }
                $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
                if($currentcomp->company->is_importer == 1){
                    $product->update([
                        'original_vendor_price' => $original_vendor_price[$x] ?? 0,
                        'charging_rate' => $charging_rate[$x] ?? 0,
                        'final_vendor_price'=>$final_vendor_price[$x] ?? 0,
                        'carrying_cost'=>$carrying_cost[$x] ?? 0,
                        'transportation_cost'=>$transportation_cost[$x] ?? 0,
                        'miscellaneous_percent'=>$miscellaneous_percent[$x] ?? 0,
                        'other_cost'=>$other_cost[$x] ?? 0,
                        'cost_of_product'=>$product_cost[$x] ?? 0,
                        'custom_duty'=>$custom_duty[$x] ?? 0,
                        'after_custom'=>$after_custom[$x] ?? 0,
                        'tax'=>$vat_select[$x] ?? 0,
                        'total_cost'=>$total_cost[$x] ?? 0,
                        'margin_type'=>$margin_type[$x] ?? 'percent',
                        'margin_value'=>$margin_value[$x] ?? 0,
                        'profit_margin'=>$profit_margin ?? 0,
                        'product_price'=>$product_price[$x] ?? 0,
                    ]);
                }else{
                    $product->update([
                        'original_vendor_price' => $original_vendor_price[$x] ?? 0,
                        'cost_of_product'=>$product_cost[$x] ?? 0,
                        'margin_type'=>$margin_type[$x] ?? 'percent',
                        'margin_value'=>$margin_value[$x] ?? 0,
                        'profit_margin'=>$profit_margin ?? 0,
                        'product_price'=>$product_price[$x] ?? 0,
                        'total_cost'=>$total_cost[$x] ?? 0,
                    ]);
                }

                $billingextras = BillingExtra::create([
                    'billing_id'=>$billing['id'],
                    'particulars'=>$particulars[$x],
                    'quantity'=>$quantity[$x],
                    'rate'=>$rate[$x],
                    'unit'=>$unit[$x],
                    // 'discountamt'=>$discountamt[$x],
                    // 'discounttype'=>$discounttype[$x],
                    // 'dtamt'=>$dtamt[$x],
                    // 'taxamt'=>$taxamt[$x],
                    // 'itemtax'=>$itemtax[$x],
                    // 'taxtype'=>$taxtype[$x],
                    'total'=>$total[$x],
                ]);
            }
            $billingextras->save();

            // For Journal Voucher
            $journal_voucher = JournalVouchers::create([
                'journal_voucher_no' => $jvno,
                'entry_date_english' => $request['eng_date'],
                'entry_date_nepali' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year_id'],
                'debitTotal' => $request['grandtotal'] + $request['discountamount'],
                'creditTotal' => $request['grandtotal'] + $request['discountamount'],
                'payment_method' => $request['payment_method'],
                'receipt_payment' => $request['receipt_payment'] ?? null,
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'narration' => 'Being Goods Purchased',
                'is_cancelled'=>'0',
                'status' =>$request['status'],
                'vendor_id'=>$request['vendor_id'],
                'entry_by'=> Auth::user()->id,
                'approved_by'=> $request['status'] == 1 ? Auth::user()->id : null,
                'client_id'=>null,
            ]);

            // Getting Child Account Id of every Particulars
            // Debit Entries
            $jv_extras = [];
            for($x=0; $x<$count; $x++){
                $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code')->first();
                $particular_child_account_id = $particular_product->child_account_id;
                $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                $particular_jv_extras = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$particular_child_account_id,
                    'remarks'=>$remark,
                    'debitAmount'=>$total[$x],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $particular_jv_extras);
            }
            // Tax Entry
            if($request['taxamount'] > 0){
                $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                $outgoing_tax_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$outgoing_tax_id,
                    'remarks'=>'Tax',
                    'debitAmount'=>$request['taxamount'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $outgoing_tax_entry);
            }


            // Shipping Entry
            if($request['shipping'] > 0){
                $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                $shipping_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$shipping_id,
                    'remarks'=>'Shipping Cost',
                    'debitAmount'=>$request['shipping'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $shipping_entry);
            }

            // Credit Entries
            // Cash Paid

            if($request['payment_type'] == 'paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $cash_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $bank_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $bank_entry);
                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['grandtotal'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $online_portal);
                }
            }elseif($request['payment_type'] == 'partially_paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);

                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['payment_amount'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $online_portal);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);
                }
            }elseif($request['payment_type'] == 'unpaid'){
                $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                $due_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$vendor_child_id,
                    'remarks'=>'',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['grandtotal'],
                ];
                array_push($jv_extras, $due_entry);
            }
            // Discount Taken
            if($request['discountamount'] > 0){
                $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                $discount_received_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$discount_received_id,
                    'remarks'=>'Discount Received',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['discountamount'],
                ];
                array_push($jv_extras, $discount_received_entry);
            }

            foreach($jv_extras as $key => $jv_extra){
                JournalExtra::create($jv_extra);
                if($request['status'] == 1){
                    $this->openingbalance($jv_extra['child_account_id'], $request['fiscal_year_id'], $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                }
            }


            $journal_voucher->save();
            // Journal Voucher Ends Here
            DB::commit();
            if($saveandcontinue == 1){
                return redirect()->route('billings.purchasecreate')->with('success', 'Billing Successfully Created.');
            }else{
                return redirect()->route('billings.report', $request['billing_type_id'])->with('success', 'Billing Successfully Created.');
            }

        }
        catch(\Exception $e)
        {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function salesstore(Request $request)
    {
        // dd('HI');
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $request['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $billscount = Billing::where('billing_type_id', 1)->count();

        if($billscount == 0)
        {
            $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = 'SB-'.$reference_no;
        }
        else
        {
            $lastbill = Billing::where('billing_type_id', 1)->latest()->first();
            $newtransaction_no = $lastbill->transaction_no+1;
            $newreference_no = $lastbill->reference_no;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            $reference_no = 'SB-'.$reference_no;
        }

        $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method' => 'required',
            'bank_id' => '',
            'online_portal' => '',
            'cheque_no' => '',
            'customer_portal_id' => '',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'cheque_no'=>'',
            'quantity'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
            'selected_filter_option'=> '',
            'original_vendor_price',
            'charging_rate',
            'final_vendor_price',
            'carrying_cost',
            'transportation_cost',
            'miscellaneous_percent',
            'other_cost',
            'product_cost',
            'custom_duty',
            'after_custom',
            'product_tax',
            'total_cost',
            'margin_type',
            'margin_value',
            'product_price',
        ]);


        if($request['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }

        $thisday = date('Y-m-d');

        if($request['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $user_id = Auth::user()->id;

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $request->fiscal_year_id)->get();
        if(count($journals) == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }

        $jvno = "JV-".$jvnumber;

        DB::beginTransaction();
        try{

            //Sales
            $billing = Billing::create([
                'billing_type_id'=>$request['billing_type_id'],
                'client_id'=>$request['client_id'],
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'ledger_no'=>$request['ledger_no'],
                'file_no'=>$request['file_no'],
                'remarks'=>$request['remarks'],
                'eng_date'=>$request['eng_date'],
                'nep_date'=>$request['nep_date'],
                'payment_method' => $request['payment_method'],
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'godown'=>$request['godown'],
                'entry_by'=>$user_id,
                'status'=>$request['status'],
                'fiscal_year_id'=>$request['fiscal_year_id'],
                'alltaxtype'=>$request['alltaxtype'],
                'alltax'=>$request['alltax'],
                'taxamount'=>$request['taxamount'],
                'alldiscounttype'=>$request['alldiscounttype'],
                'discountamount'=>$request['discountamount'],
                'alldtamt'=>$request['alldtamt'],
                'subtotal'=>$request['subtotal'],
                'shipping'=>$request['shipping'],
                'grandtotal'=>$request['grandtotal'],
                'approved_by'=>$approval_by,
                'vat_refundable'=>$request['vat_refundable'],
                'sync_ird'=>$ird_sync,
                'is_realtime'=>$is_realtime,
                'declaration_form_no'=>$request->declaration_form_no,
                'related_jv_no'=>$jvno,
            ]);


            if($request['sync_ird'] == 1 && $request['status'] == 1)
            {
                $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
                $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
                $unpaids = [];
                foreach($unpaidtaxes as $unpaidtax){
                    array_push($unpaids, $unpaidtax->total_tax);
                }
                $duetax = array_sum($unpaids);
                if($taxcount < 1){
                    $salestax = TaxInfo::create([
                        'fiscal_year'=> $current_fiscal_year->fiscal_year,
                        'nep_month'=> $nepmonth,
                        'sales_tax'=>$request['taxamount'],
                        'total_tax'=>$request['taxamount'],
                        'is_paid'=>0,
                        'due'=>$duetax,
                    ]);
                    $salestax->save();
                }else{
                    $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $sales_tax = $salestax->sales_tax + (float)$request['taxamount'];
                    $total_tax = $salestax->total_tax + (float)$request['taxamount'];
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salestax->save();
                }
            }
            if(count((array)$request['serial_No']) > 0){
                if($request['status'] == 1){
                    $serial_numbers_products = GodownSerialNumber::whereIn('serial_number', $request['serial_No'])->get();
                    foreach($serial_numbers_products as $serialproducts){
                        $serialproducts->update([
                            'is_sold'=>1,
                            'billing_id'=>$billing['id'],
                            'sales_approved'=>1,
                        ]);
                    }
                }else{
                    $serial_numbers_products = GodownSerialNumber::whereIn('serial_number', $request['serial_No'])->get();
                    foreach($serial_numbers_products as $serialproducts){
                        $serialproducts->update([
                            'is_sold'=>1,
                            'billing_id'=>$billing['id'],
                            'sales_approved'=>0,
                        ]);
                    }
                }
            }



            if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid")
            {
                $credit = Credit::where('customer_id', $request['client_id'])->where('converted', 0)->first();
                $credit_days_in_seconds = $credit->allocated_days * 86400;

                $date_in_string = strtotime($request['eng_date']);
                $bill_date_in_nepali = datenep($request['eng_date']);

                $due_date_in_string = $date_in_string + $credit_days_in_seconds;
                $due_date_in_eng = date("Y-m-d", $due_date_in_string);
                $due_date = datenep($due_date_in_eng);

                $payment_info = PaymentInfo::create([
                    'billing_id' => $billing['id'],
                    'payment_type' => $request['payment_type'],
                    'payment_amount' => $request['payment_amount'],
                    'payment_date' => $request['eng_date'],
                    'due_date' => $due_date,
                    'total_paid_amount' => $request['payment_amount'],
                    'paid_to' => Auth::user()->id,
                    'is_sales_invoice' => 1
                ]);
                $payment_info->save();

                $credit_bills_count = Credit::where('customer_id', $request['client_id'])->where('converted', 0)->get()->count();

                $new_credited_bills = $credit->credited_bills + 1;
                $new_credited_amount = $request['grandtotal'] - $request['payment_amount'];

                if($credit_bills_count == 1 && $credit->credited_bills == null)
                {
                    $credit->update([
                        'invoice_id' => $billing['id'],
                        'bill_eng_date' => $request['eng_date'],
                        'bill_nep_date' => $bill_date_in_nepali,
                        'bill_expire_eng_date' => $due_date_in_eng,
                        'bill_expire_nep_date' => $due_date,
                        'credited_bills' => $new_credited_bills,
                        'credited_amount' => $new_credited_amount,
                    ]);
                }
                else
                {
                    Credit::create([
                        'customer_id' => $credit->customer_id,
                        'allocated_days' => $credit->allocated_days,
                        'allocated_bills' => $credit->allocated_bills,
                        'allocated_amount' => $credit->allocated_amount,

                        'invoice_id' => $billing['id'],
                        'bill_eng_date' => $request['eng_date'],
                        'bill_nep_date' => $bill_date_in_nepali,
                        'bill_expire_eng_date' => $due_date_in_eng,
                        'bill_expire_nep_date' => $due_date,
                        'credited_bills' => $new_credited_bills,
                        'credited_amount' => $new_credited_amount,
                    ]);
                }
            }
            else if ($request['payment_type'] == "paid")
            {
                $payment_info = PaymentInfo::create([
                    'billing_id' => $billing['id'],
                    'payment_type' => $request['payment_type'],
                    'payment_amount' => $request['payment_amount'],
                    'payment_date' => $request['eng_date'],
                    'total_paid_amount' => $request['payment_amount'],
                    'paid_to' => Auth::user()->id,
                ]);
                $payment_info->save();
            }

            //billingcredits after payment info
            //as.k

            if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid")
            {
                $billing->due_date_eng = $request->due_date_eng;
                $billing->due_date_nep = $request->due_date_nep;
                $this->billingCredit($billing);
            }
            //as.k

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            // Product Data
            $original_vendor_price = $request['original_vendor_price'];
            $charging_rate = $request['charging_rate'];
            $final_vendor_price = $request['final_vendor_price'];
            $carrying_cost = $request['carrying_cost'];
            $transportation_cost = $request['transportation_cost'];
            $miscellaneous_percent = $request['miscellaneous_percent'];
            $other_cost = $request['other_cost'];
            $product_cost = $request['product_cost'];
            $custom_duty = $request['custom_duty'];
            $after_custom = $request['after_custom'];
            $vat_select = $request['product_tax'];
            $total_cost = $request['total_cost'];
            $margin_type = $request['margin_type'];
            $margin_value = $request['margin_value'];
            $product_price = $request['product_price'];

            $taxsum = 0;
            $discountsum = $request['discountamount'];
            for($x=0; $x<$count; $x++)
            {
                $billingextras = BillingExtra::create([
                    'billing_id'=>$billing['id'],
                    'particulars'=>$particulars[$x],
                    'quantity'=>$quantity[$x],
                    'rate'=>$rate[$x],
                    'unit'=>null,
                    'discountamt'=>$discountamt[$x],
                    'discounttype'=>$discounttype[$x],
                    'dtamt'=>$dtamt[$x],
                    'taxamt'=>$taxamt[$x],
                    'tax'=>$tax[$x],
                    'itemtax'=>$itemtax[$x],
                    'taxtype'=>$taxtype[$x],
                    'total'=>$total[$x],
                ]);

                $salesrecord = SalesRecord::create([
                    'billing_id'=>$billing['id'],
                    'product_id'=>$particulars[$x],
                    'godown_id'=>$request['godown'],
                    'stock_sold'=>$quantity[$x],
                    'date_sold'=>$request['eng_date'],
                ]);
                $salesrecord->save();
                // Stock effects on approved stock only
                if($request['status'] == 1){
                    $product = Product::findorfail($particulars[$x]);
                    $stock = $product->total_stock;
                    $remstock = $stock - $quantity[$x];
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $particulars[$x])->where('godown_id', $request['godown'])->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock - $quantity[$x];
                    $alert_on = $godownproduct->alert_on;
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();

                    if($remgostock <= $alert_on){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$particulars[$x],
                            'godown_id'=>$request['godown'],
                            'noti_type'=>'low_stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }elseif($remgostock <= 0){
                        $productnotification = ProductNotification::create([
                            'product_id'=>$particulars[$x],
                            'godown_id'=>$request['godown'],
                            'noti_type'=>'stock',
                            'status'=>0,
                            'read_at'=>null,
                            'read_by'=>null,
                        ]);
                        $productnotification->save();
                    }
                }
                $billingextras->save();

                $taxsum += $itemtax[$x];
                $discountsum += ($discountamt[$x] * $quantity[$x]);
            }
            $tottax = $taxsum == 0 ? $request['taxamount'] :  $taxsum;
            // For Journal Voucher
            $journal_voucher = JournalVouchers::create([
                'journal_voucher_no' => $jvno,
                'entry_date_english' => $request['eng_date'],
                'entry_date_nepali' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year_id'],
                'debitTotal' => $request['grandtotal'] + $discountsum,
                'creditTotal' => $request['grandtotal'] + $discountsum,
                'payment_method' => $request['payment_method'],
                'receipt_payment' => $request['receipt_payment'] ?? null,
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'narration' => 'Being Goods Sold',
                'is_cancelled'=>'0',
                'status' =>$request['status'],
                'vendor_id'=>null,
                'entry_by'=> Auth::user()->id,
                'approved_by'=> $request['status'] == 1 ? Auth::user()->id : null,
                'client_id'=>$request['vendor_id'],
            ]);

            // Getting Child Account Id of every Particulars
            // Debit Entries
            $jv_extras = [];
            if($request['payment_type'] == 'paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $bank_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $bank_entry);
                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['grandtotal'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $online_portal);
                }
            }elseif($request['payment_type'] == 'partially_paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);

                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['payment_amount'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $online_portal);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);
                }
            }elseif($request['payment_type'] == 'unpaid'){
                $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                $due_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$client_child_id,
                    'remarks'=>'',
                    'debitAmount'=>$request['grandtotal'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $due_entry);
            }
            // Discount Taken
            $totdiscount = $discountsum;
            if($totdiscount > 0){
                $discount_id = ChildAccount::where('slug', 'discount')->first()->id;
                $discount_received_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$discount_id,
                    'remarks'=>'Discount Given',
                    'debitAmount'=>$totdiscount,
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $discount_received_entry);
            }


            // Credit Entries
            // Cash Paid
            $margin = 0;
            for($x=0; $x<$count; $x++){
                $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code', 'total_cost')->first();
                $cost_price = $particular_product->total_cost;
                $total_cost_price = $cost_price * $quantity[$x];
                $rate_per_qty = $rate[$x] * $quantity[$x];
                $margin += $rate_per_qty - $total_cost_price;

                $particular_child_account_id = $particular_product->child_account_id;
                $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                $particular_jv_extras = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$particular_child_account_id,
                    'remarks'=>$remark,
                    'debitAmount'=>0,
                    'creditAmount'=>$total_cost_price,
                ];
                array_push($jv_extras, $particular_jv_extras);
            }
            // Margin
            $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
            $margin_entry = [
                'journal_voucher_id'=>$journal_voucher['id'],
                'child_account_id'=>$margin_child_id,
                'remarks'=>'Sales Total Margin',
                'debitAmount'=>0,
                'creditAmount'=>$margin,
            ];
            array_push($jv_extras, $margin_entry);
            // Tax Entry
            if($request['taxamount'] > 0){
                $incoming_tax_id = ChildAccount::where('slug', 'incoming-tax')->first()->id;
                $incoming_tax_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$incoming_tax_id,
                    'remarks'=>'Tax',
                    'debitAmount'=>0,
                    'creditAmount'=>$tottax,
                ];
                array_push($jv_extras, $incoming_tax_entry);
            }


            // Shipping Entry
            if($request['shipping'] > 0){
                $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                $shipping_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$shipping_id,
                    'remarks'=>'Shipping Cost',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['shipping'],
                ];
                array_push($jv_extras, $shipping_entry);
            }

            foreach($jv_extras as $key => $jv_extra){
                JournalExtra::create($jv_extra);
                if($request['status'] == 1){
                    $this->openingbalance($jv_extra['child_account_id'], $request['fiscal_year_id'], $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                }
            }


            $journal_voucher->save();
            // Journal Voucher Ends Here
            DB::commit();
            if($saveandcontinue == 1){
                    return redirect()->route('billings.salescreate')->with('success', 'Billing Successfully Created.');
            }else{
                return redirect()->route('billings.report', $request['billing_type_id'])->with('success', 'Billing Successfully Created.');
            }

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function debitnotestore(Request $request)
    {
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $request['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        //Purchase Return Bill
        $billscount = Billing::where('billing_type_id', 5)->count();

        if($billscount == 0)
        {
            $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = 'PR-'.$reference_no;
        }
        else
        {
            $lastbill = Billing::where('billing_type_id', 5)->latest()->first();
            $newtransaction_no = $lastbill->transaction_no+1;
            $newreference_no = $lastbill->reference_no;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            $reference_no = 'PR-'.$reference_no;
        }

        $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method' => 'required',
            'bank_id' => '',
            'online_portal' => '',
            'cheque_no' => '',
            'customer_portal_id' => '',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'cheque_no'=>'',
            'quantity'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
            'selected_filter_option'=> '',
            'original_vendor_price',
            'charging_rate',
            'final_vendor_price',
            'carrying_cost',
            'transportation_cost',
            'miscellaneous_percent',
            'other_cost',
            'product_cost',
            'custom_duty',
            'after_custom',
            'product_tax',
            'total_cost',
            'margin_type',
            'margin_value',
            'product_price',
        ]);


        if($request['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }

        $thisday = date('Y-m-d');

        if($request['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $user_id = Auth::user()->id;

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $request->fiscal_year_id)->get();
        if(count($journals) == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }

        $jvno = "JV-".$jvnumber;
        DB::beginTransaction();
        try{
            //Purchase Return / Debit Note
            $billing = Billing::create([
                'vendor_id'=>$request['vendor_id'],
                'billing_type_id'=>$request['billing_type_id'],
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'reference_invoice_no'=> $request['reference_invoice_no'],
                'ledger_no'=>$request['ledger_no'],
                'file_no'=>$request['file_no'],
                'remarks'=>$request['remarks'],
                'eng_date'=>$request['eng_date'],
                'nep_date'=>$request['nep_date'],
                'payment_method' => $request['payment_method'],
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'entry_by'=>$user_id,
                'status'=>$request['status'],
                'fiscal_year_id'=>$request['fiscal_year_id'],
                'alltaxtype'=>$request['alltaxtype'],
                'alltax'=>$request['alltax'],
                'taxamount'=>$request['taxamount'],
                'alldiscounttype'=>$request['alldiscounttype'],
                'discountamount'=>$request['discountamount'],
                'alldtamt'=>$request['alldtamt'],
                'subtotal'=>$request['subtotal'],
                'shipping'=>$request['shipping'],
                'grandtotal'=>$request['grandtotal'],
                'approved_by'=>$approval_by,
                'vat_refundable'=>$request['vat_refundable'],
                'sync_ird'=>$ird_sync,
                'is_realtime'=>$is_realtime,
                'declaration_form_no'=>$request->declaration_form_no,
                'related_jv_no'=>$jvno,
            ]);
            $debitnote_billing_id = $billing->id;

            ReturnReference::create([
                'billing_id'=>$debitnote_billing_id,
                'reference_billing_id'=>$request->billing_id,
                'notetype'=>'debitnote',
            ]);

            if($request['sync_ird'] == 1 && $request['status'] == 1)
            {
                $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
                $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
                $unpaids = [];
                foreach($unpaidtaxes as $unpaidtax){
                    array_push($unpaids, $unpaidtax->total_tax);
                }
                $duetax = array_sum($unpaids);
                if($taxcount < 1){
                    $purchasereturntax = TaxInfo::create([
                        'fiscal_year'=> $current_fiscal_year->fiscal_year,
                        'nep_month'=> $nepmonth,
                        'purchasereturn_tax'=>$request['taxamount'],
                        'total_tax'=>$request['taxamount'],
                        'is_paid'=>0,
                        'due'=>$duetax,
                    ]);
                    $purchasereturntax->save();
                }else{
                    $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $purchasereturn_tax = $purchasereturntax->purchasereturn_tax + (float)$request['taxamount'];
                    $total_tax = $purchasereturntax->total_tax + (float)$request['taxamount'];
                    $purchasereturntax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasereturntax->save();
                }
            }

            $payment_info = PaymentInfo::create([
                'billing_id'=>$billing['id'],
                'payment_type'=>$request['payment_type'],
                'payment_amount'=>$request['payment_amount'],
                'payment_date'=>$request['eng_date'],
                'total_paid_amount'=>$request['payment_amount'],
                'paid_to'=>Auth::user()->id,
            ]);
            $payment_info->save();

            //billingcredits after payment info
            //as.k
            if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid")
            {
                $billing->due_date_eng = $request->due_date_eng;
                $billing->due_date_nep = $request->due_date_nep;
                $this->billingCredit($billing);
            }
            //as.k

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            // Product Data
            $original_vendor_price = $request['original_vendor_price'];
            $charging_rate = $request['charging_rate'];
            $final_vendor_price = $request['final_vendor_price'];
            $carrying_cost = $request['carrying_cost'];
            $transportation_cost = $request['transportation_cost'];
            $miscellaneous_percent = $request['miscellaneous_percent'];
            $other_cost = $request['other_cost'];
            $product_cost = $request['product_cost'];
            $custom_duty = $request['custom_duty'];
            $after_custom = $request['after_custom'];
            $vat_select = $request['product_tax'];
            $total_cost = $request['total_cost'];
            $margin_type = $request['margin_type'];
            $margin_value = $request['margin_value'];
            $product_price = $request['product_price'];


            for($x=0; $x<$count; $x++)
            {
                if(!empty($particulars[$x])){
                $billingextras = BillingExtra::create([
                    'billing_id'=>$billing['id'],
                    'particulars'=>$particulars[$x],
                    'quantity'=>$quantity[$x],
                    'rate'=>$rate[$x],
                    'unit'=>$unit[$x],
                    // 'discountamt'=>$discountamt[$x],
                    // 'discounttype'=>$discounttype[$x],
                    // 'dtamt'=>$dtamt[$x],
                    // 'taxamt'=>$taxamt[$x],
                    // 'itemtax'=>$itemtax[$x],
                    // 'taxtype'=>$taxtype[$x],
                    'total'=>$total[$x],
                ]);
                //for stock change
                if($request['billing_type_id'] == 5 && $request->status == 1 && $request->enablestockchange == 1){
                    Product::where('id',$particulars[$x])->decrement('total_stock',$quantity[$x]);

                }
                //end for stock change
                }
                $billingextras->save();
            }
            // as.k
            if($request->status == 1 && $request->enablestockchange == 1){
                if(!empty($request->godown_id)){

                    $particulars = $request->particulars;

                    foreach($request->godown_qty as $godownkey=>$qty){

                        foreach($qty as $productkey=>$qt){

                            GodownProduct::where('godown_id',$godownkey)->where('product_id',$particulars[$productkey])->decrement('stock',$qt);
                            DebitCreditNote::updateOrCreate(
                                [
                                    'billing_id'=>$debitnote_billing_id,
                                    'product_id'=>$particulars[$productkey],
                                    'godown_id'=>$godownkey,
                                ],
                                [
                                    'amount'=>$qt,
                                    'notetype'=>'debitnote',
                                ]

                            );
                        }

                    }

                }
            }


            if(isset($request->serial_product) && !empty($request->serial_product)){
                foreach($request->serial_product as $serialkey=>$serial){
                    if(empty($serial)){
                        continue;
                    }

                    $explodegdpr = explode("-",$serialkey);
                    $explodeserial = explode(",",$serial);
                    $godownproduct = GodownProduct::where('godown_id',current($explodegdpr))->where('product_id',end($explodegdpr))->first();
                    if(!empty($godownproduct)){
                        $debitserial = DebitCreditNote::where('godown_id',$godownproduct->godown_id)->where('product_id',end($explodegdpr))->where('billing_id',$debitnote_billing_id)->first();

                        foreach($explodeserial as $serialnumber){

                            if(!empty($debitserial)){

                                $jsondata = (!empty($debitserial->serial_number)) ? json_decode($debitserial->serial_number): array();

                                array_push($jsondata,$serialnumber);
                                $debitserial->update(['serial_number'=>json_encode($jsondata)]);
                            }
                            GodownSerialNumber::where('godown_product_id',$godownproduct->id)->where('serial_number',$serialnumber)->delete();
                        }
                    }
                }
            }

            // For Journal Voucher
            $journal_voucher = JournalVouchers::create([
                'journal_voucher_no' => $jvno,
                'entry_date_english' => $request['eng_date'],
                'entry_date_nepali' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year_id'],
                'debitTotal' => $request['grandtotal'] + $request['discountamount'],
                'creditTotal' => $request['grandtotal'] + $request['discountamount'],
                'payment_method' => $request['payment_method'],
                'receipt_payment' => $request['receipt_payment'] ?? null,
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'narration' => 'Being Purchase Returned',
                'is_cancelled'=>'0',
                'status' =>$request['status'],
                'vendor_id'=>$request['vendor_id'],
                'entry_by'=> Auth::user()->id,
                'approved_by'=> $request['status'] == 1 ? Auth::user()->id : null,
                'client_id'=>null,
            ]);

            // Getting Child Account Id of every Particulars
            // Debit Entries
            $jv_extras = [];
            if($request['payment_type'] == 'paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $bank_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $bank_entry);
                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['grandtotal'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['grandtotal'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $online_portal);
                }
            }elseif($request['payment_type'] == 'partially_paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);

                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['payment_amount'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$request['payment_amount'],
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $online_portal);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$vendor_child_id,
                        'remarks'=>'',
                        'debitAmount'=>$due_amount,
                        'creditAmount'=>0,
                    ];
                    array_push($jv_extras, $due_entry);
                }
            }elseif($request['payment_type'] == 'unpaid'){
                $vendor_child_id = Vendor::where('id', $request['vendor_id'])->first()->child_account_id;
                $due_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$vendor_child_id,
                    'remarks'=>'',
                    'debitAmount'=>$request['grandtotal'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $due_entry);
            }
            // Discount Taken
            if($request['discountamount'] > 0){
                $discount_id = ChildAccount::where('slug', 'discount')->first()->id;
                $discount_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$discount_id,
                    'remarks'=>'Discount',
                    'debitAmount'=>$request['discountamount'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $discount_entry);
            }


            // Credit Entries
            // Cash Paid
            for($x=0; $x<$count; $x++){
                $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code')->first();
                $particular_child_account_id = $particular_product->child_account_id;
                $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                $particular_jv_extras = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$particular_child_account_id,
                    'remarks'=>$remark,
                    'debitAmount'=>0,
                    'creditAmount'=>$total[$x],
                ];
                array_push($jv_extras, $particular_jv_extras);
            }
            // Tax Entry
            if($request['taxamount'] > 0){
                $incoming_tax_id = ChildAccount::where('slug', 'incoming-tax')->first()->id;
                $incoming_tax_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$incoming_tax_id,
                    'remarks'=>'Tax',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['taxamount'],
                ];
                array_push($jv_extras, $incoming_tax_entry);
            }


            // Shipping Entry
            if($request['shipping'] > 0){
                $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                $shipping_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$shipping_id,
                    'remarks'=>'Shipping Cost',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['shipping'],
                ];
                array_push($jv_extras, $shipping_entry);
            }

            foreach($jv_extras as $key => $jv_extra){
                JournalExtra::create($jv_extra);
                if($request['status'] == 1){
                    $this->openingbalance($jv_extra['child_account_id'], $request['fiscal_year_id'], $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                }
            }


            $journal_voucher->save();
            // Journal Voucher Ends Here

            DB::commit();
            if($saveandcontinue == 1){
                return redirect()->route('billings.debitnotecreate')->with('success', 'Billing Successfully Created.');
            }else{
                return redirect()->route('billings.report', $request['billing_type_id'])->with('success', 'Billing Successfully Created.');
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function creditnotestore(Request $request){
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $request['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        //Purchase Return Bill
        $billscount = Billing::where('billing_type_id', 6)->count();

        if($billscount == 0)
        {
            $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
            $reference_no = 'CR-'.$reference_no;
        }
        else
        {
            $lastbill = Billing::where('billing_type_id', 6)->latest()->first();
            $newtransaction_no = $lastbill->transaction_no+1;
            $newreference_no = $lastbill->reference_no;
            $expref = explode('-', $newreference_no);
            $ref = $expref[1]+1;

            $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
            $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
            $reference_no = 'CR-'.$reference_no;
        }

        $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method' => 'required',
            'bank_id' => '',
            'online_portal' => '',
            'cheque_no' => '',
            'customer_portal_id' => '',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'cheque_no'=>'',
            'quantity'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
            'selected_filter_option'=> '',
            'original_vendor_price',
            'charging_rate',
            'final_vendor_price',
            'carrying_cost',
            'transportation_cost',
            'miscellaneous_percent',
            'other_cost',
            'product_cost',
            'custom_duty',
            'after_custom',
            'product_tax',
            'total_cost',
            'margin_type',
            'margin_value',
            'product_price',
        ]);


        if($request['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }

        $thisday = date('Y-m-d');

        if($request['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $user_id = Auth::user()->id;

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $request->fiscal_year_id)->get();
        if(count($journals) == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }

        $jvno = "JV-".$jvnumber;
        // Sales Return / Credit Note

        DB::beginTransaction();
        try{

            $billing = Billing::create([
                'client_id'=>$request['client_id'],
                'billing_type_id'=>$request['billing_type_id'],
                'transaction_no'=>$transaction_no,
                'reference_no'=>$reference_no,
                'reference_invoice_no'=> $request['reference_invoice_no'],
                'ledger_no'=>$request['ledger_no'],
                'file_no'=>$request['file_no'],
                'remarks'=>$request['remarks'],
                'eng_date'=>$request['eng_date'],
                'nep_date'=>$request['nep_date'],
                'payment_method' => $request['payment_method'],
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'godown'=>$request['godown'],
                'entry_by'=>$user_id,
                'status'=>$request['status'],
                'fiscal_year_id'=>$request['fiscal_year_id'],
                'alltaxtype'=>$request['alltaxtype'],
                'alltax'=>$request['alltax'],
                'taxamount'=>$request['taxamount'],
                'alldiscounttype'=>$request['alldiscounttype'],
                'discountamount'=>$request['discountamount'],
                'alldtamt'=>$request['alldtamt'],
                'subtotal'=>$request['subtotal'],
                'shipping'=>$request['shipping'],
                'grandtotal'=>$request['grandtotal'],
                'approved_by'=>$approval_by,
                'vat_refundable'=>$request['vat_refundable'],
                'sync_ird'=>$ird_sync,
                'is_realtime'=>$is_realtime,
                'declaration_form_no'=>$request->declaration_form_no,
                'related_jv_no'=>$jvno,
            ]);

            $creditnote_billing_id = $billing->id;

            ReturnReference::create([
                'billing_id'=>$creditnote_billing_id,
                'reference_billing_id'=>$request->billing_id,
                'notetype'=>'creditnote',
            ]);
            if($request['sync_ird'] == 1 && $request['status'] == 1){
                $taxcount = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->count();
                $unpaidtaxes = TaxInfo::where('is_paid', 0)->get();
                $unpaids = [];
                foreach($unpaidtaxes as $unpaidtax){
                    array_push($unpaids, $unpaidtax->total_tax);
                }
                $duetax = array_sum($unpaids);
                if($taxcount < 1){
                    $salesreturntax = TaxInfo::create([
                        'fiscal_year'=> $current_fiscal_year->fiscal_year,
                        'nep_month'=> $nepmonth,
                        'salesreturn_tax'=>$request['taxamount'],
                        'total_tax'=>-$request['taxamount'],
                        'is_paid'=>0,
                        'due'=>$duetax,
                    ]);
                    $salesreturntax->save();
                }else{
                    $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $salesreturn_tax = $salesreturntax->salesreturn_tax + (float)$request['taxamount'];
                    $total_tax = $salesreturntax->total_tax - (float)$request['taxamount'];
                    $salesreturntax->update([
                        'salesreturn_tax'=>$salesreturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salesreturntax->save();
                }
            }

            $payment_info = PaymentInfo::create([
                'billing_id'=>$billing['id'],
                'payment_type'=>$request['payment_type'],
                'payment_amount'=>$request['payment_amount'],
                'payment_date'=>$request['eng_date'],
                'total_paid_amount'=>$request['payment_amount'],
                'paid_to'=>Auth::user()->id,
            ]);
            $payment_info->save();
            //billingcredits after payment info
            //as.k
            if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid")
            {
                $billing->due_date_eng = $request->due_date_eng;
                $billing->due_date_nep = $request->due_date_nep;
                $this->billingCredit($billing);
            }
            //as.k

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            // Product Data
            $original_vendor_price = $request['original_vendor_price'];
            $charging_rate = $request['charging_rate'];
            $final_vendor_price = $request['final_vendor_price'];
            $carrying_cost = $request['carrying_cost'];
            $transportation_cost = $request['transportation_cost'];
            $miscellaneous_percent = $request['miscellaneous_percent'];
            $other_cost = $request['other_cost'];
            $product_cost = $request['product_cost'];
            $custom_duty = $request['custom_duty'];
            $after_custom = $request['after_custom'];
            $vat_select = $request['product_tax'];
            $total_cost = $request['total_cost'];
            $margin_type = $request['margin_type'];
            $margin_value = $request['margin_value'];
            $product_price = $request['product_price'];

            $taxsum = 0;
            $discountsum = $request['discountamount'];
            for($x=0; $x<$count; $x++)
            {

                $billingextras = BillingExtra::create([
                    'billing_id'=>$billing['id'],
                    'particulars'=>$particulars[$x],
                    'quantity'=>$quantity[$x],
                    'rate'=>$rate[$x],
                    'unit'=>$unit[$x],
                    'discountamt'=>$discountamt[$x],
                    'discounttype'=>$discounttype[$x],
                    'dtamt'=>$dtamt[$x],
                    'taxamt'=>$taxamt[$x],
                    'tax'=>$tax[$x],
                    'itemtax'=>$itemtax[$x],
                    'taxtype'=>$taxtype[$x],
                    'total'=>$total[$x],
                ]);
                $salesreturnrecord = SalesReturnRecord::create([
                    'billing_id'=>$billing['id'],
                    'product_id'=>$particulars[$x],
                    'godown_id'=>$request['godown'],
                    'stock_return'=>$quantity[$x],
                    'date_return'=>$request['eng_date'],
                ]);
                $salesreturnrecord->save();
                if($request['status'] == 1){
                    $product = Product::findorfail($particulars[$x]);
                    $stock = $product->total_stock;
                    $remstock = $stock + $quantity[$x];
                    $product->update([
                        'total_stock' => $remstock
                    ]);
                    $product->save();
                    $godownproduct = GodownProduct::where('product_id', $particulars[$x])->where('godown_id', $request['godown'])->first();
                    $gostock = $godownproduct->stock;
                    $remgostock = $gostock + $quantity[$x];
                    $godownproduct->update([
                        'stock' => $remgostock
                    ]);
                    $godownproduct->save();
                }
                $billingextras->save();

                $taxsum += $itemtax[$x];
                $discountsum += ($discountamt[$x] * $quantity[$x]);
            }

            $tottax = $taxsum == 0 ? $request['taxamount'] :  $taxsum;

            // For Journal Voucher
            $journal_voucher = JournalVouchers::create([
                'journal_voucher_no' => $jvno,
                'entry_date_english' => $request['eng_date'],
                'entry_date_nepali' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year_id'],
                'debitTotal' => $request['grandtotal'] + $discountsum,
                'creditTotal' => $request['grandtotal'] + $discountsum,
                'payment_method' => $request['payment_method'],
                'receipt_payment' => $request['receipt_payment'] ?? null,
                'bank_id' => $bank_id,
                'online_portal_id' => $online_portal_id,
                'cheque_no' => $cheque_no,
                'customer_portal_id' => $customer_portal_id,
                'narration' => 'Being Sales Returned',
                'is_cancelled'=>'0',
                'status' =>$request['status'],
                'vendor_id'=>null,
                'entry_by'=> Auth::user()->id,
                'approved_by'=> $request['status'] == 1 ? Auth::user()->id : null,
                'client_id'=>$request['client_id'],
            ]);

            // Getting Child Account Id of every Particulars
            // Debit Entries
            $jv_extras = [];
            $margin = 0;
            for($x=0; $x<$count; $x++){
                $particular_product = Product::where('id', $particulars[$x])->select('child_account_id', 'product_name', 'product_code', 'total_cost')->first();
                $cost_price = $particular_product->total_cost;
                $total_cost_price = $cost_price * $quantity[$x];
                $rate_per_qty = $rate[$x] * $quantity[$x];
                $margin += $rate_per_qty - $total_cost_price;

                $particular_child_account_id = $particular_product->child_account_id;
                $remark = $particular_product->product_name . '('. $particular_product->product_code .')';
                $particular_jv_extras = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$particular_child_account_id,
                    'remarks'=>$remark,
                    'debitAmount'=>$total_cost_price,
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $particular_jv_extras);
            }
            // Margin
            $margin_child_id = ChildAccount::where('slug', 'sales-margin')->first()->id;
            $margin_entry = [
                'journal_voucher_id'=>$journal_voucher['id'],
                'child_account_id'=>$margin_child_id,
                'remarks'=>'Sales Total Margin',
                'debitAmount'=>$margin,
                'creditAmount'=>0,
            ];
            array_push($jv_extras, $margin_entry);
            // Tax Entry
            if($request['taxamount'] > 0){
                $outgoing_tax_id = ChildAccount::where('slug', 'outgoing-tax')->first()->id;
                $outgoing_tax_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$outgoing_tax_id,
                    'remarks'=>'Tax',
                    'debitAmount'=>$tottax,
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $outgoing_tax_entry);
            }


            // Shipping Entry
            if($request['shipping'] > 0){
                $shipping_id = ChildAccount::where('slug', 'shipping-charge')->first()->id;
                $shipping_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$shipping_id,
                    'remarks'=>'Shipping Cost',
                    'debitAmount'=>$request['shipping'],
                    'creditAmount'=>0,
                ];
                array_push($jv_extras, $shipping_entry);
            }

            // Credit Entries
            // Cash Paid

            if($request['payment_type'] == 'paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $cash_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $bank_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $bank_entry);
                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['grandtotal'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['grandtotal'],
                    ];
                    array_push($jv_extras, $online_portal);
                }
            }elseif($request['payment_type'] == 'partially_paid'){
                if($request['payment_method'] == 1){
                    $cash_id = ChildAccount::where('slug', 'cash-in-hand')->first()->id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$cash_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);
                }elseif($request['payment_method'] == 2 || $request['payment_method'] == 3){
                    $bank_child_id = Bank::where('id', $request['bank_id'])->first()->child_account_id;
                    $cash_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$bank_child_id,
                        'remarks'=>$request['cheque_no'] ?? '',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $cash_entry);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);

                    if($request['payment_method'] == 2){
                        if($request['bank_id'] != null)
                        {
                            Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $request['bank_id'],
                                'cheque_no' => $request['cheque_no'],
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['payment_amount'],
                                'cheque_entry_date' => $request['nep_date'],
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                    }
                }elseif($request['payment_method'] == 4){
                    $online_portal_child_id = OnlinePayment::where('id', $request['online_portal'])->first()->child_account_id;
                    $online_portal = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$online_portal_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$request['payment_amount'],
                    ];
                    array_push($jv_extras, $online_portal);

                    $due_amount = $request['grandtotal'] - $request['payment_amount'];

                    $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                    $due_entry = [
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$client_child_id,
                        'remarks'=>'',
                        'debitAmount'=>0,
                        'creditAmount'=>$due_amount,
                    ];
                    array_push($jv_extras, $due_entry);
                }
            }elseif($request['payment_type'] == 'unpaid'){
                $client_child_id = Client::where('id', $request['client_id'])->first()->child_account_id;
                $due_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$client_child_id,
                    'remarks'=>'',
                    'debitAmount'=>0,
                    'creditAmount'=>$request['grandtotal'],
                ];
                array_push($jv_extras, $due_entry);
            }
            // Discount Taken
            $totdiscount = $discountsum;
            if($totdiscount > 0){
                $discount_received_id = ChildAccount::where('slug', 'discount-received')->first()->id;
                $discount_received_entry = [
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$discount_received_id,
                    'remarks'=>'Discount Received',
                    'debitAmount'=>0,
                    'creditAmount'=>$totdiscount,
                ];
                array_push($jv_extras, $discount_received_entry);
            }

            foreach($jv_extras as $key => $jv_extra){
                JournalExtra::create($jv_extra);
                if($request['status'] == 1){
                    $this->openingbalance($jv_extra['child_account_id'], $request['fiscal_year_id'], $jv_extra['debitAmount'], $jv_extra['creditAmount']);
                }
            }


            $journal_voucher->save();
            // Journal Voucher Ends Here

            DB::commit();
            if($saveandcontinue == 1){
                return redirect()->route('billings.creditnotecreate')->with('success', 'Billing Successfully Created.');
            }else{
                return redirect()->route('billings.report', $request['billing_type_id'])->with('success', 'Billing Successfully Created.');
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $saveandcontinue = $request->saveandcontinue ?? 0;
        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $request['eng_date'];
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        if($request['billing_type_id'] == 4)
        {
            //Purchase Return Bill
            $billscount = Billing::where('billing_type_id', 4)->count();

            if($billscount == 0)
            {
                $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = 'PAB-'.$reference_no;
            }
            else
            {
                $lastbill = Billing::where('billing_type_id', 4)->latest()->first();
                $newtransaction_no = $lastbill->transaction_no+1;
                $newreference_no = $lastbill->reference_no;
                $expref = explode('-', $newreference_no);
                $ref = $expref[1]+1;

                $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
                $reference_no = 'PAB-'.$reference_no;
            }
        }
        elseif($request['billing_type_id'] == 3)
        {
            //Purchase Return Bill
            $billscount = Billing::where('billing_type_id', 3)->count();

            if($billscount == 0)
            {
                $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = 'CRB-'.$reference_no;
            }
            else
            {
                $lastbill = Billing::where('billing_type_id', 3)->latest()->first();
                $newtransaction_no = $lastbill->transaction_no+1;
                $newreference_no = $lastbill->reference_no;
                $expref = explode('-', $newreference_no);
                $ref = $expref[1]+1;

                $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
                $reference_no = 'CRB-'.$reference_no;
            }
        }
        elseif($request['billing_type_id'] == 7)
        {
            //Purchase Return Bill
            $billscount = Billing::where('billing_type_id', 7)->count();

            if($billscount == 0)
            {
                $transaction_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad(1, 8, "0", STR_PAD_LEFT);
                $reference_no = 'QB-'.$reference_no;
            }
            else
            {
                $lastbill = Billing::where('billing_type_id', 7)->latest()->first();
                $newtransaction_no = $lastbill->transaction_no+1;
                $newreference_no = $lastbill->reference_no;
                $expref = explode('-', $newreference_no);
                $ref = $expref[1]+1;

                $transaction_no = str_pad($newtransaction_no, 8, "0", STR_PAD_LEFT);
                $reference_no = str_pad($ref, 8, "0", STR_PAD_LEFT);
                $reference_no = 'QB-'.$reference_no;
            }
        }

        $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method' => 'required',
            'bank_id' => '',
            'online_portal' => '',
            'cheque_no' => '',
            'customer_portal_id' => '',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'cheque_no'=>'',
            'quantity'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
            'selected_filter_option'=> '',
            'original_vendor_price',
            'charging_rate',
            'final_vendor_price',
            'carrying_cost',
            'transportation_cost',
            'miscellaneous_percent',
            'other_cost',
            'product_cost',
            'custom_duty',
            'after_custom',
            'product_tax',
            'total_cost',
            'margin_type',
            'margin_value',
            'product_price',
        ]);


        if($request['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }

        $thisday = date('Y-m-d');

        if($request['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $user_id = Auth::user()->id;

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }
        $journals = JournalVouchers::latest()->where('fiscal_year_id', $request->fiscal_year_id)->get();
        if(count($journals) == 0)
        {
            $jvnumber = "1";
        }
        else
        {
            $journal = JournalVouchers::latest()->first();
            $jv = $journal->journal_voucher_no;
            $arr = explode('-', $jv);
            $jvnumber = $arr[1] + 1;
        }
        DB::beginTransaction();
        try{

            $jvno = "JV-".$jvnumber;
            //Purchase
            if($request['billing_type_id'] == 3 || $request['billing_type_id'] == 4){
                //Payment & Receipt
                $billing = Billing::create([
                    'billing_type_id'=>$request['billing_type_id'],
                    'client_id'=>$request['client_id'],
                    'vendor_id'=>$request['vendor_id'],
                    'transaction_no'=>$transaction_no,
                    'reference_no'=>$reference_no,
                    'ledger_no'=>$request['ledger_no'],
                    'remarks'=>$request['remarks'],
                    'eng_date'=>$request['eng_date'],
                    'nep_date'=>$request['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'entry_by'=>$user_id,
                    'status'=>$request['status'],
                    'fiscal_year_id'=>$request['fiscal_year_id'],
                    'alltaxtype'=>$request['alltaxtype'],
                    'alltax'=>$request['alltax'],
                    'taxamount'=>$request['taxamount'],
                    'alldiscounttype'=>$request['alldiscounttype'],
                    'discountamount'=>$request['discountamount'],
                    'alldtamt'=>$request['alldtamt'],
                    'subtotal'=>$request['subtotal'],
                    'shipping'=>$request['shipping'],
                    'grandtotal'=>$request['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$request['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
            }elseif($request['billing_type_id']==7){
                // Quotation
                $billing = Billing::create([
                    'client_id'=>$request['client_id'],
                    'billing_type_id'=>$request['billing_type_id'],
                    'transaction_no'=>$transaction_no,
                    'reference_no'=>$reference_no,
                    'ledger_no'=>$request['ledger_no'],
                    'file_no'=>$request['file_no'],
                    'remarks'=>$request['remarks'],
                    'eng_date'=>$request['eng_date'],
                    'nep_date'=>$request['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'godown'=>$request['godown'],
                    'entry_by'=>$user_id,
                    'status'=>$request['status'],
                    'fiscal_year_id'=>$request['fiscal_year_id'],
                    'alltaxtype'=>$request['alltaxtype'],
                    'alltax'=>$request['alltax'],
                    'taxamount'=>$request['taxamount'],
                    'alldiscounttype'=>$request['alldiscounttype'],
                    'discountamount'=>$request['discountamount'],
                    'alldtamt'=>$request['alldtamt'],
                    'subtotal'=>$request['subtotal'],
                    'shipping'=>$request['shipping'],
                    'grandtotal'=>$request['grandtotal'],
                    'approved_by'=>$approval_by,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);

                $quantity = $request['quantity'];
                $particulars = $request['particulars'];
                $count = count($particulars);
                $rate = $request['rate'];
                $discountamt = $request['discountamt'];
                $discounttype = $request['discounttype'];
                $dtamt = $request['dtamt'];
                $taxamt = $request['taxamt'];
                $tax = $request['tax'];
                $itemtax = $request['itemtax'];
                $taxtype = $request['taxtype'];
                $total = $request['total'];
                for($x=0; $x<$count; $x++)
                {
                    $billingextras = BillingExtra::create([
                        'billing_id'=>$billing['id'],
                        'particulars'=>$particulars[$x],
                        'quantity'=>$quantity[$x],
                        'rate'=>$rate[$x],
                        'unit'=>null,
                        'discountamt'=>$discountamt[$x],
                        'discounttype'=>$discounttype[$x],
                        'dtamt'=>$dtamt[$x],
                        'taxamt'=>$taxamt[$x],
                        'tax'=>$tax[$x],
                        'itemtax'=>$itemtax[$x],
                        'taxtype'=>$taxtype[$x],
                        'total'=>$total[$x],
                    ]);
                }
            }

            //billingcredits after payment info

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            // Product Data
            $original_vendor_price = $request['original_vendor_price'];
            $charging_rate = $request['charging_rate'];
            $final_vendor_price = $request['final_vendor_price'];
            $carrying_cost = $request['carrying_cost'];
            $transportation_cost = $request['transportation_cost'];
            $miscellaneous_percent = $request['miscellaneous_percent'];
            $other_cost = $request['other_cost'];
            $product_cost = $request['product_cost'];
            $custom_duty = $request['custom_duty'];
            $after_custom = $request['after_custom'];
            $vat_select = $request['product_tax'];
            $total_cost = $request['total_cost'];
            $margin_type = $request['margin_type'];
            $margin_value = $request['margin_value'];
            $product_price = $request['product_price'];

            if($quantity == null && $unit == null && $rate == null)
            {
                for($x=0; $x<$count; $x++)
                {
                    $billingextras = BillingExtra::create([
                        'billing_id'=>$billing['id'],
                        'particulars'=>$particulars[$x],
                        'cheque_no'=>$particular_cheque_no[$x],
                        'narration'=>$narration[$x],
                        'total'=>$total[$x],
                    ]);
                    $billingextras->save();
                }
            }
            DB::commit();
            if($saveandcontinue == 1){
                if($request['billing_type_id'] == 3){
                    return redirect()->route('billings.receiptcreate')->with('success', 'Billing Successfully Created.');
                }
                elseif($request['billing_type_id'] == 4){
                    return redirect()->route('billings.paymentcreate')->with('success', 'Billing Successfully Created.');
                }
                elseif($request['billing_type_id'] == 7){
                    return redirect()->route('billings.quotationcreate')->with('success', 'Billing Successfully Created.');
                }
            }else{
                return redirect()->route('billings.report', $request['billing_type_id'])->with('success', 'Billing Successfully Created.');
            }

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            if($request->get('bill_type') == 'sales_bills'){

                return redirect()->route('service_sales.show',$id);
            }else{
                $billing = Billing::with('suppliers')->with('payment_infos')->findorfail($id);
            }

            $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
            $setting = Setting::first();
            $quotationsetting = Quotationsetting::first();
            return view('backend.billings.show', compact('billing', 'billing_type', 'setting', 'quotationsetting'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function showSalesInvoice($id, Request $request)
    {
        if($request->user()->can('manage-sales-invoices'))
        {
            $billing = Billing::with('suppliers')->with('payment_infos')->findorfail($id);
            $setting = Setting::first();
            $quotationsetting = Quotationsetting::first();
            return view('backend.billings.showSalesInvoice', compact('billing', 'setting', 'quotationsetting'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function printpreview($id, Request $request)
    {
        $billing = Billing::with('suppliers')->findorfail($id);
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $setting = Setting::first();
        $user = Auth::user()->id;
        $quotationsetting = Quotationsetting::first();
        $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
        // $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

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
        return view('backend.billings.printpreview', compact('path_img','billing', 'billing_type', 'setting', 'user', 'quotationsetting', 'currentcomp'));
    }

    public function printpospreview(Request $request)
    {
        // dd($request);
        // $data = $this->validate($request, [
        //     'customer_id'=>'required|exists:clients,id',
        //     'payment_mode'=>'required',
        //     'payment_amount' => 'sometimes',
        //     'godown'=>'required|exists:godowns,id',
        //     'alltaxtype'=>'',
        //     'alltax'=>'exists:taxes,id',
        //     'alldiscounttype'=>'',
        //     'alldiscountvalue'=>'',
        //     'bulk_discount'=>'numeric',
        //     'products' => ['required','array'],
        //     'products.*.product_id' => ['required','exists:products,id'],
        //     'products.*.total_quantity' => ['required','integer'],
        //     'products.*.product_price' => ['required','numeric'],
        //     'products.*.tax_rate_id' => ['exists:taxes,id'],
        //     'products.*.tax_type' => ['sometimes'],
        //     'products.*.discount_type' => ['sometimes'],
        //     'products.*.value_discount' => ['sometimes'],
        //     'remarks'=>'nullable',
        // ]);
        $date = date("Y-m-d");
        $nepalidate = datenep($date);

        $data = [
            'customer_id'=>1,
            'payment_mode'=>1,
            'godown'=>1,
            'alltaxtype'=>'exclusive',
            'alltax'=>10,
            'alldiscounttype'=>'percent',
            'alldiscountvalue'=>10,
            'products' => [
                    [
                    'product_id'=> 1,
                    'total_quantity'=> 2,
                    'product_price'=> 250,
                    'tax_rate_id'=>2,
                    'tax_type'=>'exclusive',
                    'discount_type'=>'percent',
                    'value_discount'=>'5',
                ],
                [
                    'product_id'=> 2,
                    'total_quantity'=> 3,
                    'product_price'=> 250,
                    'tax_rate_id'=>null,
                    'tax_type'=>null,
                    'discount_type'=>null,
                    'value_discount'=>null,
                ]
            ]
        ];

        // $products = $request['products'];
        // $tax = Tax::find($request['alltax']);

        $products = $data['products'];
        $tax = Tax::find($data['alltax']);
        $customer = Client::where('id', $data['customer_id'])->first()->name;

        $productSaleService = (new ProductSaleService($products))
        ->when(
            Arr::get($request,'alltaxtype') && Arr::get($request, 'alltax'),
            function($callback) use($request, $tax) {
                return $callback->setTaxRate($request['alltaxtype'], $tax->percent);
            }
        )
        ->when(
            Arr::get($request,'alldiscounttype') && Arr::get($request,'alldiscount'),
            function($callback) use($request) {
                return $callback->setDiscountRate($request['alldiscounttype'], $request['alldiscount']);
            }
        )
        ->calculate();
        $subtotal = $productSaleService->getSubTotal();
        $bulkDiscounttype = Arr::get($data, 'alldiscounttype');
        $bulkDiscount = $productSaleService->getBulkDiscount();
        $bulkTaxType = Arr::get($data, 'alltaxtype');
        $allTaxAmt = $productSaleService->getBulkTax();
        $totalGrossTotal = $productSaleService->getTotalCost();
        $quantitys = [];
        $billingextras = [];
        foreach($productSaleService->getContents() as $content){
            $billingextras[] = [
                'particulars'=>Product::where('id',Arr::get($content, 'product_id'))->first()->product_name,
                'quantity'=>Arr::get($content, 'quantity'),
                'rate' => Arr::get($content, 'unit_price'),
                'unit'=>null,
                'discountamt'=>Arr::get($content, 'discount_value'),
                'discounttype'=>Arr::get($content, 'discount_type'),
                'dtamt'=>Arr::get($content, 'total_discount')/Arr::get($content, 'quantity'),
                'taxamt'=>Arr::get($content, 'total_tax')/Arr::get($content, 'quantity'),
                'tax'=>Arr::get($content, 'tax_rate_id') == null ? null : Tax::findorfail(Arr::get($content, 'tax_rate_id'))->percent,
                'itemtax'=>Arr::get($content, 'total_tax'),
                'taxtype'=>Arr::get($content, 'tax_type'),
                'total'=>Arr::get($content, 'total_cost'),
            ];
            array_push($quantitys, (float)Arr::get($content, 'quantity'));
        }
        $datas = [
            'eng_date' => $date,
            'nep_date' => $nepalidate,
            'customer' => $customer,
            'billingextras' => $billingextras,
            'subtotal' => $subtotal,
            'bulkDiscount' => $bulkDiscount,
            'bulkTaxType'=>$bulkTaxType,
            'allTaxAmt'=>$allTaxAmt,
            'totalQty'=>array_sum($quantitys),
            'totalGrossTotal'=>$totalGrossTotal,
        ];
        // dd($datas);

        $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
        return view('backend.pos.printbill', compact('currentcomp', 'datas'));
    }

    public function billingprint(Request $request, $bill_id)
    {
        $billing = Billing::findorfail($bill_id);
        $billing->update([
            'printcount' => $request['nprintcount'],
            'is_printed' => 1,
        ]);

        $billprint = Billprint::create([
            'billing_id' => $bill_id,
            'printed_by' => $request['user_id'],
            'print_time' => date('Y-m-d H:i:s'),
        ]);

        $billing->save();
        $billprint->save();
        return response()->json("Successfully changes", 201);
    }

    public function generateBillingPDF($id, Request $request)
    {

        $billing = Billing::with('suppliers')->findorfail($id);
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $setting = Setting::first();
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
        $user = Auth::user();
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.billings.downloadbilling', compact('path_img_address','path_img_web','path_img_phn','user','billing', 'billing_type', 'setting', 'path_img', 'quotationsetting', 'currentcomp'));
        $ref_no = $billing->reference_no;
        $predlcount = $billing->downloadcount;
        $newdownloadcount = $predlcount +1;
        $billing->update([
            'downloadcount' => $newdownloadcount,
        ]);
        $billing->save();

        if ($billing->is_pos_data == 1 && $billing->outlet_id != null) {
            return $pdf->download('POS Bill.pdf');
        }
        return $pdf->download($ref_no.'.pdf');
    }

    public function generateSalesInvoicePDF($id, Request $request)
    {

        $billing = Billing::with('suppliers')->findorfail($id);
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $setting = Setting::first();
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.billings.salesInvoicePdf', compact('billing', 'billing_type', 'setting', 'path_img', 'quotationsetting', 'currentcomp'));
        $predlcount = $billing->downloadcount;
        $newdownloadcount = $predlcount +1;
        $billing->update([
            'downloadcount' => $newdownloadcount,
        ]);
        $billing->save();
        return $pdf->download('Sales Invoice#'.$billing->reference_no.'.pdf');
    }

    public function generatechallanBillingPDF($id, Request $request)
    {

        $billing = Billing::with('suppliers')->findorfail($id);
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $setting = Setting::first();
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.billings.downloadchallan', compact('billing', 'billing_type', 'setting', 'path_img', 'quotationsetting', 'currentcomp'));
            $ref_no = $billing->reference_no;
            return $pdf->download($ref_no.'challan.pdf');
    }

    public function generateBillingletterheadPDF($id, Request $request)
    {
        $billing = Billing::with('suppliers')->findorfail($id);
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $setting = Setting::first();
        $quotationsetting = Quotationsetting::first();
        $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.billings.letterheaddownloadbilling', compact('billing', 'billing_type', 'setting', 'quotationsetting', 'currentcomp'));
        $ref_no = $billing->reference_no;
        $predlcount = $billing->downloadcount;
        $newdownloadcount = $predlcount +1;
        $billing->update([
            'downloadcount' => $newdownloadcount,
        ]);
        $billing->save();
        return $pdf->download($ref_no.'.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {

        if($request->user()->can('manage-quotations') || $request->user()->can('manage-purchases') || $request->user()->can('manage-sales') || $request->user()->can('manage-credit-note') || $request->user()->can('manage-debit-note'))
        {
            $billing = Billing::with('billingextras')->where('id', $id)->first();
            if($billing->billing_type_id !=7){
                if($billing->status == 1)
                {
                    return redirect()->route('billings.report', $billing->billing_type_id)->with('error', 'Approved bills cant be edited.');
                }
            }
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::all();
            $vendors = Vendor::all();
            $clients = Client::with('dealertype')->get();
            $provinces = Province::all();
            $billing_type_id = $billing->billing_type_id;
            $categories = Category::with('products')->get();
            $taxes = Tax::all();
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series', 'godownproduct.serialnumbers','godownproduct.allserialnumbersforedit')->latest()->get();
            $selgodown = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series', 'godownproduct.serialnumbers','godownproduct.allserialnumbersforedit')->where('id', $billing->godown)->first();
            // dd($selgodown);
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            $quotationsetting = Quotationsetting::first();
            $online_portals = OnlinePayment::latest()->get();
            $banks = Bank::latest()->get();
            $currentcomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first();

            if($billing_type_id==1)
            {
                if($billing->is_cancelled == 0){
                    $billingexts = $billing->billingextras;
                    $productids = [];
                    foreach($billingexts as $extra){
                        array_push($productids, $extra->particulars);
                    }
                    // $selectedproducts = GodownProduct::where('godown_id', $billing->godown)->whereIn('product_id', $productids)->with('serialforedits')->get();
                    return view('backend.billings.salesedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'clients', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'selgodown', 'ird_sync'));
                }else{
                    return redirect()->back()->with('error', 'Cancelled Sales cannot be edited');
                }
            }
            elseif($billing_type_id == 2)
            {
                $godowns = Godown::all();
                return view('backend.billings.purchaseedit', compact('currentcomp','godowns','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'ird_sync'));
            }
            elseif($billing_type_id == 3)
            {
                return view('backend.billings.receiptedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'provinces', 'billing_type_id', 'categories', 'clients', 'taxes', 'ird_sync'));
            }
            elseif($billing_type_id == 4)
            {
                return view('backend.billings.paymentedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'provinces', 'clients', 'billing_type_id', 'categories', 'taxes', 'ird_sync'));
            }
            elseif($billing_type_id == 5)
            {
                return view('backend.billings.debitnoteedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'ird_sync'));
            }
            elseif($billing_type_id == 6)
            {
                return view('backend.billings.creditnoteedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors', 'clients', 'provinces', 'billing_type_id', 'categories', 'taxes', 'ird_sync', 'godowns', 'selgodown'));
            }
            elseif($billing_type_id == 7)
            {
                return view('backend.billings.quotationedit', compact('currentcomp','billing', 'fiscal_years', 'current_fiscal_year', 'payment_methods', 'online_portals', 'banks', 'vendors','clients', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'selgodown', 'quotationsetting'));
            }
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $billing = Billing::findorFail($id);
        $current_fiscal_year = FiscalYear::where('id', $request['fiscal_year_id'])->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $billing->eng_date;
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];
        $data = $this->validate($request, [
            'billing_type_id'=>'required',
            'vendor_id'=>'',
            'client_id'=>'',
            'ledger_no'=>'',
            'file_no'=>'',
            'remarks'=>'required',
            'status'=>'required',
            'eng_date'=>'required',
            'nep_date'=>'required',
            // 'payment_method'=>'required',
            'godown'=>'',
            'fiscal_year_id'=>'',
            'subtotal'=>'',
            'alltaxtype'=>'',
            'alltax'=>'',
            'taxamount'=>'',
            'alldiscounttype'=>'',
            'discountamount'=>'',
            'alldtamt'=>'',
            'shipping'=>'required',
            'grandtotal'=>'required',
            'reference_invoice_no'=>'',
            'particulars'=>'required',
            'narration'=>'',
            'quantity'=>'',
            'previous_No'=>'',
            'serial_No'=>'',
            'rate'=>'',
            'unit'=>'',
            'discountamt'=>'',
            'discounttype'=>'',
            'dtamt'=>'',
            'taxamt'=>'',
            'itemtax'=>'',
            'taxtype'=>'',
            'tax'=>'',
            'total'=>'',
            'vat_refundable'=>'',
            'sync_ird'=>'',
        ]);

        $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;

        if($ird_sync == 1)
        {
            $ird_sync = $request['sync_ird'];
        }
        else
        {
            $ird_sync = '0';
        }

        if($data['status'] == 1)
        {
            $approval_by = Auth::user()->id;
        }
        else
        {
            $approval_by = null;
        }

        $user_id = Auth::user()->id;

        if(gettype($request['godown']) == 'undefined')
        {
            $godown = null;
        }
        else
        {
            $godown = $request['godown'];
        }
        $thisday = date('Y-m-d');
        if($data['eng_date'] == $thisday)
        {
            $is_realtime = 1;
        }
        else
        {
            $is_realtime = 0;
        }

        $bank_id = null;
        $cheque_no = null;
        $online_portal_id = null;
        $customer_portal_id = null;

        if ($request['payment_method'] == 2)
        {
            $bank_id = $request['bank_id'];
            $cheque_no = $request['cheque_no'];
        }
        else if ($request['payment_method'] == 3)
        {
            $bank_id = $request['bank_id'];
        }
        else if ($request['payment_method'] == 4)
        {
            $online_portal_id = $request['online_portal'];
            $customer_portal_id = $request['customer_portal_id'];
        }

        $user_id = Auth::user()->id;

        DB::beginTransaction();
        try{
            if($request['billing_type_id'] == 2){
                //Purchase
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $purchasetax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $withoutpurchase_tax = $purchasetax->purchase_tax - (float)$billing->taxamount;
                    $withouttotal_tax = $purchasetax->total_tax + (float)$billing->taxamount;
                }
                $billing->update([
                    'vendor_id'=>$data['vendor_id'],
                    'billing_type_id'=>$data['billing_type_id'],
                    'ledger_no'=>$data['ledger_no'],
                    'file_no'=>$data['file_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'godown'=>$godown,
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$data['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);


                //ashish stock change
                $godown_qty = $request->godown_qty;

                if(count(array_unique($request->godown_id)) == 1){
                    $prostockarray = array();

                    foreach($request->quantity as $key=>$qty){
                        $prostockarray[$request->godown_id[0]][] = $qty;
                    }
                    $godown_qty = $prostockarray;
                }
                if(!empty($request->godown_id) && !empty($godown_qty) && !empty($request->particulars)){

                    ProductStock::where('billing_id',$id)->delete();

                    $this->saveGodownProductStock($request->godown_id,$godown_qty,$request->particulars,$billing->id,$update=true);
                }

                if($request->serial_product){
                    GodownSerialNumber::where('purchase_billing_id',$id)->delete();
                    $this->addserialNumberProduct($request->serial_product,$billing->id);
                }

                //end ashish stock change

                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $purchase_tax = $withoutpurchase_tax + (float)$request['taxamount'];
                    $total_tax = $withouttotal_tax - (float)$request['taxamount'];
                    $purchasetax->update([
                        'purchase_tax'=>$purchase_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasetax->save();
                }
            }elseif($request['billing_type_id'] == 1)
            {

                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $salestax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $withoutsales_tax = $salestax->sales_tax - (float)$request['taxamount'];
                    $withouttotal_tax = $salestax->total_tax - (float)$request['taxamount'];
                }
                $billing->update([
                    'billing_type_id'=>$data['billing_type_id'],
                    'client_id'=>$data['client_id'],
                    'ledger_no'=>$data['ledger_no'],
                    'file_no'=>$data['file_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'godown'=>$data['godown'],
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$data['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $sales_tax = $withoutsales_tax + (float)$request['taxamount'];
                    $total_tax = $withouttotal_tax + (float)$request['taxamount'];
                    $salestax->update([
                        'sales_tax'=>$sales_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salestax->save();
                }
            }elseif($request['billing_type_id'] == 3 || $request['billing_type_id'] == 4){
                //Payment & Receipt
                $billing->update([
                    'billing_type_id'=>$data['billing_type_id'],
                    'client_id'=>$request['client_id'],
                    'vendor_id'=>$request['vendor_id'],
                    'ledger_no'=>$data['ledger_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$data['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'vat_refundable'=>$data['vat_refundable'],
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
            }elseif($data['billing_type_id'] == 5){
                //Purchase Return / Debit Note
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $purchasereturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $withoutpurchasereturn_tax = $purchasereturntax->purchasereturn_tax - (float)$request['taxamount'];
                    $withouttotal_tax = $purchasereturntax->total_tax - (float)$request['taxamount'];
                }
                $billing->update([
                    'vendor_id'=>$data['vendor_id'],
                    'billing_type_id'=>$data['billing_type_id'],
                    'reference_invoice_no'=> $data['reference_invoice_no'],
                    'ledger_no'=>$data['ledger_no'],
                    'file_no'=>$data['file_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$data['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $purchasereturn_tax = $withoutpurchasereturn_tax + (float)$request['taxamount'];
                    $total_tax = $withouttotal_tax + (float)$request['taxamount'];
                    $purchasereturntax->update([
                        'purchasereturn_tax'=>$purchasereturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $purchasereturntax->save();
                }
            }elseif($data['billing_type_id'] == 6){
                // Sales Return / Credit Note
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $salesreturntax = TaxInfo::where('fiscal_year', $current_fiscal_year->fiscal_year)->where('nep_month', $nepmonth)->first();
                    $withoutsalesreturn_tax = $salesreturntax->salesreturn_tax - (float)$billing->taxamount;
                    $withouttotal_tax = $salesreturntax->total_tax + (float)$billing->taxamount;
                }
                $billing->update([
                    'client_id'=>$data['client_id'],
                    'billing_type_id'=>$data['billing_type_id'],
                    'reference_invoice_no'=> $data['reference_invoice_no'],
                    'ledger_no'=>$data['ledger_no'],
                    'file_no'=>$data['file_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'godown'=>$data['godown'],
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'vat_refundable'=>$data['vat_refundable'],
                    'sync_ird'=>$ird_sync,
                    'is_realtime'=>$is_realtime,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
                if($request['sync_ird'] == 1 && $request['status'] == 1){
                    $salesreturn_tax = $withoutsalesreturn_tax + (float)$request['taxamount'];
                    $total_tax = $withouttotal_tax - (float)$request['taxamount'];
                    $salesreturntax->update([
                        'salesreturn_tax'=>$salesreturn_tax,
                        'total_tax'=>$total_tax,
                    ]);
                    $salesreturntax->save();
                }
            }elseif($data['billing_type_id']==7){
                // Quotation
                $billing->update([
                    'client_id'=>$data['client_id'],
                    'billing_type_id'=>$data['billing_type_id'],
                    'ledger_no'=>$data['ledger_no'],
                    'file_no'=>$data['file_no'],
                    'remarks'=>$data['remarks'],
                    'eng_date'=>$data['eng_date'],
                    'nep_date'=>$data['nep_date'],
                    'payment_method' => $request['payment_method'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'godown'=>$data['godown'],
                    'entry_by'=>$user_id,
                    'status'=>$data['status'],
                    'fiscal_year_id'=>$data['fiscal_year_id'],
                    'alltaxtype'=>$data['alltaxtype'],
                    'alltax'=>$data['alltax'],
                    'taxamount'=>$data['taxamount'],
                    'alldiscounttype'=>$data['alldiscounttype'],
                    'discountamount'=>$data['discountamount'],
                    'alldtamt'=>$data['alldtamt'],
                    'subtotal'=>$data['subtotal'],
                    'shipping'=>$data['shipping'],
                    'grandtotal'=>$data['grandtotal'],
                    'approved_by'=>$approval_by,
                    'declaration_form_no'=>$request->declaration_form_no,
                ]);
            }

            $billing_extras = BillingExtra::where('billing_id', $id)->get();

            foreach($billing_extras as $billing_extra)
            {
                if($request['billing_type_id'] == 3 || $request['billing_type_id'] == 4 || $request['billing_type_id'] == 2 || $request['billing_type_id'] == 5 || $request['billing_type_id'] == 7)
                {
                    $billing_extra->delete();
                }
                else
                {
                    // if($request['status'] == 1)
                    // {

                    //     $product = Product::findorfail($billing_extra->particulars);
                    //     $stock = $product->total_stock;

                    //     if($request['billing_type_id'] == 1)
                    //     {
                    //         $remstock = $stock + $billing_extra->quantity;
                    //         $salesrecord = SalesRecord::where('billing_id', $id)->first();
                    //         $salesrecord->delete();
                    //     }
                    //     elseif($request['billing_type_id'] == 6)
                    //     {
                    //         $remstock = $stock - $billing_extra->quantity;
                    //         $salesreturnrecord = SalesReturnRecord::where('billing_id', $id)->first();
                    //         $salesreturnrecord->delete();
                    //     }
                    //     $product->update([
                    //         'total_stock' => $remstock
                    //     ]);

                    //     $godownproduct = GodownProduct::where('product_id', $billing_extra->particulars)->where('godown_id', $data['godown'])->first();
                    //     $gostock = $godownproduct->stock;
                    //     $remgostock = $gostock + $billing_extra->quantity;
                    //     $godownproduct->update([
                    //         'stock' => $remgostock
                    //     ]);

                    // }
                    $billing_extra->delete();
                }
            }




            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $narration = $request['narration'];
            $particular_cheque_no = $request['particular_cheque_no'];
            $discountamt = $request['discountamt'];
            $discounttype = $request['discounttype'];
            $dtamt = $request['dtamt'];
            $taxamt = $request['taxamt'];
            $tax = $request['tax'];
            $itemtax = $request['itemtax'];
            $taxtype = $request['taxtype'];
            $total = $request['total'];
            $count = count($particulars);

            if($narration == null && $cheque_no == null)
            {
                for($x=0; $x<$count; $x++)
                {
                    if($data['billing_type_id'] == 1)
                    {
                        $billingextras = BillingExtra::create([
                            'billing_id'=>$billing['id'],
                            'particulars'=>$particulars[$x],
                            'quantity'=>$quantity[$x],
                            'rate'=>$rate[$x],
                            'unit'=>null,
                            'discountamt'=>$discountamt[$x],
                            'discounttype'=>$discounttype[$x],
                            'dtamt'=>$dtamt[$x],
                            'taxamt'=>$taxamt[$x],
                            'tax'=>$tax[$x],
                            'itemtax'=>$itemtax[$x],
                            'taxtype'=>$taxtype[$x],
                            'total'=>$total[$x],
                        ]);
                        $salesrecord = SalesRecord::create([
                            'billing_id'=>$billing['id'],
                            'product_id'=>$particulars[$x],
                            'godown_id'=>$request['godown'],
                            'stock_sold'=>$quantity[$x],
                            'date_sold'=>$request['eng_date'],
                        ]);
                        $salesrecord->save();

                        if($request['status'] == 1)
                        {
                            $product = Product::findorfail($particulars[$x]);
                            $stock = $product->total_stock;
                            $remstock = $stock - $quantity[$x];
                            $product->update([
                                'total_stock' => $remstock
                            ]);

                            $godownproduct = GodownProduct::where('product_id', $particulars[$x])->where('godown_id', $data['godown'])->first();
                            $gostock = $godownproduct->stock;
                            $remgostock = $gostock - $quantity[$x];
                            $godownproduct->update([
                                'stock' => $remgostock
                            ]);
                        }

                        //Godown Serial Number Update
                        $previous_serial_nos = GodownSerialNumber::where('billing_id',$id)->get();
                        foreach($previous_serial_nos as $previous_serial)
                        {
                            $previous_serial->update([
                                'is_sold'=>0,
                                'billing_id'=>null,
                                'sales_approved'=>0,
                            ]);
                        }

                        $allserialnos = [];
                        $serialno = array_merge($allserialnos,request('serial_No') ?? []);
                        $preserialno = array_merge($serialno, request('previous_No') ?? []);
                        if(count($preserialno) > 0)
                        {
                            if($request['status'] == 1)
                            {
                                $serial_numbers_products = GodownSerialNumber::whereIn('serial_number', $preserialno)->get();
                                foreach($serial_numbers_products as $serialproducts)
                                {
                                    $serialproducts->update([
                                        'is_sold'=>1,
                                        'billing_id'=>$billing['id'],
                                        'sales_approved'=>1,
                                    ]);
                                }
                            }
                            else
                            {
                                $serial_numbers_products = GodownSerialNumber::whereIn('serial_number', $preserialno)->get();
                                foreach($serial_numbers_products as $serialproducts)
                                {
                                    $serialproducts->update([
                                        'is_sold'=>1,
                                        'billing_id'=>$billing['id'],
                                        'sales_approved'=>0,
                                    ]);
                                }
                            }
                        }
                    }
                    elseif($data['billing_type_id'] == 6)
                    {
                        $billingextras = BillingExtra::create([
                            'billing_id'=>$billing['id'],
                            'particulars'=>$particulars[$x],
                            'quantity'=>$quantity[$x],
                            'rate'=>$rate[$x],
                            'unit'=>$unit[$x],
                            'discountamt'=>$discountamt[$x],
                            'discounttype'=>$discounttype[$x],
                            'dtamt'=>$dtamt[$x],
                            'taxamt'=>$taxamt[$x],
                            'tax'=>$tax[$x],
                            'itemtax'=>$itemtax[$x],
                            'taxtype'=>$taxtype[$x],
                            'total'=>$total[$x],
                        ]);
                        $salesreturnrecord = SalesReturnRecord::create([
                            'billing_id'=>$billing['id'],
                            'product_id'=>$particulars[$x],
                            'godown_id'=>$request['godown'],
                            'stock_return'=>$quantity[$x],
                            'date_return'=>$request['eng_date'],
                        ]);
                        $salesreturnrecord->save();
                            if($request['status'] == 1){
                            $product = Product::findorfail($particulars[$x]);
                            $stock = $product->total_stock;
                            $remstock = $stock + $quantity[$x];
                            $product->update([
                                'total_stock' => $remstock
                            ]);
                            $product->save();
                            $godownproduct = GodownProduct::where('product_id', $particulars[$x])->where('godown_id', $data['godown'])->first();
                            $gostock = $godownproduct->stock;
                            $remgostock = $gostock + $quantity[$x];
                            $godownproduct->update([
                                'stock' => $remgostock
                            ]);
                            $godownproduct->save();
                        }
                    }else if($data['billing_type_id'] == 7) {
                        $billingextras = BillingExtra::create([
                            'billing_id'=>$billing['id'],
                            'particulars'=>$particulars[$x],
                            'quantity'=>$quantity[$x],
                            'rate'=>$rate[$x],
                            'unit'=>$unit[$x],
                            'discountamt'=>$discountamt[$x],
                            'discounttype'=>$discounttype[$x],
                            'dtamt'=>$dtamt[$x],
                            'taxamt'=>$taxamt[$x],
                            'tax'=>$tax[$x],
                            'itemtax'=>$itemtax[$x],
                            'taxtype'=>$taxtype[$x],
                            'total'=>$total[$x],
                        ]);
                    }
                    else
                    {
                        $billingextras = BillingExtra::create([
                            'billing_id'=>$billing['id'],
                            'particulars'=>$particulars[$x],
                            'quantity'=>$quantity[$x],
                            'rate'=>$rate[$x],
                            'unit'=>$unit[$x],
                            'total'=>$total[$x],
                        ]);
                    }
                    $billingextras->save();
                }
            }
            elseif($quantity == null && $unit == null && $rate == null)
            {
                for($x=0; $x<$count; $x++)
                {
                    $billingextras = BillingExtra::create([
                        'billing_id'=>$billing['id'],
                        'particulars'=>$particulars[$x],
                        'cheque_no'=>$particular_cheque_no[$x],
                        'narration'=>$narration[$x],
                        'total'=>$total[$x],
                    ]);
                    $billingextras->save();
                }
            }

            $payment_info = PaymentInfo::where('billing_id', $billing->id)->first();
            if($request['billing_type_id'] == 2 || $request['billing_type_id'] == 5 || $request['billing_type_id'] == 6)
            {
                $payment_info->update([
                    'billing_id' => $billing['id'],
                    'payment_type' => $request['payment_type'],
                    'payment_amount' => $request['payment_amount'],
                    'payment_date' => $request['eng_date'],
                    'total_paid_amount' => $request['payment_amount'],
                    'paid_to' => Auth::user()->id
                ]);

                //as.k
                $billing->due_date_eng = $request->due_date_eng;
                $billing->due_date_nep = $request->due_date_nep;
                $onupdate = true;
                //as.k

                $this->billingCredit($billing,$onupdate);
            }
            else if($request['billing_type_id'] == 1)
            {
                if( $request['payment_type'] == "partially_paid" || $request['payment_type'] == "unpaid")
                {

                    // $credit = Credit::where('customer_id', $request['client_id'])->where('converted', 0)->first();
                    $credit = Credit::where('invoice_id', $id)->where('converted', 0)->first();
                    if(!$credit)
                    {
                        $credit = Credit::where('customer_id', $request['client_id'])->where('converted', 0)->first();
                    }

                    $credit_days_in_seconds = $credit->allocated_days * 86400;

                    $date_in_string = strtotime($request['eng_date']);
                    $bill_date_in_nepali = datenep($request['eng_date']);

                    $due_date_in_string = $date_in_string + $credit_days_in_seconds;
                    $due_date_in_eng = date("Y-m-d", $due_date_in_string);
                    $due_date = datenep($due_date_in_eng);


                    $credit_bills_count = Credit::where('customer_id', $request['client_id'])->where('converted', 0)->get()->count();

                    $new_credited_bills = $credit->credited_bills + 1;
                    $new_credited_amount = $request['grandtotal'] - $request['payment_amount'];

                    $payment_info->update([
                        'billing_id' => $billing['id'],
                        'payment_type' => $request['payment_type'],
                        'payment_amount' => $request['payment_amount'],
                        'payment_date' => $request['eng_date'],
                        'due_date' => $due_date,
                        'total_paid_amount' => $request['payment_amount'],
                        'paid_to' => Auth::user()->id,
                        'is_sales_invoice' => 1
                    ]);
                    //as.k
                    $billing->due_date_eng = $request->due_date_eng;
                    $billing->due_date_nep = $request->due_date_nep;
                    $onupdate = true;

                    $this->billingCredit($billing,$onupdate);
                    //as.k

                    if($credit_bills_count == 1 && $credit->credited_bills == null)
                    {
                        $credit->update([
                            'invoice_id' => $billing['id'],
                            'bill_eng_date' => $request['eng_date'],
                            'bill_nep_date' => $bill_date_in_nepali,
                            'bill_expire_eng_date' => $due_date_in_eng,
                            'bill_expire_nep_date' => $due_date,
                            'credited_bills' => $new_credited_bills,
                            'credited_amount' => $new_credited_amount,
                        ]);
                    }
                    else
                    {
                        $new_credit = Credit::create([
                            'customer_id' => $credit->customer_id,
                            'allocated_days' => $credit->allocated_days,
                            'allocated_bills' => $credit->allocated_bills,
                            'allocated_amount' => $credit->allocated_amount,

                            'invoice_id' => $billing['id'],
                            'bill_eng_date' => $request['eng_date'],
                            'bill_nep_date' => $bill_date_in_nepali,
                            'bill_expire_eng_date' => $due_date_in_eng,
                            'bill_expire_nep_date' => $due_date,
                            'credited_bills' => $new_credited_bills,
                            'credited_amount' => $new_credited_amount,
                        ]);

                        $new_credit->save();
                    }

                    return redirect()->route('salesinvoice', $request['billing_type_id'])->with('success', 'Sales Invoice successfully updated.');
                }
                else if ($request['payment_type'] == "paid")
                {
                    $payment_info->update([
                        'billing_id' => $billing['id'],
                        'payment_type' => $request['payment_type'],
                        'payment_amount' => $request['payment_amount'],
                        'payment_date' => $request['eng_date'],
                        'due_date' => null,
                        'total_paid_amount' => $request['payment_amount'],
                        'paid_to' => Auth::user()->id,
                        'is_sales_invoice' => null
                    ]);
                }
            }



            if($data['billing_type_id'] == 2 && $request->status == 1){

                $prostockWithbilling = ProductStock::where('billing_id',$id)->get();
                foreach($prostockWithbilling as $billing){
                    GodownProduct::where('product_id',$billing->product_id)->where('godown_id',$billing->godown_id)->increment('stock',$billing->added_stock);
                }
                foreach($request->particulars as $pkey=>$particular){
                    $prostock =  ProductStock::where('billing_id',$id)->where('product_id',$particular)->sum('added_stock');
                    Product::where('id',$particular)->increment('total_stock',$prostock);
                }
            }
            DB::commit();
            return redirect()->route('billings.report', $data['billing_type_id'])->with('success', 'Billing Successfully Updated');

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }

    public function salesRegister(Request $request)
    {
        if($request->user()->can('manage-sales-register'))
        {
            $billing_type_id = 1;
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $salesbillings = Billing::with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();

            $servicebillings = SalesBills::with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $billings = collect($salesbillings)->merge($servicebillings);
            if($request->ajax()){
                return Datatables::of($billings)
                        ->addIndexColumn()
                        ->addColumn('bill_date',function($row){
                            return $row->nep_date .'(in B.S)<br>'.$row->eng_date.'(in A.D)';
                        })
                        ->addColumn('action',function($row){
                            $showurl = route('billings.show',['billing'=>$row->id,'bill_type'=>$row->getTable()]);
                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";
                            return $btn;
                        })
                        ->rawColumns(['bill_date','action'])
                        ->make(true);
            }
            // dd($billings);
            return view('backend.billings.salesregister', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function salesReturnRegister(Request $request)
    {
        if($request->user()->can('manage-sales-return-register'))
        {
            $billing_type_id = 6;
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $salebillings = Billing::latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $servicebillings = SalesBills::latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $billings = collect($salebillings)->merge($servicebillings);
            if($request->ajax()){
                return Datatables::of($billings)
                        ->addIndexColumn()
                        ->addColumn('bill_date',function($row){
                            return $row->nep_date .'(in B.S)<br>'.$row->eng_date.'(in A.D)';
                        })
                        ->addColumn('action',function($row){
                            $showurl = route('billings.show',['billing'=>$row->id,'bill_type'=>$row->getTable()]);
                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";
                            return $btn;
                        })
                        ->rawColumns(['bill_date','action'])
                        ->make(true);
            }

            // dd($billings);
            return view('backend.billings.salesReturnRegister', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function salesRegistersearch(Request $request)
    {
        if($request->user()->can('manage-sales-register')){
            $billing_type_id = 1;
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->where(function($query) use ($search)
            {
                $query->where('nep_date', 'LIKE', "%{$search}%");
                $query->orWhere('eng_date', 'LIKE', "%{$search}%");
                $query->orWhere('reference_no', 'LIKE', "%{$search}%");
                $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                $query->orWhereHas('suppliers', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('company_name', 'LIKE', "%{$search}%");
                        $q->orWhere('pan_vat', 'LIKE', "%{$search}%");
                    });
                });

            })->latest()->paginate(10);
            return view('backend.billings.salesregistersearch', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function salesReturnRegistersearch(Request $request)
    {
        if($request->user()->can('manage-sales-return-register')){
            $billing_type_id = 6;
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->where(function($query) use ($search)
            {
                $query->where('nep_date', 'LIKE', "%{$search}%");
                $query->orWhere('eng_date', 'LIKE', "%{$search}%");
                $query->orWhere('reference_no', 'LIKE', "%{$search}%");
                $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                $query->orWhereHas('suppliers', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('company_name', 'LIKE', "%{$search}%");
                        $q->orWhere('pan_vat', 'LIKE', "%{$search}%");
                    });
                });

            })->latest()->paginate(10);
            return view('backend.billings.salesReturnRegisterSearch', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function purchaseRegister(Request $request)
    {
        if($request->user()->can('manage-purchase-register'))
        {
            $billing_type_id = 2;
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $purchasebillings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $servicebillings = SalesBills::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $billings = collect($purchasebillings)->merge($servicebillings);
            if($request->ajax()){
                return Datatables::of($billings)
                        ->addIndexColumn()
                        ->addColumn('bill_date',function($row){
                            return $row->nep_date .'(in B.S)<br>'.$row->eng_date.'(in A.D)';
                        })
                        ->addColumn('action',function($row){
                            $showurl = route('billings.show',['billing'=>$row->id,'bill_type'=>$row->getTable()]);
                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";
                            return $btn;
                        })
                        ->rawColumns(['bill_date','action'])
                        ->make(true);
            }
            return view('backend.billings.purchaseregister', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function purchaseReturnRegister(Request $request)
    {
        if($request->user()->can('manage-purchase-return-register'))
        {
            $billing_type_id = 5;
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $salebillings = Billing::latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $servicebillings = SalesBills::latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->get();
            $billings = collect($salebillings)->merge($servicebillings);
            if($request->ajax()){
                return Datatables::of($billings)
                        ->addIndexColumn()
                        ->addColumn('bill_date',function($row){
                            return $row->nep_date .'(in B.S)<br>'.$row->eng_date.'(in A.D)';
                        })
                        ->addColumn('action',function($row){
                            $showurl = route('billings.show',['billing'=>$row->id,'bill_type'=>$row->getTable()]);
                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";
                            return $btn;
                        })
                        ->rawColumns(['bill_date','action'])
                        ->make(true);
            }
            // dd($billings);
            return view('backend.billings.purchaseReturnRegister', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function purchaseRegistersearch(Request $request)
    {
        if($request->user()->can('manage-purchase-register')){
            $billing_type_id = 2;
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->where(function($query) use ($search)
            {
                $query->where('nep_date', 'LIKE', "%{$search}%");
                $query->orWhere('eng_date', 'LIKE', "%{$search}%");
                $query->orWhere('reference_no', 'LIKE', "%{$search}%");
                $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                $query->orWhereHas('suppliers', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('company_name', 'LIKE', "%{$search}%");
                        $q->orWhere('pan_vat', 'LIKE', "%{$search}%");
                    });
                });

            })->latest()->paginate(10);
            return view('backend.billings.purchaseregistersearch', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function purchaseReturnRegisterSearch(Request $request)
    {
        if($request->user()->can('manage-purchase-return-register')){
            $billing_type_id = 5;
            $search = $request->input('search');
            $billingtype = Billingtype::where('id', $billing_type_id)->first();
            $billings = Billing::with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('taxamount', '>' , 0)->where(function($query) use ($search)
            {
                $query->where('nep_date', 'LIKE', "%{$search}%");
                $query->orWhere('eng_date', 'LIKE', "%{$search}%");
                $query->orWhere('reference_no', 'LIKE', "%{$search}%");
                $query->orWhere('transaction_no', 'LIKE', "%{$search}%");
                $query->orWhereHas('suppliers', function($q) use ($search) {
                    $q->where(function($q) use ($search) {
                        $q->where('company_name', 'LIKE', "%{$search}%");
                        $q->orWhere('pan_vat', 'LIKE', "%{$search}%");
                    });
                });

            })->latest()->paginate(10);
            return view('backend.billings.purchaseReturnRegisterSearch', compact('billing_type_id', 'billings', 'billingtype'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function extra(Request $request)
    {
        $fiscal_year = $request['fiscal_year'];
        $starting_date = $request['starting_date'];
        $ending_date = $request['ending_date'];
        $billing_type_id = $request['billing_type_id'];
        $current_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();

        if(isset($_GET['POS_generate']))
        {
            return redirect()->route('generateBillingPOSReport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date, "billing_type_id" => $billing_type_id]);
        }
        else
        {
            return redirect()->route('generateBillingReport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date, "billing_type_id" => $billing_type_id]);
        }
    }

    public function generateBillingReport(Request $request, $id, $starting_date, $ending_date, $billing_type_id)
    {

        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);

        if ($start_date > $end_date) {
            return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
        }

        $start_date_explode = explode("-", $starting_date);
        $end_date_explode = explode("-", $ending_date);

        if(($end_date_explode[0]-$start_date_explode[0]) > 1)
        {
            return redirect()->back()->with('error', 'Select dates within a fiscal year.');
        }

        if ($request['number_to_filter'] == null) {
            $number = 10;
        }
        else
        {
            $number = $request['number_to_filter'];
        }

        $billingtype = Billingtype::where('id', $billing_type_id)->first();
        $query = new Billing;
        $query = $query->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date);
        $billings = $query->latest()->with('billing_types')->with('suppliers','client')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('outlet_id', null)->where('is_cancelled', '0')->get();

        // $billings = Billing::latest()->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('outlet_id', null)->whereHas('payment_infos', function ($q) { $q->where('is_sales_invoice', null); })->get();
        // dd($billings);
        $today = date("Y-m-d");
        $nepali_today = datenep($today);
        $exploded_date = explode("-", $nepali_today);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::all();
        $request->merge([

            'starting_date'=>$starting_date,

            'start_date'=>$start_date,
            'end_date'=>$end_date,


            ]);
            // dd($billings);
            $total_sum = array_sum(array_column($billings->toArray(), 'grandtotal'));
            // return redirect()->route('billings.report',$billing_type_id);
        return view('backend.billings.index', compact('total_sum', 'number', 'fiscal_years', 'billing_type_id', 'actual_year', 'current_fiscal_year', 'billingtype', 'billings','start_date','end_date'));
        // return view('backend.billings.billingsreport', compact('number', 'starting_date', 'id', 'start_date', 'end_date', 'ending_date', 'billings', 'billingtype', 'current_fiscal_year', 'actual_year', 'fiscal_years', 'billing_type_id'));
    }

    public function generateBillingPOSReport(Request $request, $id, $starting_date, $ending_date, $billing_type_id)
    {
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);

        if ($start_date > $end_date) {
            return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
        }

        $start_date_explode = explode("-", $starting_date);
        $end_date_explode = explode("-", $ending_date);

        if(($end_date_explode[0]-$start_date_explode[0]) > 1)
        {
            return redirect()->back()->with('error', 'Select dates within a fiscal year.');
        }

        $billingtype = Billingtype::where('id', $billing_type_id)->first();
        $billings = Billing::latest()->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->with('billing_types')->with('suppliers')->where('billing_type_id', $billing_type_id)->where('status', '1')->where('is_cancelled', '0')->where('outlet_id', '!=', null)->paginate(10);

        $current_fiscal_year = FiscalYear::where('id', $id)->first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::all();

        return view('backend.pos.generatedPOSreport', compact('starting_date', 'id', 'start_date', 'end_date', 'ending_date', 'billings', 'billingtype', 'current_fiscal_year', 'actual_year', 'fiscal_years', 'billing_type_id'));
    }

    public function vatrefundreport(Request $request)
    {

        if($request->user()->can('manage-vat-refund')){

            $salebillings = SalesBills::with('billing_types')->with('suppliers')->where('billing_type_id', 1)->where('status', '1')->where('is_cancelled', '0')->where('vat_refundable', '>', '0')->get();

            $billing = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', 1)->where('status', '1')->where('is_cancelled', '0')->where('vat_refundable', '>', '0')->get();

            $billings = $billing->merge($salebillings);
            $grandtotal = $billings->sum('vat_refundable');

            return view('backend.billings.vatrefund', compact('billings','grandtotal'));
        }else{
            return view('backend.permission.permission');
        }

    }

    public function vatrefundsearch(Request $request)
    {
        $search = $request->input('search');
        $billingtype = Billingtype::where('id', 1)->first();
        $billings = Billing::query()
            ->with('billing_types')
            ->with('suppliers')
            ->where('billing_type_id', 1)
            ->Where('transaction_no', 'LIKE', "%{$search}%")
            ->orWhere('nep_date', 'LIKE', "%{$search}%")
            ->where('status', '1')
            ->where('is_cancelled', '0')
            ->latest()
            ->paginate(10);
        // dd($billings);

        return view('backend.billings.vatrefundsearch', compact('billings', 'billingtype'));
    }

    public function sendEmail($id)
    {
        $billing = Billing::with('suppliers')->with('billing_types')->findorfail($id);
        if($billing->vendor_id == null)
        {
            $data['email'] = $billing->client->email;
        }
        else
        {
            $data['email'] = $billing->suppliers->company_email;
        }
        $billing_type = Billingtype::where('id', $billing->billing_type_id)->first();
        $quotationsetting = Quotationsetting::first();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'portrait')->loadView('backend.billings.letterheaddownloadbilling', compact('billing', 'billing_type', 'quotationsetting'));

        Mail::send('emails.billingsMail', $data, function($message)use($data, $pdf)
        {
            $message->to($data["email"])
                    ->subject("Billing Information")
                    ->attachData($pdf->output(), "billing.pdf");
        });

        return redirect()->back()->with('success', 'Bill PDF is sent successfully.');
    }

    public function irdSyncForm()
    {
        return view('backend.irdSyncform');
    }

    public function salesBillsPDF(Request $request, $billingtype_id)
    {
        $billing_type = Billingtype::findorFail($billingtype_id);
        $pos_data = 0;

        if($request['export_POS'] == 1)
        {
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 1)->get();
            $pos_data = 1;
        }
        else if($request['selectedid'] == null)
        {
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 0)->get();
        }
        else
        {
            $selectedid = explode(',', $request['selectedid']);
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->whereIn('id', $selectedid)->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 0)->get();
        }
        // dd($billings);
        // $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->get();
        $fiscal_years = FiscalYear::all();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $setting = Setting::first();
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.billings.salesBillsPdf', compact(
            'billings',
            'billing_type',
            'currentcomp',
            'fiscal_years',
            'actual_year',
            'current_fiscal_year',
            'setting',
            'quotationsetting',
            'pos_data',
            'path_img'));

        if($request['export_POS'] == 1)
        {
            return $pdf->download($current_fiscal_year->fiscal_year.'_POS_Sales.pdf');
        }
        return $pdf->download($current_fiscal_year->fiscal_year.'_'.$billing_type->billing_types.'.pdf');
    }

    public function salesBillsReportPDF(Request $request, $id, $starting_date, $ending_date, $billingtype_id)
    {
        $billing_type = Billingtype::findorFail($billingtype_id);
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);
        $pos_data = 0;

        if($request['export_POS'] == 1)
        {
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 1)->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->get();
            $pos_data = 1;
        }
        elseif($request['selectedid'] == null){
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 0)->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->get();
        }else{
            $selectedid = explode(',', $request['selectedid']);
            $billings = Billing::latest()->with('billing_types')->with('suppliers')->whereIn('id', $selectedid)->where('billing_type_id', $billingtype_id)->where('status', '1')->where('is_cancelled', '0')->where('is_pos_data', 0)->where('eng_date', '>=', $start_date)->where('eng_date', '<=', $end_date)->get();
        }
        $current_fiscal_year = FiscalYear::findorFail($id);
        $setting = Setting::first();
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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.billings.salesBillsPdf',
        compact(
            'billings',
            'billing_type',
            'currentcomp',
            'current_fiscal_year',
            'setting',
            'quotationsetting',
            'start_date',
            'end_date',
            'starting_date',
            'ending_date',
            'path_img',
            'pos_data'
        ));

        if($request['export_POS'] == 1)
        {
            return $pdf->download($start_date . ' to '. $end_date.'_POS_Sales.pdf');
        }
        return $pdf->download($start_date . ' to '. $end_date.'_'.$billing_type->billing_types.'.pdf');
    }

    public function convertToBill($id)
    {
        $billing = Billing::findorFail($id);
        $payment_info = PaymentInfo::where('billing_id', $id)->first();

        $credit = Credit::where('invoice_id', $id)->first();

        $credit->update([
            'converted' => 1
        ]);

        $payment_info->update([
            'payment_type' => 'paid',
            'payment_amount' => $billing->grandtotal,
            'due_date' => null,
            'total_paid_amount' => $billing->grandtotal,
            'is_sales_invoice' => null,
        ]);

        return redirect()->route('billings.report', 1)->with('success', 'Sales Invoice is converted to sales bills successfully.');
    }

    public function convertToPurchase($id)
    {
        $purchaseOrder = PurchaseOrder::findorFail($id);
        $fiscal_years = FiscalYear::latest()->get();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();
        $payment_methods = Paymentmode::latest()->get();
        $vendors = Vendor::latest()->get();
        $provinces = Province::latest()->get();
        $billing_type_id = 2;
        $categories = Category::with('products')->get();
        $taxes = Tax::latest()->get();
        $allsuppliercodes = [];
        foreach($vendors as $supplier){
            array_push($allsuppliercodes, $supplier->supplier_code);
        }
        $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        return view('backend.billings.orderToBill', compact('fiscal_years', 'allsuppliercodes', 'current_fiscal_year', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'supplier_code', 'purchaseOrder'));
    }

    public function salesInvoiceCreate(Request $request)
    {
        if($request->user()->can('manage-sales-invoices')){
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::latest()->get();
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $billing_type_id = 1;
            $categories = Category::with('products')->get();
            $taxes = Tax::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product')->latest()->get();
            $dealerTypes = DealerType::latest()->get();

            $clients = Client::with('dealertype')->latest()->get();
            $allclientcodes = [];
            foreach($clients as $client){
                array_push($allclientcodes, $client->client_code);
            }
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $services = Service::latest()->where('status', 1)->get();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            return view('backend.billings.salesInvoiceCreate', compact('services', 'fiscal_years', 'current_fiscal_year', 'allclientcodes', 'payment_methods', 'vendors', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'clients', 'client_code', 'ird_sync', 'dealerTypes'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function salesInvoiceEdit($id, Request $request)
    {
        if($request->user()->can('manage-sales-invoices')){
            $billing = Billing::with('billingextras')->where('id', $id)->first();
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $payment_methods = Paymentmode::all();
            $vendors = Vendor::all();
            $clients = Client::with('dealertype')->latest()->get();
            $provinces = Province::all();
            $billing_type_id = $billing->billing_type_id;
            $categories = Category::with('products')->get();
            $taxes = Tax::all();
            $online_portals = OnlinePayment::latest()->get();
            $banks = Bank::latest()->get();
            $godowns = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series')->latest()->get();
            $selgodown = Godown::with('godownproduct', 'godownproduct.product', 'godownproduct.product.product_images', 'godownproduct.product.brand', 'godownproduct.product.series')->where('id', $billing->godown)->first();

            $quotationsetting = Quotationsetting::first();
            $ird_sync = UserCompany::where('user_id', Auth::user()->id)->where('is_selected', 1)->first()->company->ird_sync;
            return view('backend.billings.salesInvoiceEdit', compact('billing', 'fiscal_years', 'online_portals', 'banks', 'current_fiscal_year', 'payment_methods', 'vendors', 'clients', 'provinces', 'billing_type_id', 'categories', 'taxes', 'godowns', 'selgodown', 'ird_sync'));
        }else{
            return view('backend.permission.permission');
        }
    }


    public function allbillingcredits(Request $request){
        $billing_type_id = $request->get('billing_type_id');
        $is_service_sale = $request->get('is_service_sale');


        if($billing_type_id){
            if($is_service_sale){

                $billings = BillingCredit::with('client','vendor','sale_bill')->where('billing_type_id',$billing_type_id)->where('is_sale_service','=',1)->get();
                $total_credit = BillingCredit::where('billing_type_id',$billing_type_id)->where('is_sale_service','=',1)->sum('credit_amount');
            }else{

                $billings = BillingCredit::with('client','vendor','billing')->where('billing_type_id',$billing_type_id)->whereNull('is_sale_service')->get();

                $total_credit = BillingCredit::where('billing_type_id',$billing_type_id)->whereNull('is_sale_service')->sum('credit_amount');
                // dd($total_credit);
            }

        }else{
            $billings = BillingCredit::with('billing','client','vendor')->whereNull('is_sale_service')->get();
            $total_credit = BillingCredit::whereNull('is_sale_service')->sum('credit_amount');
        }



        if($request->ajax()){
            return Datatables::of($billings)
            ->addIndexColumn()
            ->addColumn('reference_no',function($row){
                if($row->is_sale_service == 1){
                    return $row->sale_bill->reference_no;
                }else{
                    return $row->billing->reference_no;
                }
            })
            ->addColumn('client_or_supplier',function($row){
                if($row->is_sale_service == 1){
                    // $saleBilling = SalesBills::find($row->billing_id);

                    if($row->sale_bill->billing_type_id == 2 || $row->sale_bill->billing_type_id == 5){
                        return $row->vendor->company_name ?? "";
                    }
                    if($row->sale_bill->billing_type_id == 1 || $row->sale_bill->billing_type_id == 6){
                        return $row->client->name ?? "";
                    }
                }else{
                    if($row->billing->billing_type_id == 2 || $row->billing->billing_type_id == 5){
                        return $row->vendor->company_name ?? "";
                    }
                    if($row->billing->billing_type_id == 1 || $row->billing->billing_type_id == 6){
                        return $row->client->name ?? "";
                    }
                }


            })

            ->addColumn('Payment_amount',function($row){
                if($row->is_sale_service == 1){
                    // $saleBilling = SalesBills::find($row->billing_id);
                    return $row->sale_bill->grandtotal - $row->credit_amount;
                }else{
                    return $row->billing->grandtotal - $row->credit_amount;
                }

            })
            ->addColumn('grandtotal',function($row){
                if($row->is_sale_service == 1){
                    return $row->sale_bill->grandtotal;
                }else{
                    return $row->billing->grandtotal;
                }
            })
            ->addColumn('action',function($row){
                if($row->is_sale_service){
                    $editurl = route('billing.editBillingCredits',['id'=>$row->id,'is_sale_service'=>$row->is_sale_service]);
                }else{
                    $editurl = route('billing.editBillingCredits',$row->id);
                }

                $btn ="<a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>";
                return $btn;
            })

            ->rawColumns(['grandtotal','reference_no','client_or_supplier','Payment_amount','action'])
            ->make(true);
        }
        return view('backend.billings.billingscredit',compact('total_credit','billing_type_id','is_service_sale'));
    }

    public function editBillingCredits(Request $request, $billingid){

        $is_sale_service = $request->get('is_sale_service');

        if($is_sale_service == 1){
            $billingCredit = BillingCredit::with('sale_bill')->find($billingid);
        }else{
            $billingCredit = BillingCredit::with('billing')->find($billingid);
        }

        return view('backend.billings.billingscreditedit',compact('is_sale_service','billingCredit'));
    }

    public function openingbalance($childaccounts, $fiscal_year_id, $debitAmounts, $creditAmounts){
        $opening_balance = OpeningBalance::where('child_account_id', $childaccounts)->where('fiscal_year_id', $fiscal_year_id)->first();
        if($opening_balance == null){
            $closing = $debitAmounts - $creditAmounts;
            $openingbalance = OpeningBalance::create([
                'child_account_id' => $childaccounts,
                'fiscal_year_id' => $fiscal_year_id,
                'opening_balance' => 0,
                'closing_balance' => $closing,
            ]);
            $openingbalance->save();
        }else{
            $closing = $opening_balance->closing_balance + $debitAmounts - $creditAmounts;
            $opening_balance->update([
                'closing_balance' => $closing,
            ]);
        }
        $child_account = ChildAccount::where('id', $childaccounts)->first();
        $current_sub_account = SubAccount::where('id', $child_account->sub_account_id)->first();
        $sundry_creditor_account_id = SubAccount::where('slug', "sundry-creditors")->first()->id;
        $sundry_debtor_account_id = SubAccount::where('slug', "sundry-debtors")->first()->id;
        if($current_sub_account->slug == "sundry-debtors" || $current_sub_account->slug == "sundry-creditors"){
            if($closing < 0){
                $child_account->update([
                    'sub_account_id' => $sundry_creditor_account_id,
                ]);
            }else{
                $child_account->update([
                    'sub_account_id' => $sundry_debtor_account_id,
                ]);
            }
        }
    }

}
