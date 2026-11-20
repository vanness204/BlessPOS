
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Platform\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('platform')->name('platform.')->group(function () {
    Route::middleware('guest:platform_admin')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])
            ->name('login');

        Route::post('/login', [LoginController::class, 'store'])
            ->name('login.store');
    });

    Route::middleware('platform.admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])
            ->name('logout');

        Route::get('/dashboard', function () {
            return 'Bless POS Platform Admin Dashboard';
        })->name('dashboard');
    });
});
