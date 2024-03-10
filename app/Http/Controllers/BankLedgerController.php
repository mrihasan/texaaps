<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankLedger;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;


class BankLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        abort_if(Gate::denies('payment-access'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        $ledger = ledger_account_all($start_date, $end_date);

        $header_title = 'Account Ledger From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('banking.bank_ledger_index', compact('ledger', 'header_title'));
    }

    public function account_statement(Request $request)
    {
//        abort_if(Gate::denies('payment-access'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
            $account_id = 1;
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
            $account_id = $request->bank_account;
        }
        $ledger = ledger_account($account_id, $start_date, $end_date);
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $bank_account = BankAccount::where('id', $account_id)->first();
        $header_title = 'Account Statement of ' . $bank_account->account_name . ' (' . $bank_account->account_no . ') From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');

        return view('banking.account_statement', compact('ledger', 'header_title', 'to_accounts'));
    }

    public function deposit()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('banking.deposit', compact('branches', 'transaction_methods', 'to_accounts'));
    }

    public function withdraw()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('banking.withdraw', compact('branches', 'transaction_methods', 'to_accounts'));
    }

    public function store(Request $request)
    {

        $ledger_banking = new BankLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->bank_account_id = $request->bank_account;
        $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
        $ledger_banking->transaction_code = autoTimeStampCode('AL');
        $ledger_banking->transaction_type_id = $request->transaction_type_id;
        $ledger_banking->transaction_method_id = $request->transaction_method;
        $ledger_banking->amount = $request->amount;
        $ledger_banking->particulars = $request->particulars;
        $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
        $ledger_banking->ref_no = $request->ref_no;
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->approve_status = 'Approved';
        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Added');
        return redirect('bank_ledger');
    }

    public function edit($transaction_code)
    {
//        dd($transaction_code);
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $transaction_code)->first();
//        dd($bank_ledger);
        $branches = branch_list();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        if ($bank_ledger->transaction_type_id == 8) //8=Deposit
        {
            return view('banking.edit_deposit', compact('branches', 'transaction_methods', 'bank_ledger', 'to_accounts'));
        } elseif ($bank_ledger->transaction_type_id == 9) //9=Withdraw
        {
            return view('banking.edit_withdraw', compact('branches', 'transaction_methods', 'bank_ledger', 'to_accounts'));
        } else {
            die();
        }
    }

    public function update(Request $request, BankLedger $bank_ledger)
    {
//        dd($ledger_banking);
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'branch' => 'required',
            'bank_account' => 'required',
            'transaction_method' => 'required',
            'transaction_date' => 'required',
            'amount' => 'required'
        ]);

        $bank_ledger->branch_id = $request->branch;
        $bank_ledger->bank_account_id = $request->bank_account;
        $bank_ledger->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
        $bank_ledger->transaction_method_id = $request->transaction_method;
        $bank_ledger->amount = $request->amount;
        $bank_ledger->particulars = $request->particulars;
        $bank_ledger->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
        $bank_ledger->ref_no = $request->ref_no;
        $bank_ledger->entry_by = Auth::user()->id;
        $bank_ledger->approve_status = 'Approved';
        $bank_ledger->update();

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('bank_ledger');
    }

}
