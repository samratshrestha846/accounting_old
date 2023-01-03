<?php

namespace App\Http\Controllers;

use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\OpeningBalance;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DataTables;
Use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChildAccountController extends Controller
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
        if($request->user()->can('view-accounts')){
            $childaccounts = ChildAccount::latest()->paginate(10);
            return view('backend.childaccount.index', compact('childaccounts'));
        }else{
            return view('backend.permission.permission');
        }

    }

    public function deletedchildindex(Request $request)
    {
        if($request->user()->can('manage-trash')){
            if ($request->ajax()) {
                $data = ChildAccount::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row)
                    {
                        $restoreurl = route('restorechildaccount', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restorechild$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restorechild$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body text-center'>
                                                    <label for='reason'>Are you sure you want to restore??</label><br>
                                                    <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";

                        return $btn;
                    })
                    ->addColumn('sub_account', function($row) {
                        $sub_account = SubAccount::where('id', $row->sub_account_id)->first();
                        if(!$sub_account) {
                            $sub_account = SubAccount::where('id', $row->sub_account_id)->onlyTrashed()->first();
                        }
                        return $sub_account->title;
                    })
                    ->rawColumns(['sub_account', 'action'])
                    ->make(true);
            }
            return view('backend.trash.accountstrash');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request){
        $search = $request->input('search');

        $childaccounts = ChildAccount::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('backend.childaccount.search', compact('childaccounts'));
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
        if($request->user()->can('create-account')){
            $this->validate($request, [
                'title' => 'required',
                'sub_account_id' => 'required',
                'opening_balance' => '',
                'behaviour' => '',
                'fiscal_year_id' => 'required',
            ]);

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

                $new_childaccount = ChildAccount::create([
                    'title' => $request['title'],
                    'sub_account_id' => $request['sub_account_id'],
                    'slug' => Str::slug($request['title']),
                    'opening_balance' => $opening_balance
                ]);

                $opening_balance = OpeningBalance::create([
                    'child_account_id' => $new_childaccount['id'],
                    'fiscal_year_id' => $request['fiscal_year_id'],
                    'opening_balance' => $opening_balance,
                    'closing_balance' => $opening_balance,
                ]);
                $new_childaccount->save();
                $opening_balance->save();
              DB::commit();
                if($request->ajax()){
                    return $new_childaccount;
                }
                return redirect()->back()->with('success', 'Child Account information is saved successfully.');
            }catch(\EXception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function show(ChildAccount $childAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(ChildAccount $childAccount, Request $request)
    {
        if($request->user()->can('edit-account')){
            $sub_accounts = SubAccount::latest()->get();
            $fiscal_years = FiscalYear::all();
            return view('backend.childaccount.edit', compact('sub_accounts', 'childAccount', 'fiscal_years'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChildAccount $childAccount)
    {
        if($request->user()->can('edit-account')){
            $this->validate($request, [
                'title' => 'required',
                'sub_account_id' => 'required',
                'opening_balance' => '',
                'fiscal_year_id' => 'required'
            ]);

            $journal_extras = JournalExtra::where('child_account_id', $childAccount->id)->whereHas('journal_voucher', function($query) use ($request){
                $query->where('status', 1)->where('is_cancelled', 0);
                $query->where('fiscal_year_id', $request['fiscal_year_id']);
            })->get();
            // dd($journal_extras);

            $debitAmounts = [];
            $creditAmounts = [];
            foreach($journal_extras as $jextra){
                array_push($debitAmounts, $jextra->debitAmount);
                array_push($creditAmounts, $jextra->creditAmount);
            }

            $debitsum = array_sum($debitAmounts);
            $creditsum = array_sum($creditAmounts);

            if($request['behaviour'] == "credit")
            {
                $opening_balance = '-'.$request['opening_balance'];
            }
            elseif($request['behaviour'] == "debit")
            {
                $opening_balance = $request['opening_balance'];
            }

            $closingbalance = $opening_balance + $debitsum - $creditsum;
            DB::beginTransaction();
            try{
                $childAccount->update([
                    'title' => $request['title'],
                    'sub_account_id' => $request['sub_account_id'],
                    'slug' => Str::slug($request['title']),
                    'opening_balance' => $opening_balance
                ]);


                $openingbalance = OpeningBalance::updateOrCreate(
                    ['child_account_id' => $childAccount['id'], 'fiscal_year_id' => $request['fiscal_year_id']],
                    ['opening_balance' => $opening_balance, 'closing_balance' => $closingbalance]
                );
                DB::commit();
                return redirect()->route('child_account.index')->with('success', 'Child Account information is updated successfully.');
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());

            }
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChildAccount $childAccount, Request $request)
    {
        if($request->user()->can('remove-account')){
            $childAccount->delete();
            return redirect()->route('child_account.index')->with('success', 'Child Account information is deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restorechildaccount($id, Request $request)
    {
        if($request->user()->can('remove-account'))
        {
            $child_account = ChildAccount::onlyTrashed()->findorFail($id);

            $sub_account = SubAccount::onlyTrashed()->where('id', $child_account->sub_account_id)->first();
            if($sub_account)
            {
                return redirect()->back()->with('error', 'Sub Account Type is not present or is soft deleted. Check Sub Account.');
            }
            $child_account->restore();
            return redirect()->route('child_account.index')->with('success', 'Child Account type is restored successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
