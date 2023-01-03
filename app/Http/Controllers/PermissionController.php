<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->can('view-permission')){
            $permissions = Permission::latest()->paginate(10);

            return view('backend.permission.index', compact('permissions'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request){
        $search = $request->input('search');

        $permissions = Permission::query()
            ->where('column_name', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->paginate(10);
        // dd($permissions);

        return view('backend.permission.search', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('create-permission')){
            return view('backend.permission.create');
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
        if($request->user()->can('create-permission')){
            $data = $this->validate($request, [
                'name'=>'required|string',
            ]);
            $slug = Str::slug($data['name']);
            $permission = Permission::create([
                'name'=>$data['name'],
                'slug'=>$slug,
            ]);
            $permission->save();
            return redirect()->route('permission.index')->with('success', 'Permission Successfully Created');
        }else{
            return view('backend.permission.permission');
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
    public function edit($id,Request $request)
    {
        //
        if($request->user()->can('edit-permission')){
            $permission = Permission::findorfail($id);
            return view('backend.permission.edit', compact('permission'));
        }else{
            return view('backend.permission.permission');
        }
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
        if($request->user()->can('edit-permission')){
            $permission = Permission::findorfail($id);
            $data = $this->validate($request, [
                'name'=>'required|string',
            ]);
            $slug = Str::slug($data['name']);
            $permission->update([
                'name'=>$data['name'],
                'slug'=>$slug,
            ]);
            $permission->save();
            return redirect()->route('permission.index')->with('success', 'Permission Successfully Updated');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //
        if($request->user()->can('remove-permission')){
            $permission = Permission::findorfail($id);
            $permission->delete();
            return redirect()->route('permission.index')->with('success', 'Permission Successfully Deleted');
        }else{
            return view('backend.permission.permission');
        }
    }
}
