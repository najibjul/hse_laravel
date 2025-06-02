<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login']);
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login-post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/daily-checking', [QrpController::class, 'dailyChecking'])->name('qrp.daily-checking');
    Route::get('/change-factor', [QrpController::class, 'changeFactor'])->name('qrp.change-factor');
    Route::post('/daily-checking', [QrpController::class, 'dailyCheckingPost'])->name('qrp.daily-checking-post');

    Route::get('/qrp-form', [QrpController::class, 'qrpForm'])->name('qrp.qrp-form');
    Route::get('/qrp-form/search-adh', [QrpController::class, 'searchAdh'])->name('qrp.qrp-form.search-adh');
    Route::post('/qrp-form', [QrpController::class, 'qrpFormPost'])->name('qrp.qrp-form-post');

    Route::get('/qrp-form/detail/{id}', [QrpController::class, 'qrpFormDetail'])->name('qrp.qrp-form-detail');
    Route::delete('/qrp-form/detail/{id}', [QrpController::class, 'qrpFormDelete'])->name('qrp.qrp-form-detail.destroy');
    Route::get('/qrp-form/detail/{id}/edit', [QrpController::class, 'qrpFormDetailEdit'])->name('qrp.qrp-form-detail.edit');

    
    Route::post('/qrp-form/approval/{id}', [QrpController::class, 'approval'])->name('qrp.approval');
    Route::post('/qrp-form/dh-cancel/{id}', [QrpController::class, 'dhCancel'])->name('qrp.dh-cancel');
    Route::post('/qrp-form/upload-close/{id}', [QrpController::class, 'uploadClose'])->name('qrp.upload-close');
    Route::post('/qrp-form/upload-close-galery/{id}', [QrpController::class, 'uploadCloseGalery'])->name('qrp.upload-close-galery');
    Route::post('/qrp-form/close/{id}', [QrpController::class, 'close'])->name('qrp.close');
    Route::post('/qrp-form/tolak-open/{id}', [QrpController::class, 'tolakOpen'])->name('qrp.tolak-open');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function(){
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::resource('/users', UserController::class);
            
            Route::resource('/departments', DepartmentController::class);
            Route::get('/departments/{id}/edit/search-dh', [DepartmentController::class, 'searchDh'])->name('departments.edit.search-dh');
        });
    });
});

