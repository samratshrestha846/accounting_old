<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BankAccountType;
use Illuminate\Http\Request;

class BankAccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankAccountTypes = BankAccountType::latest()->paginate(10);
        return view('backend.bank_account_types.index', compact('bankAccountTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.bank_account_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $saveandcontinue = $request->saveandcontinue ?? 0;
        $this->validate($request, ['account_type_name' => ['required']]);
        BankAccountType::create([ 'account_type_name' => $request['account_type_name']]);
        if($saveandcontinue == 1){
            return redirect()->route('bankAccountType.create')->with('success', 'Bank Account Type saved successfully.');
        }else{
            return redirect()->route('bankAccountType.index')->with('success', 'Bank Account Type saved successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( BankAccountType $bankAccountType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( BankAccountType $bankAccountType)
    {
        return view('backend.bank_account_types.edit', compact('bankAccountType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  BankAccountType $bankAccountType)
    {
        $this->validate($request, ['account_type_name' => ['required']]);
        $bankAccountType->update([ 'account_type_name' => $request['account_type_name']]);

        return redirect()->route('bankAccountType.index')->with('success', 'Bank Account Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( BankAccountType $bankAccountType)
    {
        $bankAccountType->delete();
        return redirect()->route('bankAccountType.index')->with('success', 'Bank Account Type deleted successfully.');
    }

    public function changeStatus($id)
    {
        $bankAccountType = BankAccountType::findorFail($id);

        $accountStatus = $bankAccountType->status == 0 ? 1 : 0;
        $bankAccountType->update([ 'status'=> $accountStatus ]);

        $response = array('status' => 'success', 'success' => 'Status changed successfully.');
        echo(json_encode($response));
    }
}
