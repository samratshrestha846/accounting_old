<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\ClientOrder;
use App\Models\DealerUserCompany;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerLoginController extends Controller
{

    public function signInCustomer(Request $request)
    {
        if(Auth::guard('customer')->attempt([
            'email'=>$request['email'],
            'password'=>$request['password']
            ]))
        {
            return redirect()->route('customer.home');
        }

        return redirect()->back();
    }

    public function login(){
        return view('auth.customerlogin');
    }

    public function dashboard(){
        // dd(Auth::user()->client_id);
        $total_purchase_orders = ClientOrder::where('client_id', Auth::user()->client_id)->count();
        $total_purchases = DB::table('billings')->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->where('status', 1)->count();
        $total_quotations = DB::table('billings')->where('client_id', Auth::user()->client_id)->where('billing_type_id', 7)->where('status', 1)->count();
        $recent_purchases = DB::table('billings')->latest()->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->take(10)->get();
        $recent_quotations = DB::table('billings')->latest()->where('billing_type_id', 7)->where('client_id', Auth::user()->client_id)->take(10)->get();
        $recent_purchase_returns = DB::table('billings')->latest()->where('billing_type_id', 6)->where('client_id', Auth::user()->client_id)->take(10)->get();
        // Purchase Chart
        $allpurchasebills = DB::table('billings')->latest()->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->get();
        $allpurchases = [];
        $totalpaid = [];
        foreach ($allpurchasebills as $purchaseBilling) {
            $gtotal = round($purchaseBilling->grandtotal, 2);
            $singlebillpayments = [];
            $payment_infos = DB::table('payment_infos')->where('billing_id', $purchaseBilling->id)->get();
            foreach ($payment_infos as $paymentinfo) {
                $payments = round($paymentinfo->payment_amount, 2);
                array_push($singlebillpayments, $payments);
            }
            array_push($totalpaid, array_sum($singlebillpayments));
            array_push($allpurchases, $gtotal);
        }
        $totalpurchase = array_sum($allpurchases);
        $totalpurchasepayment = array_sum($totalpaid);
        $totalpurchasedue = $totalpurchase - $totalpurchasepayment;

        $purchaseChart = [$totalpurchase, $totalpurchasepayment, $totalpurchasedue];

        // Purchase Return charts
        $allpurchasereturnbills = DB::table('billings')->latest()->where('billing_type_id', 6)->where('client_id', Auth::user()->client_id)->get();
        $allpurchasereturns = [];
        $totalpaid = [];
        foreach ($allpurchasereturnbills as $purchasereturnBilling) {
            $gtotal = round($purchasereturnBilling->grandtotal, 2);
            $singlebillpayments = [];
            $payment_infos = DB::table('payment_infos')->where('billing_id', $purchaseBilling->id)->get();
            foreach ($payment_infos as $paymentinfo) {
                $payments = round($paymentinfo->payment_amount, 2);
                array_push($singlebillpayments, $payments);
            }
            array_push($totalpaid, array_sum($singlebillpayments));
            array_push($allpurchasereturns, $gtotal);
        }
        $totalpurchasereturn = array_sum($allpurchasereturns);
        $totalpurchasereturnpayment = array_sum($totalpaid);
        $totalpurchasereturndue = $totalpurchasereturn - $totalpurchasereturnpayment;

        $purchasereturnChart = [$totalpurchasereturn, $totalpurchasereturnpayment, $totalpurchasereturndue];

        return view('customerbackend.dashboard', compact('total_purchase_orders', 'total_purchases', 'total_quotations', 'recent_purchases', 'recent_quotations', 'recent_purchase_returns', 'purchaseChart', 'purchasereturnChart'));
    }

    public function switchselected($id){
        $currcomp = DealerUserCompany::where('dealer_user_id', Auth::user()->id)->where('is_selected', 1)->first();
        $currcomp->update([
            'is_selected'=>0,
        ]);
        $currcomp->save();
        $newselect = DealerUserCompany::findorfail($id);
        $newselect->update([
            'is_selected'=> 1,
        ]);
        $newselect->save();
        return redirect()->route('customer.home');
    }
}
