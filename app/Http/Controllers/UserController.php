<?php

namespace App\Http\Controllers;

use App\Models\CompanyName;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Models\ImageProfile;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Illuminate\Support\Facades\Auth;
use  \Redirect, \Validator, \Session, \Hash;
use Illuminate\Support\Arr;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->default_password = Setting::first()->default_password;
    }

    public function index()
    {
//        dd(Auth::user()->roles[1]);
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        $users = User::where('user_type_id', 1)->where('id', '>', '1')->get();
        $user_type = 'Admin';
        return view('user.index', compact('users', 'user_type'));
    }

    public function manageClient()
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        $users = User::where('user_type_id', 3)->get();
        $user_type = 'Client';
        return view('user.index', compact('users', 'user_type'));
    }

    public function manageSupplier()
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        $users = User::where('user_type_id', 4)->get();
        $user_type = 'Supplier';
        return view('user.index', compact('users', 'user_type'));
    }

    public function create()
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        return view('user.create');
    }

    public function addClient()
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        $default_password = $this->default_password;
        return view('user.addClient', compact('default_password'));
    }

    public function addSupplier()
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        $default_password = $this->default_password;
//        $company_names = CompanyName::where('status', 'Active')->get();
        return view('user.addSupplier', compact('default_password'));
    }

    public function store(Request $request)
    {
//        dd($request);
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        if ($request->user_type == 3 || $request->user_type == 4) {
            $this->validate($request, [
                'title' => 'required|unique:company_names',
                'cell_phone' => 'nullable|digits:11|regex:/(01)[0-9]{9}/|unique:users',
                'email' => 'nullable|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'user_type' => 'required'
            ]);

            try {
                DB::transaction(function () use ($request) {

                    $user = new User;
                    $user->name = $request->title;
                    $user->email = $request->email;
                    $user->cell_phone = $request->cell_phone;
                    $user->password = bcrypt($request->password);
                    $user->user_type_id = $request->user_type;
                    $user->web_access = 0;
                    $user->save();

                    $company = new CompanyName();
                    $company->title = $request->title;
                    $company->code_name = $request->code_name;
                    $company->address = $request->address;
                    $company->address2 = $request->address2;
                    $company->contact_no = $request->cell_phone;
                    $company->contact_no2 = $request->contact_no2;
                    $company->email = $request->email;
                    $company->web = $request->web;
                    $company->status = 'Active';
                    $company->save();

                    $profile = new Profile();
                    $profile->user_id = $user->id;
                    $profile->gender = 'Male';
                    $profile->company_name_id = $company->id;
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

                    $role_ids = $request->user_type;
                    $user->roles()->attach($role_ids);
                });
                \Session::flash('flash_message', 'Successfully Added');

            } catch (\Exception $e) {
                \Session::flash('flash_error', 'Failed to save , Try again.');
            }


        } else {
            $this->validate($request, [
                'name' => 'required',
                'cell_phone' => 'required_without_all:email|nullable|digits:11|regex:/(01)[0-9]{9}/|unique:users',
                'email' => 'required_without_all:cell_phone|nullable|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'user_type' => 'required'
            ]);

            try {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->cell_phone = $request->cell_phone;
                $user->password = bcrypt($request->password);
                $user->user_type_id = $request->user_type;
                $user->web_access = $request->web_access;
                $user->save();

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->gender = $request->gender;
                $profile->company_name_id = $request->company_name_id;
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

                $role_ids = $request->user_type;
                $user->roles()->attach($role_ids);

                \Session::flash('flash_message', 'Successfully Added');

            } catch (\Exception $e) {
                \Session::flash('flash_error', 'Failed to save , Try again.');
            }
        }

        if ($request->user_type == 1)
            return redirect('user');
        elseif ($request->user_type == 2)
            return redirect('employee');
        elseif ($request->user_type == 3)
            return redirect('manageClient');
        elseif ($request->user_type == 4)
            return redirect('manageSupplier');
    }


    public function show(User $user)
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));

        if ($user->id == 1 && Auth::user()->id != 1) {
            \Session::flash('flash-error', 'You cannot view Admin ');
            return \Redirect::to('user');
        }
        $ledger = ledger_balancce($user->id);
        $ledger1 = ledgerBalance($user->id);
//        dd($ledger1);

        if ($user->user_type_id == 1)
            $user_type = 'Admin';
        elseif ($user->user_type_id == 2)
            $user_type = 'Employee';
        elseif ($user->user_type_id == 3)
            $user_type = 'Client';
        elseif ($user->user_type_id == 4)
            $user_type = 'Supplier';
        else
            $user_type_id = 'User';


        return view('user.show_sc', compact('user', 'ledger', 'ledger1','user_type'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        if ($user->id < 2 && Auth::user()->id != 1) {
            \Session::flash('flash-error', 'You cannot edit System admin ');
            return \Redirect::to('user');
        }
//        dd('tesr');
        $user = User::find($user->id);
        if (Auth::user()->email == 'superadmin@eidyict.com')
            $roles = Role::pluck('title', 'id');
        else
            $roles = Role::where('title', '!=', 'Super Admin')->pluck('title', 'id');
        $user_types = UserType::get();
        $company_names = CompanyName::where('status', 'Active')->get();
        return view('user.edit', compact(['user', 'roles', 'user_types', 'company_names']));
    }

    public function update(User $user, Request $request)
    {
        abort_if(Gate::denies('UserAccess'), redirect('error'));
        if ($user->id == 1 && Auth::user()->id != 1) {
            \Session::flash('flash_error', 'You cannot edit Super Admin ');
            return \Redirect::to('user');
        } else {

            $input = $request->all();
            if (!empty($input['password'])) {
                $this->validate($request, [
                    'password' => 'min:6|same:password_confirmation',
                    'name' => 'required',
                    'cell_phone' => 'required_without_all:email|nullable|digits:11|regex:/(01)[0-9]{9}/|unique:users,cell_phone,' . $user->id . ',id',
                    'email' => 'required_without_all:cell_phone|nullable|email|unique:users,email,' . $user->id . ',id',
                ]);
                $input['password'] = bcrypt($request['password']);
            } else {
//                dd('test');
                $this->validate($request, [
                    'name' => 'required',
                    'cell_phone' => 'required_without_all:email|nullable|digits:11|regex:/(01)[0-9]{9}/|unique:users,cell_phone,' . $user->id . ',id',
                    'email' => 'required_without_all:cell_phone|nullable|email|unique:users,email,' . $user->id . ',id',
                ]);
                $input = Arr::except($input, array('password'));
            }
//            dd($input);
            $user = User::find($user->id);
//            dd($user);
            $user->update($input);
            $user->roles()->sync($request->input('roles', []));

            //        return redirect()->route('user.index')
            \Session::flash('flash_success', 'User updated successfully');
            return redirect('user/' . $user->id);


        }
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('UserDelete'), redirect('error'));
        if ($user->id == 1 && Auth::user()->id != 1) {
            Session::flash('flash-error', 'You cannot delete Super admin ');
            return redirect('user');
        } else {
            if ($user->invoices->count()) {
                \Session::flash('flash_error', 'Can\'t Delete this, ' . $user->inventory_transaction_account->count() . ' nos used in inventory transaction');
                return redirect()->back();
            } elseif ($user->employee_salary->count()) {
                \Session::flash('flash_error', 'Can\'t Delete this, ' . $user->employee_salary->count() . ' nos used in employee Salary');
                return redirect()->back();
            } elseif ($user->ledgers()->count()) {
                \Session::flash('flash_error', 'Can\'t Delete this, ' . $user->ledger->count() . ' nos used in Ledger');
                return redirect()->back();
            } else {
                $user->delete();
                \Session::flash('flash_message', 'Successfully Deleted');
                return redirect('user');
            }
        }
    }

    public
    function myprofile()
    {
        $user = \Auth::user();

        if ($user->user_type_id == 1)
            $user_type = 'Admin';
        elseif ($user->user_type_id == 2)
            $user_type = 'Employee';
        elseif ($user->user_type_id == 3)
            $user_type = 'Client';
        elseif ($user->user_type_id == 4)
            $user_type = 'Supplier';
        else
            $user_type = 'User';

        $ledger = ledger_balancce($user->id);
        $ledger1 = ledgerBalance($user->id);
//        dd($ledger1);
        return view('user.show_sc', compact('user', 'ledger', 'ledger1','user_type'));
    }

    //By selfUser
    public
    function password_update(Request $request)
    {
//        dd($request);
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => ['required',
                'min:6',
//                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*_]).*$/',
                'different:current_password'],
            'confirm_password' => 'required|same:new_password',
        ]);
        $data = $request->all();
        $user = User::find(auth()->user()->id);
//        dd($user);
        if (!Hash::check($data['current_password'], $user->password)) {
//            dd('not match');
            \Session::flash('flash_error', 'Current password does not match the system.');
            return back();
        } else {
//            dd('match');
            $input = $request->all();
            $input['password'] = bcrypt($request['new_password']);
            $user->update($input);

            \Session::flash('flash_success', 'Password updated successfully');
            return redirect('/myprofile');
        }
    }

    public
    function select_user_action(Request $request)
    {
        if ($request->ajax()) {
            $row_id = $request->main_action;
            $data = view('user.select_user_action', compact('row_id'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function user_balance(Request $request)
    {
        if ($request->ajax()) {
            $user_balance = ledgerBalance($request->user_id);
//            dd($user_balance);
            return response()->json($user_balance);
        }
    }

}
