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
    Route::get('/check-token', [AuthController::class, 'check_token']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/2fa', [AuthController::class, 'post_check_2fa']);
        Route::get('/2fa', [AuthController::class, 'get_check_2fa']);
        Route::get('/validate', [AuthController::class, 'validate_token']);
    });
});

Route::middleware(['auth:sanctum', 'abilities:auth.is_2fa'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/{uuid}', [UserController::class, 'show']);
        Route::resource('/user_info', UserInfoController::class, ['only' => ['store', 'show', 'update']]);
        Route::get('/client_list', [UserController::class, 'client_list']);
    });

    Route::prefix('clients')->group(function () {
        Route::get('/list', [ClientUserController::class, 'index']);
    });

    /** Program route */
    Route::prefix('programs')->group(function () {
        //, ['only' => ['index', 'store', 'show', 'update', 'delete']]
        Route::get('/list/{uuid}', [ProgramController::class, 'index']);
        Route::get('/{program}', [ProgramController::class, 'show']);
        Route::post('/', [ProgramController::class, 'store']);
        Route::put('/{program}', [ProgramController::class, 'update']);
        Route::delete('/{program}', [ProgramController::class, 'destroy']);
        Route::post('/{program}/status', [ProgramController::class, 'status']);

        Route::get('/{program}/sessions/{uuid}', [SessionController::class, 'index']);
    });

    Route::prefix('sessions')->group(function () {
        Route::get('/{session}/{uuid}', [SessionController::class, 'show']);
        Route::get('/{session}/steps/{uuid}', [SessionStepController::class, 'index']);
        Route::get('/{session}/storage_info/', [SessionStorageInfoController::class, 'index']);

        Route::post('/', [SessionController::class, 'store']);
        Route::put('/{session}', [SessionController::class, 'update']);
        Route::delete('/{session}', [SessionController::class, 'destroy']);
    });

    Route::prefix('steps')->group(function () {
        Route::post('/', [SessionStepController::class, 'store']);
        Route::put('/{session_step}', [SessionStepController::class, 'update']);
        Route::delete('/{session_step}', [SessionStepController::class, 'destroy']);

        Route::put('/status/{user_step}', [SessionStepController::class, 'status_update']);
    });

    Route::prefix('consultations')->group(function () {
        Route::get('/list/{uuid}', [ConsultationController::class, 'index']);
    });

    Route::prefix('targets')->group(function () {
        Route::get('/list/{uuid}', [TargetController::class, 'index']);
        Route::post('/', [TargetController::class, 'store']);
    });

    Route::prefix('tests')->group(function () {
        Route::get('/list/{uuid}', [TestController::class, 'index']);
        Route::get('/user/{uuid}', [UserTestController::class, 'index']);
        Route::post('/user', [UserTestController::class, 'store']);
        Route::put('/user', [UserTestController::class, 'update']);
    });

    Route::prefix('files')->group(function () {
        Route::post('/upload', [FileController::class, 'store']);
        Route::put('/upload', [FileController::class, 'update']);
        Route::get('/{filename}', [FileController::class, 'index']);
        Route::delete('/{filename}', [FileController::class, 'destroy']);
    });

    Route::prefix('questions')->group(function () {
        Route::get('/{question}/answer/{uuid}', [UserQuestionAnswerController::class, 'index']);
        Route::put('/{question}/answer', [UserQuestionAnswerController::class, 'update']);
        //Route::put('/{session_step}', [SessionStepController::class, 'update']);
        //Route::delete('/{session_step}', [SessionStepController::class, 'destroy']);
    });
});

Route::prefix('messages')->group(function () {
    Route::post('/form', [MessageController::class, 'form_send']);
});
