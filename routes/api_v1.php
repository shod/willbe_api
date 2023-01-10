<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserInfoController;
use App\Http\Resources\UserResource;
use App\Models\User;

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
//middleware('guest')->
Route::prefix('auth')->group(function () {
    /** @see \App\Http\Controllers\Api\V1\AuthController::register() */
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot', [AuthController::class, 'forgot_password']);
    Route::post('/reset', [AuthController::class, 'reset_password']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/2fa', [AuthController::class, 'post_check_2fa']);
        Route::get('/2fa', [AuthController::class, 'get_check_2fa']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/{id}', [UserController::class, 'show']);
        Route::resource('/user_info', 'UserInfoController', ['only' => ['store', 'show', 'update']]);
    });
});


Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    dd($request->user);
    return ['test' => true];
});
