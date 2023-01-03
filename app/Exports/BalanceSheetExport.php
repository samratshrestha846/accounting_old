<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\OpeningBalance;
use App\Models\SubAccount;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use function App\NepaliCalender\datenep;

class BalanceSheetExport implements FromView
{
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $main_accounts = Account::whereIn('id', [1,2,4])->get();
        $current_fiscal_year = FiscalYear::findorFail($this->id);

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
        $dividend_child_account = ChildAccount::findorFail(2);
        $journal_extras = JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
            $q->where('is_cancelled', 0)->where('status', 1);
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

        return view('backend.excelview.balancesheetexcel', compact(
            'main_accounts',
            'current_fiscal_year',
            'dividend_child_account',
            'dividend_diff_amount',
            'retained_earnings',
            'today_date_nepali',
        ));
    }
}
