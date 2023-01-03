<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class SubAccountController extends Controller {
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
        if ( $request->user()->can( 'view-accounts' ) ) {
            $subaccounts = SubAccount::latest()->paginate( 10 );
            return view( 'backend.subaccount.index', compact( 'subaccounts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );

        $subaccounts = SubAccount::query()
        ->where( 'title', 'LIKE', "%{$search}%" )
        ->latest()
        ->paginate( 10 );

        return view( 'backend.subaccount.search', compact( 'subaccounts' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
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
                'title' => 'required',
                'account_id' => 'required',
                'sub_account_id' => '',
            ]);

            $new_subaccount = SubAccount::create( [
                'title' => $request['title'],
                'account_id' => $request['account_id'],
                'slug' => Str::slug( $request['title'] ),
                'sub_account_id' => $request['sub_account_id'],
            ]);

            $new_subaccount->save();

            return redirect()->back()->with( 'success', 'Sub Account information is saved successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\SubAccount  $subAccount
    * @return \Illuminate\Http\Response
    */

    public function show( SubAccount $subAccount ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\SubAccount  $subAccount
    * @return \Illuminate\Http\Response
    */

    public function edit( SubAccount $subAccount, Request $request ) {
        if ( $request->user()->can( 'edit-account' ) ) {
            $accounts = Account::all();
            $main_sub_accounts = SubAccount::latest()->where('sub_account_id', null)->get();
            return view( 'backend.subaccount.edit', compact( 'accounts', 'subAccount', 'main_sub_accounts' ) );
        } else {
            return view( 'backend.permission.permission' );
        }

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\SubAccount  $subAccount
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, SubAccount $subAccount ) {
        if ( $request->user()->can( 'edit-account' ) ) {
            $this->validate( $request, [
                'title' => 'required',
                'account_id' => 'required',
                'sub_account_id' =>'',
            ] );

            $subAccount->update( [
                'title' => $request['title'],
                'account_id' => $request['account_id'],
                'sub_account_id' => $request['sub_account_id'],
            ] );

            return redirect()->route( 'sub_account.index' )->with( 'success', 'Sub Account information is updated successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\SubAccount  $subAccount
    * @return \Illuminate\Http\Response
    */

    public function destroy( SubAccount $subAccount, Request $request ) {
        if ( $request->user()->can( 'remove-account' ) ) {
            $subAccount->delete();
            return redirect()->route( 'sub_account.index' )->with( 'success', 'Sub Account information is deleted successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function restoresubaccount( $id, Request $request ) {
        if ( $request->user()->can( 'manage-trash' ) ) {
            $sub_account = SubAccount::onlyTrashed()->findorFail( $id );
            $main_account = Account::onlyTrashed()->where( 'id', $sub_account->account_id )->first();
            if ( $main_account ) {
                return redirect()->back()->with( 'error', 'Main Account Type is not present or is soft deleted. Check Main Account.' );
            }
            $sub_account->restore();
            return redirect()->route( 'sub_account.index' )->with( 'success', 'Sub Account type is restored successfully.' );
        } else {
            return view( 'backend.permission.permission' );
        }
    }
}
