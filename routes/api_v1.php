<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserInfoController;
use App\Http\Controllers\Api\V1\ClientUserController;
use App\Http\Controllers\Api\V1\ProgramController;
use App\Http\Controllers\Api\V1\SessionController;
use App\Http\Controllers\Api\V1\SessionStepController;
use App\Http\Controllers\Api\V1\ConsultationController;
use App\Http\Controllers\Api\V1\TargetController;
use App\Http\Controllers\Api\V1\TestController;
use App\Http\Controllers\Api\V1\UserTestController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\UserQuestionAnswerController;
use App\Http\Controllers\Api\V1\SessionStorageInfoController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\StripeController;
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
Route::prefix('pay')->group(function () {
    Route::get('/planinfo/{slug}', [StripeController::class, 'planinfo']);
    Route::post('/stripeuser', [StripeController::class, 'stripe_user']);
    Route::post('/create', [StripeController::class, 'subcription_create']);
});

Route::prefix('auth')->group(function () {
    /** @see \App\Http\Controllers\Api\V1\AuthController::register() */
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot', [AuthController::class, 'forgot_password']);
    Route::post('/reset', [AuthController::class, 'reset_password']);
    Route::post('/check-token', [AuthController::class, 'check_token']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/2fa', [AuthController::class, 'get_check_2fa']);
        Route::get('/validate', [AuthController::class, 'validate_token']);
        Route::post('/2fa', [AuthController::class, 'post_check_2fa']);
    });
});

Route::middleware(['auth:sanctum', 'abilities:auth.is_2fa'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::prefix('targets')->group(function () {
            Route::get('/', [TargetController::class, 'index']);
            Route::post('/', [TargetController::class, 'store']);
            Route::put('/{target}', [TargetController::class, 'user_update']);
        });

        Route::prefix('tests')->group(function () {
            //Route::get('/list', [TestController::class, 'index']);
            Route::get('/', [UserTestController::class, 'index']);
            Route::post('/', [UserTestController::class, 'store']);
            Route::put('/{testid}', [UserTestController::class, 'update']);
        });

        Route::get('/{uuid}', [UserController::class, 'show']);
        Route::get('/{uuid}/user_info', [UserInfoController::class, 'show']);
        Route::post('/user_info', [UserInfoController::class, 'store']);
        Route::put('/user_info', [UserInfoController::class, 'update']);
        Route::put('/steps/{step}', [SessionStepController::class, 'status_update']);
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientUserController::class, 'index']);
    });

    /** Program route */
    Route::prefix('programs')->group(function () {
        Route::get('/{program}', [ProgramController::class, 'show']);
        Route::get('/', [ProgramController::class, 'index']);
        Route::get('/{program}/sessions', [SessionController::class, 'index']);
        Route::post('/', [ProgramController::class, 'store']);
        Route::put('/{program}', [ProgramController::class, 'update']);
        Route::delete('/{program}', [ProgramController::class, 'destroy']);
        //Route::post('/{program}/status', [ProgramController::class, 'status']);
    });

    Route::prefix('sessions')->group(function () {
        Route::get('/{session}', [SessionController::class, 'show']);
        Route::get('/{session}/steps', [SessionStepController::class, 'index']);
        Route::get('/{session}/storage_info/', [SessionStorageInfoController::class, 'index']);

        Route::post('/', [SessionController::class, 'store']);
        Route::put('/{session}', [SessionController::class, 'update']);
        Route::delete('/{session}', [SessionController::class, 'destroy']);
    });

    Route::prefix('steps')->group(function () {
        Route::post('/', [SessionStepController::class, 'store']);
        Route::put('/{session_step}', [SessionStepController::class, 'update']);
        Route::delete('/{session_step}', [SessionStepController::class, 'destroy']);

        //Route::put('/status', [SessionStepController::class, 'status_update']);
    });

    Route::prefix('consultations')->group(function () {
        Route::get('/', [ConsultationController::class, 'index']);
    });

    Route::prefix('files')->group(function () {
        Route::post('/upload', [FileController::class, 'store']);
        //Route::get('/{filename}', [FileController::class, 'index']);
        //Route::delete('/{filename}', [FileController::class, 'destroy']);
    });

    Route::prefix('questions')->group(function () {
        Route::get('/{question}/answer', [UserQuestionAnswerController::class, 'index']);
        Route::put('/{question}/answer', [UserQuestionAnswerController::class, 'update']);
        //Route::put('/{session_step}', [SessionStepController::class, 'update']);
        //Route::delete('/{session_step}', [SessionStepController::class, 'destroy']);
    });
});

Route::prefix('messages')->group(function () {
    Route::post('/form', [MessageController::class, 'form_send']);
});
