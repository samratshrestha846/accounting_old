<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VatRefund;
use App\Models\FiscalYear;
use Illuminate\Support\Facades\DB;

use function App\NepaliCalender\datenep;
use Yajra\Datatables\Datatables;

class VatrefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $vatrefunds = VatRefund::all();
        if($request->ajax()){
            return Datatables::of($vatrefunds)
            ->editColumn('created_at',function($row){
                if(!empty($row->created_at)){
                    return  date('M d Y',strtotime($row->created_at));
                }else{
                    return '';
                }

            })
            ->editColumn('isrefunded',function($row){
                if($row->refunded == 0){
                    return "NO";
                }else{
                    return 'YES';
                }
            })
            ->editColumn('action',function($row){
                $refundclass = ($row->refunded == 0) ? 'fa fa-times' : 'fa fa-check';
                $btn ='<div class="btn-bulk justify-content-center">';
                $btn .= "<a href='".route('vatRefundVerificationStatus',$row->id)."' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='Refund'><i class='".$refundclass."'></i></a>";
                $btn .='</div>';
                return $btn;
            })
            ->rawColumns(['created_at','isrefunded','action'])
            ->make(true);
        }

        return view('backend.vat_refund.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['fiscal_years'] = FiscalYear::all();
        return view('backend.vat_refund.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fiscalyear = FiscalYear::find($request->fiscal_year_id);
        if($fiscalyear){
            $existfiscalyear = explode('/',$fiscalyear->fiscal_year);

            $thisyear = date('Y-m-d');
            $nepalidate = datenep($thisyear);
            $explodeyear = explode('-',$nepalidate);
            if($existfiscalyear[0] > $explodeyear[0] || $existfiscalyear[0] == $explodeyear[0]){
                return redirect()->back()->with('error','You Cannot Create. Vat Refund will be Created Automatically for this Year and Next Year. ');
            }
        }

        $request->validate([
            'fiscal_year_id'=>'required|unique:vat_refunds',
            'amount'=>'required',

            'refunded'=>'required',
        ]);
        DB::beginTransaction();
        try{
            $vat_refund = $request->except(['_token','_method']);
            $isrefunded = $request->refunded;

            $vat_refund['fiscal_year'] = $fiscalyear->fiscal_year;
            $fiscal_first_year = $this->explodeyear($fiscalyear->fiscal_year);
            $insert=VatRefund::create($vat_refund);
            // if($isrefunded == 0){
            //     foreach(VatRefund::all()->except($insert->id) as $refunds){
            //         if($this->explodeyear($refunds->fiscal_year) > $fiscal_first_year ){
            //             $refunds->update([
            //                 'due'=>$refunds->due + $insert->total_amount,
            //                 'total_amount'=>$refunds->total_amount + $insert->total_amount,
            //                 'refunded'=>0,
            //             ]
            //             );
            //         }

            //     }
            // }
        DB::commit();
        return redirect()->back()->with('success','Created Successfully');

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function explodeyear($year){
       $explode =  explode('/',$year);
       return $explode[0];
    }

    public function vatRefundVerificationStatus($id){
        $vatrefund = VatRefund::find($id);
        $first_fiscal_year = $this->explodeyear($vatrefund->fiscal_year);
        $prev_fiscal_year = $first_fiscal_year - 1;
        $previous_fiscal_year = VatRefund::where('fiscal_year','LIKE', $prev_fiscal_year.'/'.'%')->first();
        DB::beginTransaction();
        try{
            if(!empty($previous_fiscal_year) && $previous_fiscal_year->refunded == 0){
                return redirect()->back()->with('error','Please Verify Previous Refund');
            }
            if($vatrefund->refunded == 1){
                $vatrefund->update(['refunded'=>'0']);
                $message = 'Unverified Successfuly';
            }else{
                $vatrefund->update(['refunded'=>'1']);
                $message = 'Verified Successfuly';
            }
            DB::commit();
            return redirect()->back()->with('success',$message);

        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
