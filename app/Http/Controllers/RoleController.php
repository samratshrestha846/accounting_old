<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
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
        if ($request->user()->can('view-role')) {
            $roles = Role::with('permissions')->latest()->where('id', '!=', 1)->paginate(10);

            return view('backend.roles.index', compact('roles'));
        } else {
            return view('backend.permission.permission');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('backend.roles.search', compact('roles'));
    }

    public function deletedroles(Request $request)
    {
        if ($request->user()->can('manage-trash')) {
            $roles = Role::onlyTrashed()->with('permissions')->latest()->paginate(10);

            return view('backend.trash.userroletrash', compact('roles'));
        } else {
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
        if ($request->user()->can('create-role')) {
            $permissions = Permission::get()->groupBy('column_name')->sortBy(function ($permission, $key) {
                return count($permission);
            });
            return view('backend.roles.create', compact('permissions'));
        } else {
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
        if ($request->user()->can('create-role')) {
            $data = $this->validate($request, [
                'name' => 'required|string',
                'permissions' => 'required',
                'permissions.' => 'integer',
            ]);

            $slug = Str::slug($data['name']);
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $slug,
            ]);
            $permissions = $data['permissions'];
            foreach ($permissions as $permission) {
                $role->permissions()->attach($permission);
            }
            $role->save();
            return redirect()->route('roles.index')->with('success', 'Role Created Successfully');
        } else {
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
        if ($request->user()->can('edit-role')) {
            $role = Role::findorfail($id);
            $permissions = Permission::get()->groupBy('column_name')->sortBy(function ($permission, $key) {
                return count($permission);
            });
            $roles_permissions = RolePermission::where('role_id', $id)->get();
            $selectedperm = array();
            foreach ($roles_permissions as $rolepermission) {
                $selectedperm[] = $rolepermission->permission_id;
            }
            return view('backend.roles.edit', compact('role', 'permissions', 'selectedperm'));
        } else {
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
        if ($request->user()->can('edit-role')) {
            $role = Role::findorfail($id);
            $data = $this->validate($request, [
                'name' => 'required|string',
                'permissions' => 'required',
                'permissions.' => 'integer',
            ]);

            $slug = Str::slug($data['name']);
            $role->update([
                'name' => $data['name'],
                'slug' => $slug,
            ]);
            $permissions = $data['permissions'];
            $perm = array();
            foreach ($permissions as $permission) {
                $perm[] = $permission;
                $role->permissions()->sync($perm);
            }
            $role->save();
            return redirect()->route('roles.index')->with('success', 'Role Updated Successfully');
        } else {
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
        if ($request->user()->can('remove-role')) {
            return redirect()->route('roles.index')->with('error', "Can't Delete roles.");
            $role = Role::findorFail($id);
            $userrolecount = UserRole::where('role_id', $id)->count();
            if ($userrolecount > 0) {
                return redirect()->back()->with('error', "Can't Delete! There are users inside this role");
            } else {
                $role->delete();
                return redirect()->route('roles.index')->with('success', 'Role Deleted Successfully');
            }
        } else {
            return view('backend.permission.permission');
        }
    }

    public function restoreroles(Request $request, $id)
    {
        if ($request->user()->can('manage-trash')) {
            $role = Role::onlyTrashed()->findorFail($id);
            $role->restore();
            return redirect()->route('roles.index')->with('success', 'Role is restored successfully.');
        } else {
            return view('backend.permission.permission');
        }
    }
}
