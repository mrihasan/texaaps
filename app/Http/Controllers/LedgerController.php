<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankLedger;
use App\Models\Branch;
use App\Models\BranchLedger;
use App\Models\Ledger;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class LedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        $payments = Ledger::with('user')->with('entryby')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'desc')->get();
//        dd($payments);
        $header_title = 'Ledger From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('accounting.index', compact('payments', 'header_title'));
    }

    public function payment_index(Request $request)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        $payments = Ledger::with('user')->with('entryby')
            ->where('transaction_type_id', 4)//4=Payment
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'desc')->get();
//        dd($payments);
        $header_title = 'Payment From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('accounting.payment_index', compact('payments', 'header_title'));
    }

    public function receipt_index(Request $request)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        $receipts = Ledger::with('user')->with('entryby')
            ->where('transaction_type_id', 3)//3=Received
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'desc')->get();
        $header_title = 'Receipt From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('accounting.receipt_index', compact('receipts', 'header_title'));
    }

    public function payment()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $user = supplier_list();
        $branches = branch_list();
        $invoices = DB::table('invoices')->where('transaction_type', 'Purchase')->pluck('sl_no', 'id')->prepend('Select Invoice', '')->toArray();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('accounting.payment', compact('user', 'branches', 'transaction_methods', 'to_accounts','invoices'));
    }

    public function receipt()
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $user = customer_list();
        $branches = branch_list();
        $invoices = DB::table('invoices')->where('transaction_type', 'Sales')->pluck('sl_no', 'id')->prepend('Select Invoice', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        return view('accounting.receipt', compact('user', 'branches', 'transaction_methods', 'to_accounts','transaction_methods','invoices'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'branch' => 'required',
            'bank_account' => 'required',
            'user' => 'required',
            'transaction_method' => 'required',
            'transaction_date' => 'required',
            'amount' => 'required|numeric|between:0,99999999.99'
        ]);
//        dd($request);
        try {
            DB::transaction(function () use ($request) {

                $ledger = new Ledger();
                $ledger_branch = new BranchLedger();
                $ledger_banking = new BankLedger();
                if ($request->ledger_type == 'Payment') {
                    $tcode = autoTimeStampCode('LP');
                    $ledger->transaction_type_id = 4; //4=payment
                    $ledger->transaction_code = $tcode;
                    $ledger_branch->transaction_code = $tcode;
                    $ledger_branch->transaction_type_id = 4;//4=payment
                    $ledger_banking->transaction_code = $tcode;
                    $ledger_banking->transaction_type_id = 4;//4=payment
                } elseif ($request->ledger_type == 'Receipt') {
                    $tcode = autoTimeStampCode('LR');
                    $ledger->transaction_type_id = 3; //3=received
                    $ledger->transaction_code = $tcode;
                    $ledger_branch->transaction_code = $tcode;
                    $ledger_branch->transaction_type_id = 3; //3=received
                    $ledger_banking->transaction_code = $tcode;
                    $ledger_banking->transaction_type_id = 3; //3=received
                } else {
                    die();
                }
                $ledger->user_id = $request->user;
                $ledger->branch_id = $request->branch;
                $ledger->transaction_method_id = $request->transaction_method;
                $ledger->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
                $ledger->amount = $request->amount;
                $ledger->comments = $request->comments;
                $ledger->entry_by = Auth::user()->id;
                $ledger->invoice_id = $request->invoice;

                $ledger_branch->branch_id = $request->branch;
                $ledger_branch->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
                $ledger_branch->amount = $request->amount;
                $ledger_branch->transaction_method_id = $request->transaction_method;
                $ledger_branch->comments = $request->comments;
                $ledger_branch->entry_by = Auth::user()->id;
                $ledger_branch->approve_status = 'Approved';

                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->bank_account_id = $request->bank_account;
                $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
                $ledger_banking->transaction_method_id = $request->transaction_method;
                $ledger_banking->amount = $request->amount;
                $ledger_banking->particulars = $request->comments;
                $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
                $ledger_banking->ref_no = $request->ref_no;
                $ledger_banking->entry_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Approved';

                $ledger->save();
                $ledger_branch->save();
                $ledger_banking->save();
            });

            \Session::flash('flash_message', 'Successfully Added');
        } catch (\Exception $e) {
            \Session::flash('flash_error', 'Failed to save , Try again.');
        }
        if ($request->ledger_type == 'Payment')
            return redirect('payment_index');
        elseif ($request->ledger_type == 'Receipt')
            return redirect('receipt_index');
        else
            return redirect('ledger');
    }

    public function edit(Ledger $ledger)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $branches = branch_list();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $ledger->transaction_code)->first();
//        dd($bank_ledger);
        if ($ledger->transaction_type_id == 4) //4=Payment
        {
            $user = supplier_list();
            $invoices = DB::table('invoices')->where('transaction_type', 'Purchase')->pluck('transaction_code', 'id')->prepend('Select Invoice', '')->toArray();
            return view('accounting.edit_payment', compact('user', 'branches', 'transaction_methods', 'ledger', 'bank_ledger', 'to_accounts','invoices'));
        } elseif ($ledger->transaction_type_id == 3) //3=Receipt
        {
            $user = customer_list();
            $invoices = DB::table('invoices')->where('transaction_type', 'Sales')->pluck('transaction_code', 'id')->prepend('Select Invoice', '')->toArray();
            return view('accounting.edit_receipt', compact('user', 'branches', 'transaction_methods', 'ledger', 'bank_ledger', 'to_accounts','invoices'));
        } else {
            $user = user_list();
            return view('accounting.edit', compact('user', 'branches', 'transaction_methods', 'ledger', 'bank_ledger', 'to_accounts'));
        }
    }

    public function update(Request $request, Ledger $ledger)
    {
        abort_if(Gate::denies('AccountMgtAccess'), redirect('error'));
        $this->validate($request, [
            'branch' => 'required',
            'bank_account' => 'required',
            'user' => 'required',
            'transaction_method' => 'required',
            'transaction_date' => 'required',
            'amount' => 'required'
        ]);
        $ledger->user_id = $request->user;
        $ledger->branch_id = $request->branch;
        $ledger->transaction_type_id = $request->ledger_type; //4=Payment, 3=Receipt
        $ledger->transaction_method_id = $request->transaction_method;
        $ledger->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
        $ledger->amount = $request->amount;
        $ledger->comments = $request->comments;
        $ledger->entry_by = Auth::user()->id;
        $ledger->invoice_id = $request->invoice;
        $ledger->update();

        $del_lb = DB::table('branch_ledgers')->where('transaction_code', $ledger->transaction_code)->delete();
        $ledger_branch = new BranchLedger();
        $ledger_branch->branch_id = $request->branch;
        $ledger_branch->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
        $ledger_branch->transaction_code = $ledger->transaction_code;
        $ledger_branch->amount = $request->amount;
        $ledger_branch->transaction_type_id = $request->ledger_type; //4=Payment, 3=Receipt
        $ledger_branch->transaction_method_id = $request->transaction_method;
        $ledger_branch->comments = $ledger->comments;
        $ledger_branch->entry_by = Auth::user()->id;
        $ledger_branch->approve_status = 'Approved';
        $ledger_branch->save();

        $del_la = DB::table('bank_ledgers')->where('transaction_code', $ledger->transaction_code)->delete();
        $ledger_banking = new BankLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->bank_account_id = $request->bank_account;
        $ledger_banking->transaction_code = $ledger->transaction_code;
        $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
        $ledger_banking->transaction_method_id = $request->transaction_method;
        $ledger_banking->transaction_type_id = $request->ledger_type; //4=Payment, 3=Receipt
        $ledger_banking->amount = $request->amount;
        $ledger_banking->particulars = $request->comments;
        $ledger_banking->ref_date = ($request->ref_date != null) ? date('Y-m-d', strtotime($request->ref_date)) : null;
        $ledger_banking->ref_no = $request->ref_no;
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->approve_status = 'Approved';
        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Updated');
        if ($request->ledger_type == 'Payment')
            return redirect('payment_index');
        elseif ($request->ledger_type == 'Receipt')
            return redirect('receipt_index');
        else
            return redirect('ledger');
    }
    public function show(Ledger $ledger)
    {
//        dd($ledger);
        $transaction_type1=$ledger->transaction_type->title;
        if ($transaction_type1=='Payment')
            $transaction_type= 'manage_payment';
        elseif ($transaction_type1=='Receipt')
            $transaction_type= 'manage_receipt';
        else
            $transaction_type= 'manage_ledger';

        return view('accounting.show_ledger', compact('ledger','transaction_type'));
    }

    public function destroy(Ledger $ledger)
    {
        abort_if(Gate::denies('AccountMgtDelete'), redirect('error'));
        $del_lb = DB::table('branch_ledgers')->where('transaction_code', $ledger->transaction_code)->delete();
        $del_bl = DB::table('bank_ledgers')->where('transaction_code', $ledger->transaction_code)->delete();
        $ledger->delete();
        \Session::flash('flash_message', 'Successfully Deleted');

        return back();
    }

}
