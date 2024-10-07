<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Branch;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

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
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'cell_phone';

        if (auth()->attempt(array($fieldType => $request->username, 'password' => $request->password))) {

            // Check if the user account is active and has the necessary conditions
            if (auth::user()->web_access == 0) {
                Auth::logout();
                return redirect('/login')
                    ->withErrors(['global' => "Sorry, Account not activated. Please contact the Administrator."]);
            } elseif (Auth::user()->user_type_id == 2 && Auth::user()->employee == null) {
                Auth::logout();
                return redirect('/login')
                    ->withErrors(['global' => "No branch assigned for this employee. Please assign a branch first."]);
            }

            // Regenerate session after successful login
            $request->session()->regenerate();

//            // API call to check access permission from the master app
//            $response = Http::get('http://eidyict.com/api/access-permission/1EP1727954177537');
//            // Ensure the API response is successful
//            if ($response->successful()) {
//                $data = $response->json();
//                // Check if the access permission date is valid
//                if (isset($data['access_permission_date'])) {
//                    $accessPermissionDate = $data['access_permission_date'];
//                    // Compare the current date with the access permission date
//                    if (now()->gt($accessPermissionDate)) {
//                        Auth::logout();
//                        return redirect('/login')
//                            ->withErrors(['global' => "Access permission expired at " . $accessPermissionDate . '. Please contact Khairuzzaman, 01716383038']);
//                    }
//                }
//            } else {
//                // If API call fails, handle the error (optional)
//                Auth::logout();
//                return redirect('/login')
//                    ->withErrors(['global' => "Failed to verify access permission. Please try again later."]);
//            }


            // Retrieve PROJECT_CODE from the .env file
            $projectCode = env('PROJECT_CODE', null);

// Check if PROJECT_CODE is set and not empty
            if (!empty($projectCode)) {

                // Perform your logic here, e.g., making the API call using PROJECT_CODE
                $response = Http::get('http://eidyict.com/api/access-permission/' . $projectCode);

                if ($response->successful()) {
                    $data = $response->json();

//                    if (isset($data['access_permission_date'])) {
//                        $accessPermissionDate = $data['access_permission_date'];
//                        if (now()->gt($accessPermissionDate)) {
//                            Auth::logout();
//                            return redirect('/login')
//                                ->withErrors(['global' => "Access permission expired at " . $accessPermissionDate . '. Please contact Khairuzzaman, 01716383038']);
//                        }
//                    }
                    if (isset($data['access_permission_date'])) {
                        $accessPermissionDate = Carbon::parse($data['access_permission_date']);  // Ensure it's a Carbon instance

                        // Calculate the warning date (30 days before the accessPermissionDate)
                        // Clone the access permission date to avoid mutating the original
                        $warningDate = $accessPermissionDate->copy()->subDays(30);
                        //dd($accessPermissionDate.'-warning '.$warningDate);
                        // If the access permission date has passed
                        if (now()->gt($accessPermissionDate)) {
                            Auth::logout();
                            return redirect('/login')
                                ->withErrors(['global' => "Access permission expired on " . $accessPermissionDate->toDateString() . '. and make sure you paid as per contract for the software subscription. Please contact Khairuzzaman, 01716383038']);
                        }

                        // Check if the current date is within the 30-day warning period
                        if (now()->greaterThanOrEqualTo($warningDate) && now()->lessThanOrEqualTo($accessPermissionDate)) {
                            // Set a session variable to trigger the warning modal
                            session()->flash('showWarningModal', true);
                            session()->flash('accessPermissionDate', $accessPermissionDate->toDateString());

                            // Handle branch assignment logic
                            $branch_info = Branch::get();
                            if (count($branch_info) == 1) {
                                $default_branch = $branch_info[0]->id;
                            } else {
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
                            // Store default branch in session
                            session()->put('branch', $default_branch);

                            // Redirect to home page
                            return redirect()->intended(RouteServiceProvider::HOME);
                        }

                    }

                } else {
                    Auth::logout();
                    return redirect('/login')
                        ->withErrors(['global' => "Failed to verify access permission. Please try again later."]);
                }

            }


            // Handle branch assignment logic
            $branch_info = Branch::get();
            if (count($branch_info) == 1) {
                $default_branch = $branch_info[0]->id;
            } else {
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

            // Store default branch in session
            session()->put('branch', $default_branch);

            // Redirect to the home page after successful login
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            // Return error if login fails
            return redirect()->route('login')
                ->withErrors(['global' => "Cell-Phone/Email-Address or Password are wrong."]);
        }
    }

    public function store_whout_apicall(LoginRequest $request)
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
