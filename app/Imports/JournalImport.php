<?php

namespace App\Imports;

use App\Models\ChildAccount;
use App\Models\Client;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\JournalImage;
use App\Models\JournalVouchers;
use App\Models\Vendor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
Use Illuminate\Support\Str;

use function app\NepaliCalender\dateeng;
use function app\NepaliCalender\datenep;

class JournalImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $supplier = $this->getSupplier($row['supplier_code']);
        $customer = $this->getCustomer($row['customer_code']);
        $english_date = dateeng($row['date_in_nepaliyy_mm_dd']);
        $debit_array = explode(',', $row['debit_amounts']);
        $credit_array = explode(',', $row['credit_amounts']);
        $debit_accounts = explode(',', $row['debit_accounts']);
        $credit_accounts = explode(',', $row['credit_accounts']);

        $today = date("Y-m-d");
        $nepalitoday = datenep($today);
        $explode = explode('-', $nepalitoday);
        $year = $explode[0];
        $month = $explode[1];

        $fiscalyear = ($month < 4) ? ($year - 1).'/'.$year : $year.'/'.($year + 1);

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
            'company_id' => 1,
            'branch_id' => 1,
            'journal_voucher_no' => $jvno,
            'entry_date_english' => $english_date,
            'entry_date_nepali' => $row['date_in_nepaliyy_mm_dd'],
            'fiscal_year_id' => $fiscal_year->id,
            'debitTotal' => array_sum($debit_array),
            'creditTotal' => array_sum($credit_array),
            'narration' => $row['narration'],
            'is_cancelled' => '0',
            'status'  => '1',
            'vendor_id' => $supplier->id ?? null,
            'entry_by' =>  Auth::user()->id,
            'approved_by' => Auth::user()->id,
            'client_id' => $customer->id ?? null,
        ]);

        for($x = 0; $x < count($debit_array); $x++)
        {
            $slug = Str::slug($debit_accounts[$x]);
            $account_head = $this->getAccountHead($slug);
            JournalExtra::create([
                'company_id' => 1,
                'branch_id' => 1,
                'journal_voucher_id' => $journal_voucher->id,
                'child_account_id' => $account_head->id,
                'debitAmount' => $debit_array[$x],
            ]);
        }

        for($x = 0; $x < count($credit_array); $x++)
        {
            $slug = Str::slug($credit_accounts[$x]);
            $account_head = $this->getAccountHead($slug);
            JournalExtra::create([
                'company_id' => 1,
                'branch_id' => 1,
                'journal_voucher_id' => $journal_voucher->id,
                'child_account_id' => $account_head->id,
                'creditAmount' => $credit_array[$x],
            ]);
        }

        JournalImage::create([
            'journalvoucher_id' => $journal_voucher->id,
            'location' => 'favicon.png',
        ]);
    }

    private function getSupplier($code)
    {
        return Vendor::select('id')->where('supplier_code', $code)->first() ?? new Vendor();
    }

    private function getCustomer($code)
    {
        return Client::select('id')->where('client_code', $code)->first() ?? new Client();
    }

    private function getAccountHead($slug)
    {
        return ChildAccount::select('id')->where('slug', $slug)->first();
    }
}
