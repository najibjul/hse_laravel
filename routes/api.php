<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'post']);

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/activities', [App\Http\Controllers\Api\ActivityController::class, 'post']);

    Route::post('/qrp', [App\Http\Controllers\Api\ApiQrpController::class, 'store']);

    Route::get('/reports', [App\Http\Controllers\Api\CheckController::class, 'reports']);

    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);

    Route::get('/ranks', [App\Http\Controllers\Api\RankController::class, 'index']);

    Route::post('/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePasswordPost']);

    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});

