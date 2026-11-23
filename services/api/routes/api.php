<?php

use App\Http\Controllers\Api\V1\Auth\TenantAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Bless POS API is running.',
            'version' => '1.0.0',
        ]);
    });

    Route::post('/login', [TenantAuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [TenantAuthController::class, 'user']);

        Route::post('/logout', [TenantAuthController::class, 'logout']);

    });

});