<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Models\BankLedger;
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
use DateTime;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';
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

        // If not provided, set default date range (last 90 days)
        if ($start_date === null) {
            $start_date = Carbon::now()->subDays(90)->startOfDay();
        }

        if ($end_date === null) {
            $end_date = Carbon::now()->endOfDay();
        }
//        dd($start_date, $end_date);
//        dd($dataTable);
        $header_title = 'test';
        // Pass start_date and end_date to the DataTable instance
//        dd($dataTable);
        return $dataTable->with(['start_date' => $start_date, 'end_date' => $end_date])->render('expense.index_dt', compact('header_title'));
    }


    public function expense_approved()
    {
//        abort_if(Gate::denies('expense-access'), redirect('error'));
        $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';

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
        $branches = branch_list();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        return view('expense.create', compact('expense_type', 'transaction_methods', 'branches', 'to_accounts'));
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
            'bank_account' => 'required',
            'transaction_method' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $transaction_code = autoTimeStampCode('EXP');

                $exp_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
                $td = new DateTime($exp_date);
                $sl_no = createSl('TA-EXP-', 'expenses', 'expense_date', $td);

                $expense = new Expense();
                $expense->sl_no = $sl_no;
                $expense->expense_type_id = $request->expense_type;
                $expense->branch_id = $request->branch;
                $expense->expense_date = $exp_date;
                $expense->expense_amount = $request->expense_amount;
                $expense->comments = $request->expense_comments;
                $expense->status = 'Submitted';
                $expense->user_id = Auth::user()->id;
                $expense->transaction_code = $transaction_code;
                $expense->transaction_method_id = $request->transaction_method;
                $expense->save();

                $branch_ledger = new BranchLedger();
                $branch_ledger->branch_id = $request->branch;
                $branch_ledger->transaction_date = $exp_date;
                $branch_ledger->sl_no = $sl_no;
                $branch_ledger->transaction_code = $transaction_code;
                $branch_ledger->amount = $request->expense_amount;
                $branch_ledger->transaction_type_id = 2; //Debited
                $branch_ledger->transaction_method_id = $request->transaction_method;
                $branch_ledger->comments = $expense->expense_type->expense_name . ' ' . $request->expense_amount;
                $branch_ledger->entry_by = Auth::user()->id;
                $branch_ledger->approve_status = 'Not Approved';
                $branch_ledger->reftbl = 'expenses';
                $branch_ledger->reftbl_id = $expense->id;
                $branch_ledger->save();

                $ledger_banking = new BankLedger();
                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->bank_account_id = $request->bank_account;
                $ledger_banking->sl_no = $sl_no;
                $ledger_banking->transaction_code = $transaction_code;
                $ledger_banking->transaction_date = $exp_date;
                $ledger_banking->transaction_method_id = $request->transaction_method;
                $ledger_banking->transaction_type_id = 2; //Debited
                $ledger_banking->amount = $request->expense_amount;
                $ledger_banking->particulars = $expense->expense_type->expense_name . ' ' . $request->expense_amount;
                $ledger_banking->entry_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Not Approved';
                $ledger_banking->reftbl = 'expenses';
                $ledger_banking->reftbl_id = $expense->id;
                $ledger_banking->save();

            });
            \Session::flash('flash_message', 'Successfully Added');


        } catch (\Exception $e) {
            \Session::flash('flash_error', 'Failed to save , Try again.');
        }
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
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $expense->transaction_code)->first();
        return view('expense.edit', compact('expense', 'expense_type', 'branches', 'transaction_methods', 'to_accounts', 'bank_ledger'));
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

        try {
            DB::transaction(function () use ($request, $expense) {
                $inputDate = $request->expense_date;
                $givenDate = $expense->expense_date;
                $sl_no = null;
                if ($inputDate) {
                    // Create DateTime objects for comparison
                    $inputDateTime = new DateTime($inputDate);
                    $givenDateTime = new DateTime($givenDate);
                    // Extract month and year components
                    $inputMonthYear = $inputDateTime->format('Y-m');
                    $givenMonthYear = $givenDateTime->format('Y-m');
                    $exp_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
                    $td = new DateTime($exp_date);
                    // Compare the month and year
                    if (($inputMonthYear != $givenMonthYear) || $expense->sl_no == null) {
                        $sl_no = createSl('TA-EXP-', 'expenses', 'expense_date', $td);
                    } else {
                        $sl_no = $expense->sl_no;
                    }
                }

                $expense->expense_type_id = $request->expense_type;
                $expense->sl_no = $sl_no;
                $expense->branch_id = $request->branch;
                $expense->expense_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
                $expense->expense_amount = $request->expense_amount;
                $expense->comments = $request->expense_comments;
                $expense->status = 'Updated';
                $expense->updated_by = Auth::user()->id;
                $expense->checked_by = null;
                $expense->checked_date = null;
                $expense->approved_by = null;
                $expense->approved_date = null;
                $expense->transaction_method_id = $request->transaction_method_id;
                $expense->updated_at = date('Y-m-d H:i:s');
                $expense->update();

                $del_lb = DB::table('branch_ledgers')->where('transaction_code', $expense->transaction_code)->delete();
                $branch_ledger = new BranchLedger();
                $branch_ledger->branch_id = $request->branch;
                $branch_ledger->sl_no = $sl_no;
                $branch_ledger->transaction_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
                $branch_ledger->transaction_code = $expense->transaction_code;
                $branch_ledger->amount = $request->expense_amount;
                $branch_ledger->transaction_type_id = 2; //Debited
                $branch_ledger->transaction_method_id = $request->transaction_method_id;
                $branch_ledger->comments = $expense->expense_type->expense_name . ' ' . $request->expense_amount;
                $branch_ledger->entry_by = $expense->user_id;
                $branch_ledger->updated_by = Auth::user()->id;
                $branch_ledger->approve_status = 'Not Approved';
                $branch_ledger->reftbl = 'expenses';
                $branch_ledger->reftbl_id = $expense->id;
                $branch_ledger->save();

                $del_la = DB::table('bank_ledgers')->where('transaction_code', $expense->transaction_code)->delete();
                $ledger_banking = new BankLedger();
                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->sl_no = $sl_no;
                $ledger_banking->bank_account_id = $request->bank_account;
                $ledger_banking->transaction_code = $expense->transaction_code;
                $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->expense_date)) . date(' H:i:s');
                $ledger_banking->transaction_method_id = $request->transaction_method_id;
                $ledger_banking->transaction_type_id = 2; //Debited
                $ledger_banking->amount = $request->expense_amount;
                $ledger_banking->particulars = $expense->expense_type->expense_name . ' ' . $request->expense_amount;
                $ledger_banking->entry_by = $expense->user_id;
                $ledger_banking->updated_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Not Approved';
                $ledger_banking->reftbl = 'expenses';
                $ledger_banking->reftbl_id = $expense->id;
                $ledger_banking->save();

            });
            \Session::flash('flash_message', 'Successfully Updated');
        } catch (\Exception $e) {
            \Session::flash('flash_error', 'Failed to save , Try again.');
        }
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

    public function checked_expense($id)
    {
//        dd($id);
//        abort_if(Gate::denies('$payment_request-approval'), redirect('error'));
        $expense = Expense::find($id);
        $expense->checked_by = Auth::user()->id;
        $expense->checked_date = date('Y-m-d H:i:s');
        $expense->save();

        \Session::flash('flash_message', 'Successfully Saved');

        return redirect()->back();
    }

    public function approve_expense($id)
    {
//        abort_if(Gate::denies('expense-approval'), redirect('error'));
        $expense = Expense::find($id);
        if ($expense->status == 'Submitted' || $expense->status == 'Updated') {
            $expense->status = 'Approved';
            $expense->approved_by = Auth::user()->id;
            $expense->approved_date = date('Y-m-d H:i:s');
            $expense->save();
            $ledger_expense = BranchLedger::where('transaction_code', $expense->transaction_code)->first();
            $ledger_expense->approve_status = 'Approved';
            $ledger_expense->save();
            $bankledger_expense = BankLedger::where('transaction_code', $expense->transaction_code)->first();
            $bankledger_expense->approve_status = 'Approved';
            $bankledger_expense->save();

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
