<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Imports\JournalImport;
use App\Imports\PurchaseImport;
use App\Imports\SalesImport;
use App\Imports\SupplierImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportSuppliersController extends Controller
{
    public function suppliers(Request $request)
    {
        // $data =  $this->validate($request, [
        //     'excelFile' => 'required|max:50000|mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp,csv'
        // ]);
        DB::beginTransaction();
        try {
            Excel::import(new SupplierImport, $request['excelFile']);
            DB::commit();
            request()->session()->flash('success', 'suppliers Imported Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function journals(Request $request)
    {
        $data =  $this->validate($request, [
            'excelFile' => 'required|max:50000|mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new JournalImport, $data['excelFile']);
            DB::commit();
            request()->session()->flash('success', 'Journals Imported Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function customers(Request $request)
    {

        // $data =  $this->validate($request, [
        //     'excelFile' => 'required|max:50000|mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp,csv'
        // ]);
        DB::beginTransaction();
        try {
            Excel::import(new CustomerImport, $request['excelFile']);
            DB::commit();
            request()->session()->flash('success', 'Customers Imported Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function sales(Request $request)
    {

        $data =  $this->validate($request, [
            'excelFile' => 'required|max:50000|mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp,csv'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new SalesImport, $data['excelFile']);
            DB::commit();
            request()->session()->flash('success', 'Sales Bills Imported Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function purchase(Request $request)
    {

        $data =  $this->validate($request, [
            'excelFile' => 'required|max:50000|mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp,csv'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new PurchaseImport, $data['excelFile']);
            DB::commit();
            request()->session()->flash('success', 'Purchase Bills Imported Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function suppliersDemoExcel()
    {
        return response()->file(public_path('excelDemo/suppliers.xlsx'));
    }

    public function customersDemoExcel()
    {
        return response()->file(public_path('excelDemo/customers.xlsx'));
    }

    public function journalsDemoExcel()
    {
        return response()->file(public_path('excelDemo/journals.xlsx'));
    }

    public function salesDemoExcel()
    {
        return response()->file(public_path('excelDemo/sales.xlsx'));
    }

    public function purchaseDemoExcel()
    {
        return response()->file(public_path('excelDemo/purchase.xlsx'));
    }
}
