<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankLedger;
use App\Models\BranchLedger;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DateTime;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function bankac($tr_type)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        if ($tr_type=='Bank Account'){
            $banking = BankAccount::where('account_type','!=','Loan Account')->latest()->get();
        }
        elseif ($tr_type=='Loan Account'){
            $banking = BankAccount::where('account_type','Loan Account')->latest()->get();
        }
        else
        {
            $banking = BankAccount::latest()->get();
        }
        return view('banking.index', compact('banking','tr_type'));
    }
    public function index()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $banking = BankAccount::latest()->get();
        $tr_type='All';
        return view('banking.index', compact('banking','tr_type'));
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
        $banking->bank_name = $request->bank_name;
        $banking->account_no = $request->account_no;
        $banking->account_type = $request->account_type;
        $banking->details = $request->details;
        $banking->save();

        $transaction_code = autoTimeStampCode('BL');
        $transaction_date = date('Y-m-d H:i:s');
        $td = new DateTime($transaction_date);
//        $sl_no=createSl('TA-BL-','bank_ledgers','transaction_date',date('Y-m-d H:i:s'));
        $sl_no = createSl('TA-BL-', 'bank_ledgers', 'transaction_date', $td);

        $ledger_banking = new BankLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->bank_account_id = $banking->id;
        $ledger_banking->transaction_date = date('Y-m-d H:i:s');
        $ledger_banking->transaction_code = $transaction_code;
        $ledger_banking->sl_no = $sl_no;
        $ledger_banking->amount = $request->opening_balance;
        $ledger_banking->transaction_type_id = ($request->account_type=='Loan Account')? 5 : 8; //if Account type is Loan Account then transaction_type_id=5 otherwise transaction_type_id=8
        $ledger_banking->transaction_method_id = 1;
        $ledger_banking->ref_date = null;
        $ledger_banking->particulars = ($request->account_type=='Loan Account')?'Loan Account Opening':'Opening';
        $ledger_banking->approve_status = 'Submitted';
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->save();

        $ledger_branch = new BranchLedger();
        $ledger_branch->branch_id = $request->branch;
        $ledger_branch->transaction_date = date('Y-m-d H:i:s');
        $ledger_branch->transaction_code = $transaction_code;
        $ledger_branch->sl_no = $sl_no;
        $ledger_branch->amount = $request->opening_balance;
        $ledger_branch->transaction_type_id = ($request->account_type=='Loan Account')? 5 : 8; //if Account type is Loan Account then transaction_type_id=5 otherwise transaction_type_id=8
        $ledger_branch->transaction_method_id = 1;
        $ledger_branch->comments = ($request->account_type=='Loan Account')?'Loan Account Opening':'Bank Account Opening';
        $ledger_branch->entry_by = Auth::user()->id;
        $ledger_branch->approve_status = 'Submitted';
        $ledger_branch->reftbl = 'bank_ledgers';
        $ledger_branch->reftbl_id = $ledger_banking->id;
        $ledger_branch->save();


        \Session::flash('flash_message', 'Successfully Added');
        return redirect('bank_account');
    }

    public function show(BankAccount $bank_account)
    {
            $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
            $account_id = $bank_account->id;
        $ledger = ledger_account($account_id, $start_date, $end_date);
        $header_title= 'Account Information';
        return view('banking.show', compact('bank_account','ledger','header_title'));
    }

    public function edit(BankAccount $bank_account)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        return view('banking.edit',compact('bank_account'));
    }

    public function update(Request $request, BankAccount $bank_account)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'account_no' => 'required|unique:bank_accounts,account_no,' . $bank_account->id . ',id',
            'account_name' => 'required',
            'account_type' => 'required',
//            'opening_balance' => 'required',
        ]);
        $bank_account->account_name = $request->account_name;
        $bank_account->bank_name = $request->bank_name;
        $bank_account->account_no = $request->account_no;
        $bank_account->account_type = $request->account_type;
        $bank_account->details = $request->details;
        $bank_account->update();
        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('bank_account');
    }

    public function destroy(BankAccount $banking)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $banking->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return redirect('banking');
    }

}
