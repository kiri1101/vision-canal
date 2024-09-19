<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\CheckPaymentStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use MeSomb\Operation\PaymentOperation;
use MeSomb\Util\RandomGenerator;

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

Route::post('collect-payment', function (Request $request) {
    // return response()->json(['foo is bar', $request->all()]);
    // Add MTN && Orange shortCode to respective payment Methods in DB
    $client = new PaymentOperation(env('MESOMB_APP_KEY'), env('MESOMB_ACCESS_KEY'), env('MESOMB_SECRET_KEY'));
    // Generate uuid string
    $transactionId = Str::uuid();
    // initiate collect transaction
    info('transaction id: ', ['id' => $transactionId]);

    $response = $client->makeCollect([
        'amount' => $request->input('amount'),
        'service' => 'ORANGE',
        'payer' => $request->input('phone'),
        'fees' => false,
        'nonce' => RandomGenerator::nonce(),
        'trxID' => $transactionId
    ]);

    return response()->json([
        'transactionId' => $transactionId,
        'response' => $response
    ]);
});

Route::post('check-transaction', function (Request $request) {
    $client = new PaymentOperation(env('MESOMB_APP_KEY'), env('MESOMB_ACCESS_KEY'), env('MESOMB_SECRET_KEY'));

    $transactions = $client->getTransactions([$request->input('transactionId')], 'EXTERNAL');

    return response()->json([$transactions]);
});

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

    Route::post('payment/check-status', CheckPaymentStatusController::class);
});

// require __DIR__ . '/setting.php';
