<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class ExpenseTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        $expense_type = ExpenseType::all();
        return view('expense_type.index', compact('expense_type'));
    }
    public function show(ExpenseType $expense_type)
    {
//        dd($expense_type);
        return view('expense_type.show',compact('expense_type'));
    }
    public function destroy(ExpenseType $expense_type)
    {
        abort_if(Gate::denies('ExpenseDelete'), redirect('error'));
        if ($expense_type->expense->count()) {
            \Session::flash('flash_message', 'Can\'t Delete this, ' . $expense_type->expense->count() . ' nos used');
            return Redirect::back();
        } else {
            $expense_type->delete();
            \Session::flash('flash_message','Successfully Deleted');
            return redirect('expense');
        }
    }
    public function create()
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        return view('expense_type.create');
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $this->validate($request, [
            'expense_name' => 'required|unique:expense_types',
        ]);
        $input = $request->all();
        ExpenseType::create($input);
        \Session::flash('flash_message','Successfully Added');
        return redirect('expense_type');
    }
    public function edit(ExpenseType $expense_type)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        return view('expense_type.edit',compact('expense_type'));
    }
    public function update(Request $request, ExpenseType $expense_type)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $this->validate($request, [
            'expense_name' => 'required|unique:expense_types',
        ]);
        $expense_type->update($request->all());

        \Session::flash('flash_message','Successfully Updated');
        return redirect('expense_type');
    }

}
