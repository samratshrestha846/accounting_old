<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Http\Request;
use DataTables;

class SeriesController extends Controller
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
        if($request->user()->can('manage-series-information')){
            if($request->ajax()) {
                $data = Series::latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('brand_name', function($row) {
                        $brand = $row->brand->brand_name;
                        return $brand;
                    })
                    ->addColumn('action', function($row){
                        $editurl = route('series.edit', $row->id);
                        $deleteurl = route('series.destroy', $row->id);
                        $csrf_token = csrf_token();
                            $btn = "<div class='btn-bulk'><a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletechild$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button></div>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deletechild$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class='modal-body text-center'>
                                                        <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                        <label for='reason'>Are you sure you want to delete??</label><br>
                                                        <input type='hidden' name='_method' value='DELETE' />
                                                            <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ";

                        return $btn;
                    })
                    ->rawColumns(['brand_name','action'])
                    ->make(true);
            }
            return view('backend.series.index');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function deletedseries(Request $request)
    {
        if($request->user()->can('manage-trash')){
            if ($request->ajax()) {
                $data = Series::onlyTrashed()->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('brand_name', function($row) {
                        $brand = Brand::where('id', $row->brand_id)->first();

                        if ($brand) {
                            $brand_name = $row->brand->brand_name;
                        } else {
                            $trashed_brand = Brand::onlyTrashed()->where('id', $row->brand_id)->first();
                            $brand_name = $trashed_brand->brand_name;
                        }
                        return $brand_name;
                    })
                    ->addColumn('action', function($row)
                    {
                        $restoreurl = route('restoreseries', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restoreaccount$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restoreaccount$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body text-center'>
                                                    <label for='reason'>Are you sure you want to restore??</label><br>
                                                    <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";

                        return $btn;
                    })
                    ->rawColumns(['brand_name', 'action'])
                    ->make(true);
            }
            return view('backend.trash.seriestrash');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('manage-series-information')){
            $brands = Brand::latest()->get();
            $series = Series::all();

            $allseriescodes = [];
            foreach($series as $serie){
                array_push($allseriescodes, $serie->series_code);
            }
            $series_code = 'SE'.str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            return view('backend.series.create', compact('brands', 'allseriescodes', 'series_code'));
        }else{
            return view('backend.permission.permission');
        }
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
        $this->validate($request, [
            'related_brand' => 'required',
            'series_name' => 'required',
            'series_code' => 'required|unique:series'
        ]);

        Series::create([
            'brand_id' => $request['related_brand'],
            'series_name' => $request['series_name'],
            'series_code' => $request['series_code'],
        ]);
        if($saveandcontinue == 1){
            return redirect()->back()->with('success', 'Series Information successfully saved.');
        }
        return redirect()->route('series.index')->with('success', 'Series Information successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->user()->can('manage-series-information')){
            $selected_series = Series::findorFail($id);
            $brands = Brand::all();
            $series = Series::all();

            $allseriescodes = [];
            foreach($series as $serie){
                array_push($allseriescodes, $serie->series_code);
            }
            return view('backend.series.edit', compact('selected_series', 'brands', 'allseriescodes'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $selected_series = Series::findorFail($id);
        $this->validate($request, [
            'related_brand' => 'required',
            'series_name' => 'required',
            'series_code' => 'required|unique:series,series_code,'.$selected_series->id
        ]);

        $selected_series->update([
            'brand_id' => $request['related_brand'],
            'series_name' => $request['series_name'],
            'series_code' => $request['series_code'],
        ]);

        return redirect()->route('series.index')->with('success', 'Series Information successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->can('manage-series-information')){
            $selected_series = Series::findorFail($id);
            $products = Product::where('series_id', $selected_series->id)->get();

            if(count($products) > 0){
                return redirect()->back()->with('error', 'Products are available in brand. Cant delete.');
            }else{
                $selected_series->delete();

                return redirect()->route('series.index')->with('success', 'Series Information successfully deleted.');
            }

        }else{
            return view('backend.permission.permission');
        }
    }

    public function restoreseries(Request $request, $id)
    {
        if($request->user()->can('manage-trash')){
            $deleted_series = Series::onlyTrashed()->findorFail($id);
            $deleted_series->restore();
            return redirect()->route('series.index')->with('success', 'Series information is restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }
}
