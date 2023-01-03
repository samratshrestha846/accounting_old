<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\FiscalYear;
use App\Models\TaxInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use function App\NepaliCalender\datenep;

class TaxInfoController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ) {
        if ( $request->user()->can( 'manage-tax-information' ) ) {
            $engtoday = date( 'Y-m-d' );
            $neptoday = datenep( $engtoday );
            $monthint = explode( '-', $neptoday );
            $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

            $nepdate = ( int )$monthint[1] - 1;
            $nepmonth = $monthname[$nepdate];

            $current_year = $monthint[0].'/'.( $monthint[0] + 1 );
            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $current_year )->first();
            $taxinfos = TaxInfo::orderBy( 'id', 'Desc' )->paginate( 10 );
            $taxdetail = TaxInfo::latest()->where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->first();
            return view( 'backend.taxinfo.index', compact( 'taxdetail', 'taxinfos' ) );
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    public function update( Request $request, $id ) {
        if ( $request->user()->can( 'manage-tax-information' ) ) {
            DB::beginTransaction();
            try{
            $taxinfos = TaxInfo::where( 'is_paid', 0 )->where( 'id', '!=', $id )->get();
            $this->validate( $request, [
                'file'=>'mimes:pdf',
            ] );
            $imagename = '';
            if ( $request->hasfile( 'file' ) ) {
                $image = $request->file( 'file' );
                $imagename = $image->store( 'tax_info', 'uploads' );
            } else {
                $imagename = null;
            }
            foreach ( $taxinfos as $taxinf ) {
                $taxinf->update( [
                    'is_paid'=>$request['is_paid'],
                    'paid_at'=>$request['paid_at'],
                    'file'=>$imagename,
                ] );

                $taxinf->save();
            }
            $alltaxinfos = Taxinfo::all();
            foreach ( $alltaxinfos as $alltaxinfo ) {
                $alltaxinfo->update( [
                    'due'=>0,
                ] );
                $alltaxinfo->save();
            }
            DB::commit();
            return redirect()->route( 'taxinfo.index' )->with( 'success', 'Successfully Updated' );
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        } else {
            return view( 'backend.permission.permission' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\TaxInfo  $taxInfo
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }

    public function taxcalculate()
    {
        $all_billings = Billing::all();

        foreach ( $all_billings as $billing ) {
            $date_in_nepali = $billing->nep_date;
            $explodenepali = explode( '-', $date_in_nepali );
            if ( $explodenepali[1] < 4 ) {
                $fiscal_year = ( $explodenepali[0] - 1 ).'/'.$explodenepali[0];
            } else {
                $fiscal_year = $explodenepali[0].'/'.( $explodenepali[0] + 1 );
            }

            $current_fiscal_year = FiscalYear::where( 'fiscal_year', $fiscal_year )->first();
            $monthname = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

            $nepdate = ( int )$explodenepali[1] - 1;
            $nepmonth = $monthname[$nepdate];

            if ( $billing->billing_type_id == 2 ) {
                if ( $billing->sync_ird == 1 && $billing->status == 1 ) {
                    $taxcount = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->count();
                    $unpaidtaxes = TaxInfo::where( 'is_paid', 0 )->get();
                    $unpaids = [];
                    foreach ( $unpaidtaxes as $unpaidtax ) {
                        array_push( $unpaids, $unpaidtax->total_tax );
                    }
                    $duetax = array_sum( $unpaids );
                    if ( $taxcount < 1 ) {
                        $purchasetax = TaxInfo::create( [
                            'fiscal_year'=> $current_fiscal_year->fiscal_year,
                            'nep_month'=> $nepmonth,
                            'purchase_tax'=>$billing->taxamount,
                            'total_tax'=>-$billing->taxamount,
                            'is_paid'=>0,
                            'due'=>$duetax,
                        ] );
                        $purchasetax->save();
                    } else {
                        $purchasetax = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->first();
                        $purchase_tax = $purchasetax->purchase_tax + ( float )$billing->taxamount;
                        $total_tax = $purchasetax->total_tax - ( float )$billing->taxamount;
                        $purchasetax->update( [
                            'purchase_tax'=>$purchase_tax,
                            'total_tax'=>$total_tax,
                        ] );
                    }
                }
            } elseif ( $billing->billing_type_id == 1 ) {
                if ( $billing->sync_ird == 1 && $billing->status == 1 ) {
                    $taxcount = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->count();
                    $unpaidtaxes = TaxInfo::where( 'is_paid', 0 )->get();
                    $unpaids = [];
                    foreach ( $unpaidtaxes as $unpaidtax ) {
                        array_push( $unpaids, $unpaidtax->total_tax );
                    }
                    $duetax = array_sum( $unpaids );
                    if ( $taxcount < 1 ) {
                        $salestax = TaxInfo::create( [
                            'fiscal_year'=> $current_fiscal_year->fiscal_year,
                            'nep_month'=> $nepmonth,
                            'sales_tax'=>$billing->taxamount,
                            'total_tax'=>$billing->taxamount,
                            'is_paid'=>0,
                            'due'=>$duetax,
                        ] );
                        $salestax->save();
                    } else {
                        $salestax = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->first();
                        $sales_tax = $salestax->sales_tax + ( float )$billing->taxamount;
                        $total_tax = $salestax->total_tax + ( float )$billing->taxamount;
                        $salestax->update( [
                            'sales_tax'=>$sales_tax,
                            'total_tax'=>$total_tax,
                        ] );
                        $salestax->save();
                    }
                }
            } elseif ( $billing->billing_type_id == 5 ) {
                if ( $billing->sync_ird == 1 && $billing->status == 1 ) {
                    $taxcount = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->count();
                    $unpaidtaxes = TaxInfo::where( 'is_paid', 0 )->get();
                    $unpaids = [];
                    foreach ( $unpaidtaxes as $unpaidtax ) {
                        array_push( $unpaids, $unpaidtax->total_tax );
                    }
                    $duetax = array_sum( $unpaids );
                    if ( $taxcount < 1 ) {
                        $purchasereturntax = TaxInfo::create( [
                            'fiscal_year'=> $current_fiscal_year->fiscal_year,
                            'nep_month'=> $nepmonth,
                            'purchasereturn_tax'=>$billing->taxamount,
                            'total_tax'=>$billing->taxamount,
                            'is_paid'=>0,
                            'due'=>$duetax,
                        ] );
                        $purchasereturntax->save();
                    } else {
                        $purchasereturntax = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->first();
                        $purchasereturn_tax = $purchasereturntax->purchasereturn_tax + ( float )$billing->taxamount;
                        $total_tax = $purchasereturntax->total_tax + ( float )$billing->taxamount;
                        $purchasereturntax->update( [
                            'purchasereturn_tax'=>$purchasereturn_tax,
                            'total_tax'=>$total_tax,
                        ] );
                        $purchasereturntax->save();
                    }
                }
            } elseif ( $billing->billing_type_id == 6 ) {
                if ( $billing->sync_ird == 1 && $billing->status == 1 ) {
                    $taxcount = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->count();
                    $unpaidtaxes = TaxInfo::where( 'is_paid', 0 )->get();
                    $unpaids = [];
                    foreach ( $unpaidtaxes as $unpaidtax ) {
                        array_push( $unpaids, $unpaidtax->total_tax );
                    }
                    $duetax = array_sum( $unpaids );
                    if ( $taxcount < 1 ) {
                        $salesreturntax = TaxInfo::create( [
                            'fiscal_year'=> $current_fiscal_year->fiscal_year,
                            'nep_month'=> $nepmonth,
                            'salesreturn_tax'=>$billing->taxamount,
                            'total_tax'=>-$billing->taxamount,
                            'is_paid'=>0,
                            'due'=>$duetax,
                        ] );
                        $salesreturntax->save();
                    } else {
                        $salesreturntax = TaxInfo::where( 'fiscal_year', $current_fiscal_year->fiscal_year )->where( 'nep_month', $nepmonth )->first();
                        $salesreturn_tax = $salesreturntax->salesreturn_tax + ( float )$billing->taxamount;
                        $total_tax = $salesreturntax->total_tax - ( float )$billing->taxamount;
                        $salesreturntax->update( [
                            'salesreturn_tax'=>$salesreturn_tax,
                            'total_tax'=>$total_tax,
                        ] );
                        $salesreturntax->save();
                    }
                }
            }
        }

        return redirect()->route('taxinfo.index');
    }
}
