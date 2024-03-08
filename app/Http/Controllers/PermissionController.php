<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $role = Auth::user()->email;
            if ($role !== 'superadmin@eidyict.com') {
                abort(503);
            }
            return $next($request);
        });
    }

    public function index()
    {
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        $permissions = Permission::orderBy('title','asc')->get();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
//        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        return view('permissions.create');
    }

    public function store(Request $request)
    {
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:permissions',
        ]);
        $permission = Permission::create($request->all());
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('permission.index');
    }

    public function edit(Permission $permission)
    {
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:permissions,title,'. $permission->id . ',id',
        ]);
        $permission->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect()->route('permission.index');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function destroy(Permission $permission)
    {
//        abort_if(Gate::denies('permission-access'), redirect('error'));
        $permission->delete();
        \Session::flash('flash_message', 'Successfully Deleted');

        return back();
    }

}
