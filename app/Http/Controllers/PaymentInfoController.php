<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingCredit;
use App\Models\PaymentInfo;
use App\Models\SalesBills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $lastpaid = PaymentInfo::where('billing_id', $request['billing_id'])->first();
        $total_paid = round($lastpaid->total_paid_amount,2) + round($request['payment_amount'],2);
        DB::beginTransaction();
        try{
            $paymentInfo = PaymentInfo::create([
                'billing_id'=>$request['billing_id'],
                'payment_type'=>$request['payment_type'],
                'payment_amount'=>$request['payment_amount'],
                'payment_date'=>$request['payment_date'],
                'total_paid_amount'=>$total_paid,
                'paid_to'=>Auth::user()->id,
            ]);
            
            $paymentInfo->save();

            DB::commit();

            if($request->wantsJson()) {
                return $this->responseOk(
                    "Payment successfully created",
                    $paymentInfo,
                );
            }

            return redirect()->back()->with('success', 'Payment Successfully Created');
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function show(PaymentInfo $paymentInfo)
    {
        //
    }

    public function edit($id)
    {
        $paymentinfo = PaymentInfo::findorfail($id);
        $billing = Billing::with('payment_infos')->where('id',$paymentinfo->billing_id)->first();
        return view('backend.billings.paymentinfoedit', compact('paymentinfo', 'billing'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            if(!$request->is_sale_service){
                $paymentinfo = PaymentInfo::findorfail($id);
                $billing = Billing::with('suppliers')->with('payment_infos')->where('id',$paymentinfo->billing_id)->first();

                $lastpaid = PaymentInfo::where('billing_id', $request['billing_id'])->first();
                if($request['is_billingcredit']){
                    $total_paid = $paymentinfo->total_paid_amount + $request['payment_amount'];
                    $payment_amount = $paymentinfo->payment_amount + $request['payment_amount'];
                }else{
                    $total_paid = round($lastpaid->total_paid_amount,2) - round($paymentinfo->payment_amount, 2) + round($request['payment_amount']);
                    $payment_amount = $request['payment_amount'];
                }

                $paymentinfo->update([
                    'billing_id'=>$request['billing_id'],
                    'payment_type'=>$request['payment_type'],
                    'payment_amount'=>$payment_amount,
                    'payment_date'=>$request['payment_date'],
                    'total_paid_amount'=>$total_paid,
                    'paid_to'=>Auth::user()->id,
                ]);
                $paymentinfo->save();

                //billingcredit
                $billingCredit = BillingCredit::where('billing_id',$request['billing_id'])->first();
                if($billingCredit){
                    $billingCredit->credit_amount = $billingCredit->credit_amount - $request['payment_amount'];
                    $billingCredit->due_date_nep = $request['due_date_nep'];
                    $billingCredit->due_date_eng = $request['payment_date'];
                    $billingCredit->save();
                }
            }else{

                $sale_bill = new SalesBills();
                $saleBill = $sale_bill->find($request['billing_id']);
                $saleBill->increment('payment_amount',$request['payment_amount']);

                $servicebillingcredit = BillingCredit::where('billing_id',$request['billing_id'])->where('is_sale_service',1)->first();
                $servicebillingcredit->decrement('credit_amount',$request['payment_amount']);
            }

            DB::commit();
            if($request['is_billingcredit']){
                return redirect()->back()->with('success', 'Payment Successfully Updated');
            }else{
                return redirect()->route('billings.show', $paymentinfo->billing_id)->with('success', 'Payment Successfully Updated');
            }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    public function destroy(PaymentInfo $paymentInfo)
    {
        //
    }
}
