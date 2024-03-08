<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
//        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        if (Auth::user()->email == 'superadmin@eidyict.com')
            $roles= Role::all();
        else
            $roles = Role::where('title','!=','Super Admin')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        if (Auth::user()->email == 'superadmin@eidyict.com')
            $permissions = Permission::all()->pluck('title', 'id');
        else
            $permissions = Permission::where('title', '!=', 'operationMode-edit')
                ->where('title', '!=', 'settings-access')
                ->where('title', '!=', 'permission-access')->pluck('title', 'id');

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('role.index');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        if (Auth::user()->email == 'superadmin@eidyict.com')
            $permissions = Permission::all()->pluck('title', 'id');
        else
            $permissions = Permission::where('title', '!=', 'operationMode-edit')
                ->where('title', '!=', 'settings-access')
                ->where('title', '!=', 'permission-access')->pluck('title', 'id');
        $role->load('permissions');

        return view('roles.edit', compact('permissions', 'role'));
    }

    public function update(Request $request, Role $role)
    {
        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        \Session::flash('flash_message', 'Successfully Updated');

        return redirect()->route('role.index');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('RoleAccess'), redirect('error'));
        $role->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return back();
    }

}
