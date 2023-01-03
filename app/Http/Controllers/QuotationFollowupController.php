<?php

namespace App\Http\Controllers;

use App\Models\QuotationFollowup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuotationFollowupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'billing_id'=>'required',
            'followup_date'=>'required',
            'followup_title'=>'required',
            'followup_details'=>'',
            'is_followed'=>'boolean'
        ]);
        if($request['is_followed'] == 0){
            $is_notified = 0;
        }else{
            $is_notified = 1;
        }

        QuotationFollowup::create([
            'billing_id'=>$request['billing_id'],
            'followup_date'=>$request['followup_date'],
            'followup_title'=>$request['followup_title'],
            'followup_details'=>$request['followup_details'],
            'is_followed'=>$request['is_followed'],
            'is_notified'=>$is_notified,
        ]);

        return redirect()->back()->with('success', 'Followup Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuotationFollowup  $quotationFollowup
     * @return \Illuminate\Http\Response
     */
    public function show(QuotationFollowup $quotationFollowup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuotationFollowup  $quotationFollowup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $followup = QuotationFollowup::findorfail($id);
        return view('backend.billings.editfollowup', compact('followup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuotationFollowup  $quotationFollowup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $followup = QuotationFollowup::findorfail($id);
        $data = $this->validate($request, [
            'billing_id'=>'required',
            'followup_date'=>'required',
            'followup_title'=>'required',
            'followup_details'=>'',
            'is_followed'=>'boolean'
        ]);
        if($data['is_followed'] == 0){
            $is_notified = 0;
        }else{
            $is_notified = 1;
        }
        $followup->update([
            'billing_id'=>$data['billing_id'],
            'followup_date'=>$data['followup_date'],
            'followup_title'=>$data['followup_title'],
            'followup_details'=>$data['followup_details'],
            'is_followed'=>$data['is_followed'],
            'is_notified'=>$is_notified,
        ]);
        $followup->save();
        return redirect()->route('billings.show', $data['billing_id'])->with('success', 'Followup Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuotationFollowup  $quotationFollowup
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuotationFollowup $quotationFollowup)
    {
        //
    }
}
