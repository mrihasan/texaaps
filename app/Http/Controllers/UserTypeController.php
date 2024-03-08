<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $user_types = UserType::all();
        return view('user_type.index', compact(['user_types']));
    }

    public function create()
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $roles = Role::select('id', 'title')->get();
        return view('user_type.create', compact(['roles']));
    }

    public function store(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $user_type = UserType::create($request->all());
        $user_type->user_type_role()->attach($request->roles);

        return redirect('user-type');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $user_type = UserType::find($id);
        $roles = Role::pluck('title', 'id');
        return view('user_type.edit', compact(['user_type', 'roles']));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $user_type = UserType::find($id);
        $user_type->update($request->all());

        $roles = count($request->roles) > 0 ? $request->roles : [];

        $user_type->user_type_role()->sync($roles);
        return redirect('user-type');
    }

    public function show($id)
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $user_type = UserType::find($id);
        return view('user_type.show', compact(['user_type']));
    }

//    public function delete()
//    {
//    }
}
