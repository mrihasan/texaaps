<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegistrationController extends BaseController
{

    public function login(Request $request)
    {
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'cell_phone';
        if (auth()->attempt(array($fieldType => $request->username, 'password' => $request->password))) {
            $user = User::where('id', Auth::user()->id)->first();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;
            return $this->sendResponse( 'Authorized', 200, $success, 'User login successfully.');
        } else {
            return $this->sendResponse( 'Unauthorized', 401, '', 'User Not Registered.');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse( 'Exists', 409, '', 'User Already Registered.');
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

//        return $this->sendResponse($success, 'User register successfully.');
        return $this->sendResponse( 'Registered', 201, $success, 'User register successfully.');
    }

    public function test()
    {
        $users = User::all();
        return $this->sendResponse( 'User List', 200, $users, 'User List Retrieved Successfully.');
    }
}
