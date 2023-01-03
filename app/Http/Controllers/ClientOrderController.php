<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Client;
use App\Models\ClientOrder;
use App\Models\ClientOrderExtras;
use App\Models\DealerUserCompany;
use App\Models\FiscalYear;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;
use PDF;

class ClientOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request);
        $this->validate( $request, [
            'fiscal_year' => 'required',
            'nep_date' => 'required',
            'eng_date' => 'required',
            'particulars' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'remarks' => 'required',
        ] );

        $user_id = Auth::user()->id;
        // dd($user_id);
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', $user_id)
            ->where('is_selected', 1)
            ->first();
        $client_id = $currentcomp->dealeruser->client_id;
        $total_quantity = array_sum($request['quantity']);

        $randalphabet = strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 2));

        // Order Id
        $purchase_order_count = ClientOrder::count();

        if($purchase_order_count == 0)
        {
            $purchaseOrderNo = str_pad( 1, 8, '0', STR_PAD_LEFT );
            $purchaseOrderNo = 'PO-'.$randalphabet.$purchaseOrderNo;
        }
        else
        {
            $po = $purchase_order_count+1;

            $purchaseOrderNo = str_pad($po, 8, "0", STR_PAD_LEFT);
            $purchaseOrderNo = 'PO-'.$randalphabet.$purchaseOrderNo;
        }
        DB::beginTransaction();
        try{
            $clientOrder = ClientOrder::create( [
                'company_id' => $currentcomp->company_id,
                'branch_id' => $currentcomp->branch_id,
                'client_id' => $client_id,
                'remarks' => $request['remarks'],
                'eng_date' => $request['eng_date'],
                'nep_date' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year'],
                'quantity'=>$total_quantity,
                'order_no'=>$purchaseOrderNo,
            ] );

            $clientOrder->save();

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $unit = $request['unit'];
            $count = count( $particulars );

            for ( $x = 0; $x<$count; $x++ ) {
                $clientOrderExtra = ClientOrderExtras::create( [
                    'client_order_id' => $clientOrder['id'],
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'unit' => $unit[$x],
                ] );

                $clientOrderExtra->save();
            }
            DB::commit();
            return redirect()->route( 'purchaseOrder.customerindex' )->with( 'success', 'Purchase order information saved successfully.' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientOrder  $clientOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $clientOrder = ClientOrder::where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->findorfail($id);
        return view('customerbackend.purchaseordershow', compact('clientOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientOrder  $clientOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $clientOrder = ClientOrder::where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->findorfail($id);
        $fiscal_years = FiscalYear::latest()->get();
        $date = date( 'Y-m-d' );
        $nepalidate = datenep( $date );
        $exploded_date = explode( '-', $nepalidate );

        $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
        $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year)->first();
        $units = Unit::all();
        return view('customerbackend.purchaseorderedit', compact('clientOrder','fiscal_years', 'current_fiscal_year', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientOrder  $clientOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $clientOrder = ClientOrder::where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->findorfail($id);
        $this->validate( $request, [
            'fiscal_year' => 'required',
            'nep_date' => 'required',
            'eng_date' => 'required',
            'particulars' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'remarks' => 'required',
        ] );

        $total_quantity = array_sum($request['quantity']);
        DB::beginTransaction();
        try{
            $clientOrder->update( [
                'remarks' => $request['remarks'],
                'eng_date' => $request['eng_date'],
                'nep_date' => $request['nep_date'],
                'fiscal_year_id' => $request['fiscal_year'],
                'quantity'=>$total_quantity,
            ] );

            $clientOrder->save();

            foreach($clientOrder->client_order_extras as $extra){
                $extra->delete();
            }

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $unit = $request['unit'];
            $count = count( $particulars );
            for ( $x = 0; $x<$count; $x++ ) {
                $clientOrderExtra = ClientOrderExtras::create( [
                    'client_order_id' => $clientOrder['id'],
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'unit' => $unit[$x],
                ] );

                $clientOrderExtra->save();
            }
            DB::commit();
            return redirect()->route( 'purchaseOrder.customerindex' )->with( 'success', 'Purchase order information saved successfully.' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientOrder  $clientOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function purchaseordernotify($id){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $clientOrder = ClientOrder::where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->findorfail($id);
        $clientOrder->update([
            'is_notified'=>1,
        ]);
        return redirect()->route('purchaseOrder.customerindex')->with('success', 'Purchase Order Notified');
    }

    public function purchaseorderprint($id){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $clientOrder = ClientOrder::where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->findorfail($id);
        $fiscal_year = FiscalYear::findorfail($clientOrder->fiscal_year_id)->fiscal_year;
        $client = Client::findorfail($clientOrder->client_id);

        return view('customerbackend.purchaseorderprint', compact('clientOrder', 'fiscal_year', 'client'));
    }

    public function purchases(){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $purchases = DB::table('billings')->latest()->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->paginate(15);
        return view('customerbackend.mypurchasesindex', compact('purchases'));
    }

    public function paidpurchases(){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $purchases = DB::table('billings')->latest()->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->paginate(15);
        return view('customerbackend.mypurchasespaidindex', compact('purchases'));
    }

    public function duepurchases(){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $purchases = DB::table('billings')->latest()->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('billing_type_id', 1)->where('client_id', Auth::user()->client_id)->paginate(15);
        return view('customerbackend.mypurchasesdueindex', compact('purchases'));
    }

    public function singlepurchase($id){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
        ->where('is_selected', 1)
        ->first();
        $purchase = DB::table('billings')->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('id', $id)->first();
        $billingextras = DB::table('billing_extras')->where('billing_id', $purchase->id)->get();
        return view('customerbackend.purchaseshow', compact('purchase', 'billingextras'));
    }

    public function quotations(){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $quotations = DB::table('billings')->latest()->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('billing_type_id', 7)->where('client_id', Auth::user()->client_id)->paginate(15);
        return view('customerbackend.myquotationsindex', compact('quotations'));
    }
    public function singlequotation($id){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $quotation = DB::table('billings')->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('id', $id)->first();
        $billingextras = DB::table('billing_extras')->where('billing_id', $quotation->id)->get();
        return view('customerbackend.quotationshow', compact('quotation', 'billingextras'));
    }

    public function purchasereturns(){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $purchasereturns = DB::table('billings')->latest()->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('billing_type_id', 6)->where('client_id', Auth::user()->client_id)->paginate(15);
        return view('customerbackend.mypurchasereturnsindex', compact('purchasereturns'));
    }
    public function singlepurchasereturn($id){
        $currentcomp = DealerUserCompany::with('dealeruser')->where('dealer_user_id', Auth::user()->id)
            ->where('is_selected', 1)
            ->first();
        $purchasereturn = DB::table('billings')->where('company_id', $currentcomp->company_id)->where('branch_id', $currentcomp->branch_id)->where('id', $id)->first();
        $billingextras = DB::table('billing_extras')->where('billing_id', $purchasereturn->id)->get();
        return view('customerbackend.purchasereturnshow', compact('purchasereturn', 'billingextras'));
    }
}
