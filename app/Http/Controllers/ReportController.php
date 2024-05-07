<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchLedger;
use App\Models\EmployeeSalary;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use DateTime;
use DateInterval;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datewise_expense_summary_home()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';
        $title_date_range = 'Expense Summary From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        if (session()->get('branch') != 'all') {
            $expense = DB::table("expenses")
                ->select(DB::raw('expense_date'),
                    DB::raw('sum(expense_amount) as expense_amount'),
                    DB::raw('count(*) as total'),
                    DB::Raw('DATE(expense_date) day')
                )
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->where('branch_id', session()->get('branch'))
                ->groupBy('day')
                ->get();
//            dd($expense);
        } else {
            $expense = DB::table("expenses")
                ->select(DB::raw('expense_date'),
                    DB::raw('sum(expense_amount) as expense_amount'),
                    DB::raw('count(*) as total'),
                    DB::Raw('DATE(expense_date) day')
                )
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->groupBy('day')
                ->get();

        }
        return view('report.datewise_expense_summary', compact('expense', 'title_date_range'));
    }

    public function datewise_expense_summary(Request $request)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
//        $start_date = date('Y-m-d', strtotime($request->start_date));
//        $end_date = date('Y-m-d', strtotime($request->end_date));
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        $title_date_range = 'Expense Summary From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        if (session()->get('branch') != 'all') {
            if ($request->approval_type == 'All')
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_date'),
                        DB::raw('sum(expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('branch_id', session()->get('branch'))
                    ->groupBy(DB::raw('expense_date'))
                    ->get();
            else
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_date'),
                        DB::raw('sum(expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('branch_id', session()->get('branch'))
                    ->where('status', $request->approval_type)
                    ->groupBy(DB::raw('expense_date'))
                    ->get();
        } else {
            if ($request->approval_type == 'All')
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_date'),
                        DB::raw('sum(expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->whereBetween('expense_date', [$start_date, $end_date])
                    ->groupBy(DB::raw('expense_date'))
                    ->get();
            else
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_date'),
                        DB::raw('sum(expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('status', $request->approval_type)
                    ->groupBy(DB::raw('expense_date'))
                    ->get();
        }
        return view('report.datewise_expense_summary', compact('expense', 'title_date_range'));
    }

    public function datewise_expense_details($date)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        if (session()->get('branch') != 'all') {
            $expense = Expense::with('expense_type')->with('user')
                ->with('approvedBy')->whereDate('expense_date', $date)
                ->where('branch_id', session()->get('branch'))
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $expense = Expense::with('expense_type')->with('user')
                ->with('approvedBy')->whereDate('expense_date', $date)
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        }
        $header_title = 'Expense Details of ' . Carbon::parse($date);
        return view('report.expense_details', compact('expense', 'header_title'));
    }

    public function typewise_expense_summary_home()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';
        $title_date_range = 'Type Wise Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        if (session()->get('branch') != 'all') {
            $expense = DB::table("expenses")
                ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                    DB::raw('sum(expenses.expense_amount) as expense_amount'),
                    DB::raw('count(*) as total')
                )
                ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->where('expenses.branch_id', session()->get('branch'))
                ->groupBy(DB::raw('expenses.expense_type_id'))
                ->get();
        } else {
            $expense = DB::table("expenses")
                ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                    DB::raw('sum(expenses.expense_amount) as expense_amount'),
                    DB::raw('count(*) as total')
                )
                ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                ->whereBetween('expense_date', [$start_date, $end_date])
                ->groupBy(DB::raw('expenses.expense_type_id'))
                ->get();
        }
//        dd($expense);
        return view('report.typewise_expense_summary', compact('expense', 'title_date_range'));
    }

    public function typewise_expense_summary(Request $request)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
//        $start_date = date('Y-m-d', strtotime($request->start_date));
//        $end_date = date('Y-m-d', strtotime($request->end_date));
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        $title_date_range = 'Type Wise Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        if (session()->get('branch') != 'all') {
            if ($request->approval_type == 'All') {
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                        DB::raw('sum(expenses.expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                    ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                    ->where('expenses.branch_id', session()->get('branch'))
                    ->groupBy(DB::raw('expenses.expense_type_id'))
                    ->get();
            } else {
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                        DB::raw('sum(expenses.expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                    ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                    ->where('expenses.branch_id', session()->get('branch'))
                    ->where('expenses.status', $request->approval_type)
                    ->groupBy(DB::raw('expenses.expense_type_id'))
                    ->get();
            }
        } else {
            if ($request->approval_type == 'All') {
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                        DB::raw('sum(expenses.expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                    ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                    ->groupBy(DB::raw('expenses.expense_type_id'))
                    ->get();
            } else {
                $expense = DB::table("expenses")
                    ->select(DB::raw('expense_types.id'), DB::raw('expense_types.expense_name'),
                        DB::raw('sum(expenses.expense_amount) as expense_amount'),
                        DB::raw('count(*) as total')
                    )
                    ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
                    ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                    ->where('expenses.status', $request->approval_type)
                    ->groupBy(DB::raw('expenses.expense_type_id'))
                    ->get();
            }
        }
        return view('report.typewise_expense_summary', compact('expense', 'title_date_range'));
    }

    public function typewise_expense_details($type)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $type_name = DB::table('expense_types')->where('id', $type)->first();
        if (session()->get('branch') != 'all') {
            $expense = Expense::where('expense_type_id', $type)
                ->where('expenses.branch_id', session()->get('branch'))
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $expense = Expense::where('expense_type_id', $type)->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        }
        $header_title = 'Expense Details of ' . $type_name->expense_name;
        return view('report.expense_details', compact('expense', 'header_title'));
    }

    public function expense_details_home()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
//        $start_date = date('Y-m-d', strtotime(Carbon::now()->subDays(30)));
//        $end_date = date('Y-m-d', strtotime(Carbon::now()));
        $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
        $end_date = date('Y-m-d') . ' 23:59:59';

        $title_date_range = 'Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');

        if (session()->get('branch') != 'all') {
            $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                ->where('expenses.branch_id', session()->get('branch'))
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
        }
        return view('report.expense_details_daterange', compact('expense', 'title_date_range'));
    }

    public function expense_details_daterange(Request $request)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
//        $start_date = date('Y-m-d', strtotime($request->start_date));
//        $end_date = date('Y-m-d', strtotime($request->end_date));
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        $title_date_range = 'Expense From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');

        if (session()->get('branch') != 'all') {
            if ($request->approval_type == 'Approved') {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('status', $request->approval_type)
                    ->where('expenses.branch_id', session()->get('branch'))
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            } elseif ($request->approval_type == 'Submitted') {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('expenses.branch_id', session()->get('branch'))
                    ->where('status', $request->approval_type)
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            } else {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('expenses.branch_id', session()->get('branch'))
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            }
        } else {
            if ($request->approval_type == 'Approved') {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('status', $request->approval_type)
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            } elseif ($request->approval_type == 'Submitted') {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->where('status', $request->approval_type)
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            } else {
                $expense = Expense::with('expense_type')->whereBetween('expense_date', [$start_date, $end_date])
                    ->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc')->get();
            }

        }

        return view('report.expense_details_daterange', compact('expense', 'title_date_range'));
    }

    public function ledger_report_home()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $user = DB::table('users')
            ->select(['users.id', DB::raw("CONCAT(COALESCE(users.name,''), ':', COALESCE(users.cell_phone,''), '-', COALESCE(user_types.title,'')) as user_info")])
            ->join('user_types', 'user_types.id', '=', 'users.user_type_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->where('users.id', '!=', '1')
            ->orderBy('users.name')->pluck('user_info', 'users.id')
            ->prepend('Select User', '')->toArray();
//        dd($user);
        $account = DB::table('branches')
            ->orderBy('title')->pluck('title', 'id')
            ->prepend('Select Branch', '')->toArray();
//        dd($user);

        return view('report.ledger_report_home', compact('user', 'account'));

    }

    public function ledger_report_user(Request $request)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        $title_date_range = 'Ledger Report of ' . entryBy($request->user) . ' From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        $transaction_account = DB::table('invoices')
            ->select('transaction_date', 'transaction_code', 'invoice_total as amount', 'transaction_type', 'reference', 'created_at')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('transaction_type', '!=', 'Order')->where('user_id', $request->user)
            ->get();
        $ledger = DB::table('ledgers')->select('ledgers.transaction_date', 'ledgers.transaction_code', 'ledgers.amount',
            'transaction_types.title as transaction_type', 'ledgers.comments as reference', 'ledgers.created_at')
            ->join('transaction_types', 'transaction_types.id', '=', 'ledgers.transaction_type_id')
            ->whereBetween('transaction_date', [$start_date, $end_date])->where('ledgers.user_id', $request->user)->get();
        $myall = array_merge($transaction_account->all(), $ledger->all());
        $merged_ledger = collect($myall)->sortBy('transaction_date')->sortBy('created_at');
        $sort_array = [];
        foreach ($merged_ledger as $key => $data)
            $sort_array[] = $data;
//        dd($sort_array);
        $ledger_balance['transaction_type'] = [];
        $ledger_balance['transaction_date'] = [];
        $ledger_balance['transaction_code'] = [];
        $ledger_balance['reference'] = [];
        $ledger_balance['transaction_amount'] = [];
        $ledger_balance['balance'] = [];
        $ledger = [];
        $runningSum = 0;
        for ($i = 0; $i < count($sort_array); $i++) {
            if ($sort_array[$i]->transaction_type == 'Credited') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Debited') {
                $runningSum -= $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Receipt') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Sales') {
                $runningSum -= $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Return') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Payment') {
                $runningSum -= $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Purchase') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Put Back') {
                $runningSum -= $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Payslip') {
                $runningSum += $sort_array[$i]->amount;
            }
            $ledger_balance['transaction_type'][] = $sort_array[$i]->transaction_type;
            $ledger_balance['transaction_date'][] = $sort_array[$i]->transaction_date;
            $ledger_balance['transaction_code'][] = $sort_array[$i]->transaction_code;
            $ledger_balance['reference'][] = $sort_array[$i]->reference;
            $ledger_balance['transaction_amount'][] = $sort_array[$i]->amount;
            $ledger_balance['balance'][] = $runningSum;
            $ledger[] = $ledger_balance;
        }
        return view('report.ledger_report_user', compact('ledger', 'title_date_range'));

    }

    public function ledger_report_account(Request $request)
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $account = Branch::where('id', $request->account)->first();
        $start_date = date('Y-m-d', strtotime($request->start_date1)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date1)) . ' 23:59:59';
        $title_date_range = 'Ledger Report of ' . $account->title . ' From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        $ledger = DB::table('branch_ledgers')->select('transaction_date', 'transaction_code', 'amount', 'transaction_types.title as transaction_type', 'comments as reference', 'branch_ledgers.created_at')
            ->join('transaction_types', 'transaction_types.id', '=', 'branch_ledgers.transaction_type_id')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('branch_id', $request->account)
            ->get();

        $sort_array = [];
        foreach ($ledger as $key => $data)
            $sort_array[] = $data;
        $ledger_balance['transaction_type'] = [];
        $ledger_balance['transaction_date'] = [];
        $ledger_balance['transaction_code'] = [];
        $ledger_balance['reference'] = [];
        $ledger_balance['transaction_amount'] = [];
        $ledger_balance['balance'] = [];
        $ledger = [];
        $runningSum = 0;
        for ($i = 0; $i < count($sort_array); $i++) {
            if ($sort_array[$i]->transaction_type == 'Credited') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Receipt') {
                $runningSum += $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Payment') {
                $runningSum -= $sort_array[$i]->amount;
            }
            if ($sort_array[$i]->transaction_type == 'Debited') {
                $runningSum -= $sort_array[$i]->amount;
            }
            $ledger_balance['transaction_type'][] = $sort_array[$i]->transaction_type;
            $ledger_balance['transaction_date'][] = $sort_array[$i]->transaction_date;
            $ledger_balance['transaction_code'][] = $sort_array[$i]->transaction_code;
            $ledger_balance['reference'][] = $sort_array[$i]->reference;
            $ledger_balance['transaction_amount'][] = $sort_array[$i]->amount;
            $ledger_balance['balance'][] = $runningSum;
            $ledger[] = $ledger_balance;
        }
//            dd($ledger);
        return view('report.ledger_report_account', compact('ledger', 'title_date_range'));

    }

    public function customer_report()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $users = User::where('user_type_id', 3)
            ->orderBy('name','desc')->get();
        $trt=[];
        for ($i = 0; $i < count($users); $i++){
            if (ledgerBalance($users[$i]->id)['balance']<0)
                $trt[]=(ledgerBalance($users[$i]->id));
        }
//        dd($trt);
        $header_title='Customer Report';
        return view('report.customer_report',compact('trt','header_title'));
    }
    public function supplier_report()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $users = User::where('user_type_id', 4)
            ->orderBy('name','desc')->get();
        $trt=[];
        for ($i = 0; $i < count($users); $i++){
            if (ledgerBalance($users[$i]->id)['balance']<0)
                $trt[]=(ledgerBalance($users[$i]->id));
        }
//        dd($trt);
        $header_title='Supplier Report';
        return view('report.supplier_report',compact('trt','header_title'));
    }

    public function balance_report(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        $mindate_ledger = DB::table('ledgers')->MIN('transaction_date');
        $mindate_invoice = DB::table('invoices')->MIN('transaction_date');
        $mindate_expense = DB::table('expenses')->MIN('expense_date');
        $mindate_salary = DB::table('employee_salaries')->MIN('created_at');

//        $before1day = new DateTime($start_date);
//        $before1day->sub(new DateInterval('P1D'));
        $before1day = date('Y-m-d H:i:s', strtotime($start_date . ' -1 second'));
//        dd($end_of_day);

        //        expense b/d = brought down
        $bd_total_expense = DB::table('expenses')
            ->whereBetween('expense_date', [$mindate_expense, $before1day])
            ->sum('expense_amount');
//        dd($bd_total_expense);
        $bd_total_salary = DB::table('employee_salaries')
            ->whereBetween('created_at', [$mindate_salary, $before1day])
            ->sum('paidsalary_amount');
        //        income b/d = brought down
        $bd_salesamount = DB::table('invoices')
            ->where('transaction_type', 'Sales')
            ->whereBetween('transaction_date', [$mindate_invoice, $before1day])
//            ->whereBetween('transaction_date', [$mindate_invoice, '2024-02-29 23:00:00'])
            ->sum('invoice_total');
//        dd($bd_salesamount);
        $bd_salesamount_collect = DB::table('ledgers')
            ->where('transaction_type_id', 3) //3=Receipt
            ->whereBetween('transaction_date', [$mindate_ledger, $before1day])
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $bd_purchaseamount = DB::table('invoices')
            ->where('transaction_type', 'Purchase')
            ->whereBetween('transaction_date', [$mindate_invoice, $before1day])
            ->sum('invoice_total');
        $bd_purchaseamount_paid = DB::table('ledgers')
            ->where('transaction_type_id', 4)//4=payment
            ->whereBetween('transaction_date', [$mindate_ledger, $before1day])
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $balance_bd = $bd_salesamount - $bd_purchaseamount - $bd_total_expense - $bd_total_salary;
        $balance_bd_collect = $bd_salesamount_collect - $bd_purchaseamount_paid - $bd_total_expense - $bd_total_salary;
        //        balance b/d = brought down

        //        Income in given date range
        $sales = Invoice::with('user.profile')->orderBy('transaction_date', 'desc')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('invoice_total', '>', 0)
            ->where('transaction_type', 'Sales')
            ->get();
//        dd($sales);
        $purchase = Invoice::orderBy('transaction_date', 'desc')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('invoice_total', '>', 0)
            ->where('transaction_type', 'Purchase')
            ->get();
//        dd($purchase);
        $receipt = Ledger::orderBy('transaction_date', 'desc')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('amount', '>', 0)
            ->where('transaction_type_id', 3)
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->get();
        $payment = Ledger::orderBy('transaction_date', 'desc')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('amount', '>', 0)
            ->where('transaction_type_id', 4)
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->get();
//        dd($sales);
        $total_salesamount = DB::table('invoices')
            ->where('transaction_type', 'Sales')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->sum('invoice_total');
        $total_salesamount_collect = DB::table('ledgers')
            ->where('transaction_type_id', 3)
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $total_purchaseamount = DB::table('invoices')
            ->where('transaction_type', 'Purchase')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->sum('invoice_total');
        $total_purchaseamount_paid = DB::table('ledgers')
            ->where('transaction_type_id', 4)
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $total_income = $total_salesamount_collect - $total_purchaseamount_paid;
        //        Expense in given date range
        $expense = Expense::with('expense_type')
            ->orderBy('expense_date', 'desc')
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->get();
        $total_expense = DB::table('expenses')
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->sum('expense_amount');

        $salary = EmployeeSalary::with('user.profile')->orderBy('created_at', 'desc')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('paidsalary_amount','>',0)
            ->get();
        $total_salary = DB::table('employee_salaries')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->sum('paidsalary_amount');

        $balance = $balance_bd + $total_salesamount - $total_purchaseamount - $total_expense - $total_salary;
        $balance_collect = $balance_bd_collect + $total_salesamount_collect - $total_purchaseamount_paid - $total_expense - $total_salary;

        $of = (session()->get('branch') != 'all') ? branch_info(session()->get('branch'))->title : 'All Branches';
        $title_date_range = 'Balance Summary Report of ' . $of . ' From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');


        return view('report.balance_report', compact('expense',  'total_expense', 'total_income', 'balance', 'balance_collect',
            'bd_total_expense', 'balance_bd', 'balance_bd_collect', 'start_date', 'end_date', 'total_salesamount', 'total_salesamount_collect', 'total_salary',
            'total_purchaseamount', 'total_purchaseamount_paid', 'sales', 'purchase', 'salary', 'receipt', 'payment','title_date_range'));
    }

    public function balance_sheet()
    {
        abort_if(Gate::denies('ReportAccess'), redirect('error'));
        $inventory_products = DB::table('invoice_details')->select('product_id')->distinct('product_id')->pluck('product_id')->toArray();
        $product_total=[];
        for ($i = 0; $i < count($inventory_products); $i++) {
            $instock = (DB::table('invoice_details')->where('product_id', $inventory_products[$i])
                    ->where('transaction_type', 'Purchase')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $inventory_products[$i])
                    ->where('transaction_type', 'Sales')->sum('qty'));
            $mrp = DB::table('products')->select('unitbuy_price')->where('id', $inventory_products[$i])->sum('unitbuy_price');
            $product_total[] = $instock * $mrp;
        }
        $total_asset_inventory = array_sum($product_total);

        $total_salesamount = DB::table('invoices')
            ->where('transaction_type', 'Sales')
            ->sum('invoice_total');
        $total_salesamount_collect = DB::table('ledgers')
            ->where('transaction_type_id', 3)
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $total_purchaseamount = DB::table('invoices')
            ->where('transaction_type', 'Purchase')
            ->sum('invoice_total');
        $total_purchaseamount_paid = DB::table('ledgers')
            ->where('transaction_type_id', 4)
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->sum('amount');
        $total_expense = DB::table('expenses')
            ->sum('expense_amount');
        $total_salary = DB::table('employee_salaries')
            ->sum('paidsalary_amount');

        return view('report.balance_sheet', compact('total_expense', 'total_salesamount', 'total_salesamount_collect',
            'total_salary', 'total_purchaseamount', 'total_purchaseamount_paid', 'total_asset_inventory'));
    }


}
