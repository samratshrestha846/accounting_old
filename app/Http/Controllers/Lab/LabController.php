<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabRequest;
use App\Models\Lab\Lab;
use App\Models\User;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labs = Lab::paginate(20);
        return view('lab.lab.index', compact('labs'));
    }

    public function create()
    {
        $lab = new Lab();
        $users = $this->getUser();
        return view('lab.lab.form', compact('lab','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabRequest $request)
    {
        $data = $request->validated();
        try {
            $lab = Lab::create($data);
            request()->session()->flash('success', 'lab ' . $lab->title . '  created Successfully');
            return redirect()->route('lab.show', $lab->id);
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
        $lab = Lab::findorfail($id);
        return view('lab.lab.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lab = Lab::findorfail($id);
        $users = array_merge([['id' => $lab->labIncharge, 'name' => $lab->incharge->name]], $this->getUser($id));
        return view('lab.lab.form', compact('lab','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LabRequest $request, $id)
    {
        $lab = Lab::findorfail($id);
        $data = $request->validated();
        try {
            $lab->update($data);
            request()->session()->flash('success', 'lab ' . $lab->title . ' updated Successfully');
            return redirect()->route('lab.show', $lab->id);
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
    public function destroy($id)
    {
        $lab = Lab::findorfail($id);
        try {
            $lab->delete();
            request()->session()->flash('success', 'lab ' . $lab->title . ' deleted Successfully');
            return redirect()->route('lab.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function getUser($id = 0)
    {
        return User::query()
            ->select('users.id', 'users.name')
            ->leftJoin('labs', 'labs.labIncharge', 'users.id')
            ->where(
                fn ($query) =>
                $query->where('labs.labIncharge', '=', null)
            )
            ->join('users_roles', 'users_roles.user_id', 'users.id')
            ->where('users_roles.role_id', '<>', 1)
            ->get()
            ->toArray();
    }
}
