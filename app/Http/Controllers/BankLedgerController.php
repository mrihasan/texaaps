<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankLedger;
use App\Models\BranchLedger;
use App\Models\TransactionMethod;
use App\Models\TransactionType;
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
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
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
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
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
        $transaction_types = TransactionType::whereIn('id', [5, 10])->orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Type', '')->toArray();
        return view('banking.deposit', compact('branches', 'transaction_methods', 'to_accounts', 'transaction_types'));
    }

    public function withdraw()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        $transaction_types = TransactionType::whereIn('id', [6, 11])->orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Type', '')->toArray();
        return view('banking.withdraw', compact('branches', 'transaction_methods', 'to_accounts', 'transaction_types'));
    }

    public function account_transfer()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        $from_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('From Account', '')->toArray();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('To Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('banking.account_transfer', compact('branches', 'transaction_methods', 'to_accounts', 'from_accounts'));
    }

    public function store(Request $request)
    {
        if ($request->transaction_type_id == 'account_transfer') {
//            withdraw
            $utime = round(microtime(true) * 1000); //1704696475337 milliseconds

            $ledger_banking = new BankLedger();
            $ledger_banking->branch_id = $request->branch;
            $ledger_banking->bank_account_id = $request->withdraw_account;
            $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_banking->transaction_code = 'W' . Auth::user()->id . 'AL' . $utime;
            $ledger_banking->transaction_type_id = 9; //Withdraw=9
            $ledger_banking->transaction_method_id = $request->withdraw_method;
            $ledger_banking->amount = $request->amount;
            $ledger_banking->particulars = $request->particulars;
            $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $ledger_banking->ref_no = $request->ref_no;
            $ledger_banking->entry_by = Auth::user()->id;
            $ledger_banking->approve_status = 'Approved';
            $ledger_banking->save();
//            Deposit
            $ledger_banking = new BankLedger();
            $ledger_banking->branch_id = $request->branch;
            $ledger_banking->bank_account_id = $request->deposit_account;
            $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_banking->transaction_code = 'D' . Auth::user()->id . 'AL' . $utime;
            $ledger_banking->transaction_type_id = 8; //deposit=8
            $ledger_banking->transaction_method_id = $request->deposit_method;
            $ledger_banking->amount = $request->amount;
            $ledger_banking->particulars = $request->particulars;
            $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $ledger_banking->ref_no = $request->ref_no;
            $ledger_banking->entry_by = Auth::user()->id;
            $ledger_banking->approve_status = 'Approved';
            $ledger_banking->save();
        } else {
            $tcode = autoTimeStampCode('AL');
            $ledger_banking = new BankLedger();
            $ledger_banking->branch_id = $request->branch;
            $ledger_banking->bank_account_id = $request->bank_account;
            $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_banking->transaction_code = $tcode;
            $ledger_banking->transaction_type_id = $request->transaction_type;
            $ledger_banking->transaction_method_id = $request->transaction_method;
            $ledger_banking->amount = $request->amount;
            $ledger_banking->particulars = $request->particulars;
            $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $ledger_banking->ref_no = $request->ref_no;
            $ledger_banking->entry_by = Auth::user()->id;
            $ledger_banking->approve_status = 'Approved';
            $ledger_banking->save();

            $ledger_branch = new BranchLedger();
            $ledger_branch->branch_id = $request->branch;
            $ledger_branch->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_branch->transaction_code = $tcode;
            $ledger_branch->amount = $request->amount;
            $ledger_branch->transaction_type_id = $request->transaction_type;
            $ledger_branch->transaction_method_id = $request->transaction_method;
            $ledger_branch->comments = $request->particulars;
            $ledger_branch->entry_by = Auth::user()->id;
            $ledger_branch->approve_status = 'Approved';
            $ledger_branch->save();

        }
        \Session::flash('flash_message', 'Successfully Added');
        return redirect('bank_ledger');
    }

    public function edit($transaction_code)
    {
//        $ledger_branch1 = BranchLedger::where('transaction_code', $transaction_code)->first();
//        dd($ledger_branch1);
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));

        $ledger_check = DB::table('ledgers')->where('transaction_code', $transaction_code)->first();
        if ($ledger_check) {
            return redirect('ledger/' . $ledger_check->id . '/edit');
        } elseif (substr($transaction_code, 0, 1) === 'W' || substr($transaction_code, 0, 1) === 'D') {
//            dd(substr($transaction_code, 1));
            $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', 'W'.substr($transaction_code, 1))->first();
            $deposit_ledger = DB::table('bank_ledgers')->where('transaction_code', 'D'.substr($transaction_code, 1))->first();
//            dd($deposit_ledger);
            $branches = branch_list();
            $accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->toArray();

            $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
            if ($bank_ledger==null || $deposit_ledger==null )
                return redirect('/bank_ledger')->withErrors(['error' => 'Unable to Edit']);
            return view('banking.edit_transfer', compact('branches', 'transaction_methods', 'accounts','deposit_ledger','bank_ledger'));
        } else {
            $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $transaction_code)->first();
            $branches = branch_list();
            $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
            $transaction_types = TransactionType::whereIn('id', [5, 10, 6, 11, 8])->orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Type', '')->toArray();
            $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
            return view('banking.edit_common_bank_ledger', compact('branches', 'transaction_methods', 'bank_ledger', 'to_accounts','transaction_types'));
        }

    }

    public function update(Request $request, BankLedger $bank_ledger)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));

        if ($request->edit_type=='common') {
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
            $bank_ledger->transaction_type_id = $request->transaction_type;
            $bank_ledger->transaction_method_id = $request->transaction_method;
            $bank_ledger->amount = $request->amount;
            $bank_ledger->particulars = $request->particulars;
            $bank_ledger->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $bank_ledger->ref_no = $request->ref_no;
            $bank_ledger->updated_by = Auth::user()->id;
            $bank_ledger->approve_status = 'Approved';
            $bank_ledger->update();

            $del_lb = DB::table('branch_ledgers')->where('transaction_code', $bank_ledger->transaction_code)->delete();
            $ledger_branch = new BranchLedger();
            $ledger_branch->branch_id = $request->branch;
            $ledger_branch->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_branch->transaction_code = $bank_ledger->transaction_code;
            $ledger_branch->amount = $request->amount;
            $ledger_branch->transaction_type_id = $request->transaction_type;
            $ledger_branch->transaction_method_id = $request->transaction_method;
            $ledger_branch->comments = $request->particulars;
            $ledger_branch->entry_by = $bank_ledger->entry_by;
            $ledger_branch->updated_by = Auth::user()->id;
            $ledger_branch->approve_status = 'Approved';
            $ledger_branch->save();
        }elseif ($request->edit_type=='transfer') {
            $bank_ledger->branch_id = $request->branch;
            $bank_ledger->bank_account_id = $request->withdraw_account;
            $bank_ledger->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
//            $ledger_banking->transaction_code = 'W' . Auth::user()->id . 'AL' . $utime;
//            $ledger_banking->transaction_type_id = 9; //Withdraw=9
            $bank_ledger->transaction_method_id = $request->withdraw_method;
            $bank_ledger->amount = $request->amount;
            $bank_ledger->particulars = $request->particulars;
            $bank_ledger->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $bank_ledger->ref_no = $request->ref_no;
            $bank_ledger->updated_by = Auth::user()->id;
            $bank_ledger->approve_status = 'Approved';
            $bank_ledger->update();
//            Deposit
            $del_deposit=DB::table('bank_ledgers')->where('transaction_code', 'D'.substr($bank_ledger->transaction_code, 1))->delete();
            $ledger_banking = new BankLedger();
            $ledger_banking->branch_id = $request->branch;
            $ledger_banking->bank_account_id = $request->deposit_account;
            $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $ledger_banking->transaction_code = 'D'.substr($bank_ledger->transaction_code, 1);
            $ledger_banking->transaction_type_id = 8; //deposit=8
            $ledger_banking->transaction_method_id = $request->deposit_method;
            $ledger_banking->amount = $request->amount;
            $ledger_banking->particulars = $request->particulars;
            $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
            $ledger_banking->ref_no = $request->ref_no;
            $ledger_banking->entry_by = $bank_ledger->entry_by;
            $ledger_banking->updated_by = Auth::user()->id;
            $ledger_banking->approve_status = 'Approved';
            $ledger_banking->save();

        }

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('bank_ledger');
    }
    public function show($transaction_code)
    {
//        dd($bank_ledger);
        $bank_ledger = BankLedger::where('transaction_code', $transaction_code)->first();

        return view('banking.show_account_ledger', compact('bank_ledger'));
    }

    public function destroy($transaction_code)
    {
        abort_if(Gate::denies('AccountMgtDelete'), redirect('error'));

        if (substr($transaction_code, 0, 1) === 'W' || substr($transaction_code, 0, 1) === 'D') {
            $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', 'W'.substr($transaction_code, 1))->delete();
            $deposit_ledger = DB::table('bank_ledgers')->where('transaction_code', 'D'.substr($transaction_code, 1))->delete();
        } else {
            $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $transaction_code)->first();

            $ledger = DB::table('ledgers')->where('transaction_code', $transaction_code)->delete();
            $del_lb = DB::table('branch_ledgers')->where('transaction_code', $transaction_code)->delete();
            $del_bl = DB::table('bank_ledgers')->where('transaction_code', $transaction_code)->delete();
        }

        \Session::flash('flash_message', 'Successfully Deleted');

        return back();
    }


}
