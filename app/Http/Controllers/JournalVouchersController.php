<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\CancelledVoucher;
use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\DealerType;
use App\Models\JournalImage;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\JournalVouchers;
use App\Models\OnlinePayment;
use App\Models\OpeningBalance;
use App\Models\Paymentmode;
use App\Models\Province;
use App\Models\Reconciliation;
use App\Models\Setting;
use App\Models\SubAccount;
use App\Models\SuperSetting;
use App\Models\UserCompany;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Yajra\Datatables\Datatables;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;
use Illuminate\Support\Facades\DB;

class JournalVouchersController extends Controller
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
        if($request->user()->can('view-journals'))
        {
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            if ($exploded_date[1] > 3)
            {
                $new_fiscal_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
                $existing_fiscal_year = FiscalYear::where('fiscal_year', $new_fiscal_year)->first();

                if(!$existing_fiscal_year)
                {
                    FiscalYear::create([
                        'fiscal_year' => $new_fiscal_year
                    ]);
                }
            }
            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            if ($request->ajax()) {

                $data = JournalVouchers::with('journal_extras','journal_extras.child_account')->where('fiscal_year_id', $current_fiscal_year->id)->where('is_cancelled', '0')->where('status', 1);
                $datatable = new Datatables;

                return $datatable->eloquent($data)
                    ->addIndexColumn()
                    ->addColumn('select', function($row){
                        $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                        return $select;
                    })
                    ->addColumn('particulars', function($row){
                        $particulars = '';

                            foreach ($row->journal_extras as $jextra) {
                                if(!empty($jextra->child_account)){
                                $particulars = $particulars . $jextra->child_account->title . '<br>';
                            }
                        }
                        return $particulars;
                    })
                    ->addColumn('debit', function($row){
                        $debit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->debitAmount == 0) {
                                $debit_amounts = $debit_amounts . '-' . '<br>';
                            } else {
                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                            }
                        }
                        return $debit_amounts;
                    })
                    ->addColumn('credit', function($row){
                        $credit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->creditAmount == 0) {
                                $credit_amounts = $credit_amounts . '-' . '<br>';
                            } else {
                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                            }
                        }
                        return $credit_amounts;
                    })
                    ->editColumn('status', function($row){
                        if ($row->status == '1') {
                            $status = 'Approved';
                        } else {
                            $status = 'Awaiting for Approval';
                        }
                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $showurl = route('journals.show', $row->id);
                        $editurl = route('journals.edit', $row->id);
                        $statusurl = route('journals.status', $row->id);
                        $cancellationurl = route('journals.cancel', $row->id);
                        $printurl = route('pdf.generateJournal', $row->id);
                        $csrf_token = csrf_token();
                        if ($row->status == 1) {
                            $btnname = 'fa fa-thumbs-down';
                            $btnclass = 'btn-info';
                            $title = 'Disapprove';
                        } else {
                        $btnname = 'fa fa-thumbs-up';
                            $btnclass = 'btn-info';
                            $title = 'Approve';
                        }

                        $btn = "<div class='btn-bulk'>
                                    <a href='$printurl' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                    <a href='$showurl' class='btn btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$row->id' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name='$title' class='btn $btnclass btn-primary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Please give reason for Cancellation</p>
                                                    <hr>
                                                    <form action='$cancellationurl' method='POST'>
                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                        <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                        <div class='form-group'>
                                                            <label for='reason'>Reason:</label>
                                                            <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='description'>Description: </label>
                                                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                        </div>
                                                        <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    ";

                        return $btn;
                    })
                    ->rawColumns(['select', 'particulars', 'debit', 'credit', 'status','action'])
                    ->make(true);
            }


            // $journalvouchers = JournalVouchers::with('journal_extras')->latest()->where('fiscal_year_id', $current_fiscal_year->id)->where('is_cancelled', '0')->where('status', 1)->paginate(10);
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::latest()->get();
            return view('backend.journalvoucher.index', compact('fiscal_years', 'current_fiscal_year', 'actual_year'));
        }
        else
        {
            return view('backend.permission.permission');
        }
    }


    // public function journalsearch(Request $request)
    // {
    //     $search = $request->input('search');
    //     $date = date("Y-m-d");
    //     $nepalidate = datenep($date);
    //     $exploded_date = explode("-", $nepalidate);

    //     if ($exploded_date[1] > 3) {
    //         $new_fiscal_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
    //         $existing_fiscal_year = FiscalYear::where('fiscal_year', $new_fiscal_year)->first();

    //         if(!$existing_fiscal_year) {
    //             $fiscal_year = FiscalYear::create([
    //                 'fiscal_year' => $new_fiscal_year
    //             ]);
    //             $fiscal_year->save();
    //         }
    //     }

    //     $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
    //     $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();

    //     $journalvouchers = JournalVouchers::query()
    //         ->where('journal_voucher_no', 'LIKE', "%{$search}%")
    //         ->orWhere('entry_date_nepali', 'LIKE', "%{$search}%")
    //         ->orWhere('narration', 'LIKE', "%{$search}%")
    //         ->latest()->where('fiscal_year_id', $current_fiscal_year->id)->where('is_cancelled', '0')->where('status', 1)
    //         ->paginate(10);

    //     $actual_year = explode("/", $current_fiscal_year->fiscal_year);
    //     $fiscal_years = FiscalYear::latest()->get();

    //     return view('backend.journalvoucher.search', compact('journalvouchers','fiscal_years', 'current_fiscal_year', 'actual_year'));
    // }

    public function status($id, Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $journal = JournalVouchers::with('journal_extras')->findorfail($id);
            $thisday = date("Y-m-d");
            $entryday = $journal->entry_date_english;
            $thisdaymilli = strtotime($thisday);
            $entrydaymilli = strtotime($entryday);
            $milliperday = 86400;
            $numofdays = 200;
            $totalmilli = $milliperday * $numofdays;
            $diff_bet_days = $thisdaymilli - $entrydaymilli;

            if(isset($_POST['Disapprove']))
            {
                if($diff_bet_days < $totalmilli)
                {
                    $journal->update([
                        'status'=>'0',
                    ]);
                    if($journal->is_cancelled == 0){
                        foreach($journal->journal_extras as $jextra){
                            $opening_balance = OpeningBalance::where('child_account_id', $jextra->child_account_id)->where('fiscal_year_id', $journal->fiscal_year_id)->first();
                            $closing = $opening_balance->closing_balance - $jextra->debitAmount + $jextra->creditAmount;
                            // dd($closing, $opening_balance->closing_balance, $jextra->debitAmount, $jextra->creditAmount);
                            $opening_balance->update([
                                'closing_balance' => $closing,
                            ]);

                        }
                    }

                    return redirect()->route('journals.index')->with('success', 'Status Updated Successfully');
                }
                else
                {
                    return redirect()->route('journals.index')->with('error', 'Journal Voucher is to old to change status');
                }
            }
            else if(isset($_POST['Approve']))
            {
                $user = Auth::user()->id;
                if($diff_bet_days < $totalmilli)
                {
                    $journal->update([
                        'status' => '1',
                        'approved_by' => $user,
                    ]);
                    if($journal->is_cancelled == 0){
                        foreach($journal->journal_extras as $jextra){
                            $this->openingbalance($jextra->child_account_id, $journal->fiscal_year_id, $jextra->debitAmount, $jextra->creditAmount);
                        }
                    }
                    $journal->save;
                    return redirect()->route('journals.index')->with('success', 'Status Updated Successfully');
                }
                else
                {
                    return redirect()->back()->with('error', "Can't approve! Journal too old.");
                }
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function multiapprove(Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            DB::beginTransaction();
            try{
                foreach($request['id'] as $id)
                {
                    $journal_voucher = JournalVouchers::find($id);
                    $thisday = date("Y-m-d");
                    $entryday = $journal_voucher->entry_date_english;
                    $thisdaymilli = strtotime($thisday);
                    $entrydaymilli = strtotime($entryday);
                    $milliperday = 86400;
                    $numofdays = 200;
                    $totalmilli = $milliperday * $numofdays;
                    $diff_bet_days = $thisdaymilli - $entrydaymilli;
                    if($diff_bet_days < $totalmilli){
                        $journal_voucher->update([
                            'status' => '1',
                            'approved_by' => Auth::user()->id,
                        ]);
                        if($journal_voucher->is_cancelled == 0){
                            foreach($journal_voucher->journal_extras as $jextra){
                                $this->openingbalance($jextra->child_account_id, $journal_voucher->fiscal_year_id, $jextra->debitAmount, $jextra->creditAmount);
                            }
                        }
                        $journal_voucher->save;
                    }
                }
                DB::commit();
                return response()->json(['success'=>'Selected journals Successfully Approved']);
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function multiunapprove(Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            DB::beginTransaction();
            try{
                foreach($request['id'] as $id)
                {
                    $journal_voucher = JournalVouchers::find($id);
                    $thisday = date("Y-m-d");
                    $entryday = $journal_voucher->entry_date_english;
                    $thisdaymilli = strtotime($thisday);
                    $entrydaymilli = strtotime($entryday);
                    $milliperday = 86400;
                    $numofdays = 200;
                    $totalmilli = $milliperday * $numofdays;
                    $diff_bet_days = $thisdaymilli - $entrydaymilli;

                    if($diff_bet_days < $totalmilli){
                        $journal_voucher->update([
                            'status'=>'0',
                            'approved_by'=>null,
                        ]);
                        if($journal_voucher->is_cancelled == 0){
                            foreach($journal_voucher->journal_extras as $jextra){
                                $opening_balance = OpeningBalance::where('child_account_id', $jextra->child_account_id)->where('fiscal_year_id', $journal_voucher->fiscal_year_id)->first();
                                $closing = $opening_balance->closing_balance - $jextra->debitAmount + $jextra->creditAmount;
                                $opening_balance->update([
                                    'closing_balance' => $closing,
                                ]);
                            }
                        }
                    }
                }
                DB::commit();
                return response()->json(['success'=>'Selected journals Successfully Unapproved']);
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function cancel(Request $request, $id)
    {
        if($request->user()->can('view-journals'))
        {
            $journal = JournalVouchers::findorfail($id);
            $thisday = date("Y-m-d");
            $entryday = $journal->entry_date_english;
            $thisdaymilli = strtotime($thisday);
            $entrydaymilli = strtotime($entryday);
            $milliperday = 86400;
            $numofdays = 200;
            $totalmilli = $milliperday * $numofdays;
            $diff_bet_days = $thisdaymilli - $entrydaymilli;

            if($diff_bet_days < $totalmilli){
                DB::beginTransaction();
                try{
                    $this->validate($request,[
                        'reason'=>'required',
                        'description'=>'required',
                    ]);
                    $user = Auth::user()->id;
                    $cancellation = CancelledVoucher::create([
                        'journalvoucher_id'=>$id,
                        'reason'=>$request['reason'],
                        'description'=>$request['description'],
                    ]);

                    $journal->update([
                        'is_cancelled'=>'1',
                        'cancelled_by'=>$user,
                    ]);
                    if($journal->status == 1){
                        // dd($journal->journal_extras);
                        foreach($journal->journal_extras as $jextra){
                            $opening_balance = OpeningBalance::where('child_account_id', $jextra->child_account_id)->where('fiscal_year_id', $journal->fiscal_year_id)->first();
                            $closing = $opening_balance->closing_balance - $jextra->debitAmount + $jextra->creditAmount;
                            $opening_balance->update([
                                'closing_balance' => $closing,
                            ]);
                        }
                    }

                    $cancellation->save();
                    return redirect()->route('journals.index')->with('success', 'Journal Voucher is cancelled Successfully');
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollBack();
                    throw new \Exception($e->getMessage());
                }
            }else{
                return redirect()->route('journals.index')->with('error', 'Journal Voucher is to old to Cancel');
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function cancelledjournalsearch(Request $request)
    {
        $search = $request->input('search');

        $journalvouchers = JournalVouchers::query()
            ->where('journal_voucher_no', 'LIKE', "%{$search}%")
            ->orWhere('entry_date_nepali', 'LIKE', "%{$search}%")
            ->orWhere('narration', 'LIKE', "%{$search}%")
            ->latest()->where('is_cancelled', '1')
            ->paginate(10);

        return view('backend.journalvoucher.cancelledsearch', compact('journalvouchers'));
    }

    public function revive($id, Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $journal = JournalVouchers::findorfail($id);
            $thisday = date("Y-m-d");
            $entryday = $journal->entry_date_english;
            $thisdaymilli = strtotime($thisday);
            $entrydaymilli = strtotime($entryday);
            $milliperday = 86400;
            $numofdays = 200;
            $totalmilli = $milliperday * $numofdays;
            $diff_bet_days = $thisdaymilli - $entrydaymilli;
            if($diff_bet_days < $totalmilli){
                $cancelledjournal = CancelledVoucher::where('journalvoucher_id', $id)->first();
                $cancelledjournal->delete();

                $journal->update([
                    'is_cancelled'=>'0',
                    'cancelled_by' => null
                ]);
                if($journal->status == 1){
                    foreach($journal->journal_extras as $jextra){
                        $this->openingbalance($jextra->child_account_id, $journal->fiscal_year_id, $jextra->debitAmount, $jextra->creditAmount);
                    }
                }
                // else{
                //     foreach($journal->journal_extras as $jextra){
                //         $opening_balance = OpeningBalance::where('child_account_id', $jextra->child_account_id)->where('fiscal_year_id', $journal->fiscal_year_id)->first();
                //         $closing = $opening_balance->closing_balance - $jextra->debitAmount + $jextra->creditAmount;
                //         $opening_balance->update([
                //             'closing_balance' => $closing,
                //         ]);
                //     }
                // }
                return redirect()->route('journals.index')->with('success', 'Journal Voucher Successfully Revived');
            }else{
                return redirect()->route('journals.index')->with('error', 'Journal Voucher is to old to Revive');
            }
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function cancelledindex(Request $request)
    {
        if($request->user()->can('cancelled-journals')){
            if ($request->ajax()) {
                $data = JournalVouchers::latest()->with('journal_extras')->where('is_cancelled', '1')->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('particulars', function($row){
                        $particulars = '';
                        foreach ($row->journal_extras as $jextra) {
                            if(empty($jextra->child_account)){
                                continue;
                            }
                            $particulars = $particulars . $jextra->child_account->title . '<br>';
                        }
                        return $particulars;
                    })
                    ->addColumn('debit', function($row){
                        $debit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->debitAmount == 0) {
                                $debit_amounts = $debit_amounts . '-' . '<br>';
                            } else {
                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                            }
                        }
                        return $debit_amounts;
                    })
                    ->addColumn('credit', function($row){
                        $credit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->creditAmount == 0) {
                                $credit_amounts = $credit_amounts . '-' . '<br>';
                            } else {
                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                            }
                        }
                        return $credit_amounts;
                    })
                    ->editColumn('status', function($row){
                        if ($row->status == '1') {
                            $status = 'Approved';
                        } else {
                            $status = 'Awaiting for Approval';
                        }
                        return $status;
                    })
                    ->addColumn('action', function($row){
                        $showurl = route('journals.show', $row->id);
                        $editurl = route('journals.edit', $row->id);
                        $reviveurl = route('journals.revive', $row->id);
                        $statusurl = route('journals.status', $row->id);
                        $csrf_token = csrf_token();
                        if ($row->status == 1) {
                            $btnname = 'fa fa-thumbs-down';
                            $btnclass = 'btn-info';
                            $title = 'Disapprove';
                            $btn = "<div class='btn-bulk'>
                                        <a href='{{ route('journals.print', $row->id) }}' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                        <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                        <form action='$reviveurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' class='btn btn-secondary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                        </form>
                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' name = '$title' class='btn $btnclass btn-primary icon-btn text-light btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                        </form>
                                    </div>
                                            ";
                            } else {
                                $btnname = 'fa fa-thumbs-up';
                                $btnclass = 'btn-info';
                                $title = 'Approve';
                                $btn = "<div class='btn-bulk'>
                                            <a href='{{ route('journals.print', $row->id) }}' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                            <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                            <form action='$reviveurl' method='POST' style='display:inline-block'>
                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                <button type='submit' class='btn btn-primary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                            </form>
                                            <form action='$statusurl' method='POST' style='display:inline-block'>
                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                <button type='submit' name = '$title' class='btn $btnclass btn-secondary icon-btn text-light btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                            </form>
                                        </div>
                                        ";
                        }
                        return $btn;
                    })
                    ->rawColumns(['particulars', 'debit', 'credit', 'status','action'])
                    ->make(true);
            }
            return view('backend.journalvoucher.cancelledindex');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovedindex(Request $request)
    {
        if($request->user()->can('unapproved-journals')){
            if ($request->ajax()) {
                $data = JournalVouchers::with('journal_extras')->latest()->where('status', 0)->where('is_cancelled', 0)->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('select', function($row){
                        $select = "<input name='select[]' type='checkbox' class='select' value='$row->id'>";
                        return $select;
                    })
                    ->addColumn('particulars', function($row){
                        $particulars = '';
                        foreach ($row->journal_extras as $jextra) {
                            $particulars = $particulars . $jextra->child_account->title . '<br>';
                        }
                        return $particulars;
                    })
                    ->addColumn('debit', function($row){
                        $debit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->debitAmount == 0) {
                                $debit_amounts = $debit_amounts . '-' . '<br>';
                            } else {
                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                            }
                        }
                        return $debit_amounts;
                    })
                    ->addColumn('credit', function($row){
                        $credit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->creditAmount == 0) {
                                $credit_amounts = $credit_amounts . '-' . '<br>';
                            } else {
                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                            }
                        }
                        return $credit_amounts;
                    })
                    ->editColumn('status', function($row){
                        if ($row->status == '1') {
                            $status = 'Approved';
                        } else {
                            $status = 'Awaiting for Approval';
                        }
                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $showurl = route('journals.show', $row->id);
                        $editurl = route('journals.edit', $row->id);
                        $statusurl = route('journals.status', $row->id);
                        $cancellationurl = route('journals.cancel', $row->id);
                        if ($row->status == 1) {
                            $btnname = 'fa fa-thumbs-down';
                            $btnclass = 'btn-info';
                            $title = 'Disapprove';
                        } else {
                            $btnname = 'fa fa-thumbs-up';
                            $btnclass = 'btn-info';
                            $title = 'Approve';
                        }
                        $csrf_token = csrf_token();
                        $btn = "<div class='btn-bulk'>
                                    <a href='{{ route('journals.print', $row->id) }}' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                    <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-secondary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <p>Please give reason for Cancellation</p>
                                                        <hr>
                                                        <form action='$cancellationurl' method='POST'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                            <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                            <div class='form-group'>
                                                                <label for='reason'>Reason:</label>
                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <label for='description'>Description: </label>
                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                            </div>
                                                            <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                        return $btn;
                    })
                    ->rawColumns(['select', 'particulars', 'debit', 'credit', 'status','action'])
                    ->make(true);
            }
            return view('backend.journalvoucher.unapproved');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovejournalsearch(Request $request)
    {
        $search = $request->input('search');
        $journalvouchers = JournalVouchers::query()
            ->where('journal_voucher_no', 'LIKE', "%{$search}%")
            ->orWhere('entry_date_nepali', 'LIKE', "%{$search}%")
            ->orWhere('narration', 'LIKE', "%{$search}%")
            ->latest()->where('status', '0')->where('is_cancelled', '0')
            ->paginate(10);

        return view('backend.journalvoucher.unapprovesearch', compact('journalvouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('create-journals'))
        {
            $today = date("Y-m-d");
            $nepalitoday = datenep($today);

            $explode = explode('-', $nepalitoday);
            $year = $explode[0];
            $month = $explode[1];

            if($month < 4){
                $fiscalyear = ($year - 1).'/'.$year;

            }else{
                $fiscalyear = $year.'/'.($year + 1);
            }

            $fiscal_year = FiscalYear::where('fiscal_year', $fiscalyear)->first();
            $accounts = Account::latest()->get();
            $vendors = Vendor::all();
            $provinces = Province::all();
            $payment_methods = Paymentmode::where('status', 1)->get();
            $banks = Bank::all();
            $dealerTypes = DealerType::latest()->get();

            $allsuppliercodes = [];
            foreach($vendors as $supplier){
                array_push($allsuppliercodes, $supplier->supplier_code);
            }
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $client_code = 'CL'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $bankAccountTypes = BankAccountType::latest()->where('status', 1)->get();
            return view('backend.journalvoucher.create', compact('banks', 'accounts', 'bankAccountTypes', 'vendors', 'provinces', 'payment_methods', 'allsuppliercodes', 'supplier_code', 'client_code', 'dealerTypes'));
        }
        else
        {
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
        DB::beginTransaction();
        try{
            $saveandcontinue = $request->saveandcontinue ?? 0;
            if($request->user()->can('create-journals')){
                $user = Auth::user()->id;
                $this->validate($request, [
                    'english_date' => 'required',
                    'child_account_id' => 'required',
                    'code' => '',
                    'remarks' => '',
                    'debitAmount' => '',
                    'creditAmount' => '',
                    'debitTotal' => 'required',
                    'creditTotal' => 'required',
                    'payment_method' => '',
                    'receipt_payment' => '',
                    'bank_id' => '',
                    'online_portal' => '',
                    'cheque_no' => '',
                    'customer_portal_id' => '',
                    'narration' => 'required',
                    'file'=>'',
                    'file.*' => 'mimes:png,jpg,jpeg',
                    'vendor_id'=>'',
                    'client_id'=>'',
                ]);

                $nepali_date = datenep($request['english_date']);
                $date_array = explode("-", $nepali_date);

                if ($date_array[1] < 4)
                {
                    $last_year = $date_array[0] - 1;
                    $fiscal_year = $last_year . "/" . $date_array[0];
                }
                else
                {
                    $next_year = $date_array[0] + 1;
                    $fiscal_year = $date_array[0] . "/" . $next_year;
                }

                $available_fiscal_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();

                if ($available_fiscal_year)
                {
                    $fiscal_year_id = $available_fiscal_year->id;
                }
                else
                {
                    $new_fiscal_year = FiscalYear::create([
                        'fiscal_year' => $fiscal_year
                    ]);
                    $fiscal_year_id = $new_fiscal_year->id;
                }

                if($request->has('status'))
                {
                    $status = $request['status'];
                }
                else
                {
                    $status = '0';
                }

                if($status=='1')
                {
                    $approved_by = Auth::user()->id;
                }
                else
                {
                    $approved_by = null;
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

                $today = date("Y-m-d");
                $nepalitoday = datenep($today);

                $explode = explode('-', $nepalitoday);

                $year = $explode[0];
                $month = $explode[1];

                if($month < 4)
                {
                    $fiscalyear = ($year - 1).'/'.$year;

                }
                else
                {
                    $fiscalyear = $year.'/'.($year + 1);
                }

                $fiscal_year = FiscalYear::where('fiscal_year', $fiscalyear)->first();

                $journals = JournalVouchers::latest()->where('fiscal_year_id', $fiscal_year->id)->get();
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
                $journal_voucher = JournalVouchers::create([
                    'journal_voucher_no' => $jvno,
                    'entry_date_english' => $request['english_date'],
                    'entry_date_nepali' => $nepali_date,
                    'fiscal_year_id' => $fiscal_year_id,
                    'debitTotal' => $request['debitTotal'],
                    'creditTotal' => $request['creditTotal'],
                    'payment_method' => $request['payment_method'],
                    'receipt_payment' => $request['receipt_payment'],
                    'bank_id' => $bank_id,
                    'online_portal_id' => $online_portal_id,
                    'cheque_no' => $cheque_no,
                    'customer_portal_id' => $customer_portal_id,
                    'narration' => $request['narration'],
                    'is_cancelled'=>'0',
                    'status' =>$status,
                    'vendor_id'=>$request['vendor_id'],
                    'entry_by'=> $user,
                    'approved_by'=>$approved_by,
                    'client_id'=>$request['client_id'],
                ]);

                $childaccounts = $request['child_account_id'];
                $remarks = $request['remarks'];
                $debitAmounts = $request['debitAmount'];
                $creditAmounts = $request['creditAmount'];
                $count = count($debitAmounts);
                for($x = 0; $x < $count; $x++)
                {
                    JournalExtra::create([
                        'journal_voucher_id'=>$journal_voucher['id'],
                        'child_account_id'=>$childaccounts[$x],
                        'remarks'=>$remarks[$x],
                        'debitAmount'=>$debitAmounts[$x],
                        'creditAmount'=>$creditAmounts[$x],
                    ]);

                    if($status == 1){
                        $this->openingbalance($childaccounts[$x], $fiscal_year_id, $debitAmounts[$x], $creditAmounts[$x]);
                    }
                }


                $imagename = '';
                if($request->hasfile('file')) {
                    $images = $request->file('file');
                    foreach($images as $image){
                        $imagename = $image->store('jv_images', 'uploads');
                        $journalimage = JournalImage::create([
                            'journalvoucher_id' => $journal_voucher['id'],
                            'location' => $imagename,
                        ]);
                        $journalimage->save();
                    }
                }

                if($request['bank_id'] != null)
                {
                    Reconciliation::create([
                        'jv_id' => $journal_voucher['id'],
                        'bank_id' => $bank_id,
                        'cheque_no' => $cheque_no,
                        'receipt_payment' => $request['receipt_payment'],
                        'amount' => $request['debitTotal'],
                        'cheque_entry_date' => $nepali_date,
                        'vendor_id' => $request['vendor_id'],
                        'client_id'=>$request['client_id'],
                        'other_receipt' => '-'
                    ]);
                }


                $journal_voucher->save();
                DB::commit();
                if($saveandcontinue == 1){
                    return redirect()->back()->with('success', 'Journal Entry is successfully inserted.');
                }

                if ($request['status'] == 0) {
                    return redirect()->route('journals.unapproved')->with('success', 'Journal Entry is successfully inserted.');

                } else {
                    return redirect()->route('journals.index')->with('success', 'Journal Entry is successfully inserted.');
                }

            }else{
                return view('backend.permission.permission');
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if($request->user()->can('view-journals')){
            $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
            $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();

            $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
            $created_nepali_date = datenep($created_at);

            return view('backend.journalvoucher.view', compact('journalVoucher', 'journal_extras', 'created_nepali_date'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if($request->user()->can('edit-journals'))
        {
            $journalVouchers = JournalVouchers::findorFail($id);
            if($journalVouchers->status == 1)
            {
                return redirect()->route('journals.index')->with('error', 'Approved Journals cannot be edited.');
            }
            $superSetting = SuperSetting::first();

            if ($superSetting->journal_edit_number <= $journalVouchers->editcount)
            {
                return redirect()->back()->with('error', 'Journal Voucher cannot be edited more than this.');
            }

            $thisday = date("Y-m-d");
            $entryday = $journalVouchers->entry_date_english;
            $thisdaymilli = strtotime($thisday);
            $entrydaymilli = strtotime($entryday);
            $milliperday = 86400;
            $numofdays = $superSetting->journal_edit_days_limit;
            $totalmilli = $milliperday * $numofdays;
            $diff_bet_days = $thisdaymilli - $entrydaymilli;

            if($diff_bet_days < $totalmilli)
            {
                $journal_extras = JournalExtra::where('journal_voucher_id', $id)->get();
                $journalimage = JournalImage::where('journalvoucher_id', $id)->get();
                $vendors = Vendor::latest()->get();
                $clients = Client::latest()->get();
                $accounts = Account::latest()->get();
                $payment_methods = Paymentmode::where('status', 1)->latest()->get();
                $banks = Bank::latest()->get();
                $online_portals = OnlinePayment::latest()->get();
                return view('backend.journalvoucher.edit', compact('journalVouchers', 'online_portals', 'journal_extras', 'journalimage','vendors', 'accounts', 'payment_methods', 'banks', 'clients'));
            }
            else
            {
                return redirect()->back()->with('error', 'Old Journal! Journal Entry cannot be edited.');
            }
        }
        else
        {
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->can('edit-journals')){
            DB::beginTransaction();
            try{
                $user = Auth::user()->id;
                $journal_voucher = JournalVouchers::findorFail($id);

                if(isset($_POST['save'])){
                    $this->validate($request, [
                        'journal_voucher_no' => 'required',
                        'english_date' => 'required',
                        'child_account_id' => 'required',
                        'code' => '',
                        'remarks' => '',
                        'status'=>'',
                        'debitAmount' => '',
                        'creditAmount' => '',
                        'debitTotal' => 'required',
                        'creditTotal' => 'required',
                        'narration' => 'required',
                        'client_id' =>'',
                    ]);

                    $nepali_date = datenep($request['english_date']);

                    $date_array = explode("-", $nepali_date);

                    if ($date_array[1] < 4) {
                        $last_year = $date_array[0] - 1;
                        $fiscal_year = $last_year . "/" . $date_array[0];
                    } else {
                        $next_year = $date_array[0] + 1;
                        $fiscal_year = $date_array[0] . "/" . $next_year;
                    }

                    $available_fiscal_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();

                    if ($available_fiscal_year) {
                        $fiscal_year_id = $available_fiscal_year->id;
                    } else {
                        $new_fiscal_year = FiscalYear::create([
                            'fiscal_year' => $fiscal_year
                        ]);
                        $fiscal_year_id = $new_fiscal_year->id;
                    }
                    if($request->has('status')){
                        $status = $request['status'];
                    }else{
                        $status = $journal_voucher->status;
                    }
                    if($status=='1'){
                        $approved_by = Auth::user()->id;
                    }else{
                        $approved_by = null;
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

                    $new_count = $journal_voucher->editcount + 1;

                    $journal_voucher->update([
                        'journal_voucher_no' => $request['journal_voucher_no'],
                        'entry_date_english' => $request['english_date'],
                        'entry_date_nepali' => $nepali_date,
                        'fiscal_year_id' => $fiscal_year_id,
                        'status'=>$status,
                        'debitTotal' => $request['debitTotal'],
                        'creditTotal' => $request['creditTotal'],
                        'payment_method' => $request['payment_method'],
                        'receipt_payment' => $request['receipt_payment'],
                        'bank_id' => $bank_id,
                        'online_portal_id' => $online_portal_id,
                        'cheque_no' => $cheque_no,
                        'customer_portal_id' => $customer_portal_id,
                        'narration' => $request['narration'],
                        'vendor_id'=>$request['vendor_id'],
                        'client_id'=>$request['client_id'],
                        'edited_by'=>$user,
                        'editcount'=> $new_count,
                        'approved_by'=>$approved_by,
                    ]);

                    if($bank_id != null)
                    {
                        $bank_reconciliation = Reconciliation::where('jv_id', $journal_voucher->id)->first();
                        if($bank_reconciliation)
                        {
                            $bank_reconciliation->update([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $bank_id,
                                'cheque_no' => $cheque_no,
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['debitTotal'],
                                'cheque_entry_date' => $nepali_date,
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);
                        }
                        else
                        {
                            $bank_reconciliation = Reconciliation::create([
                                'jv_id' => $journal_voucher['id'],
                                'bank_id' => $bank_id,
                                'cheque_no' => $cheque_no,
                                'receipt_payment' => $request['receipt_payment'],
                                'amount' => $request['debitTotal'],
                                'cheque_entry_date' => $nepali_date,
                                'vendor_id' => $request['vendor_id'],
                                'client_id'=>$request['client_id'],
                                'other_receipt' => '-'
                            ]);

                            $bank_reconciliation->save();
                        }
                    }
                    else {
                        $bank_reconciliation = Reconciliation::where('jv_id', $journal_voucher->id)->first();
                        if($bank_reconciliation)
                        {
                            $bank_reconciliation->delete();
                        }
                    }

                    $journal_extra = JournalExtra::where('journal_voucher_id', $id)->get();
                    foreach($journal_extra as $jextra){
                        $opening_balance = OpeningBalance::where('child_account_id', $jextra->child_account_id)->where('fiscal_year_id', $fiscal_year_id)->first();
                        $closing = $opening_balance->closing_balance - $jextra->debitAmount + $jextra->creditAmount;
                        $opening_balance->update([
                            'closing_balance' => $closing,
                        ]);
                        $jextra->delete();
                    }

                    $childaccounts = $request['child_account_id'];
                    $remarks = $request['remarks'];
                    $debitAmounts = $request['debitAmount'];
                    $creditAmounts = $request['creditAmount'];
                    $count = count($debitAmounts);

                    for($x = 0; $x < $count; $x++){
                        $journal_extra= JournalExtra::create([
                            'journal_voucher_id'=>$id,
                            'child_account_id'=>$childaccounts[$x],
                            'remarks'=>$remarks[$x],
                            'debitAmount'=>$debitAmounts[$x],
                            'creditAmount'=>$creditAmounts[$x],
                        ]);
                        if($status == 1){
                            $this->openingbalance($childaccounts[$x], $fiscal_year_id, $debitAmounts[$x], $creditAmounts[$x]);
                        }
                        $journal_extra->save();

                    }

                }
                elseif(isset($_POST['update'])){
                    $this->validate($request, [
                        'file'=>'',
                        'file*.' => 'mimes:png,jpg,jpeg',
                    ]);
                    $imagename = '';
                    if($request->hasfile('file'))
                    {
                        $images = $request->file('file');
                        foreach($images as $image){
                            $imagename = $image->store('jv_images', 'uploads');
                            $journalimage = JournalImage::create([
                                'journalvoucher_id' => $journal_voucher['id'],
                                'location' => $imagename,
                            ]);
                            $journalimage->save();
                        }
                    }
                }
                DB::commit();
                return redirect()->route('journals.index')->with('success', 'Journal Entry is successfully updated.');
            }catch(\Exception $e){
                DB::rollBack();
                throw new \exception($e->getMessage());

            }
        }else{
            return view('backend.permission.permission');
        }
    }

    public function trialbalance(Request $request)
    {
        if($request->user()->can('manage-trial-balance'))
        {
            $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            return view('backend.journalvoucher.trialbalance', compact('current_fiscal_year', 'mainaccounts', 'actual_year', 'fiscal_years'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generatetrialreport(Request $request, $id, $starting_date, $ending_date)
    {
        if($request->user()->can('manage-trial-balance'))
        {
            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            if ($start_date > $end_date) {
                return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
            }

            $start_date_explode = explode("-", $starting_date);
            $end_date_explode = explode("-", $ending_date);

            if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
                return redirect()->back()->with('error', 'Select dates within a fiscal year.');
            }

            $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();

            $current_fiscal_year = FiscalYear::where('id', $id)->first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::all();
            $actual_date = $actual_year[0]. '-4-1';
            $actual_eng_date = dateeng($actual_date);

            return view('backend.journalvoucher.trialbalancereport', compact('mainaccounts', 'fiscal_years', 'current_fiscal_year', 'actual_year', 'id', 'start_date', 'end_date', 'starting_date', 'ending_date', 'actual_eng_date'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function extra(Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $fiscal_year = $request['fiscal_year'];
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];
            $current_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();
            return redirect()->route('generatereport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date]);
        }else{
            return view('backend.permission.permission');
        }
    }

    public function trialextra(Request $request)
    {
        if($request->user()->can('manage-trial-balance'))
        {
            $fiscal_year = $request['fiscal_year'];
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];
            $current_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();
            return redirect()->route('generatetrialreport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date]);
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generatereport(Request $request, $id, $starting_date, $ending_date)
    {
        if($request->user()->can('view-journals'))
        {
            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            // dd($end_date);
            if ($start_date > $end_date) {
                return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
            }

            $start_date_explode = explode("-", $starting_date);
            $end_date_explode = explode("-", $ending_date);

            if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
                return redirect()->back()->with('error', 'Select dates within a fiscal year.');
            }
            // $journalvouchers = JournalVouchers::latest()->where('fiscal_year_id', $id)->where('entry_date_english', '>=', $start_date)->where('entry_date_english', '<=', $end_date)->where('is_cancelled', '0')->get();
            // dd($journalvouchers);
            if ($request->ajax()) {
                $data = JournalVouchers::with('journal_extras')->latest()->where('fiscal_year_id', $id)->where('entry_date_english', '>=', $start_date)->where('entry_date_english', '<=', $end_date)->where('is_cancelled', '0');
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('particulars', function($row){
                        $particulars = '';
                        foreach ($row->journal_extras as $jextra) {
                            $particulars = $particulars . $jextra->child_account->title . '<br>';
                        }
                        return $particulars;
                    })
                    ->addColumn('debit', function($row){
                        $debit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->debitAmount == 0) {
                                $debit_amounts = $debit_amounts . '-' . '<br>';
                            } else {
                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                            }
                        }
                        return $debit_amounts;
                    })
                    ->addColumn('credit', function($row){
                        $credit_amounts = '';
                        foreach ($row->journal_extras as $jextra) {
                            if ($jextra->creditAmount == 0) {
                                $credit_amounts = $credit_amounts . '-' . '<br>';
                            } else {
                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                            }
                        }
                        return $credit_amounts;
                    })
                    ->editColumn('status', function($row){
                        if ($row->status == '1') {
                            $status = 'Approved';
                        } else {
                            $status = 'Awaiting for Approval';
                        }
                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $showurl = route('journals.show', $row->id);
                        $editurl = route('journals.edit', $row->id);
                        $statusurl = route('journals.status', $row->id);
                        $cancellationurl = route('journals.cancel', $row->id);
                        $csrf_token = csrf_token();
                        if ($row->status == 1) {
                            $btnname = 'fa fa-thumbs-down';
                            $btnclass = 'btn-info';
                            $title = 'Disapprove';
                            $btn = " <div class='btn-bulk'>
                                        <a href='{{ route('journals.print', $row->id) }}' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                        <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' name = '$title' class='btn $btnclass btn-primary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                        </form>
                                        <!-- Modal -->
                                            <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <p>Please give reason for Cancellation</p>
                                                        <hr>
                                                        <form action='$cancellationurl' method='POST'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                            <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                            <div class='form-group'>
                                                                <label for='reason'>Reason:</label>
                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <label for='description'>Description: </label>
                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                            </div>
                                                            <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                        } else {
                            $btnname = 'fa fa-thumbs-up';
                            $btnclass = 'btn-info';
                            $title = 'Approve';
                            $btn = "<div class='btn-bulk'>
                                        <a href='{{ route('journals.print', $row->id) }}' class='btn btn-secondary icon-btn btnprn' title='Print' ><i class='fa fa-print'></i> </a>
                                        <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                        <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                        <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                        <form action='$statusurl' method='POST' style='display:inline-block'>
                                        <input type='hidden' name='_token' value='$csrf_token'>
                                            <button type='submit' name = '$title' class='btn $btnclass btn-secondary icon-btn btn-sm text-light' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                        </form>
                                        <!-- Modal -->
                                            <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        <p>Please give reason for Cancellation</p>
                                                        <hr>
                                                        <form action='$cancellationurl' method='POST'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                            <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                            <div class='form-group'>
                                                                <label for='reason'>Reason:</label>
                                                                <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                            </div>
                                                            <div class='form-group'>
                                                                <label for='description'>Description: </label>
                                                                <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                            </div>
                                                            <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                        }

                        return $btn;
                    })
                    ->rawColumns(['particulars', 'debit', 'credit', 'status','action'])
                    ->make(true);
            }
            $current_fiscal_year = FiscalYear::where('id', $id)->first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::all();
            return view('backend.journalvoucher.report', compact('fiscal_years', 'current_fiscal_year', 'actual_year', 'id', 'starting_date', 'ending_date', 'start_date', 'end_date'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function printpreview($id, Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
            $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();

            $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
            $created_nepali_date = datenep($created_at);
            $setting = Setting::first();
            $currentcomp = UserCompany::with('company')->where('user_id', Auth::user()->id)->where('is_selected', 1)->first();
            return view('backend.journalvoucher.printpreview', compact('journalVoucher', 'currentcomp', 'journal_extras', 'created_nepali_date', 'setting'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateJournalPDF($id, Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
            $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();
            $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadjournal', compact('journalVoucher', 'currentcomp', 'journal_extras', 'created_nepali_date', 'setting', 'path_img'));
            return $pdf->download('Journals ('.$journalVoucher->journal_voucher_no.').pdf');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateTrialBalancePDF(Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
            $fiscal_years = FiscalYear::all();

            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();

            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadtrialbalance', compact(
                'mainaccounts',
                'currentcomp',
                'fiscal_years',
                'actual_year',
                'current_fiscal_year',
                'setting',
                'path_img'));
            return $pdf->download($current_fiscal_year->fiscal_year.'_trial_balance_report.pdf');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function generateTrialBalanceReportPDF($id, $starting_date, $ending_date, Request $request)
    {
        if($request->user()->can('manage-trial-balance'))
        {
            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadtrialbalancereport',
            compact(
                'mainaccounts',
                'currentcomp',
                'current_fiscal_year',
                'setting',
                'start_date',
                'end_date',
                'starting_date',
                'ending_date',
                'path_img'
            ));
            return $pdf->download($start_date . ' to '. $end_date.' trial_balance_report_filter.pdf');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function ledgers(Request $request)
    {
        if($request->user()->can('manage-ledgers'))
        {
            $accounts = Account::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::all();
            return view('backend.journalvoucher.ledgers', compact('accounts', 'fiscal_years', 'current_fiscal_year', 'actual_year'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateledgers(Request $request)
    {
        if($request->user()->can('manage-ledgers'))
        {
            $journal_extras = JournalExtra::where('child_account_id', $request['child_account_id'])
                                ->whereHas('journal_voucher', function($q){
                                    $q->orderBy('id', 'ASC');
                                })->get();
            $childAccount = ChildAccount::where('id', $request['child_account_id'])->first();
            $accounts = Account::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();

            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::all();

            $selected_fiscal_year = $request['fiscal_year'];
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];
            $selected_year = FiscalYear::where('fiscal_year', $selected_fiscal_year)->first();

            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            if ($start_date > $end_date) {
                return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
            }

            $start_date_explode = explode("-", $starting_date);
            $end_date_explode = explode("-", $ending_date);

            if(($end_date_explode[0] - $start_date_explode[0]) > 1) {
                return redirect()->back()->with('error', 'Select dates within a fiscal year.');
            }

            $opening_balance = 0;
            foreach ($journal_extras as $extra)
            {
                $previous_journal = JournalVouchers::where('id', $extra->journal_voucher_id)
                                                    ->where('entry_date_english', '<=', $start_date)
                                                    ->where('is_cancelled', 0)
                                                    ->where('status', 1)
                                                    ->first();
                if($previous_journal)
                {
                    $opening_balance = $opening_balance + $extra->debitAmount - $extra->creditAmount;
                }
            }

            $main_opening_balance = $childAccount->opening_balance + $opening_balance;

            return view('backend.journalvoucher.generateledgers',
                    compact('accounts',
                            'journal_extras',
                            'childAccount',
                            'fiscal_years',
                            'current_fiscal_year',
                            'actual_year',
                            'start_date',
                            'end_date',
                            'selected_year',
                            'starting_date',
                            'ending_date',
                            'main_opening_balance',
                        ));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateLedgerReportPDF($fiscal_year_id, $id, $starting_date, $ending_date, Request $request)
    {
        if($request->user()->can('manage-ledgers'))
        {
            $journal_extras = JournalExtra::where('child_account_id', $id)
                                ->whereHas('journal_voucher', function($q){
                                    $q->orderBy('id', 'ASC');
                                })->get();
            $childAccount = ChildAccount::findorFail($id);

            $selected_fiscal_year = FiscalYear::findorFail($fiscal_year_id);

            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);

            $setting = Setting::first();

            $opening_balance = 0;
            foreach ($journal_extras as $extra)
            {
                $previous_journal = JournalVouchers::where('id', $extra->journal_voucher_id)
                                                    ->where('entry_date_english', '<=', $start_date)
                                                    ->where('is_cancelled', 0)
                                                    ->where('status', 1)
                                                    ->first();
                if($previous_journal)
                {
                    $opening_balance = $opening_balance + $extra->debitAmount - $extra->creditAmount;
                }
            }

            $main_opening_balance = $childAccount->opening_balance + $opening_balance;

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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadledger',
                compact(
                    'journal_extras',
                    'selected_fiscal_year',
                    'currentcomp',
                    'childAccount',
                    'start_date',
                    'end_date',
                    'starting_date',
                    'ending_date',
                    'path_img',
                    'main_opening_balance',
                    'setting',
                ));
            return $pdf->download($childAccount->title. ' Account.pdf');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function profitandloss(Request $request)
    {
        if($request->user()->can('manage-profit-and-loss'))
        {
            $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();
            $date_in_eng = date('Y-m-d');
            $date_in_nep = datenep($date_in_eng);
            $fiscal_years = FiscalYear::all();

            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();

            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            return view('backend.journalvoucher.profitandloss', compact('sub_accounts', 'date_in_nep', 'current_fiscal_year', 'fiscal_years', 'actual_year'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function profitandlossfilter(Request $request)
    {
        if($request->user()->can('manage-profit-and-loss'))
        {
            $selected_fiscal_year = FiscalYear::where('fiscal_year', $request['fiscal_year'])->first();
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];

            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            if ($start_date > $end_date) {
                return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
            }

            $start_date_explode = explode("-", $starting_date);
            $end_date_explode = explode("-", $ending_date);

            if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
                return redirect()->back()->with('error', 'Select dates within a fiscal year.');
            }

            $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();
            $fiscal_years = FiscalYear::all();

            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);

            return view('backend.journalvoucher.profitandlossfilter', compact(
                                'sub_accounts',
                                'current_fiscal_year',
                                'fiscal_years',
                                'start_date',
                                'end_date',
                                'starting_date',
                                'ending_date',
                                'selected_fiscal_year',
                                'actual_year')
                            );
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateProfitandLossPDF(Request $request)
    {
        if($request->user()->can('manage-profit-and-loss'))
        {
            $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();
            $date_in_eng = date('Y-m-d');
            $date_in_nep = datenep($date_in_eng);
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();

            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.profitandlosspdf', compact(
                'sub_accounts',
                'currentcomp',
                'date_in_nep',
                'fiscal_years',
                'actual_year',
                'current_fiscal_year',
                'setting',
                'path_img'));

            return $pdf->download($current_fiscal_year->fiscal_year. ' profit_and_loss.pdf');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateProfitandLossReportPDF($id, $starting_date, $ending_date, Request $request)
    {
        // dd('HI');exit;
        if($request->user()->can('manage-profit-and-loss'))
        {
            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);

            $sub_accounts = SubAccount::whereIn('id', [8,9,10,13,11,12])->get();

            $selected_fiscal_year = FiscalYear::findorFail($id);
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

            $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadprofitandlossreport',
            compact(
                'sub_accounts',
                'selected_fiscal_year',
                'currentcomp',
                'setting',
                'start_date',
                'end_date',
                'starting_date',
                'ending_date',
                'path_img'
            ));
            return $pdf->download($start_date. ' to '. $end_date. ' profitandloss_report_filter.pdf');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function balancesheet(Request $request)
    {
        if($request->user()->can('manage-balance-sheet'))
        {
            $main_accounts = Account::whereIn('id', [1,2,4])->get();
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);

            // Profit and Loss Calculation
            $main_sub_accounts = [8,9,10,13,11,12];
            $sub_accounts_inside = [];
            foreach($main_sub_accounts as $subAccount){
                $subchild_accounts = SubAccount::where('sub_account_id', $subAccount)->get();
                foreach($subchild_accounts as $subchild){
                    array_push($sub_accounts_inside, $subchild->id);
                }
            }
            $sub_accounts_id = array_merge($main_sub_accounts, $sub_accounts_inside);
            $sub_accounts = SubAccount::whereIn('id', $sub_accounts_id)->get();

            $all_debit_amounts = [];
            $all_credit_amounts = [];
            $all_opening_amount = [];

            foreach ($sub_accounts as $sub_account) {
                $child_accounts = ChildAccount::where('sub_account_id', $sub_account->id)->get();

                foreach ($child_accounts as $child_account) {
                    $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                            $q->where('is_cancelled', 0)->where('status', 1);
                            $q->where('fiscal_year_id', $current_fiscal_year->id);
                        })
                        ->where('child_account_id', $child_account->id)->get();

                        foreach ($journal_extras as $jextra) {
                            array_push($all_debit_amounts, $jextra->debitAmount);
                            array_push($all_credit_amounts, $jextra->creditAmount);
                    }
                    $openingbalance = OpeningBalance::where('child_account_id', $child_account->id)->where('fiscal_year_id', $current_fiscal_year->id)->first();
                    array_push($all_opening_amount, $openingbalance->opening_balance ?? 0);
                }

            }

            $all_opening_balance_sum = array_sum($all_opening_amount);
            $all_debit_sum = array_sum($all_debit_amounts);
            $all_credit_sum = array_sum($all_credit_amounts);
            $profit_loss = $all_opening_balance_sum + $all_debit_sum - $all_credit_sum;
            // Profit and Loss End

            // Dividend Calculation
            $dividend_child_account = ChildAccount::where('slug', 'dividend')->first();
            $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                $q->where('is_cancelled', 0)->where('status', 1);
                $q->where('fiscal_year_id', $current_fiscal_year->id);
            })
            ->where('child_account_id', $dividend_child_account->id)->get();

            $dividend_openingamount = OpeningBalance::where('child_account_id', $dividend_child_account->id)->where('fiscal_year_id',$current_fiscal_year->id)->first()->opening_balance ?? 0;
            $dividend_debitamount = [];
            $dividend_creditamount = [];
            foreach ($journal_extras as $jextra) {
                array_push($dividend_debitamount, $jextra->debitAmount);
                array_push($dividend_creditamount, $jextra->creditAmount);
            }

            $dividend_debitsum = array_sum($dividend_debitamount);
            $dividend_creditsum = array_sum($dividend_creditamount);
            $dividend_diff_amount = $dividend_openingamount + $dividend_debitsum - $dividend_creditsum;
            // End Dividend Calculation

            // Retained Earnings Calculation
            $retained_earnings = $profit_loss - $dividend_diff_amount;

            $today_date_english = date('Y-m-d');
            $today_date_nepali = datenep($today_date_english);

            // Queries
            $subAccounts = SubAccount::all();
            $child_accounts = ChildAccount::get();
            $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use ($current_fiscal_year) {
                                    $q->where('is_cancelled', 0)->where('status', 1);
                                    $q->where('fiscal_year_id', $current_fiscal_year->id);
                                })->get();

            return view('backend.journalvoucher.balancesheet',
                        compact('main_accounts',
                                'current_fiscal_year',
                                'dividend_child_account',
                                'dividend_diff_amount',
                                'retained_earnings',
                                'today_date_nepali',
                                'fiscal_years',
                                'actual_year',
                                'subAccounts',
                                'child_accounts',
                                'journal_extras'
                            ));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function balancesheetfilter(Request $request)
    {
        if($request->user()->can('manage-balance-sheet'))
        {
            $selected_fiscal_year = FiscalYear::where('fiscal_year', $request['fiscal_year'])->first();
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];

            $start_date = dateeng($starting_date);
            $end_date = dateeng($ending_date);
            if ($start_date > $end_date) {
                return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
            }

            $start_date_explode = explode("-", $starting_date);
            $end_date_explode = explode("-", $ending_date);

            if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
                return redirect()->back()->with('error', 'Select dates within a fiscal year.');
            }

            $main_accounts = Account::whereIn('id', [1,2,4])->get();
            $fiscal_years = FiscalYear::latest()->get();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);

            // Profit and Loss Calculation
            $main_sub_accounts = [8,9,10,13,11,12];
            $sub_accounts_inside = [];
            foreach($main_sub_accounts as $subAccount){
                $subchild_accounts = SubAccount::where('sub_account_id', $subAccount)->get();
                foreach($subchild_accounts as $subchild){
                    array_push($sub_accounts_inside, $subchild->id);
                }
            }
            $sub_accounts_id = array_merge($main_sub_accounts, $sub_accounts_inside);
            $sub_accounts = SubAccount::whereIn('id', $sub_accounts_id)->get();

            $all_debit_amounts = [];
            $all_credit_amounts = [];
            $all_opening_amount = [];

            foreach ($sub_accounts as $sub_account) {
                $child_accounts = ChildAccount::where('sub_account_id', $sub_account->id)->get();

                foreach ($child_accounts as $child_account) {
                    $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q)
                                                            use($selected_fiscal_year, $start_date, $end_date)
                                                            {
                                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                                $q->where('entry_date_english', '>=', $start_date);
                                                                $q->where('entry_date_english', '<=', $end_date);
                                                            })
                        ->where('child_account_id', $child_account->id)->get();

                        foreach ($journal_extras as $jextra) {
                            array_push($all_debit_amounts, $jextra->debitAmount);
                            array_push($all_credit_amounts, $jextra->creditAmount);
                    }
                    $openingbalance = OpeningBalance::where('child_account_id', $child_account->id)->where('fiscal_year_id', $selected_fiscal_year->id)->first();
                    array_push($all_opening_amount, $openingbalance->opening_balance ?? 0);
                }
            }

            $all_opening_balance_sum = array_sum($all_opening_amount);
            $all_debit_sum = array_sum($all_debit_amounts);
            $all_credit_sum = array_sum($all_credit_amounts);
            $profit_loss =  $all_opening_balance_sum + $all_debit_sum - $all_credit_sum;
            // Profit and Loss End

            // Dividend Calculation
            $dividend_child_account = ChildAccount::where('slug', 'dividend')->first();
            $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q)
                                                use($selected_fiscal_year, $start_date, $end_date)
                                                {
                                                    $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                    $q->where('entry_date_english', '>=', $start_date);
                                                    $q->where('entry_date_english', '<=', $end_date);
                                                })
            ->where('child_account_id', $dividend_child_account->id)->get();

            $dividend_debitamount = [];
            $dividend_creditamount = [];
            foreach ($journal_extras as $jextra) {
                array_push($dividend_debitamount, $jextra->debitAmount);
                array_push($dividend_creditamount, $jextra->creditAmount);
            }

            $dividend_openingamount = OpeningBalance::where('child_account_id', $dividend_child_account->id)->where('fiscal_year_id',$selected_fiscal_year->id)->first()->opening_balance ?? 0;
            $dividend_debitsum = array_sum($dividend_debitamount);
            $dividend_creditsum = array_sum($dividend_creditamount);
            $dividend_diff_amount = $dividend_openingamount + $dividend_debitsum - $dividend_creditsum;
            // End Dividend Calculation

            // Retained Earnings Calculation
            $retained_earnings = $profit_loss - $dividend_diff_amount;


            return view('backend.journalvoucher.balancesheetfilter',
                        compact('main_accounts',
                                'current_fiscal_year',
                                'dividend_child_account',
                                'dividend_diff_amount',
                                'retained_earnings',
                                'fiscal_years',
                                'actual_year',
                                'starting_date',
                                'ending_date',
                                'start_date',
                                'end_date',
                                'selected_fiscal_year'
                            ));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function generateBalanceSheetPDF()
    {
        $main_accounts = Account::whereIn('id', [1,2,4])->get();
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $current_fiscal_year = FiscalYear::first();

        // Profit and Loss Calculation
        $main_sub_accounts = [8,9,10,13,11,12];
        $sub_accounts_inside = [];
        foreach($main_sub_accounts as $subAccount){
            $subchild_accounts = SubAccount::where('sub_account_id', $subAccount)->get();
            foreach($subchild_accounts as $subchild){
                array_push($sub_accounts_inside, $subchild->id);
            }
        }
        $sub_accounts_id = array_merge($main_sub_accounts, $sub_accounts_inside);
        $sub_accounts = SubAccount::whereIn('id', $sub_accounts_id)->get();
        $all_debit_amounts = [];
        $all_credit_amounts = [];
        $all_opening_amount = [];

        foreach ($sub_accounts as $sub_account) {
            $child_accounts = ChildAccount::where('sub_account_id', $sub_account->id)->get();

            foreach ($child_accounts as $child_account) {
                $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                        $q->where('is_cancelled', 0)->where('status', 1);
                        $q->where('fiscal_year_id', $current_fiscal_year->id);
                    })
                    ->where('child_account_id', $child_account->id)->get();

                    foreach ($journal_extras as $jextra) {
                        array_push($all_debit_amounts, $jextra->debitAmount);
                        array_push($all_credit_amounts, $jextra->creditAmount);
                }
                $openingbalance = OpeningBalance::where('child_account_id', $child_account->id)->where('fiscal_year_id', $current_fiscal_year->id)->first();
                array_push($all_opening_amount, $openingbalance->opening_balance ?? 0);
            }
        }

        $all_opening_balance_sum = array_sum($all_opening_amount);
        $all_debit_sum = array_sum($all_debit_amounts);
        $all_credit_sum = array_sum($all_credit_amounts);
        $profit_loss =  $all_opening_balance_sum + $all_debit_sum - $all_credit_sum;
        // Profit and Loss End

        // Dividend Calculation
        $dividend_child_account = ChildAccount::where('slug', 'dividend')->first();
        $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
            $q->where('is_cancelled', 0)->where('status', 1);
            $q->where('fiscal_year_id', $current_fiscal_year->id);
        })
        ->where('child_account_id', $dividend_child_account->id)->get();

        $dividend_openingamount = OpeningBalance::where('child_account_id', $dividend_child_account->id)->where('fiscal_year_id',$current_fiscal_year->id)->first()->opening_balance ?? 0;
        $dividend_debitamount = [];
        $dividend_creditamount = [];
        foreach ($journal_extras as $jextra) {
            array_push($dividend_debitamount, $jextra->debitAmount);
            array_push($dividend_creditamount, $jextra->creditAmount);
        }

        $dividend_debitsum = array_sum($dividend_debitamount);
        $dividend_creditsum = array_sum($dividend_creditamount);
        $dividend_diff_amount = $dividend_openingamount + $dividend_debitsum - $dividend_creditsum;
        // End Dividend Calculation

        // Retained Earnings Calculation
        $retained_earnings = $profit_loss - $dividend_diff_amount;

        $today_date_english = date('Y-m-d');
        $today_date_nepali = datenep($today_date_english);

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

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.balancesheetpdf', compact(
                                                                        'main_accounts',
                                                                        'current_fiscal_year',
                                                                        'currentcomp',
                                                                        'dividend_child_account',
                                                                        'dividend_diff_amount',
                                                                        'retained_earnings',
                                                                        'today_date_nepali',
                                                                        'setting',
                                                                        'path_img'
                                                                    ));
        return $pdf->download($current_fiscal_year->fiscal_year.' balance_sheet.pdf');
    }

    public function generateBalanceSheetReportPDF($id, $starting_date, $ending_date)
    {
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);

        $selected_fiscal_year = FiscalYear::findorFail($id);
        $setting = Setting::first();

        $main_accounts = Account::whereIn('id', [1,2,4])->get();

        // Profit and Loss Calculation
        $main_sub_accounts = [8,9,10,13,11,12];
        $sub_accounts_inside = [];
        foreach($main_sub_accounts as $subAccount){
            $subchild_accounts = SubAccount::where('sub_account_id', $subAccount)->get();
            foreach($subchild_accounts as $subchild){
                array_push($sub_accounts_inside, $subchild->id);
            }
        }
        $sub_accounts_id = array_merge($main_sub_accounts, $sub_accounts_inside);
        $sub_accounts = SubAccount::whereIn('id', $sub_accounts_id)->get();

        $all_debit_amounts = [];
        $all_credit_amounts = [];
        $all_opening_amount = [];

        foreach ($sub_accounts as $sub_account) {
            $child_accounts = ChildAccount::where('sub_account_id', $sub_account->id)->get();

            foreach ($child_accounts as $child_account) {
                $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q)
                                                        use($selected_fiscal_year, $start_date, $end_date)
                                                        {
                                                            $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                            $q->where('entry_date_english', '>=', $start_date);
                                                            $q->where('entry_date_english', '<=', $end_date);
                                                        })
                    ->where('child_account_id', $child_account->id)->get();

                    foreach ($journal_extras as $jextra) {
                        array_push($all_debit_amounts, $jextra->debitAmount);
                        array_push($all_credit_amounts, $jextra->creditAmount);
                }
                $openingbalance = OpeningBalance::where('child_account_id', $child_account->id)->where('fiscal_year_id', $selected_fiscal_year->id)->first();
                array_push($all_opening_amount, $openingbalance->opening_balance ?? 0);
            }
        }

        $all_opening_balance_sum = array_sum($all_opening_amount);
        $all_debit_sum = array_sum($all_debit_amounts);
        $all_credit_sum = array_sum($all_credit_amounts);
        $profit_loss = $all_opening_balance_sum + $all_debit_sum - $all_credit_sum;
        // Profit and Loss Calculation End

        // Dividend Calculation
        $dividend_child_account = ChildAccount::where('slug', 'dividend')->first();
        $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q)
                                            use($selected_fiscal_year, $start_date, $end_date)
                                            {
                                                $q->where('fiscal_year_id', '=', $selected_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                $q->where('entry_date_english', '>=', $start_date);
                                                $q->where('entry_date_english', '<=', $end_date);
                                            })
        ->where('child_account_id', $dividend_child_account->id)->get();

        $dividend_debitamount = [];
        $dividend_creditamount = [];
        foreach ($journal_extras as $jextra) {
            array_push($dividend_debitamount, $jextra->debitAmount);
            array_push($dividend_creditamount, $jextra->creditAmount);
        }

        $dividend_openingamount = OpeningBalance::where('child_account_id', $dividend_child_account->id)->where('fiscal_year_id',$selected_fiscal_year->id)->first()->opening_balance ?? 0;
        $dividend_debitsum = array_sum($dividend_debitamount);
        $dividend_creditsum = array_sum($dividend_creditamount);
        $dividend_diff_amount = $dividend_openingamount + $dividend_debitsum - $dividend_creditsum;
        // End Dividend Calculation

        // Retained Earnings Calculation
        $retained_earnings = $profit_loss - $dividend_diff_amount;

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
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;;

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.balancesheetreportpdf',

        compact(
            'main_accounts',
            'dividend_child_account',
            'currentcomp',
            'dividend_diff_amount',
            'retained_earnings',
            'selected_fiscal_year',
            'setting',
            'start_date',
            'end_date',
            'starting_date',
            'ending_date',
            'path_img'
        ));
        return $pdf->download($start_date. ' to '. $end_date. ' balancesheet_report_filter.pdf');
    }

    public function bankReconciliationStatement(Request $request)
    {
        if($request->user()->can('manage-reconciliation-statement'))
        {
            return view('backend.journalvoucher.bankReconciliationStatement');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function openingbalance($childaccounts, $fiscal_year_id, $debitAmounts, $creditAmounts){
        $opening_balance = OpeningBalance::where('child_account_id', $childaccounts)->where('fiscal_year_id', $fiscal_year_id)->first();
        // dd($opening_balance);
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
