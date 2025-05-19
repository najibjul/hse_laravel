<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QrpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginPost'])->name('login-post');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/daily-checking', [QrpController::class, 'dailyChecking'])->name('qrp.daily-checking');
    Route::get('/change-category', [QrpController::class, 'changeCategory'])->name('qrp.change-category');
    Route::post('/daily-checking', [QrpController::class, 'dailyCheckingPost'])->name('qrp.daily-checking-post');

    Route::get('/qrp-form', [QrpController::class, 'qrpForm'])->name('qrp.qrp-form');
    Route::post('/qrp-form', [QrpController::class, 'qrpFormPost'])->name('qrp.qrp-form-post');

    Route::get('/qrp-form/detail/{id}', [QrpController::class, 'qrpFormDetail'])->name('qrp.qrp-form-detail');
    Route::post('/qrp-form/dh-approval/{id}', [QrpController::class, 'dhApproval'])->name('qrp.dh-approval');
    Route::post('/qrp-form/dh-cancel/{id}', [QrpController::class, 'dhCancel'])->name('qrp.dh-cancel');
    Route::post('/qrp-form/upload-close/{id}', [QrpController::class, 'uploadClose'])->name('qrp.upload-close');
    Route::post('/qrp-form/upload-close-galery/{id}', [QrpController::class, 'uploadCloseGalery'])->name('qrp.upload-close-galery');
    Route::post('/qrp-form/close/{id}', [QrpController::class, 'close'])->name('qrp.close');
    Route::post('/qrp-form/tolak-open/{id}', [QrpController::class, 'tolakOpen'])->name('qrp.tolak-open');
});

