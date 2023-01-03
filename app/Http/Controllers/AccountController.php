<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function app\NepaliCalender\datenep;

class AccountController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct() {
        $this->middleware( 'auth' );
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'view-accounts' ) ) {
            $accounts = Account::latest()->paginate( 10 );
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            return view( 'backend.account.index', compact( 'accounts') );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        if ( $request->user()->can( 'view-accounts' ) ) {
            $search = $request->input( 'search' );
            $accounts = Account::query()
            ->where( 'title', 'LIKE', "%{$search}%" )
            ->latest()
            ->paginate( 10 );

            return view( 'backend.account.search', compact( 'accounts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function deletedindex( Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $accounts = Account::onlyTrashed()->latest()->paginate( 10 );
            $subaccounts = SubAccount::onlyTrashed()->latest()->paginate( 10 );
            $childaccounts = ChildAccount::onlyTrashed()->latest()->paginate( 10 );
            return view( 'backend.trash.accountstrash', compact( 'accounts', 'subaccounts', 'childaccounts' ) );
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
        if ( $request->user()->can( 'create-account' ) ) {
            $accounts = Account::latest()->get();
            $sub_accounts = SubAccount::latest()->get();
            $main_sub_accounts = SubAccount::latest()->where('sub_account_id', null)->get();
            $fiscal_years = FiscalYear::all();
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
            // $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
            $current_fiscal_year = FiscalYear::first();
            return view( 'backend.account.create', compact( 'accounts', 'sub_accounts', 'main_sub_accounts', 'fiscal_years', 'current_fiscal_year' ) );
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
        if ( $request->user()->can( 'create-account' ) ) {
            $this->validate( $request, [
                'title' => 'required'
            ] );

            $new_account = Account::create( [
                'title' => $request[ 'title' ],
                'slug' => Str::slug( $request[ 'title' ], '-' )
            ] );

            $new_account->save();
            return redirect()->back()->with( 'success', 'Account type is saved successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Account  $account
    * @return \Illuminate\Http\Response
    */

    public function show( Account $account ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Account  $account
    * @return \Illuminate\Http\Response
    */

    public function edit( Account $account, Request $request ) {
        if ( $request->user()->can( 'edit-account' ) ) {
            return view( 'backend.account.edit', compact( 'account' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Account  $account
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Account $account ) {
        if ( $request->user()->can( 'edit-account' ) ) {
            $this->validate( $request, [
                'title' => 'required'
            ] );
            $account->update( [
                'title' => $request[ 'title' ],
                'slug' => Str::slug( $request[ 'title' ], '-' )
            ] );

            return redirect()->route( 'account.index' )->with( 'success', 'Account type is updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Account  $account
    * @return \Illuminate\Http\Response
    */

    public function destroy( Account $account, Request $request ) {
        if ( $request->user()->can( 'remove-account' ) ) {
            $account->delete();
            return redirect()->route( 'account.index' )->with( 'success', 'Account type is deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restore( $id, Request $request ) {
        if ( $request->user()->can( 'remove-account' ) ) {
            $account = Account::onlyTrashed()->findorFail( $id );
            $account->restore();
            return redirect()->route( 'account.index' )->with( 'success', 'Account type is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function accounthierarchy( Request $request ) {
        if ( $request->user()->can( 'view-accounts' ) ) {
            $current_fiscal_year = FiscalYear::latest()->first();
            $mainaccounts = Account::with( 'sub_accounts', 'sub_accounts.child_accounts' )->get();
            return view( 'backend.account.hierarchy', compact( 'mainaccounts', 'current_fiscal_year' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
