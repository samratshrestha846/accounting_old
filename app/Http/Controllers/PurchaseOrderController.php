<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CancelledPurchaseOrders;
use App\Models\Category;
use App\Models\ClientOrder;
use App\Models\FiscalYear;
use App\Models\Province;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderExtra;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\UserCompany;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\DB;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class PurchaseOrderController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $number = 10;
            $purchaseOrders = PurchaseOrder::latest()->where( 'status', 1 )->where( 'is_cancelled', 0 )->paginate( $number );
            $fiscal_years = FiscalYear::latest()->get();
            $date = date( 'Y-m-d' );
            $nepalidate = datenep( $date );
            $exploded_date = explode( '-', $nepalidate );

            $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year )->first();
            $actual_year = explode( '/', $current_fiscal_year->fiscal_year );
            return view( 'backend.purchase_order.index', compact( 'purchaseOrders', 'number', 'fiscal_years', 'actual_year', 'current_fiscal_year' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create(Request $request) {
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $fiscal_years = FiscalYear::latest()->get();
            $date = date( 'Y-m-d' );
            $nepalidate = datenep( $date );
            $exploded_date = explode( '-', $nepalidate );

            $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year )->first();
            $categories = Category::with( 'products' )->get();
            $provinces = Province::latest()->get();
            $taxes = Tax::latest()->get();
            $vendors = Vendor::latest()->get();
            $allsuppliercodes = [];
            foreach ( $vendors as $supplier ) {
                array_push( $allsuppliercodes, $supplier->supplier_code );
            }
            $supplier_code = 'SU'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );

            $purchase_order_count = PurchaseOrder::count();

            if($purchase_order_count == 0)
            {
                $purchaseOrderNo = str_pad( 1, 8, '0', STR_PAD_LEFT );
                $purchaseOrderNo = 'PO-'.$purchaseOrderNo;
            }
            else
            {
                $last_purchase_order = PurchaseOrder::latest()->first();
                $new_purchase_order_no = $last_purchase_order->purchase_order_no;
                $exppo = explode('-', $new_purchase_order_no);
                $po = $exppo[1]+1;

                $purchaseOrderNo = str_pad($po, 8, "0", STR_PAD_LEFT);
                $purchaseOrderNo = 'PO-'.$purchaseOrderNo;
            }

            $units = Unit::all();
            return view( 'backend.purchase_order.create', compact( 'purchaseOrderNo', 'fiscal_years', 'current_fiscal_year', 'categories', 'provinces', 'taxes', 'allsuppliercodes', 'supplier_code', 'units') );
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

    public function store( Request $request )
    {
        $this->validate( $request, [
            'fiscal_year' => 'required',
            'nep_date' => 'required',
            'eng_date' => 'required',
            'supplier' => 'required',
            'purchase_order_no' => 'required',
            'particulars' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'rate' => 'required',
            'total' => 'required',
            'subtotal' => 'required',
            'taxamount' => '',
            'alltaxtype' => '',
            'alltax' => '',
            'grandtotal' => '',
            'status' => '',
            'remarks' => 'required',
        ] );

        if ( $request['status'] == 1 ) {
            $approval_by = Auth::user()->id;
        } else {
            $approval_by = null;
        }

        $thisday = date( 'Y-m-d' );

        if ( $request['eng_date'] == $thisday ) {
            $is_realtime = 1;
        } else {
            $is_realtime = 0;
        }
        DB::beginTransaction();
        try{

            $purchaseOrder = PurchaseOrder::create( [
                'general_stock' => 1,
                'vendor_id' => $request['supplier'],
                'purchase_order_no' => $request['purchase_order_no'],
                'remarks' => $request['remarks'],
                'eng_date' => $request['eng_date'],
                'nep_date' => $request['nep_date'],
                'entry_by' => Auth::user()->id,
                'status' => $request['status'],
                'fiscal_year_id' => $request['fiscal_year'],
                'alltaxtype' => $request['alltaxtype'],
                'alltax' => $request['alltax'],
                'taxamount' => $request['taxamount'],
                'subtotal' => $request['subtotal'],
                'grandtotal' => $request['grandtotal'],
                'approved_by' => $approval_by,
                'is_realtime'=>$is_realtime
            ] );

            $purchaseOrder->save();

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $total = $request['total'];
            $count = count( $particulars );

            for ( $x = 0; $x<$count; $x++ ) {
                $purchaseOrderExtra = PurchaseOrderExtra::create( [
                    'purchase_order_id' => $purchaseOrder['id'],
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'rate' => $rate[$x],
                    'unit' => $unit[$x],
                    'total' => $total[$x],
                ] );

                $purchaseOrderExtra->save();
            }
            DB::commit();
            return redirect()->route( 'purchaseOrder.index' )->with( 'success', 'Purchase order information saved successfully.' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\PurchaseOrder  $purchaseOrder
    * @return \Illuminate\Http\Response
    */

    public function show( Request $request, $id ) {
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $purchaseOrder = PurchaseOrder::findorFail( $id );
            return view( 'backend.purchase_order.show', compact( 'purchaseOrder' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\PurchaseOrder  $purchaseOrder
    * @return \Illuminate\Http\Response
    */

    public function edit( Request $request, $id ) {
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $fiscal_years = FiscalYear::latest()->get();
            $date = date( 'Y-m-d' );
            $nepalidate = datenep( $date );
            $exploded_date = explode( '-', $nepalidate );

            $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year )->first();
            $categories = Category::with( 'products' )->get();
            $provinces = Province::latest()->get();
            $taxes = Tax::latest()->get();
            $vendors = Vendor::latest()->get();
            $purchaseOrder = PurchaseOrder::findorFail( $id );
            $units = Unit::all();

            return view( 'backend.purchase_order.edit', compact( 'purchaseOrder', 'fiscal_years', 'current_fiscal_year', 'categories', 'provinces', 'taxes', 'vendors', 'units' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\PurchaseOrder  $purchaseOrder
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $purchaseOrder = PurchaseOrder::findorFail( $id );
        $purchaseOrderExtras = PurchaseOrderExtra::where( 'purchase_order_id', $purchaseOrder->id )->get();
        $this->validate( $request, [
            'fiscal_year' => 'required',
            'nep_date' => 'required',
            'eng_date' => 'required',
            'supplier' => 'required',
            'purchase_order_no' => 'required',
            'particulars' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'rate' => 'required',
            'total' => 'required',
            'subtotal' => 'required',
            'taxamount' => '',
            'alltaxtype' => '',
            'alltax' => '',
            'grandtotal' => '',
            'status' => '',
            'remarks' => 'required',
        ] );

        if ( $request['status'] == 1 ) {
            $approval_by = Auth::user()->id;
        } else {
            $approval_by = null;
        }

        $thisday = date( 'Y-m-d' );

        if ( $request['eng_date'] == $thisday ) {
            $is_realtime = 1;
        } else {
            $is_realtime = 0;
        }
        DB::beginTransaction();
        try{

            $purchaseOrder->update( [
                'general_stock' => 1,
                'vendor_id' => $request['supplier'],
                'purchase_order_no' => $request['purchase_order_no'],
                'remarks' => $request['remarks'],
                'eng_date' => $request['eng_date'],
                'nep_date' => $request['nep_date'],
                'entry_by' => Auth::user()->id,
                'status' => $request['status'],
                'fiscal_year_id' => $request['fiscal_year'],
                'alltaxtype' => $request['alltaxtype'],
                'alltax' => $request['alltax'],
                'taxamount' => $request['taxamount'],
                'subtotal' => $request['subtotal'],
                'grandtotal' => $request['grandtotal'],
                'approved_by' => $approval_by,
                'is_realtime'=>$is_realtime
            ] );

            foreach ( $purchaseOrderExtras as $extra ) {
                $extra->delete();
            }

            $particulars = $request['particulars'];
            $quantity = $request['quantity'];
            $unit = $request['unit'];
            $rate = $request['rate'];
            $total = $request['total'];
            $count = count( $particulars );

            for ( $x = 0; $x<$count; $x++ ) {
                $purchaseOrderExtra = PurchaseOrderExtra::create( [
                    'purchase_order_id' => $purchaseOrder['id'],
                    'particulars' => $particulars[$x],
                    'quantity' => $quantity[$x],
                    'rate' => $rate[$x],
                    'unit' => $unit[$x],
                    'total' => $total[$x],
                ] );

                $purchaseOrderExtra->save();
            }
            DB::commit();
            return redirect()->route( 'purchaseOrder.index' )->with( 'success', 'Purchase order information updated successfully.' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());

        }
    }

    public function filterByNumber( Request $request ) {
        $fiscal_years = FiscalYear::latest()->get();
        $date = date( 'Y-m-d' );
        $nepalidate = datenep( $date );
        $exploded_date = explode( '-', $nepalidate );

        $current_year = $exploded_date[0].'/'.( $exploded_date[0] + 1 );
        $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year )->first();
        $actual_year = explode( '/', $current_fiscal_year->fiscal_year );

        if ( $request['number_to_filter'] == null ) {
            $number = 10;
        } else {
            $number = $request['number_to_filter'];
        }

        $purchaseOrders = PurchaseOrder::latest()->where( 'status', 1 )->where( 'is_cancelled', 0 )->paginate( $number );
        return view( 'backend.purchase_order.index', compact( 'number', 'fiscal_years', 'actual_year', 'current_fiscal_year', 'purchaseOrders' ) );
    }

    public function purchaseOrderReport( Request $request ) {
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $fiscal_year = $request['fiscal_year'];
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];

            $start_date = dateeng( $starting_date );
            $end_date = dateeng( $ending_date );

            if ( $start_date > $end_date ) {
                return redirect()->back()->with( 'error', 'Starting date cannot be greater than ending date.' );
            }

            $start_date_explode = explode( '-', $starting_date );
            $end_date_explode = explode( '-', $ending_date );

            if ( ( $end_date_explode[0] - $start_date_explode[0] ) > 1 ) {
                return redirect()->back()->with( 'error', 'Select dates within a fiscal year.' );
            }

            if ( $request['number_to_filter'] == null ) {
                $number = 10;
            } else {
                $number = $request['number_to_filter'];
            }

            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $fiscal_year )->first();
            $actual_year = explode( '/', $current_fiscal_year->fiscal_year );
            $fiscal_years = FiscalYear::all();

            $purchaseOrders = PurchaseOrder::latest()->where( 'eng_date', '>=', $start_date )->where( 'eng_date', '<=', $end_date )->where( 'status', '1' )->where( 'is_cancelled', '0' )->paginate( $number );

            return view( 'backend.purchase_order.report', compact( 'number', 'starting_date', 'start_date', 'end_date', 'ending_date', 'purchaseOrders', 'current_fiscal_year', 'actual_year', 'fiscal_years' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function multiunapprove( Request $request )
    {
        foreach ( $request['id'] as $id ) {
            $purchaseOrder = PurchaseOrder::findorFail( $id );
            $purchaseOrder->update( [
                'status'=>'0',
                'approved_by'=>null,
            ] );
        }
        return response()->json( ['success'=>'Selected purchase orders Successfully Unapproved'] );
    }

    public function multiapprove( Request $request ) {
        foreach ( $request['id'] as $id ) {
            $purchaseOrder = PurchaseOrder::findorFail( $id );
            $purchaseOrder->update( [
                'status'=>'1',
                'approved_by'=>null,
            ] );
        }
        return response()->json( ['success'=>'Selected purchase orders Successfully Approved'] );
    }

    public function status( $id ) {
        $purchaseOrder = PurchaseOrder::findorFail( $id );
        if ( $purchaseOrder->status == 0 ) {
            $purchaseOrder->update( ['status' => 1] );
            return redirect()->route( 'purchaseOrder.index' )->with( 'success', 'Purchase Order is successfully approved' );
        } else {
            $purchaseOrder->update( ['status' => 0] );
            return redirect()->route( 'purchaseOrder.index' )->with( 'success', 'Purchase Order is successfully unapproved' );
        }
    }

    public function unapprovedPurchaseOrders() {
        $number = 10;
        $purchaseOrders = PurchaseOrder::latest()->where( 'status', 0 )->where( 'is_cancelled', 0 )->paginate( $number );
        return view( 'backend.purchase_order.unapprovedPurchaseOrders', compact( 'purchaseOrders', 'number' ) );
    }

    public function cancelledPurchaseOrders() {
        $number = 10;
        $purchaseOrders = PurchaseOrder::latest()->where( 'is_cancelled', 1 )->paginate( $number );
        return view( 'backend.purchase_order.cancelledPurchaseOrders', compact( 'purchaseOrders', 'number' ) );
    }

    public function cancel( Request $request ) {
        $this->validate( $request, [
            'purchase_order_id'=> 'required',
            'reason'=>'required',
            'description'=>'required',
        ] );

        $purchaseOrder = PurchaseOrder::findorFail( $request['purchase_order_id'] );
        $user = Auth::user()->id;
        $current_fiscal_year = FiscalYear::where( 'id', $purchaseOrder->fiscal_year_id )->first();
        $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        $engtoday = $purchaseOrder->eng_date;
        $date_in_nepali = datenep( $engtoday );
        $explodenepali = explode( '-', $date_in_nepali );

        $nepdate = ( int )$explodenepali[1] - 1;
        $nepmonth = $monthname[$nepdate];

        $cancellation = CancelledPurchaseOrders::create( [
            'purchase_order_id' => $request['purchase_order_id'],
            'reason' => $request['reason'],
            'description' => $request['description'],
        ] );

        $purchaseOrder->update( [
            'is_cancelled' => '1',
            'cancelled_by' => $user,
        ] );

        $cancellation->save();
        return redirect()->route( 'cancelledPurchaseOrders' )->with( 'success', 'Purchase Order is successfully Cancelled' );
    }

    public function revive( Request $request ) {
        $this->validate( $request, [
            'purchase_order_id' =>'required',
        ] );

        $purchaseOrder = PurchaseOrder::findorfail( $request['purchase_order_id'] );
        $cancelledPurchaseOrder = CancelledPurchaseOrders::where( 'purchase_order_id', $request['purchase_order_id'] )->first();
        $cancelledPurchaseOrder->delete();

        $purchaseOrder->update( [
            'is_cancelled' => '0',
            'cancelled_by' => null,
        ] );

        return redirect()->route( 'purchaseOrder.index' )->with( 'success', 'Purchase Order is successfully revived.' );
    }

    public function generatePurchaseOrderPDF( $id ) {
        $purchaseOrder = PurchaseOrder::with( 'suppliers' )->findorfail( $id );
        $setting = Setting::first();
        $currentcomp = UserCompany::with( 'company' )->where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();

        $opciones_ssl = array(
            'ssl'=>array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            ),
        );

        $img_path = 'uploads/' . $currentcomp->company->company_logo;
        $extencion = pathinfo( $img_path, PATHINFO_EXTENSION );
        $data = file_get_contents( $img_path, false, stream_context_create( $opciones_ssl ) );
        $img_base_64 = base64_encode( $data );
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;

        $pdf = PDF::setOptions( ['defaultFont' => 'sans-serif'] )->setPaper( 'a4', 'portrait' )->loadView( 'backend.purchase_order.purchaseOrderPdf', compact( 'purchaseOrder', 'setting', 'path_img', 'currentcomp' ) );
        $predlcount = $purchaseOrder->downloadcount;
        $newdownloadcount = $predlcount +1;
        $purchaseOrder->update( [
            'downloadcount' => $newdownloadcount,
        ] );

        return $pdf->download( $purchaseOrder->purchase_order_no.'.pdf' );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\PurchaseOrder  $purchaseOrder
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }

    public function search( Request $request ) {
        $search = $request->input( 'search' );
        $purchaseOrders = PurchaseOrder::query()
        ->where( 'purchase_order_no', 'LIKE', "%{$search}%" )
        ->orWhere( 'nep_date', 'LIKE', "%{$search}%" )
        ->where( 'status', '1' )
        ->where( 'is_cancelled', '0' )
        ->paginate( 10 );

        return view( 'backend.purchase_order.search', compact( 'purchaseOrders' ) );
    }

    public function unapprovesearch( Request $request ) {
        $search = $request->input( 'search' );
        $purchaseOrders = PurchaseOrder::query()
        ->where( 'purchase_order_no', 'LIKE', "%{$search}%" )
        ->orWhere( 'nep_date', 'LIKE', "%{$search}%" )
        ->where( 'status', '0' )
        ->where( 'is_cancelled', '0' )
        ->paginate( 10 );

        return view( 'backend.purchase_order.unapprovedsearchPurchaseOrder', compact( 'purchaseOrders' ) );
    }

    public function cancelledsearch( Request $request ) {
        $search = $request->input( 'search' );
        $purchaseOrders = PurchaseOrder::query()
        ->where( 'purchase_order_no', 'LIKE', "%{$search}%" )
        ->orWhere( 'nep_date', 'LIKE', "%{$search}%" )
        ->where( 'status', '1' )
        ->where( 'is_cancelled', '1' )
        ->paginate( 10 );

        return view( 'backend.purchase_order.cancelledSearchPurchaseOrders', compact( 'purchaseOrders' ) );
    }


    public function sendPurchaseOrderEmail($id)
    {
        $purchaseOrder = PurchaseOrder::with( 'suppliers' )->findorfail( $id );
        $email['email'] = $purchaseOrder->suppliers->company_email;
        $setting = Setting::first();
        $currentcomp = UserCompany::with( 'company' )->where( 'user_id', Auth::user()->id )->where( 'is_selected', 1 )->first();

        $opciones_ssl = array(
            'ssl'=>array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            ),
        );

        $img_path = 'uploads/' . $currentcomp->company->company_logo;
        $extencion = pathinfo( $img_path, PATHINFO_EXTENSION );
        $data = file_get_contents( $img_path, false, stream_context_create( $opciones_ssl ) );
        $img_base_64 = base64_encode( $data );
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;

        $pdf = PDF::setOptions( ['defaultFont' => 'sans-serif'] )->setPaper( 'a4', 'portrait' )->loadView( 'backend.purchase_order.purchaseOrderPdf', compact( 'purchaseOrder', 'setting', 'path_img', 'currentcomp' ) );

        Mail::send('emails.billingsMail', $email, function($message)use($email, $pdf)
        {
            $message->to($email["email"])
                    ->subject("Purchase Order Information")
                    ->attachData($pdf->output(), "purchaseOrder.pdf");
        });

        return redirect()->back()->with('success', 'Purchase Order PDF is sent successfully.');
    }

    public function clientorders(Request $request){
        if ( $request->user()->can( 'manage-purchase-orders' ) ) {
            $usercomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected',1)->first();
            $clientorders = ClientOrder::where('company_id', $usercomp->company_id)->where('branch_id', $usercomp->branch_id)->paginate(15);
            // dd($clientorders);
            return view('backend.purchase_order.clientorders', compact('clientorders'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function clientorderssearch( Request $request ) {
        $search = $request->input( 'search' );
        $usercomp = UserCompany::where('user_id', Auth::user()->id)->where('is_selected',1)->first();
        $clientorders = ClientOrder::query()
        ->where( 'order_no', 'LIKE', "%{$search}%")
        ->orWhere( 'nep_date', 'LIKE', "%{$search}%")
        ->orWhereHas('clients', function($query) use  ($search){
            $query->where('name', 'LIKE', "%{$search}%");
        })
        ->where('company_id', $usercomp->company_id)
        ->where('branch_id', $usercomp->branch_id)
        ->paginate(15);

        return view( 'backend.purchase_order.searchclientorders', compact( 'clientorders' ) );
    }
}
