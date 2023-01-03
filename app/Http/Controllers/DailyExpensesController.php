<?php

namespace App\Http\Controllers;

use App\Models\DailyExpenses;
use App\Models\Province;
use App\Models\Vendor;
use Illuminate\Http\Request;

class DailyExpensesController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'view-daily-expenses' ) ) {
            $dailyexpenses = DailyExpenses::latest()->paginate( 10 );
            return view( 'backend.dailyexpenses.index', compact( 'dailyexpenses' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $dailyexpenses = DailyExpenses::query()
        ->where( 'bill_number', 'LIKE', "%{$search}%" )
        ->orWhere( 'bill_amount', 'LIKE', "%{$search}%" )
        ->orWhere( 'paid_amount', 'LIKE', "%{$search}%" )
        ->orWhere( 'purpose', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );
        // dd( $dailyexpenses );

        return view( 'backend.dailyexpenses.search', compact( 'dailyexpenses' ) );
    }

    public function deletedexpenses( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $dailyexpenses = DailyExpenses::onlyTrashed()->latest()->paginate();
            return view( 'backend.trash.expenses', compact( 'dailyexpenses' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create( Request $request ) {
        if ( $request->user()->can( 'create-daily-expenses' ) ) {
            $vendors = Vendor::latest()->get();
            $provinces = Province::latest()->get();
            $allsuppliercodes = [];
            foreach ( $vendors as $supplier ) {
                array_push( $allsuppliercodes, $supplier->supplier_code );
            }
            $supplier_code = 'SU'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            return view( 'backend.dailyexpenses.create', compact( 'vendors', 'provinces', 'allsuppliercodes', 'supplier_code'));
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
        $saveandcontinue = $request->saveandcontinue ?? 0;
        if ( $request->user()->can( 'create-daily-expenses' ) ) {
            $this->validate( $request, [
                'bill_number' => 'required',
                'date' => 'required',
                'vendor_name' => 'required',
                'bill_amount' => 'required',
                'paid_amount' => 'required',
                'purpose' => 'required',
                'bill_image' => ''
            ] );

            if ( $request->hasfile( 'bill_image' ) ) {
                $image = $request->file( 'bill_image' );
                $imagename = $image->store( 'bill_images', 'uploads' );
                $dailyExpenses = DailyExpenses::create( [
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_image' => $imagename,
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ] );
            } else {
                $dailyExpenses = DailyExpenses::create( [
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ] );
            }

            $dailyExpenses->save();
            if($saveandcontinue == 1){
                return redirect()->back()->with( 'success', 'Daily expenses is saved successfully.' );
            }else{
                return redirect()->route( 'dailyexpenses.index' )->with( 'success', 'Daily expenses is saved successfully.' );
            }

        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\DailyExpenses  $dailyExpenses
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {

    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\DailyExpenses  $dailyExpenses
    * @return \Illuminate\Http\Response
    */

    public function edit( $id, Request $request ) {
        if ( $request->user()->can( 'edit-daily-expenses' ) ) {
            $dailyExpenses = DailyExpenses::findorFail( $id );
            $vendors = Vendor::latest()->get();
            return view( 'backend.dailyexpenses.edit', compact( 'dailyExpenses', 'vendors' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\DailyExpenses  $dailyExpenses
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        if ( $request->user()->can( 'edit-daily-expenses' ) ) {
            $dailyExpenses = DailyExpenses::findorFail( $id );

            $this->validate( $request, [
                'bill_number' => 'required',
                'date' => 'required',
                'bill_image' => '',
                'vendor_name' => 'required',
                'bill_amount' => 'required',
                'paid_amount' => 'required',
                'purpose' => 'required'
            ] );

            if ( $request->hasfile( 'bill_image' ) ) {
                $image = $request->file( 'bill_image' );
                $imagename = $image->store( 'bill_images', 'uploads' );
                $dailyExpenses->update( [
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_image' => $imagename,
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ] );
            } else {
                $dailyExpenses->update( [
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ] );
            }
            return redirect()->route( 'dailyexpenses.index' )->with( 'success', 'Daily expenses is updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\DailyExpenses  $dailyExpenses
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id, Request $request ) {
        if ( $request->user()->can( 'remove-daily-expenses' ) ) {
            $dailyExpenses = DailyExpenses::findorFail( $id );
            $dailyExpenses->delete();

            return redirect()->route( 'dailyexpenses.index' )->with( 'success', 'Daily expenses is deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restoreexpenses( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $deleted_expenses = DailyExpenses::onlyTrashed()->findorFail( $id );
            $vendor = Vendor::onlyTrashed()->where( 'id', $deleted_expenses->vendor_id )->first();
            if ( $vendor ) {
                return redirect()->back()->with( 'error', 'Supplier is not present or is soft deleted. Check Suppliers.' );
            }

            $deleted_expenses->restore();
            return redirect()->route( 'dailyexpenses.index' )->with( 'success', 'Expenses information is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
