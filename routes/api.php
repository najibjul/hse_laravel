<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'post']);

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

//65|ajU2TCSPnEUMBLSVq2F4E4YPKhUEB3GYCaHf5u403e2cb428

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/activities', [App\Http\Controllers\Api\ActivityController::class, 'post']);

    Route::post('/qrp', [App\Http\Controllers\Api\ApiQrpController::class, 'store']);

    Route::get('/reports', [App\Http\Controllers\Api\CheckController::class, 'reports']);

    Route::get('/reports/confirmations', [App\Http\Controllers\Api\CheckController::class, 'confirmationReports']);

    Route::get('/reports/summary', [App\Http\Controllers\Api\CheckController::class, 'reportSummary']);

    Route::put('/reports/approve/{id}', [App\Http\Controllers\Api\CheckController::class, 'approve']);

    Route::post('/reports/recomendations/{id}', [App\Http\Controllers\Api\CheckController::class, 'tambahRekomendasi']);

    Route::post('/reports/riseup/{id}', [App\Http\Controllers\Api\CheckController::class, 'riseUp']);

    Route::post('/reports/bukti-penyelesaian/{daily_check_id}', [App\Http\Controllers\Api\CheckController::class, 'uploadBuktiPenyelesaian']);

    Route::get('/reports/{id}', [App\Http\Controllers\Api\CheckController::class, 'showReport']);

    Route::delete('/reports/{id}', [App\Http\Controllers\Api\CheckController::class, 'deleteReport']);

    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);

    Route::get('/ranks', [App\Http\Controllers\Api\RankController::class, 'index']);

    Route::post('/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePasswordPost']);

    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});

