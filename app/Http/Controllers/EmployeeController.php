<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImageProfile;
use App\Models\Ledger;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('employee-access'), redirect('error'));
        if (session()->get('branch') != 'all') {
            $employees = Employee::with('user')->orderBy('last_working_day')
                ->where('branch_id', session()->get('branch'))->get();
        }
        else{
            $employees = Employee::with('user')->orderBy('last_working_day')
                ->get();
        }
        $header_title = 'All Employees';
        return view('employee.index', compact('employees','header_title'));
    }

    public function create()
    {
        abort_if(Gate::denies('EmployeeAccess'), redirect('error'));
        $user = DB::table('users')
            ->select(['users.id', DB::raw("CONCAT(COALESCE(users.name,''), ':', COALESCE(users.cell_phone,'')) as user_info")])
            ->where('user_type_id','<=',2)
            ->orderBy('users.name')->pluck('user_info', 'users.id')
            ->prepend('Select User', '')->toArray();
//        dd($user);
        $branches = DB::table('branches')->where('status', 'Active')->get();
        return view('employee.create',compact('user','branches'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('EmployeeAccess'), redirect('error'));
        $this->validate($request, [
            'name' => 'required',
            'cell_phone' => 'required_without_all:email|nullable|digits:11|regex:/(01)[0-9]{9}/|unique:users',
            'email' => 'required_without_all:cell_phone|nullable|email|unique:users',
            'password' => 'required|min:6',
            'branch_id' => 'required',
            'religion' => 'required',
            'designation' => 'required',
            'salary_amount' => 'required|numeric|between:0,99999999.99',
            'bonus_amount' => 'required|numeric|between:0,100',
        ]);

        try{
            DB::transaction(function() use ($request) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->cell_phone = $request->cell_phone;
                $user->password = bcrypt($request->password);
                $user->user_type_id = 2;
                $user->web_access = $request->web_access;
                $user->save();

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->gender = $request->gender;
                $profile->save();

                $image_profile = new ImageProfile();
                $image_profile->user_id = $user->id;
                $image_profile->save();

                $ledger = new Ledger();
                $ledger->user_id = $user->id;
                $ledger->branch_id = (session()->get('branch') != 'all') ? session()->get('branch') : 1;
                $ledger->transaction_type_id = 1;
                $ledger->transaction_date = date('Y-m-d H:i:s');
                $ledger->transaction_code = autoTimeStampCode('LOB');
                $ledger->transaction_method_id = 5;
                $ledger->comments = 'Opening';
                $ledger->entry_by = Auth::user()->id;
                $ledger->save();

                $role_ids = $user->user_type_id;
                $user->roles()->attach($role_ids);

                $employee = new Employee();
                $employee->user_id = $user->id;
                $employee->branch_id = $request->branch_id;
                $employee->salary_amount = $request->salary_amount;
                $employee->bonus_amount = $request->bonus_amount;
                $employee->designation = $request->designation;
                $employee->religion = $request->religion;
                $employee->id_number = $request->id_number;
//        $employee->joining_day = date('Y-m-d', strtotime($request->joining_day));
                $employee->save();

                $access_branch = $request->input('access_branch');
                $employee->user->branches()->attach($access_branch);

            });
            \Session::flash('flash_message', 'Successfully Added');
        }
        catch(\Exception $e){
            \Session::flash('flash_error', $e->getMessage().' Failed to save , Try again.');
        }



        return redirect('employee');
    }

    public function show(Employee $employee)
    {
        return view('employee.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        abort_if(Gate::denies('EmployeeAccess'), redirect('error'));
        $user = DB::table('users')
            ->select(['users.id', DB::raw("CONCAT(COALESCE(users.name,''), ':', COALESCE(users.cell_phone,'')) as user_info")])
            ->where('user_type_id','<=',2)
            ->orderBy('users.name')->pluck('user_info', 'users.id')
            ->prepend('Select User', '')->toArray();
//        dd($user);
        $branches = DB::table('branches')->where('status', 'Active')->pluck('title', 'id');
        return view('employee.edit',compact('employee','user','branches'));
    }

    public function update(Request $request, Employee $employee)
    {
        abort_if(Gate::denies('EmployeeAccess'), redirect('error'));
        $this->validate($request, [
            'user_id' => 'required',
            'branch_id' => 'required',
            'religion' => 'required',
            'designation' => 'required',
            'salary_amount' => 'required|numeric|between:0,99999999.99',
            'bonus_amount' => 'required|numeric|between:0,100',
        ]);
        $employee->user_id = $request->user_id;
        $employee->salary_amount = $request->salary_amount;
        $employee->bonus_amount = $request->bonus_amount;
        $employee->designation = $request->designation;
        $employee->religion = $request->religion;
        $employee->branch_id = $request->branch_id;
        $employee->id_number = $request->id_number;
//        $employee->joining_day = date('Y-m-d', strtotime($request->joining_day));
        $employee->last_working_day = ($request->last_working_day != null) ? date('Y-m-d', strtotime($request->last_working_day)) : null;
        $employee->update();
        $employee->user->branches()->sync($request->input('access_branch', []));

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('employee');
    }

//    public function destroy(Employee $employee)
//    {
//        abort_if(Gate::denies('employee-access'), redirect('error'));
//        $employee->delete();
//        \Session::flash('flash_message', 'Successfully Deleted');
//        return redirect('employee');
//    }

}
