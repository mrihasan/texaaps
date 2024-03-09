<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


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
            $mysqldump_path="mysqldump";

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
        $table=DB::table($table)->select('id','expense_name')->get();
        return view('seeder',compact('table'));
    }

}
