<?php

use App\Http\Controllers\Admin\CostCenterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PlantController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login']);
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginPost'])->name('login-post');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('daily-checking', [QrpController::class, 'dailyChecking'])->name('qrp.daily-checking');
    Route::get('change-factor', [QrpController::class, 'changeFactor'])->name('qrp.change-factor');
    Route::post('daily-checking', [QrpController::class, 'dailyCheckingPost'])->name('qrp.daily-checking-post');

    Route::middleware('factor')->group(function(){
        Route::get('qrp-form', [QrpController::class, 'qrpForm'])->name('qrp.qrp-form');
        Route::post('qrp-form', [QrpController::class, 'qrpFormPost'])->name('qrp.qrp-form-post');
    });

    Route::get('qrp-form/detail/{id}', [QrpController::class, 'qrpFormDetail'])->name('qrp.qrp-form-detail');
    Route::delete('qrp-form/detail/{id}', [QrpController::class, 'qrpFormDelete'])->name('qrp.qrp-form-detail.destroy');
    Route::get('qrp-form/detail/{id}/edit', [QrpController::class, 'qrpFormDetailEdit'])->name('qrp.qrp-form-detail.edit');
    Route::patch('qrp-form/update/{id}', [QrpController::class, 'qrpFormUpdate'])->name('qrp.qrp-form-update');
    
    Route::post('qrp-form/approval/{id}', [QrpController::class, 'approval'])->name('qrp.approval');
    Route::post('qrp-form/confirm/{id}', [QrpController::class, 'confirm'])->name('qrp.confirm');
    Route::post('qrp-form/dh-cancel/{id}', [QrpController::class, 'dhCancel'])->name('qrp.dh-cancel');
    Route::post('qrp-form/upload-close/{id}', [QrpController::class, 'uploadClose'])->name('qrp.upload-close');
    Route::post('qrp-form/upload-close/edit/{id}', [QrpController::class, 'uploadCloseEdit'])->name('qrp.upload-close-edit');
    Route::post('qrp-form/upload-close-galery/{id}', [QrpController::class, 'uploadCloseGalery'])->name('qrp.upload-close-galery');
    Route::post('qrp-form/upload-close-galery/edit/{id}', [QrpController::class, 'uploadCloseGaleryEdit'])->name('qrp.upload-close-galery-edit');
    Route::post('qrp-form/close/{id}', [QrpController::class, 'close'])->name('qrp.close');
    Route::post('qrp-form/tolak-open/{id}', [QrpController::class, 'tolakOpen'])->name('qrp.tolak-open');

    Route::post('rise-up/{id}', [QrpController::class, 'riseUp'])->name('rise-up');    

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::patch('notification/{id}', [NotificationController::class, 'update'])->name('notification.update');

    Route::post('export', [ExportController::class, 'store'])->name('export.store');
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/qrp/export', [ExportController::class, 'qrpExport'])->name('qrp.export');

    Route::middleware('admin')->group(function(){
        Route::prefix('admin')->as('admin.')->group(function () {
            
            Route::resource('users', UserController::class);
            Route::resource('departments', DepartmentController::class);
            Route::resource('cost-centers', CostCenterController::class);
            Route::resource('positions', PositionController::class);
            Route::resource('plants', PlantController::class);
            
            Route::get('department-master', [MasterController::class, 'department'])->name('department-master');
            Route::get('department-master/{id}', [MasterController::class, 'departmentShow'])->name('department-master-selected');
            
            Route::get('position-master', [MasterController::class, 'position'])->name('position-master');
            Route::get('position-master/{id}', [MasterController::class, 'positionShow'])->name('position-master-selected');
            
            Route::get('cost-center-master', [MasterController::class, 'costCenter'])->name('cost-center-master');
            Route::get('cost-center-master/{id}', [MasterController::class, 'costCenterShow'])->name('cost-center-master-selected');
            
            Route::get('plant-master', [MasterController::class, 'plant'])->name('plant-master');
            Route::get('plant-master/{id}', [MasterController::class, 'plantShow'])->name('plant-master-selected');
            
            Route::get('leader-master', [MasterController::class, 'leader'])->name('leader-master');
            Route::get('leader-master/{id}', [MasterController::class, 'leaderShow'])->name('leader-master-selected');
        });
        
        Route::get('/users/export', [ExportController::class, 'userExport'])->name('users.export');
        Route::get('/departments/export', [ExportController::class, 'departmentExport'])->name('departments.export');
        Route::get('/cost-centers/export', [ExportController::class, 'costCenterExport'])->name('cost-centers.export');
        Route::get('/positions/export', [ExportController::class, 'positionExport'])->name('positions.export');
        Route::get('/plants/export', [ExportController::class, 'plantExport'])->name('plants.export');
    });
});