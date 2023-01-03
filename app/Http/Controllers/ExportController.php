<?php

namespace App\Http\Controllers;

use App\Exports\BalanceSheetExport;
use App\Exports\BalanceSheetReportExport;
use App\Exports\BankLedgerExport;
use App\Exports\CustomerLedgerExport;
use App\Exports\ClientsaleExport;
use App\Exports\CustomerExport;
use App\Exports\JournalExport;
use App\Exports\JournalFilterExport;
use App\Exports\LedgerExport;
use App\Exports\ProfitandLossExport;
use App\Exports\ProductExport;
use App\Exports\ProfitandLossReportExport;
use App\Exports\SalesBillExport;
use App\Exports\SalesBillFilterExport;
use App\Exports\ServiceSalesBillExport;
use App\Exports\ServiceSalesBillFilterExport;
use App\Exports\SupplierLedgerExport;
use App\Exports\SupplierpurchaseExport;
use App\Exports\SupplierExport;
use App\Exports\TrialBalanceExport;
use App\Exports\TrialBalanceFilterExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function journalexport(Request $request)
    {
        return Excel::download(new JournalExport($request->id), 'Journal Vouchers.csv');
    }

    public function journalfilterexport(Request $request)
    {
        return Excel::download(new JournalFilterExport($request->id, $request->start_date, $request->end_date), 'Journal Vouchers Filter.csv');
    }

    public function trialbalanceexport(Request $request)
    {
        return Excel::download(new TrialBalanceExport($request->id), 'Trial Balance.csv');
    }

    public function trialbalancefilterexport(Request $request)
    {
        return Excel::download(new TrialBalanceFilterExport($request->id, $request->start_date, $request->end_date), 'Trial Balance Filter.csv');
    }

    public function profitandlossexport(Request $request)
    {
        return Excel::download(new ProfitandLossExport($request->id), 'Profit and Loss.csv');
    }

    public function profitandlossfilterexport(Request $request)
    {
        return Excel::download(new ProfitandLossReportExport($request->id, $request->starting_date, $request->ending_date), 'Profit and Loss Filter.csv');
    }

    public function balancesheetexport(Request $request)
    {
        return Excel::download(new BalanceSheetExport($request->id), 'Balance Sheet.csv');
    }

    public function balancesheetfilterexport(Request $request)
    {
        return Excel::download(new BalanceSheetReportExport($request->id, $request->starting_date, $request->ending_date), 'Balance Sheet Filter.csv');
    }

    public function ledgerfilterexport(Request $request)
    {
        return Excel::download(new LedgerExport($request->fiscal_year_id, $request->id, $request->starting_date, $request->ending_date), 'Ledger Reports.csv');
    }

    public function salesBillsexport(Request $request, $id, $billing_type_id)
    {
        // dd('hi');
        $selectedcsvid = explode(',', $request['selectedcsvid']);
        if($request['export_POS'] == null)
        {
            $pos_data = 0;
        } else {
            $pos_data = $request['export_POS'];
        }
        return Excel::download(new SalesBillExport($selectedcsvid, $id, $billing_type_id, $pos_data), 'Billing Report.csv');
    }

    public function salesBillsfilterexport(Request $request, $id, $start_date, $end_date, $billing_type_id)
    {
        $selectedcsvid = explode(',', $request['selectedcsvid']);
        if($request['export_POS'] == null)
        {
            $pos_data = 0;
        } else {
            $pos_data = $request['export_POS'];
        }
        return Excel::download(new SalesBillFilterExport($selectedcsvid, $id, $start_date, $end_date, $billing_type_id, $pos_data), 'Filtered Billing.csv');
    }

    public function serviceSalesBillsexport(Request $request, $id)
    {
        $selectedcsvid = explode(',', $request['selectedcsvid']);
        return Excel::download(new ServiceSalesBillExport($selectedcsvid, $id), 'Service Sales Billing Report.csv');
    }

    public function serviceSalesBillsFilterExport(Request $request, $id, $start_date, $end_date)
    {
        $selectedcsvid = explode(',', $request['selectedcsvid']);
        return Excel::download(new ServiceSalesBillFilterExport($selectedcsvid, $id, $start_date, $end_date), 'Filtered Service Sales Billing.csv');
    }

    public function clientsalesExport(Request $request, $id){
        return Excel::download(new ClientsaleExport($id), 'Client sale.csv');
    }
    public function supplierpurchaseExport($id){
        return Excel::download(new SupplierpurchaseExport($id), 'Supplier sale.csv');
    }

    public function supplierExport(){
        return Excel::download(new SupplierExport(), 'Supplier.csv');
    }
    public function supplierledgerexportexcel(){
        return Excel::download(new SupplierLedgerExport(), 'SupplierLedger.csv');
    }

    public function customerledgerexportexcel(){
        return Excel::download(new CustomerLedgerExport(), 'CustomerLedger.csv');
    }

    public function bankledgerexportexcel(){
        return Excel::download(new BankLedgerExport(), 'BankLedger.csv');
    }

    public function clientExport(){
        return Excel::download(new CustomerExport(), 'customer.csv');
    }

    public function productsExportCsv(){
        return Excel::download(new ProductExport(), 'products.csv');
    }
}
