<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ( $request->user()->can( 'manage-credit' ) ) {
            $clients = Client::with('credits')->latest()->get();
            return view('backend.credit.index', compact('clients'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $selected_credit = Credit::findorFail($id);
        $credits = Credit::where('customer_id', $selected_credit->customer_id)->get();

        foreach ($credits as $credit) {
            $credit->update([
                'allocated_days' => $request['allocated_days'],
                'allocated_bills' => $request['allocated_bills'],
                'allocated_amount' => $request['allocated_amount'],
            ]);
        }

        return redirect()->back()->with('success', 'Credit information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credit $credit)
    {
        //
    }

    public function customerCredit(Request $request)
    {
        if ( $request->user()->can( 'manage-credit' ) ) {
            $this->validate($request, [
                'customer_name' => 'required'
            ]);

            $clients = Client::latest()->get();
            $selected_client = Client::findorFail($request['customer_name']);
            $credits = Credit::where('customer_id', $request['customer_name'])->where('converted', 0)->get();
            $single_credit_for_allocation = Credit::where('customer_id', $request['customer_name'])->first();
            return view('backend.credit.result', compact('credits', 'clients', 'selected_client', 'single_credit_for_allocation'));
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $credits = Credit::whereHas('client', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}");
        })->get();

        return view('backend.credit.search', compact('credits'));
    }
}
