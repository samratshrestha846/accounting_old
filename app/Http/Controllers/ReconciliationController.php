<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\JournalVouchers;
use App\Models\Province;
use App\Models\Reconciliation;
use App\Models\Vendor;
use Illuminate\Http\Request;

use function App\NepaliCalender\datenep;

class ReconciliationController extends Controller {
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
        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $reconciliations = Reconciliation::latest()->paginate( 10 );
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            return view( 'backend.bank_reconciliation.index', compact( 'reconciliations', 'today', 'nepali_today' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function deletedBankReconciliation( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $reconciliations = Reconciliation::onlyTrashed()->latest()->paginate( 10 );
            return view( 'backend.trash.reconciliationtrash', compact( 'reconciliations' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $reconciliations = Reconciliation::query()
        ->where( 'cheque_entry_date', 'LIKE', "%{$search}%" )
        ->orWhere( 'cheque_cashed_date', 'LIKE', "%{$search}%" )
        ->orWhere( 'amount', 'LIKE', "%{$search}%" )
        ->orWhere( 'cheque_no', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.bank_reconciliation.search', compact( 'reconciliations' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $suppliers = Vendor::all();
            $provinces = Province::all();
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            return view( 'backend.bank_reconciliation.create', compact( 'suppliers', 'provinces', 'nepali_today' ) );
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
            'cheque_entry_date' => 'required',
            'vendor_id' => '',
            'client_id' => '',
            'others' => '',
            'method' => '',
            'cheque_no' => '',
            'bank_id' => 'required',
            'receipt_payment' => 'required',
            'amount' => 'required',
        ] );

        if ( $request['vendor_id'] == 'other' ) {
            $vendor = null;
            $others = $request['others'];
        } else {
            $vendor = $request['vendor_id'];
            $others = null;
        }

        if ( $request['method'] == 'Bank Transfer' ) {
            $cheque_no = null;
        } else {
            $cheque_no = $request['cheque_no'];
        }

        Reconciliation::create( [
            'bank_id' => $request['bank_id'],
            'cheque_no' => $cheque_no,
            'receipt_payment' => $request['receipt_payment'],
            'amount' => $request['amount'],
            'cheque_entry_date' => $request['cheque_entry_date'],
            'vendor_id' => $vendor,
            'client_id'=>$request['client_id'],
            'other_receipt' => $others
        ] );

        return redirect()->route( 'bankReconciliationStatement.index' )->with( 'success', 'Reconciliation information saved successfully.' );
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Reconciliation  $reconciliation
    * @return \Illuminate\Http\Response
    */

    public function show( Request $request, $id ) {
        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $reconciliation = Reconciliation::findorFail( $id );
            $bank = Bank::where( 'id', $reconciliation->bank_id )->first();
            $journalVoucher = JournalVouchers::where( 'id', $reconciliation->jv_id )->first();
            $supplier = Vendor::where( 'id', $reconciliation->vendor_id )->first();
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            return view( 'backend.bank_reconciliation.show', compact( 'reconciliation', 'bank', 'journalVoucher', 'supplier', 'nepali_today' ) );
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Reconciliation  $reconciliation
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $suppliers = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $reconciliation = Reconciliation::findorFail( $id );
            $banks = Bank::latest()->get();
            $today = date( 'Y-m-d' );
            $nepali_today = datenep( $today );
            return view( 'backend.bank_reconciliation.edit', compact( 'banks', 'suppliers', 'provinces', 'reconciliation', 'nepali_today' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Reconciliation  $reconciliation
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id )
    {
        $reconciliation = Reconciliation::findorFail( $id );
        $this->validate( $request, [
            'cheque_entry_date' => 'required',
            'cheque_cashed_date' => 'required',
            'vendor_id' => '',
            'client_id' => '',
            'others' => '',
            'method' => '',
            'cheque_no' => '',
            'bank_id' => 'required',
            'receipt_payment' => 'required',
            'amount' => 'required',
        ] );

        if ( $request['vendor_id'] == 'other' ) {
            $vendor = null;
            $others = $request['others'];
        } else {
            $vendor = $request['vendor_id'];
            $others = null;
        }

        if ( $request['method'] == 'Bank Transfer' ) {
            $cheque_no = null;
        } else {
            $cheque_no = $request['cheque_no'];
        }

        $reconciliation->update( [
            'bank_id' => $request['bank_id'],
            'cheque_no' => $cheque_no,
            'receipt_payment' => $request['receipt_payment'],
            'amount' => $request['amount'],
            'cheque_entry_date' => $request['cheque_entry_date'],
            'cheque_cashed_date' => $request['cheque_cashed_date'],
            'vendor_id' => $vendor,
            'client_id' => $request['client_id'],
            'other_receipt' => $others
        ] );

        return redirect()->route( 'bankReconciliationStatement.index' )->with( 'success', 'Reconciliation Statement updated successfully.' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Reconciliation  $reconciliation
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request, $id )
    {
        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $reconciliation = Reconciliation::findorFail( $id );
            $reconciliation->delete();

            return redirect()->route( 'bankReconciliationStatement.index' )->with( 'success', 'Reconciliation Statement deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restoreBankReconciliation( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $deleted_reconciliation = Reconciliation::onlyTrashed()->findorFail( $id );
            $deleted_reconciliation->restore();
            return redirect()->route( 'bankReconciliationStatement.index' )->with( 'success', 'Reconciliation information is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function reconciliationCashedOut( $id, Request $request ) {

        if ( $request->user()->can( 'manage-reconciliation-statement' ) ) {
            $reconciliation = Reconciliation::findorFail( $id );
            $reconciliation->update( ['cheque_cashed_date' => $request['cheque_cashed_date']] );

            return redirect()->back()->with( 'success', 'Check Out Date is updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
