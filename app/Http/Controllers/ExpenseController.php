<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Models\Branch;
use App\Models\BranchLedger;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        $start_date = date('Y-m-d', strtotime(Carbon::now()->subDays(30)));
        $end_date = date('Y-m-d', strtotime(Carbon::now()));
        $header_title = 'Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');

        if (session()->get('branch') != 'all') {
            $expense = Expense::with('user')->with('approvedBy')->with('expense_type')
                ->with('branch')
                ->where('status', 'Submitted')
                ->orWhere('status', 'Updated')
                ->where('branch_id', session()->get('branch'))
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $expense = Expense::with('user')->with('approvedBy')->with('expense_type')
                ->with('branch')
                ->where('status', 'Submitted')
                ->orWhere('status', 'Updated')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        }
        return view('expense.index', compact('expense', 'header_title'));
    }


    public function expense_dt(ExpenseDataTable $dataTable, Request $request)
    {
        // Initialize start_date and end_date
        $start_date = null;
        $end_date = null;

        // Check if start_date and end_date are provided in the request
        if ($request->filled('start_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
        }

        if ($request->filled('end_date')) {
            $end_date = Carbon::parse($request->end_date)->endOfDay();
        }

        // If not provided, set default date range (last 30 days)
        if ($start_date === null) {
            $start_date = Carbon::now()->subDays(30)->startOfDay();
        }

        if ($end_date === null) {
            $end_date = Carbon::now()->endOfDay();
        }
//        dd($start_date, $end_date);
//        dd($dataTable);
        $header_title='test';
        // Pass start_date and end_date to the DataTable instance
//        dd($dataTable);
        return $dataTable->with(['start_date' => $start_date, 'end_date' => $end_date])->render('expense.index_dt',compact('header_title'));
    }


public function expense_approved()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        $start_date = date('Y-m-d', strtotime(Carbon::now()->subDays(30)));
        $end_date = date('Y-m-d', strtotime(Carbon::now()));

        $expense = Expense::with('user')->with('approvedBy')->with('expense_type')->
        where('status', 'Approved')
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        $header_title = 'Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('expense.index_approved', compact('expense', 'header_title'));
    }

    public function create()
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $expense_type = ExpenseType::orderBy('expense_name')->pluck('expense_name', 'id')->prepend('Select a Expense Type', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        $branches=branch_list();
        return view('expense.create', compact('expense_type', 'transaction_methods', 'branches'));
    }

    public function store(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $this->validate($request, [
            'expense_date' => 'required',
            'expense_amount' => 'required',
            'expense_type' => 'required',
            'branch' => 'required',
            'transaction_method' => 'required',
        ]);
        $transaction_code=autoTimeStampCode('EXP');

        $expense = new Expense();
        $expense->expense_type_id = $request->expense_type;
        $expense->branch_id = $request->branch;
        $expense->expense_date = date('Y-m-d', strtotime($request->expense_date)).date(' H:i:s');
        $expense->expense_amount = $request->expense_amount;
        $expense->comments = $request->expense_comments;
        $expense->status = 'Submitted';
        $expense->user_id = Auth::user()->id;
        $expense->transaction_code = $transaction_code;
        $expense->transaction_method_id = $request->transaction_method;
        $expense->save();

        $ledger_banking = new BranchLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->expense_date)).date(' H:i:s');
        $ledger_banking->transaction_code = $transaction_code;
        $ledger_banking->amount = $request->expense_amount;
        $ledger_banking->transaction_type_id = 2; //Debited
        $ledger_banking->transaction_method_id = $request->transaction_method;
        $ledger_banking->comments = $expense->expense_type->expense_name.' '.$request->expense_amount;
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->approve_status = 'Not Approved';

        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Added');
        return redirect('expense');
    }

    public function show(Expense $expense)
    {
        return view('expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
//        dd($expense->office_rent);
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
        $expense_type = ExpenseType::pluck('expense_name', 'id');
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->toArray();
        if (session()->get('branch') == 'all') {
            $branches = DB::table('branches')->where('status', 'Active')->pluck('title', 'id');
        } else {
            $branches = DB::table('branches')->where('id', session()->get('branch'))->pluck('title', 'id');
        }

        return view('expense.edit', compact('expense', 'expense_type', 'branches','transaction_methods'));
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if(Gate::denies('ExpenseAccess'), redirect('error'));
            $this->validate($request, [
                'expense_date' => 'required',
                'expense_amount' => 'required',
                'expense_type' => 'required',
                'branch' => 'required',
                'transaction_method_id' => 'required',
            ]);

        $expense->expense_type_id = $request->expense_type;
        $expense->branch_id = $request->branch;
        $expense->expense_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
        $expense->expense_amount = $request->expense_amount;
        $expense->comments = $request->expense_comments;
        $expense->status = 'Updated';
        $expense->user_id = Auth::user()->id;
        $expense->approved_by = null;
        $expense->approved_date = null;
        $expense->transaction_method_id = $request->transaction_method_id;
        $expense->update();


        $del_lb = DB::table('branch_ledgers')->where('transaction_code', $expense->transaction_code)->delete();
        $ledger_banking = new BranchLedger();
        $ledger_banking->branch_id = $request->branch;
        $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
        $ledger_banking->transaction_code = $expense->transaction_code;
        $ledger_banking->amount = $request->expense_amount;
        $ledger_banking->transaction_type_id = 2; //Debited
        $ledger_banking->transaction_method_id = $request->transaction_method_id;
        $ledger_banking->comments = $expense->expense_type->expense_name.' '.$request->expense_amount;
        $ledger_banking->entry_by = Auth::user()->id;
        $ledger_banking->approve_status = 'Not Approved';
        $ledger_banking->save();

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('expense');
    }

    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('ExpenseDelete'), redirect('error'));
        $del_lb = DB::table('branch_ledgers')->where('transaction_code', $expense->transaction_code)->delete();
        $expense->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return redirect('expense');
    }

    public function approve_expense($id)
    {
//        abort_if(Gate::denies('expense-approval'), redirect('error'));
        $expense = Expense::find($id);
        if ($expense->status == 'Submitted' || $expense->status == 'Updated') {
            $expense->status = 'Approved';
            $expense->approved_by = Auth::user()->id;
            $expense->approved_date = date('Y-m-d');
            $expense->save();
            $ledger_expense = BranchLedger::where('transaction_code', $expense->transaction_code)->first();
            $ledger_expense->approve_status = 'Approved';
            $ledger_expense->save();

            \Session::flash('flash_message', 'Successfully Approved');
        } else {
            \Session::flash('flash_message', 'Already Approved');

        }

        return redirect()->back();
    }

    public function date_wise_expense(Request $request)
    {
        abort_if(Gate::denies('expense-access'), redirect('error'));
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $header_title = 'Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');

        if ($request->approval_type == 'Approved') {
            $expense = Expense::with('user')->with('approvedBy')->with('expense_type')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->where('status', $request->approval_type)
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            return view('expense.index_approved', compact('expense', 'header_title'));
        } elseif ($request->approval_type == 'Submitted') {
            $expense = Expense::with('user')->with('approvedBy')->with('expense_type')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->where('status', $request->approval_type)
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            return view('expense.index', compact('expense', 'header_title'));
        } else
            $expense = Expense::with('user')->with('approvedBy')->with('expense_type')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        return view('expense.index', compact('expense', 'header_title'));
    }

}
