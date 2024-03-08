<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchLedger;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branches = Branch::get();
        return view('branch.index', compact('branches'));
    }

    public function create()
    {
//        abort_if(Gate::denies('superadmin-access'), redirect('error'));
        return view('branch.create');
    }

    public function store(Request $request)
    {
//        abort_if(Gate::denies('superadmin-access'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:branches',
            'code_no' => 'nullable|unique:branches',
        ]);

        $branch = new Branch();
        $branch->title = $request->title;
        $branch->code_no = $request->code_no;
        $branch->address = $request->address;
        $branch->contact_no1 = $request->contact_no1;
        $branch->contact_no2 = $request->contact_no2;
        $branch->status = 'Active';
        $branch->save();


//        $s_year = date('Y-m') . '-00 00:00:00';
//        $e_year = date('Y-m') . '-31 23:59:59';
//        $count_ledger = (DB::table('branch_ledgers')
//                ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
//        $transaction_code = 'LB-' . date('ym') . '-' . str_pad($count_ledger, 6, '0', STR_PAD_LEFT);
        $transaction_code = autoTimeStampCode('LB');

        $ledger_banking = new BranchLedger();
        $ledger_banking->branch_id = $branch->id;
        $ledger_banking->transaction_date = date('Y-m-d H:i:s');
        $ledger_banking->transaction_code = $transaction_code;
        $ledger_banking->amount = 0;
        $ledger_banking->transaction_type_id = 1;
        $ledger_banking->transaction_method_id = 1;
        $ledger_banking->comments = 'Opening';
        $ledger_banking->approve_status = 'Approved';
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Added');
        return redirect('branch');
    }


    public function edit(Branch $branch)
    {
        return view('branch.edit',compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
//        dd($request);
        $this->validate($request, [
            'title' => 'required|unique:branches,title,' . $branch->id . ',id',
            'code_no' => 'nullable|unique:branches,code_no,' . $branch->id . ',id',
        ]);
        $branch->title = $request->title;
        $branch->code_no = $request->code_no;
        $branch->address = $request->address;
        $branch->contact_no1 = $request->contact_no1;
        $branch->contact_no2 = $request->contact_no2;
        $branch->status = $request->status;
        $branch->update();

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('branch');
    }

    public function show(Branch $branch)
    {
        return view('branch.show', compact('branch'));
    }


}
