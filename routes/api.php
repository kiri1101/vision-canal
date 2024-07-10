<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SupportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->middleware(['auth.token'])->prefix('auth')->group(function () {
    Route::post('login', 'loginUser');
    Route::post('register', 'createNewUser');
    // Route::post('reset-password', 'sendOtp');
    // Route::post('confirming-otp', 'confirmingOtp');
    // Route::post('users/{user:uuid}/update-password', 'saveNewPassword');
});

Route::middleware(['auth.token', 'auth:sanctum'])->group(function () {
    Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
        Route::post('/store', 'subscribeStore');
        Route::post('/renewal/store', 'renewSubscribeStore');
        Route::get('/users/{user:uuid}/subscriptions', 'subscriptionList');
        Route::get('/users/{user:uuid}/subscriptions-renewals', 'subscriptionRenewalList');
    });

    Route::controller(SupportController::class)->prefix('support')->group(function () {
        Route::post('/store', 'supportStore');
        Route::post('/users/{user:uuid}/accessory/store', 'saveAccessory');
        Route::post('/users/{user:uuid}/upload/file', 'fileUpload');
        Route::post('/users/{user:uuid}/edit', 'editUser');
        Route::post('/partner/auth', 'searchAccount');
        Route::post('users/{user:uuid}/top-up', 'topUpAccount');
    });

    Route::post('users/{user:uuid}/logout', [AuthController::class, 'logoutUser']);
});

// require __DIR__ . '/setting.php';
