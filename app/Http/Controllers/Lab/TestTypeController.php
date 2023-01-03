<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\TestType;
use App\Http\Requests\TestTypeRequest;
class TestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $testTypes = TestType::latest()
        ->when($request->search, function ($qr) use ($request) {
            return $qr->where('title', 'LIKE', '%'. $request->search . '%');
               })
        ->paginate(20);
        return view('lab.testType.index',compact('testTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testType = new TestType();
        return view('lab.testType.form',compact('testType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestTypeRequest $request)
    {
        if($request->ajax()){
            $testType =  TestType::create($request->except(['_token']));
            return '<option value="'.$testType->id.'">'.$testType->title.'</option>';
        }
        $data = $request->validated();
        $data['createdBy'] = auth()->id();
        try {
            $testType =  TestType::create($data);
            request()->session()->flash('success', 'Test Type ' . $testType->title . '  created Successfully');
            return redirect()->route('test-type.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
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
    public function edit(TestType $testType)
    {
        return view('lab.testType.form',compact('testType'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TestTypeRequest $request, TestType $testType)
    {
        $data = $request->validated();
        try {
            $testType->update($data);
            request()->session()->flash('success', 'Test Type ' . $testType->title . ' updated Successfully');
            return redirect()->route('test-type.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestType $testType)
    {
        try {
            $testType->delete();
            request()->session()->flash('success', 'Test Type' . $testType->title . ' deleted Successfully');
            return redirect()->route('test-type.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
