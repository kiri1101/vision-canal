<?php

use App\Http\Controllers\Api\AuthController;
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
    Route::post('register', 'createNewUser');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['data' => $request->user()]);
});

// require __DIR__ . '/setting.php';
