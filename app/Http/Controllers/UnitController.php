<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('unit-access'), redirect('error'));
        $units = Unit::orderBy('title','asc')->get();
        return view('unit.index', compact('units'));
    }

    public function create()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('unit.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:units',
        ]);
        $unit = Unit::create($request->all());
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('unit.index');
    }

    public function edit(Unit $unit)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:units,title,'. $unit->id . ',id',
        ]);
        $unit->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect()->route('unit.index');
    }

    public function show(Unit $unit)
    {
        return view('unit.show', compact('unit'));
    }

    public function destroy(Unit $unit)
    {
        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        if ($unit->products->count()) {
            \Session::flash('flash_error', 'Can\'t Delete this, ' . $unit->products->count() . ' nos used in product');
            return redirect()->back();
        } else {
        $unit->delete();
        \Session::flash('flash_message', 'Successfully Deleted');

        return back();
    }
    }

}
