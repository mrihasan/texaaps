<?php

namespace App\Http\Controllers;

use App\Models\BankLedger;
use App\Models\BranchLedger;
use App\Models\EmployeeSalary;
use App\Models\Expense;
use App\Models\Ledger;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DateTime;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['fallback']]);
        $this->middleware(function ($request, $next) {
            $role = Auth::user()->email;
            if ($role !== 'superadmin@eidyict.com') {
                abort(503);
            }
            return $next($request);
        }, ['except' => ['error', 'clear_all', 'cache_clear', 'config_clear', 'view_clear', 'view_cache',
            'route_clear', 'config_cache', 'route_cache', 'backupDatabase', 'fallback']]);
    }

    public function index()
    {
//        abort_if(Gate::denies('settings-access'), redirect('error'));
        $settings = Setting::first();
//        dd($settings);
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, Setting $setting)
    {
//        abort_if(Gate::denies('settings-access'), redirect('error'));
        $this->validate($request, [
            'org_name' => 'required',
            'address_line1' => 'required',
            'contact_no1' => 'required',
        ]);

        $setting->update($request->all());
        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('setting');
    }


    public function fallback()
    {
        if (Auth::user())
            return view("errors.404");
        else
            return view("errors.404_guest");

    }

    public function error()
    {
//        dd(Response::HTTP_FORBIDDEN);
        if (Response::HTTP_FORBIDDEN == 403)
            return view('errors.403');
        else
            return redirect('/home')
                ->withErrors(array('global' => "Please Contact with Administrator" . Response::HTTP_FORBIDDEN));
    }

    public function clear_all()
    {
        $exitCode = Artisan::call('config:clear');
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('view:clear');
        $exitCode = Artisan::call('route:clear');
        echo '<script>alert("Config, Cache, View & Route clear Success")</script>';

    }

    public function cache_clear()
    {
        $exitCode = Artisan::call('cache:clear');
        echo '<script>alert("Cache clear Success")</script>';
    }

    public function config_clear()
    {
        $exitCode = Artisan::call('config:clear');
        echo '<script>alert("Config clear Success")</script>';
    }

    public function view_clear()
    {
        $exitCode = Artisan::call('view:clear');
        echo '<script>alert("view clear Success")</script>';
    }

    public function route_clear()
    {
        $exitCode = Artisan::call('route:clear');
        echo '<script>alert("route clear Success")</script>';
    }

    public function config_cache()
    {
        $exitCode = Artisan::call('config:cache');
        echo '<script>alert("Config cache Success")</script>';
    }

    public function route_cache()
    {
        $exitCode = Artisan::call('route:cache');
        echo '<script>alert("route cache Success")</script>';
    }

    public function storage_link()
    {
        $exitCode = Artisan::call('storage:link');
        echo '<script>alert("Storage Link Success")</script>';
    }


    public function backupDatabase($code)
    {
        if ($code == 'Ei$2021') {
            $filename = "texaapa-" . date('YmdHis') . ".sql";
            $toFile = storage_path() . "/backups/" . $filename;
//        $mysqldump_path="F:\laragon5\bin\mysql\mysql-5.7.33-winx64\bin\mysqldump.exe";
            $mysqldump_path = "mysqldump";

            $process = new Process([
                $mysqldump_path,
                '--user=' . config('database.connections.mysql.username'),
                '--password=' . config('database.connections.mysql.password'),
                '--host=' . config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                '--result-file=' . $toFile
            ]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $path = storage_path('backups/*');
            $latest_ctime = 0;
            $latest_filename = '';
            $files = glob($path);
            foreach ($files as $file) {
                if (is_file($file) && filectime($file) > $latest_ctime) {
                    $latest_ctime = filectime($file);
                    $latest_filename = $file;
                }
            }
            return response()->download($latest_filename);
        } else
            return "Backup unsuccessful";
    }

    public function getSeeder($table)
    {
        $table = DB::table($table)->select('id', 'expense_name')->get();
        return view('seeder', compact('table'));
    }

    public function reftbl_fix($table)
    {
        if ($table == 'expenses') {
            $unfixed = DB::table($table)->orderBy('expense_date', 'asc')->get();
            for ($i = 0; $i < $unfixed->count(); $i++) {
                $td = new DateTime($unfixed[$i]->expense_date);
                $sl_no = createSl('TA-EXP-', $table, 'expense_date', $td);
                $exp = Expense::where('transaction_code', $unfixed[$i]->transaction_code)->first();
                if ($exp) {
                    $exp->sl_no = ($exp->sl_no == null) ? $sl_no : $unfixed[$i]->sl_no;
                    $exp->status = 'Submitted';
                    $exp->save();
                }
                $hwa = BankLedger::where('transaction_code', $unfixed[$i]->transaction_code)->first();
                if ($hwa) {
                    $hwa->sl_no = ($hwa->sl_no == null) ? $sl_no : $unfixed[$i]->sl_no;
                    $hwa->reftbl = $table;
                    $hwa->reftbl_id = $unfixed[$i]->id;
                    $hwa->approve_status = 'Submitted';
                    $hwa->save();
                }
                $hwb = BranchLedger::where('transaction_code', $unfixed[$i]->transaction_code)->first();
                if ($hwb) {
                    $hwb->sl_no = ($hwb->sl_no == null) ? $sl_no : $unfixed[$i]->sl_no;
                    $hwb->reftbl = $table;
                    $hwb->reftbl_id = $unfixed[$i]->id;
                    $hwb->approve_status = 'Submitted';
                    $hwb->save();
                }
            }
        }
        elseif ($table == 'employee_salaries') {
            $unfixedes = DB::table($table)->orderBy('created_at', 'asc')->get();
            for ($i = 0; $i < $unfixedes->count(); $i++) {
                $tdes = new DateTime($unfixedes[$i]->created_at);
                $sl_noes = createSl('TA-ESP-', $table, 'created_at', $tdes);
                $esp = EmployeeSalary::where('transaction_code', $unfixedes[$i]->transaction_code)->first();
                if ($esp) {
                    $esp->sl_no = ($esp->sl_no == null) ? $sl_noes : $unfixedes[$i]->sl_no;
//                    $esp->status = 'Submitted';
                    $esp->save();
                }
                $hwles = Ledger::where('transaction_code', $unfixedes[$i]->transaction_code)->first();
                if ($hwles) {
                    $hwles->sl_no = ($hwles->sl_no == null) ? $sl_noes : $unfixedes[$i]->sl_no;
                    $hwles->reftbl = $table;
                    $hwles->reftbl_id = $unfixedes[$i]->id;
                    $hwles->approve_status = 'Approved';
                    $hwles->save();
                }
                $hwaes = BankLedger::where('transaction_code', $unfixedes[$i]->transaction_code)->first();
                if ($hwaes) {
                    $hwaes->sl_no = ($hwaes->sl_no == null) ? $sl_noes : $unfixedes[$i]->sl_no;
                    $hwaes->reftbl = $table;
                    $hwaes->reftbl_id = $unfixedes[$i]->id;
                    $hwaes->approve_status = 'Approved';
                    $hwaes->save();
                }
                $hwb = BranchLedger::where('transaction_code', $unfixedes[$i]->transaction_code)->first();
                if ($hwb) {
                    $hwb->sl_no = ($hwb->sl_no == null) ? $sl_noes : $unfixedes[$i]->sl_no;
                    $hwb->reftbl = $table;
                    $hwb->reftbl_id = $unfixedes[$i]->id;
                    $hwb->approve_status = 'Approved';
                    $hwb->save();
                }
            }
        }
        elseif ($table == 'ledgers') {
            $unfixedr = DB::table($table)->where('transaction_type_id', 3)->orderBy('transaction_date', 'asc')->get();
            for ($i = 0; $i < $unfixedr->count(); $i++) {
                $tdr = new DateTime($unfixedr[$i]->transaction_date);
                $sl_nor = createSl('TA-LR-', $table, 'transaction_date', $tdr);
                $hwlr = Ledger::where('transaction_type_id', 3)->where('transaction_code', $unfixedr[$i]->transaction_code)->first();
                if ($hwlr) {
                    $hwlr->sl_no = ($hwlr->sl_no == null) ? $sl_nor : $unfixedr[$i]->sl_no;
                    $hwlr->approve_status = 'Submitted';
                    $hwlr->save();
                }
                $hwar = BankLedger::where('transaction_code', $unfixedr[$i]->transaction_code)->first();
                if ($hwar) {
                    $hwar->sl_no = ($hwar->sl_no == null) ? $sl_nor : $unfixedr[$i]->sl_no;
                    $hwar->reftbl = $table;
                    $hwar->reftbl_id = $unfixedr[$i]->id;
                    $hwar->approve_status = 'Submitted';
                    $hwar->save();
                }
                $hwbr = BranchLedger::where('transaction_code', $unfixedr[$i]->transaction_code)->first();
                if ($hwbr) {
                    $hwbr->sl_no = ($hwbr->sl_no == null) ? $sl_nor : $unfixedr[$i]->sl_no;
                    $hwbr->reftbl = $table;
                    $hwbr->reftbl_id = $unfixedr[$i]->id;
                    $hwbr->approve_status = 'Submitted';
                    $hwbr->save();
                }
            }
            $unfixedp = DB::table($table)->where('transaction_type_id', 4)->orderBy('transaction_date', 'asc')->get();
            for ($i = 0; $i < $unfixedp->count(); $i++) {
                $tdp = new DateTime($unfixedp[$i]->transaction_date);
                $sl_nop = createSl('TA-LP-', $table, 'transaction_date', $tdp);
                $hwlp = Ledger::where('transaction_type_id', 4)->where('transaction_code', $unfixedp[$i]->transaction_code)->first();
                if ($hwlp) {
                    $hwlp->sl_no = ($hwlp->sl_no == null) ? $sl_nop : $unfixedp[$i]->sl_no;
                    $hwlp->approve_status = 'Submitted';
                    $hwlp->save();
                }
                $hwap = BankLedger::where('transaction_code', $unfixedp[$i]->transaction_code)->first();
                if ($hwap) {
                    $hwap->sl_no = ($hwap->sl_no == null) ? $sl_nop : $unfixedp[$i]->sl_no;
                    $hwap->reftbl = $table;
                    $hwap->reftbl_id = $unfixedp[$i]->id;
                    $hwap->approve_status = 'Submitted';
                    $hwap->save();
                }
                $hwbp = BranchLedger::where('transaction_code', $unfixedp[$i]->transaction_code)->first();
                if ($hwbp) {
                    $hwbp->sl_no = ($hwbp->sl_no == null) ? $sl_nop : $unfixedp[$i]->sl_no;
                    $hwbp->reftbl = $table;
                    $hwbp->reftbl_id = $unfixedp[$i]->id;
                    $hwbp->approve_status = 'Submitted';
                    $hwbp->save();
                }
            }
            $unfixedo = DB::table($table)->where('transaction_type_id', 1)->orderBy('transaction_date', 'asc')->get();
            for ($i = 0; $i < $unfixedo->count(); $i++) {
                $tdo = new DateTime($unfixedo[$i]->transaction_date);
                $sl_noo = createSl('TA-LO-', $table, 'transaction_date', $tdo);
                $hwlo = Ledger::where('transaction_type_id', 1)->where('transaction_code', $unfixedo[$i]->transaction_code)->first();
                if ($hwlo) {
                    $hwlo->sl_no = ($hwlo->sl_no == null) ? $sl_noo : $unfixedo[$i]->sl_no;
                    $hwlo->approve_status = 'Approved';
                    $hwlo->checked_by= $unfixedo[$i]->entry_by;
                    $hwlo->approved_by= $unfixedo[$i]->entry_by;
                    $hwlo->checked_date= $unfixedo[$i]->created_at;
                    $hwlo->approved_date= $unfixedo[$i]->created_at;
                    $hwlo->save();
                }
            }
        }
        elseif ($table=='bank_ledgers'){
            $unfixedt = DB::table($table)->where('transaction_type_id', 10)->orderBy('transaction_date', 'asc')->get();
            for ($i = 0; $i < $unfixedt->count(); $i++) {
                $tdt = new DateTime($unfixedt[$i]->transaction_date);
                $sl_not = createSl('TA-AL-', $table, 'transaction_date', $tdt);
                $hwat = BankLedger::where('transaction_type_id', 10)->where('transaction_code', $unfixedt[$i]->transaction_code)->first();
                if ($hwat) {
                    $hwat->sl_no = ($hwat->sl_no == null) ? $sl_not : $unfixedt[$i]->sl_no;
                    $hwat->approve_status = 'Submitted';
                    $hwat->save();
                }
                $hwbt = BranchLedger::where('transaction_code', $unfixedt[$i]->transaction_code)->first();
                if ($hwbt) {
                    $hwbt->sl_no = ($hwbt->sl_no == null) ? $sl_not : $unfixedt[$i]->sl_no;
                    $hwbt->reftbl = $table;
                    $hwbt->reftbl_id = $unfixedt[$i]->id;
                    $hwbt->approve_status = 'Submitted';
                    $hwbt->save();
                }
            }
        }
//        dd($hw);
    }


}
