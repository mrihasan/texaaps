<?php

namespace App\Http\Controllers;

use App\Models\BankLedger;
use App\Models\BranchLedger;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use  \Redirect, \Validator, \Input, \Session;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class EmployeeSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $employee_salaries = EmployeeSalary::orderBy('year')->orderBy('salary_month')->orderBy('user_id')->get();
        $settings = Setting::first();
        return view('employee_salary.index', compact('employee_salaries', 'settings'));

    }

    public function create()
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $current_month = date('n');
        $user = Employee::with('user')->get()->pluck('user.name', 'user.id')->prepend('Select Employee', '')->toArray();

        $account = branch_list();
        $transaction_methods = transaction_method();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
//        dd($user);
        return view('employee_salary.create', compact('user', 'current_month', 'account', 'transaction_methods', 'to_accounts'));

    }

    public function create_bonus()
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $current_month = date('n');
        $user = Employee::with('user')->get()->pluck('user.name', 'user.id')->prepend('Select Employee', '')->toArray();;
        $account = branch_list();
        $transaction_methods = transaction_method();
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        return view('employee_salary.create_bonus', compact('user', 'current_month', 'account','transaction_methods','to_accounts'));

    }

    public function create_payslip()
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $current_month = date('n');
        $user = Employee::with('user')->get()->pluck('user.name', 'user.id')->prepend('Select Employee', '')->toArray();;
        $account = branch_list();
//        dd($user);
        return view('employee_salary.create_payslip', compact('user', 'current_month', 'account'));

    }

    public function store(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denie/s('employee-salary'), redirect('error'));

        $this->validate($request, [
            'user_id' => 'required',
            'salary_month' => 'required',
            'year' => 'required',
            'paidsalary_amount' => 'required|numeric|between:0,99999999.99',
            'branch' => 'required',
            'bank_account' => 'required',
            'transaction_method' => 'required',
        ]);

        if ($request->salary_type == 'Salary') {
            $salary_payslip = EmployeeSalary::where('type', 'Salary Payslip')->where('user_id', $request->user_id)
                ->where('salary_month', (int)($request->salary_month))->where('year', (int)($request->year))
                ->first();

//            dd($salary_payslip);
            if ($salary_payslip == null) {
                \Session::flash('flash_error', 'Salary Pay Slip not Generated, Please Generate First');
                return Redirect::back();
            } else {
                $salary_payment = EmployeeSalary::where('type', 'Salary Payment')->where('user_id', $request->user_id)
                    ->where('salary_month', (int)$request->salary_month)->where('year', (int)$request->year)
                    ->sum('paidsalary_amount');
                $rest_salary = ($salary_payslip->salary_amount) - $salary_payment;
                if ($request->paidsalary_amount > $rest_salary) {
                    \Session::flash('flash_error', 'You can not pay more then the Payslip amount ');
                    return Redirect::back();
                }
            }
        } elseif ($request->salary_type == 'Bonus') {
            $salary_payslip = EmployeeSalary::where('type', 'Bonus Payslip')->where('user_id', $request->user_id)
                ->where('salary_month', (int)($request->salary_month))->where('year', (int)($request->year))
                ->first();
//            dd($salary_payslip);
            if ($salary_payslip == null) {
                \Session::flash('flash_error', 'Bonus Pay Slip not Generated, Please Generate First');
                return Redirect::back();
            } else {
                $salary_payment = EmployeeSalary::where('type', 'Bonus Payment')->where('user_id', $request->user_id)
                    ->where('salary_month', (int)$request->salary_month)->where('year', (int)$request->year)
                    ->sum('paidsalary_amount');
                $rest_salary = ($salary_payslip->salary_amount) - $salary_payment;
                if ($request->paidsalary_amount > $rest_salary) {
                    \Session::flash('flash_error', 'You can not pay more then the Payslip amount ');
                    return Redirect::back();
                }
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $transaction_code = autoTimeStampCode('ESP');

                $items = new EmployeeSalary();
                $items->type = $request->type;
                $items->user_id = $request->user_id;
                $items->branch_id = $request->branch;
                $items->transaction_code = $transaction_code;
                $items->salary_month = $request->salary_month;
                $items->year = $request->year;
                $items->holiday_weekend = $request->holiday_weekend;
                $items->leave_day = $request->leave_day;
                $items->working_day = $request->working_day;
                $items->absent_day = $request->absent_day;
                $items->paidsalary_amount = $request->paidsalary_amount;
                $items->created_at = date('Y-m-d H:i:s', strtotime($request->create_date));
                $items->entry_by = Auth::user()->id;
                $items->save();

                $ledger = new Ledger();
                $ledger->user_id = $request->user_id;
                $ledger->branch_id = $request->branch;
                $ledger->transaction_type_id = 4; //4=payment
                $ledger->transaction_method_id = $request->transaction_method;
                $ledger->transaction_date = date('Y-m-d H:i:s', strtotime($request->create_date));
                $ledger->transaction_code = $transaction_code;
                $ledger->amount = $request->paidsalary_amount;
                $ledger->comments = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year;
                $ledger->entry_by = Auth::user()->id;
                $ledger->reftbl = 'employee_salaries';
                $ledger->reftbl_id = $items->id;

                $ledger_branch = new BranchLedger();
                $ledger_branch->branch_id = $request->branch;
                $ledger_branch->transaction_date = date('Y-m-d H:i:s', strtotime($request->create_date));
                $ledger_branch->transaction_code = $transaction_code;
                $ledger_branch->amount = $request->paidsalary_amount;
                $ledger_branch->transaction_type_id = 4; //4=payzment
                $ledger_branch->transaction_method_id = $request->transaction_method;
                $ledger_branch->comments = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year
                    . ' (' . $request->comments . ')';
                $ledger_branch->entry_by = Auth::user()->id;
                $ledger_branch->approve_status = 'Approved';
                $ledger_branch->reftbl = 'employee_salaries';
                $ledger_branch->reftbl_id = $items->id;

                $ledger_banking = new BankLedger();
                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->bank_account_id = $request->bank_account;
                $ledger_banking->transaction_code = $transaction_code;
                $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->create_date)) . date(' H:i:s');
                $ledger_banking->transaction_method_id = $request->transaction_method;
                $ledger_banking->transaction_type_id = 4; //4=payzment
                $ledger_banking->amount = $request->paidsalary_amount;
                $ledger_banking->particulars = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year
                    . ' (' . $request->comments . ')';
                $ledger_banking->entry_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Approved';
                $ledger_banking->reftbl = 'employee_salaries';
                $ledger_banking->reftbl_id = $items->id;

                $ledger->save();
                $ledger_branch->save();
                $ledger_banking->save();
            });
            \Session::flash('flash_message', 'Successfully Added');

        } catch (\Exception $e) {
            \Session::flash('flash_error', 'Failed to save , Try again.');
        }

        return redirect('employee_salary');

    }

    public function payslip_all_employee(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        if ($request->type == 'Salary Payslip') {
            $this->validate($request, [
                'salary_month' => 'required',
                'year' => 'required',
                'type' => 'required',
            ]);
            $salary_payslip_exists = EmployeeSalary::where('type', 'Salary Payslip')
                ->where('year', $request->year)->where('salary_month', $request->salary_month)
                ->exists();
            if ($salary_payslip_exists) {
                \Session::flash('flash_error', 'Already Generated Salary Pay Slip for this month ( ' . $request->salary_month . '-' . $request->year . ' )');
                return Redirect::back();
            } else
                $employees = Employee::where('last_working_day', null)->get();
        } elseif ($request->type == 'Bonus Payslip') {
            $this->validate($request, [
                'salary_month' => 'required',
                'year' => 'required',
                'type' => 'required',
                'religion' => Rule::requiredIf($request->type == 'Bonus Payslip'),
            ]);

            $salary_payslip_exists = EmployeeSalary::where('type', 'Bonus Payslip')
                ->where('year', $request->year)->where('salary_month', $request->salary_month)
                ->exists();
            if ($salary_payslip_exists) {
                \Session::flash('flash_error', 'Already Generated Bonus Pay Slip for this month ( ' . $request->salary_month . '-' . $request->year . ' )');
                return Redirect::back();
            } else
                $employees = Employee::where('religion', $request->religion)->where('last_working_day', null)->get();
        } else {

        }
//        dd($employees->count());
        if ($employees->count() > 0) {
            for ($i = 0; $i < $employees->count(); $i++) {
                $transaction_code = autoTimeStampCode('ESP');
//dd($transaction_code);
                $items = new EmployeeSalary();
                $items->type = $request->type;
                $items->user_id = $employees[$i]->user_id;
                $items->branch_id = $employees[$i]->branch_id;
                $items->salary_month = $request->salary_month;
                $items->year = $request->year;
                $items->salary_amount = ($request->type == 'Bonus Payslip') ? ($employees[$i]->salary_amount * $employees[$i]->bonus_amount) / 100 : $employees[$i]->salary_amount;
                $items->transaction_code = $transaction_code;
                $items->entry_by = Auth::user()->id;
                $items->save();

                $ledger = new Ledger();
                $ledger->user_id = $employees[$i]->user_id;
                $ledger->branch_id = $employees[$i]->branch_id;
                $ledger->transaction_type_id = 7; //7=Payslip
                $ledger->transaction_method_id = 5;
                $ledger->transaction_date = date('Y-m-d H:i:s');
                $ledger->transaction_code = $transaction_code;
                $ledger->amount = ($request->type == 'Bonus Payslip') ? ($employees[$i]->salary_amount * $employees[$i]->bonus_amount) / 100 : $employees[$i]->salary_amount;
                $ledger->comments = $request->type . ' of ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year;
                $ledger->entry_by = Auth::user()->id;
                $ledger->reftbl = 'employee_salaries';
                $ledger->reftbl_id = $items->id;
                $ledger->save();
            }
            Session::flash('flash_success', 'Successfully Added');
            return Redirect::to('employee_salary');
        } else {
            \Session::flash('flash_error', 'No Employee Found');
            return Redirect::back();
        }

    }

    public function payslip_single_employee(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $this->validate($request, [
            'salary_month' => 'required',
            'year' => 'required',
            'user_id' => 'required',
            'type' => 'required',
        ]);

        if ($request->type == 'Salary Payslip') {
            $salary_payslip_exists = EmployeeSalary::where('type', 'Salary Payslip')
                ->where('year', $request->year)->where('salary_month', $request->salary_month)
                ->where('user_id', $request->user_id)
                ->exists();
            if ($salary_payslip_exists) {
                \Session::flash('flash_error', 'Already Generated Salary Pay Slip for this month ( ' . $request->salary_month . '-' . $request->year . ' )');
                return Redirect::back();
            }
        } elseif ($request->type == 'Bonus Payslip') {
            $salary_payslip_exists = EmployeeSalary::where('type', 'Bonus Payslip')
                ->where('year', $request->year)->where('salary_month', $request->salary_month)
                ->where('user_id', $request->user_id)
                ->exists();
            if ($salary_payslip_exists) {
                \Session::flash('flash_error', 'Already Generated Bonus Pay Slip for this month ( ' . $request->salary_month . '-' . $request->year . ' )');
                return Redirect::back();
            }
        }

        $transaction_code = autoTimeStampCode('ESP');
        $employees = Employee::where('user_id', $request->user_id)->first();

        $items = new EmployeeSalary();
        $items->type = $request->type;
        $items->user_id = $request->user_id;
        $items->branch_id = $employees->branch_id;
        $items->salary_month = $request->salary_month;
        $items->year = $request->year;
        $items->salary_amount = ($request->type == 'Bonus Payslip') ? ($employees->salary_amount * $employees->bonus_amount) / 100 : $employees->salary_amount;
        $items->transaction_code = $transaction_code;
        $items->entry_by = Auth::user()->id;
        $items->save();

        $ledger = new Ledger();
        $ledger->user_id = $request->user_id;
        $ledger->branch_id = $employees->branch_id;
        $ledger->transaction_type_id = 7; //7=Payslip
        $ledger->transaction_method_id = 5;
        $ledger->transaction_date = date('Y-m-d H:i:s');
        $ledger->transaction_code = $transaction_code;
        $ledger->amount = ($request->type == 'Bonus Payslip') ? ($employees->salary_amount * $employees->bonus_amount) / 100 : $employees->salary_amount;
        $ledger->comments = $request->type . ' of ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year;
        $ledger->entry_by = Auth::user()->id;
        $ledger->reftbl = 'employee_salaries';
        $ledger->reftbl_id = $items->id;
        $ledger->save();

        Session::flash('flash_success', 'Successfully Added');
        return Redirect::to('employee_salary');

    }

    public function show(EmployeeSalary $employee_salary)
    {
        return view('employee_salary.show', compact('employee_salary'));
    }

    public function edit(EmployeeSalary $employee_salary)
    {
//        dd($employee_salary);
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        if ($employee_salary->type == 'Salary Payslip' ||$employee_salary->type == 'Bonus Payslip') {
            return view('employee_salary.edit_payslip', compact('employee_salary'));
        } else {

            $account = branch_list();
            $transaction_methods = transaction_method();
            $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
            $bank_ledger = DB::table('bank_ledgers')->where('transaction_code', $employee_salary->transaction_code)->first();
            return view('employee_salary.edit', compact('employee_salary', 'account', 'transaction_methods', 'to_accounts', 'bank_ledger'));

        }
    }

    public function update(Request $request, $id)
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        if ($request->salary_type == 'Salary Payment'||$request->salary_type == 'Bonus Payment') {
            $this->validate($request, [
                'salary_month' => 'required',
                'year' => 'required',
                'paidsalary_amount' => 'required|numeric|between:0,99999999.99',
                'branch' => 'required',
                'bank_account' => 'required',
                'transaction_method' => 'required',
            ]);
            $items = EmployeeSalary::find($id);
            if ($request->salary_type == 'Salary Payment') {
                $salary_payslip = EmployeeSalary::where('type', 'Salary Payslip')->where('user_id', $items->user_id)
                    ->where('salary_month', (int)($request->salary_month))->where('year', (int)($request->year))
                    ->first();
                if ($salary_payslip == null) {
                    \Session::flash('flash_error', 'Salary Pay Slip not Generated, Please Generate First');
                    return Redirect::back();
                } else {
                    $salary_payment = EmployeeSalary::where('type', 'Salary Payment')->where('user_id', $items->user_id)
                        ->where('salary_month', (int)$request->salary_month)->where('year', (int)$request->year)
                        ->sum('paidsalary_amount');
                    $rest_salary = ($salary_payslip->salary_amount) - ($salary_payment-$items->paidsalary_amount);
                    if ($request->paidsalary_amount > $rest_salary) {
                        \Session::flash('flash_error', 'You can not pay more then the Payslip amount of '.$salary_payslip->salary_amount);
                        return Redirect::back();
                    }
                }
            }
            if ($request->salary_type == 'Bonus Payment') {
                $salary_payslip = EmployeeSalary::where('type', 'Bonus Payslip')->where('user_id', $items->user_id)
                    ->where('salary_month', (int)($request->salary_month))->where('year', (int)($request->year))
                    ->first();
                if ($salary_payslip == null) {
                    \Session::flash('flash_error', 'Salary Pay Slip not Generated, Please Generate First');
                    return Redirect::back();
                } else {
                    $salary_payment = EmployeeSalary::where('type', 'Bonus Payment')->where('user_id', $items->user_id)
                        ->where('salary_month', (int)$request->salary_month)->where('year', (int)$request->year)
                        ->sum('paidsalary_amount');
                    $rest_salary = ($salary_payslip->salary_amount) - ($salary_payment-$items->paidsalary_amount);
                    if ($request->paidsalary_amount > $rest_salary) {
                        \Session::flash('flash_error', 'You can not pay more then the Payslip amount of '.$salary_payslip->salary_amount);
                        return Redirect::back();
                    }
                }
            }

//        $items->user_id = $request->user_id;
            $items->branch_id = $request->branch;
            $items->salary_month = $request->salary_month;
            $items->year = $request->year;
            $items->holiday_weekend = $request->holiday_weekend;
            $items->leave_day = $request->leave_day;
            $items->working_day = $request->working_day;
            $items->absent_day = $request->absent_day;
            $items->paidsalary_amount = $request->paidsalary_amount;
            $items->created_at = date('Y-m-d H:i:s', strtotime($request->create_date));
            $items->updated_by = Auth::user()->id;
            $items->save();


            $del_l = DB::table('ledgers')->where('transaction_code', $items->transaction_code)->delete();
            $ledger = new Ledger();
            $ledger->user_id = $items->user_id;
            $ledger->branch_id = $request->branch;
            $ledger->transaction_type_id = 4; //4=payment
            $ledger->transaction_method_id = $request->transaction_method;
            $ledger->transaction_date = date('Y-m-d H:i:s', strtotime($request->create_date));
            $ledger->transaction_code = $items->transaction_code;
            $ledger->amount = $request->paidsalary_amount;
            $ledger->comments = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year;
            $ledger->entry_by = $items->entry_by;
            $ledger->updated_by = Auth::user()->id;
            $ledger->approve_status = 'Updated';
            $ledger->reftbl = 'employee_salaries';
            $ledger->reftbl_id = $items->id;
            $ledger->save();

            $del_bl = DB::table('branch_ledgers')->where('transaction_code', $items->transaction_code)->delete();
            $ledger_branch = new BranchLedger();
            $ledger_branch->branch_id = $request->branch;
            $ledger_branch->transaction_date = date('Y-m-d H:i:s', strtotime($request->create_date));
            $ledger_branch->transaction_code = $items->transaction_code;
            $ledger_branch->amount = $request->paidsalary_amount;
            $ledger_branch->transaction_type_id = 4; //4=payzment
            $ledger_branch->transaction_method_id = $request->transaction_method;
            $ledger_branch->comments = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year
                . ' (' . $request->comments . ')';
            $ledger_branch->entry_by = $items->entry_by;
            $ledger_branch->updated_by = Auth::user()->id;
            $ledger_branch->approve_status = 'Updated';
            $ledger_branch->reftbl = 'employee_salaries';
            $ledger_branch->reftbl_id = $items->id;
            $ledger_branch->save();

            $del_bl = DB::table('bank_ledgers')->where('transaction_code', $items->transaction_code)->delete();
            $ledger_banking = new BankLedger();
            $ledger_banking->branch_id = $request->branch;
            $ledger_banking->bank_account_id = $request->bank_account;
            $ledger_banking->transaction_code = $items->transaction_code;
            $ledger_banking->transaction_date = date('Y-m-d', strtotime($request->create_date)) . date(' H:i:s');
            $ledger_banking->transaction_method_id = $request->transaction_method;
            $ledger_banking->transaction_type_id = 4; //4=payzment
            $ledger_banking->amount = $request->paidsalary_amount;
            $ledger_banking->particulars = $request->salary_type . ' of ' . entryBy($request->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year
                . ' (' . $request->comments . ')';
            $ledger_banking->entry_by = $items->entry_by;
            $ledger_banking->updated_by = Auth::user()->id;
            $ledger_banking->approve_status = 'Updated';
            $ledger_banking->reftbl = 'employee_salaries';
            $ledger_banking->reftbl_id = $items->id;
            $ledger_banking->save();
        } elseif ($request->salary_type == 'Salary Payslip'||$request->salary_type == 'Bonus Payslip') {
            $this->validate($request, [
                'salary_month' => 'required',
                'year' => 'required',
                'salary_amount' => 'required|numeric|between:0,99999999.99',
            ]);

            $items = EmployeeSalary::find($id);
            $items->salary_month = $request->salary_month;
            $items->year = $request->year;
            $items->salary_amount = $request->salary_amount;
            $items->created_at = date('Y-m-d H:i:s', strtotime($request->create_date));
            $items->updated_by = Auth::user()->id;
            $items->save();

            $del_l = DB::table('ledgers')->where('transaction_code', $items->transaction_code)->delete();
            $ledger = new Ledger();
            $ledger->user_id = $items->user_id;
            $ledger->branch_id = $items->branch_id;
            $ledger->transaction_type_id = 7; //7=payslip
            $ledger->transaction_method_id = 5;
            $ledger->transaction_date = date('Y-m-d H:i:s', strtotime($request->create_date));
            $ledger->transaction_code = $items->transaction_code;
            $ledger->amount = $request->salary_amount;
            $ledger->comments = $request->salary_type . ' of ' . entryBy($items->user_id) . ' for ' . date("F", mktime(0, 0, 0, $request->salary_month, 10)) . ' - ' . $request->year;
            $ledger->entry_by = $items->entry_by;
            $ledger->updated_by = Auth::user()->id;
            $ledger->approve_status = 'Updated';
            $ledger->reftbl = 'employee_salaries';
            $ledger->reftbl_id = $items->id;
            $ledger->save();
        }

        Session::flash('flash_message', 'successfully updated');
        return Redirect::to('employee_salary');
    }

    public function destroy($id)
    {
//        abort_if(Gate::denies('employee-salary'), redirect('error'));
        $items = EmployeeSalary::find($id);
        $del_l = DB::table('ledgers')->where('transaction_code', $items->transaction_code)->delete();
        $items->delete();

        Session::flash('flash_message', 'successfully deleted');
        return Redirect::to('employee_salary');

    }


    public function employ_salary_value(Request $request)
    {
        if ($request->ajax()) {
            $employ_salary = Employee::where('user_id', $request->user_id)
                ->first();
            return response()->json($employ_salary);
        }
    }

    public function employ_salary_rest(Request $request)
    {
        if ($request->ajax()) {

            if ($request->user_id == null) {
                $rest_salary = 'Select User First';
            } else {
                $salary_payslip = EmployeeSalary::where('type', 'Salary Payslip')->where('user_id', $request->user_id)
                    ->where('salary_month', $request->month)->where('year', $request->year)
                    ->first();
                if ($salary_payslip == null)
                    $rest_salary = 'Pay Slip not Generated, Please Generate First ';
                else {
//                    $salary_payslip = EmployeeSalary::where('type', 'Salary Payslip')->where('user_id', $request->user_id)
//                        ->where('salary_month', $request->month)->where('year', $request->year)
//                        ->first();
                    $salary_payment = EmployeeSalary::where('type', 'Payment')->where('user_id', $request->user_id)
                        ->where('salary_month', $request->month)->where('year', $request->year)
                        ->sum('paidsalary_amount');
                    $rest_salary = ($salary_payslip->salary_amount) - $salary_payment;
                }
            }

            return response()->json($rest_salary);
        }
    }
}