<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::post('/cash-in', 'cashIn')->name('deposit.store');
        Route::post('/cash-out', 'cashOut')->name('withdraw.store');
    });

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/list', 'index')->name('index');
        Route::post('/new', 'createUser')->name('create.new');
        Route::post('/update', 'updateUser')->name('update.account');
        Route::post('{user:uuid}/delete', 'deleteUser')->name('delete.account');
    });

    Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('/list', 'index')->name('index');
        Route::post('/update/social-link', 'updateSocialLink')->name('store.social');
        Route::post('/articles/{article:uuid}/upload', 'articlesFileUpload')->name('article.upload');
        Route::post('/articles/{article:uuid}/update', 'updateArticle')->name('store.article');
        Route::post('/transactions/list', 'transactionList')->name('transactions.list');
        Route::post('/subscriptions/list', 'subscriptionList')->name('subscriptions.list');
        Route::post('/subscriptions/renewal/list', 'subscriptionRenewalList')->name('subscriptions.renewals');
    });
});

Route::redirect('/', '/login');
