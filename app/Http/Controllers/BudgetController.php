<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use Illuminate\Http\Request;

use function App\NepaliCalender\dateeng;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function budgetsetup(Request $request)
    {
        if($request->user()->can('budget-setup'))
        {
            $accounts = Account::latest()->get();
            $fiscal_years = FiscalYear::all();
            $current_fiscal_year = FiscalYear::latest()->first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            return view('backend.budget.budgetsetup', compact('accounts', 'fiscal_years', 'current_fiscal_year', 'actual_year'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function saveinfo(Request $request)
    {
        $this->validate($request, [
            'child_account_id' => 'required',
            'fiscal_year' => 'required',
            'starting_date' => 'required',
            'ending_date' => 'required',
            'budget_amount' => 'required',
            'details' => 'required'
        ]);

        $starting_date_english = dateeng($request['starting_date']);
        $ending_date_english = dateeng($request['ending_date']);

        $last_allocation = Budget::where('child_account_id', $request['child_account_id'])->first();
        DB::beginTransaction();
        try {
        if ($last_allocation) {
            if ($starting_date_english < $last_allocation->ending_date_english)
            {
                return redirect()->back()->with('error', 'Already allocated budget within selected time period. Can renew or edit.');
            }
            else
            {
                $budget_balance = $last_allocation->budget_balance + $request['budget_amount'];

                Budget::create([
                    'child_account_id' => $request['child_account_id'],
                    'fiscal_year' => $request['fiscal_year'],
                    'starting_date_english' => $starting_date_english,
                    'starting_date_nepali' => $request['starting_date'],
                    'ending_date_english' => $ending_date_english,
                    'ending_date_nepali' => $request['ending_date'],
                    'budget_allocated' => $request['budget_amount'],
                    'budget_balance' => $budget_balance,
                    'details' => $request['details'],
                ]);

                return redirect()->route('budgetinfo')->with('success', 'Successfully allocated budget.');
            }
        }
        else
        {
            Budget::create([
                'child_account_id' => $request['child_account_id'],
                'fiscal_year' => $request['fiscal_year'],
                'starting_date_english' => $starting_date_english,
                'starting_date_nepali' => $request['starting_date'],
                'ending_date_english' => $ending_date_english,
                'ending_date_nepali' => $request['ending_date'],
                'budget_allocated' => $request['budget_amount'],
                'budget_balance' => $request['budget_amount'],
                'details' => $request['details'],
            ]);
            DB::commit();
            return redirect()->route('budgetinfo')->with('success', 'Successfully allocated budget.');
        }
    }catch(\Exception $e){
        DB::rollBack();
        throw new \Exception($e->getMessage());
    }
    }

    public function budgetinfo(Request $request)
    {
        if($request->user()->can('manage-budget-report')){
            $budgets = Budget::latest()->paginate(10);
            return view('backend.budget.bugetlist', compact('budgets'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $budgets = Budget::query()
            ->where('budget_allocated', 'LIKE', "%{$search}%")
            ->orWhere('starting_date_nepali', 'LIKE', "%{$search}%")
            ->orWhere('ending_date_nepali', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('backend.budget.search', compact('budgets'));
    }

    public function editbudget($id, Request $request)
    {
        if($request->user()->can('budget-setup'))
        {
            $budget_info = Budget::findorFail($id);
            $fiscal_years = FiscalYear::latest()->get();
            $accounts = Account::latest()->get();
            return view('backend.budget.edit', compact('accounts', 'budget_info', 'fiscal_years'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function updatebudget($id, Request $request)
    {
        $budget_info = Budget::findorFail($id);

        $this->validate($request, [
            'child_account_id' => 'required',
            'fiscal_year' => 'required',
            'starting_date' => 'required',
            'ending_date' => 'required',
            'budget_amount' => 'required',
            'details' => 'required',
        ]);

        $starting_date_english = dateeng($request['starting_date']);
        $ending_date_english = dateeng($request['ending_date']);
        DB::beginTransaction();
        try{
            $budget_info->update([
                'child_account_id' => $request['child_account_id'],
                'fiscal_year' => $request['fiscal_year'],
                'starting_date_english' => $starting_date_english,
                'starting_date_nepali' => $request['starting_date'],
                'ending_date_english' => $ending_date_english,
                'ending_date_nepali' => $request['ending_date'],
                'budget_allocated' => $request['budget_amount'],
                'details' => $request['details'],
            ]);
            DB::commit();

            return redirect()->route('budgetinfo')->with('success', 'Successfully allocated budget.');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    public function budgetview(Request $request, $id)
    {
        if($request->user()->can('manage-budget-report')){
            $budget_info = Budget::findorFail($id);
            $fiscalyear = FiscalYear::where('fiscal_year', $budget_info->fiscal_year)->first();
            $child_account = ChildAccount::where('id', $budget_info->child_account_id)->first();
            $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q)
                                                        use($fiscalyear)
                                                        {
                                                            $q->where('fiscal_year_id', '=', $fiscalyear->id)->where('is_cancelled', 0)->where('status', 1);
                                                        })
                    ->where('child_account_id', $child_account->id)->get();
            return view('backend.budget.budgetview', compact('budget_info', 'child_account', 'journal_extras', 'fiscalyear'));
        }else{
            return view('backend.permission.permission');
        }
    }
}
