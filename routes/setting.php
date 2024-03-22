<?php

use App\Http\Controllers\Api\Settings\SettingController;
use Illuminate\Support\Facades\Route;

Route::post('get-authorization', [SettingController::class, 'getAuthorization']);

Route::controller(SettingController::class)->middleware(['auth.token'])->group(function () {
    Route::get('/all-settings', 'getSettings');
});
