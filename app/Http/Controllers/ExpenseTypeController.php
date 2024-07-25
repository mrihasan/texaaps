<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class ExpenseTypeController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
    protected $module;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->module = $request->route('module');
    }

    public function index()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        if ($this->module == 'expense_type') {
            $sidebar['main_menu'] = 'expense';
            $sidebar['main_menu_cap'] = 'Expense';
            $sidebar['module_name_menu'] = 'expense_type';
            $sidebar['module_name'] = 'Expense Type';
            $expense_type = ExpenseType::where('type', 'expense')->get();
            return view('expense_type.index', compact('expense_type', 'sidebar'));
        } elseif ($this->module == 'fixed_asset_type') {
            $sidebar['main_menu'] = 'fixed_asset';
            $sidebar['main_menu_cap'] = 'Fixed Asset';
            $sidebar['module_name_menu'] = 'fixed_asset_type';
            $sidebar['module_name'] = 'Fixed Asset Type';
            $expense_type = ExpenseType::where('type', 'Fixed Asset')->get();
            return view('expense_type.index', compact('expense_type', 'sidebar'));
        }

    }

    public function show( $module, $id)
    {
//        dd($module);
//        return view('expense_type.show', compact('expense_type'));
        if ($this->module == 'expense_type') {
            $sidebar['main_menu'] = 'expense';
            $sidebar['main_menu_cap'] = 'Expense';
            $sidebar['module_name_menu'] = 'expense_type';
            $sidebar['module_name'] = 'Expense Type';
            $expense_type = ExpenseType::where('id', $id)->first();
            return view('expense_type.show', compact('expense_type', 'sidebar'));
        } elseif ($this->module == 'fixed_asset_type') {
            $sidebar['main_menu'] = 'fixed_asset';
            $sidebar['main_menu_cap'] = 'Fixed Asset';
            $sidebar['module_name_menu'] = 'fixed_asset_type';
            $sidebar['module_name'] = 'Fixed Asset Type';
            $expense_type = ExpenseType::where('id', $id)->first();
            return view('expense_type.show', compact('expense_type', 'sidebar'));
        }
    }

    public function create()
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        if ($this->module == 'expense_type') {
            $sidebar['main_menu'] = 'expense';
            $sidebar['main_menu_cap'] = 'Expense';
            $sidebar['module_name_menu'] = 'expense_type';
            $sidebar['module_name'] = 'Expense Type';
            return view('expense_type.create', compact('sidebar'));
        } elseif ($this->module == 'fixed_asset_type') {
            $sidebar['main_menu'] = 'fixed_asset';
            $sidebar['main_menu_cap'] = 'Fixed Asset';
            $sidebar['module_name_menu'] = 'fixed_asset_type';
            $sidebar['module_name'] = 'Fixed Asset Type';
            return view('expense_type.create', compact('sidebar'));
        }
    }

    public function store(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $this->validate($request, [
            'expense_name' => 'required|unique:expense_types',
        ]);
        $input = $request->all();
        ExpenseType::create($input);
        \Session::flash('flash_message', 'Successfully Added');

        if ($request->type == 'Expense')
            return redirect('expense_type/items');
        elseif ($request->type == 'Fixed Asset')
            return redirect('fixed_asset_type/items');
        else
            return redirect('error');
    }

    public function edit($model, $id)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
//        return view('expense_type.edit', compact('expense_type'));
        if ($this->module == 'expense_type') {
            $sidebar['main_menu'] = 'expense';
            $sidebar['main_menu_cap'] = 'Expense';
            $sidebar['module_name_menu'] = 'expense_type';
            $sidebar['module_name'] = 'Expense Type';
            $expense_type = ExpenseType::where('id', $id)->first();
            return view('expense_type.Edit', compact('sidebar','expense_type'));
        } elseif ($this->module == 'fixed_asset_type') {
            $sidebar['main_menu'] = 'fixed_asset';
            $sidebar['main_menu_cap'] = 'Fixed Asset';
            $sidebar['module_name_menu'] = 'fixed_asset_type';
            $sidebar['module_name'] = 'Fixed Asset Type';
            $expense_type = ExpenseType::where('id', $id)->first();
            return view('expense_type.edit', compact('sidebar','expense_type'));
        }

    }

    public function update(Request $request, $module, $item)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $this->validate($request, [
            'expense_name' => 'required|unique:expense_types',
        ]);
        $expense_type = ExpenseType::findOrFail($item);
        $expense_type->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');

        if ($expense_type->type == 'Expense')
            return redirect('expense_type/items');
        elseif ($expense_type->type == 'Fixed Asset')
            return redirect('fixed_asset_type/items');
        else
            return redirect('error');
    }

    public function destroy($module, $item)
    {
//        dd('te');
        abort_if(Gate::denies('ExpenseDelete'), redirect('error'));

        $expense_type = ExpenseType::where('id', $item)->first();
        $type=$expense_type->type;
        if ($expense_type->expense->count()) {
            \Session::flash('flash_message', 'Can\'t Delete this, ' . $expense_type->expense->count() . ' nos used');
            return Redirect::back();
        } else {
            $expense_type->delete();
            \Session::flash('flash_message', 'Successfully Deleted');
            if ($type == 'Expense')
                return redirect('expense_type/items');
            elseif ($type == 'Fixed Asset')
                return redirect('fixed_asset_type/items');
            else
                return redirect('error');
        }
    }

}
