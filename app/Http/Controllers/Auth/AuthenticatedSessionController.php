<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Branch;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
//        dd($request);
//        dd(auth::user());
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'cell_phone';
//        dd($fieldType);
        if (auth()->attempt(array($fieldType => $request->username, 'password' => $request->password))) {
//                $request->authenticate();
            if (auth::user()->web_access == 0) {
                Auth::logout();
                return redirect('/login')
                    ->withErrors(array('global' => "Sorry Account not activated, Please Contact with Administrator"));
            } elseif (Auth::user()->user_type_id == 2 && Auth::user()->employee == null) {
                Auth::logout();
                return redirect('/login')
                    ->withErrors(array('global' => "No Branch Assigned for this Employee, Please assign a branch First"));
            } else
                $request->session()->regenerate();

            $branch_info = Branch::get();
            if (count($branch_info) == 1)
                $default_branch = $branch_info[0]->id;
            else {
                $default_branch = [];
                if (Auth::user()->user_type_id == 1) {
                    $default_branch = 'all';
                } elseif (count(Auth::user()->branches) > 1) {
                    $default_branch = 'all';
                } else {
                    $branchIds = Auth::user()->branches;
                    foreach ($branchIds as $bid) {
                        array_push($default_branch, $bid->id);
                    }
                }
            }
            session()->put('branch', $default_branch);

            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return redirect()->route('login')
//                ->with('error','Email-Address And Password Are Wrong.');
                ->withErrors(array('global' => "Cell-Phone/Email-Address Or Password Are Wrong."));
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
