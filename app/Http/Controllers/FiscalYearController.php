<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FiscalYear;
use Yajra\Datatables\Datatables;
use function App\NepaliCalender\datenep;

class FiscalYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fiscalyears = FiscalYear::get();
        if($request->ajax()){
            return Datatables::of($fiscalyears)
            ->editColumn('created_at',function($row){
                if(!empty($row->created_at)){
                    return  date('M d Y',strtotime($row->created_at));
                }else{
                    return '';
                }

            })

            ->rawColumns(['created_at'])
            ->make(true);
        }
        return view('backend.fiscalyear.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('backend.fiscalyear.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $thisyear = date('Y-m-d');
        $nepalidate = datenep($thisyear);
        $explodeyear = explode('-',$nepalidate);
        if($request->start_year > $explodeyear[0] || $request->end_year > $explodeyear[0] + 1){
            return redirect()->back()->with('error','You Cannot Create Next Fiscal Year.Next Year will be Created Automatically');
        }
        $request->validate([
            'start_year'=>'required',
            'end_year'=>'required',
        ]);
        $fiscalyear = $request->start_year.'/'.$request->end_year;
        if(!FiscalYear::where('fiscal_year','LIKE',$fiscalyear)->first()){
            FiscalYear::create(['fiscal_year'=>$fiscalyear]);
        return redirect()->back()->with('success',"Successfully Created Fiscal Year");
        }else{
            return redirect()->back()->with('success',"Already Exist Fiscal Year");
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
