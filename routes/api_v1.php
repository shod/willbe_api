<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
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

/** @see \App\Http\Controllers\Api\V1\AuthController::register() */
Route::post('/register', [AuthController::class, 'register']);

// Matches "/api/loыы
Route::post('/login', 'AuthController@login');

// Matches "/api/login
Route::get('/logout', 'AuthController@logout');

Route::middleware('auth:sanctum')->get('/user/{id}', [UserController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user1/{id}', function ($id) {
    return new UserResource(User::findOrFail($id));
});

Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return [];
});
