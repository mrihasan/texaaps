<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// v1 api routes
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
//    Route::get('test', [\App\Http\Controllers\Api\V1\RegistrationController::class, 'test']);
    Route::get('signup', [\App\Http\Controllers\Api\V1\RegistrationController::class, 'signup']);
    Route::post('register', [\App\Http\Controllers\Api\V1\RegistrationController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Api\V1\RegistrationController::class, 'login']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    Route::get('test', [App\Http\Controllers\Api\V1\RegistrationController::class, 'test']);
});
