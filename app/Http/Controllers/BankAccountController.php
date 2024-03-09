<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankLedger;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $banking = BankAccount::latest()->get();
        return view('banking.index', compact('banking'));
    }

    public function create()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        return view('banking.create',compact('branches'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'branch' => 'required',
            'account_name' => 'required',
            'account_no' => 'required|unique:bank_accounts',
            'account_type' => 'required',
            'opening_balance' => 'required',
        ]);
        $banking = new BankAccount();
        $banking->account_name = $request->account_name;
        $banking->account_no = $request->account_no;
        $banking->account_type = $request->account_type;
        $banking->details = $request->details;
        $banking->save();

        $transaction_code = autoTimeStampCode('BL');;

        $ledger_banking = new BankLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->bank_account_id = $banking->id;
        $ledger_banking->transaction_date = date('Y-m-d H:i:s');
        $ledger_banking->transaction_code = $transaction_code;
        $ledger_banking->amount = $request->opening_balance;
        $ledger_banking->transaction_type_id = 8;
        $ledger_banking->transaction_method_id = 1;
        $ledger_banking->ref_date = null;
        $ledger_banking->particulars = 'Opening';
        $ledger_banking->approve_status = 'Approved';
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Added');
        return redirect('bank_account');
    }

    public function show(BankAccount $banking)
    {
        return view('banking.show', compact('banking'));
    }

    public function edit(BankAccount $banking)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        return view('banking.edit',compact('banking'));
    }

    public function update(Request $request, BankAccount $banking)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'account_no' => 'required|unique:bankings,account_no,' . $banking->id . ',id',
            'account_name' => 'required',
            'account_type' => 'required',
//            'opening_balance' => 'required',
        ]);
        $banking->account_name = $request->account_name;
        $banking->account_no = $request->account_no;
        $banking->account_type = $request->account_type;
        $banking->details = $request->details;
//        $banking->opening_balance = $request->opening_balance;
//        $banking->is_status = 1;
        $banking->update();
        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('banking');
    }

    public function destroy(BankAccount $banking)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $banking->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return redirect('banking');
    }

}
