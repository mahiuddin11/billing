<?php

use App\Http\Controllers\Admin\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SupportTicketsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MPPPProfilesController;
use App\Http\Controllers\Api\PackageUpdateDownRatesController;
use App\Http\Controllers\Api\PaymentHistoryController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\RechargeHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Billing\BillingController;

// Public routes
Route::get('/subzones', [ApiController::class, 'get_subzones'])->name('api.subzones');
Route::get('/tjs', [ApiController::class, 'get_tjs'])->name('api.tjs');
Route::get('/cores', [ApiController::class, 'findAvailabelCore'])->name('api.cores');
Route::post('/tjs-for-new-client', [ApiController::class, 'get_tjs_for_new_clients'])->name('api.new_tj');
Route::post('/cores-for-new-client', [ApiController::class, 'get_cores_for_new_clients'])->name('api.new_cores');
Route::post('/splitter-for-new-client', [ApiController::class, 'get_splitters_for_new_clients'])->name('api.new_splitters');
Route::get('/box', [ApiController::class, 'get_box'])->name('api.box');
Route::get('/splitter', [ApiController::class, 'get_splitter'])->name('api.splitter');
Route::post('/login', [AuthController::class, 'login']);
// Protected routes with Sanctum authentication

Route::get('/dashboard/{id}', [DashboardController::class, 'index']);
Route::get('/paidhistory/{id}', [PaymentHistoryController::class, 'index']);
Route::get('/deulists/{id}', [RechargeHistoryController::class, 'index']);
Route::get('/paymentmethods', [PaymentMethodController::class, 'index']);
Route::get('/edashboard/{id}', [DashboardController::class, 'edashboard']);
Route::get('/dataProcessing/{id}', [DashboardController::class, 'dataProcessing']);

Route::name('package_update_and_down_rate.')->prefix('package_update_and_down_rate')->group(function () {
    Route::get('/list/{id}', [PackageUpdateDownRatesController::class, 'index'])->name('index');
    Route::get('/dataProcessing', [PackageUpdateDownRatesController::class, 'dataProcessing'])->name('dataProcessing');
    Route::get('/create', [PackageUpdateDownRatesController::class, 'create'])->name('create');
    Route::post('/store/{id}', [PackageUpdateDownRatesController::class, 'store'])->name('store');
    Route::get('/edit/{Packageupdatedownrate:id}', [PackageUpdateDownRatesController::class, 'edit'])->name('edit');
    Route::post('/update/{Packageupdatedownrate:id}', [PackageUpdateDownRatesController::class, 'update'])->name('update');
    Route::get('/delete/{Packageupdatedownrate:id}', [PackageUpdateDownRatesController::class, 'destroy'])->name('destroy');
});

Route::name('m_p_p_p_profiles.')->prefix('ppp-profiles')->group(function () {
    Route::get('/list/{id}', [MPPPProfilesController::class, 'index'])->name('index');
    Route::get('/dataProcessing', [MPPPProfilesController::class, 'dataProcessing'])->name('dataProcessing');
    Route::get('/create', [MPPPProfilesController::class, 'create'])->name('create');
    Route::post('/store', [MPPPProfilesController::class, 'store'])->name('store');
    Route::get('/show/{m_p_p_p_profile:id}', [MPPPProfilesController::class, 'show'])->name('show');
    Route::get('/edit/{m_p_p_p_profile:id}', [MPPPProfilesController::class, 'edit'])->name('edit');
    Route::post('/update/{m_p_p_p_profile:id}', [MPPPProfilesController::class, 'update'])->name('update');
    Route::get('/delete/{m_p_p_p_profile:id}', [MPPPProfilesController::class, 'destroy'])->name('destroy');
    Route::get('/status/{m_p_p_p_profile:id}/send-message', [MPPPProfilesController::class, 'sendMessage'])->name('send.message');
});


Route::name('supportticket.')->prefix('supportticket')->group(function () {
    Route::get('/list/{id}', [SupportTicketsController::class, 'index'])->name('index');
    Route::get('/dataProcessing', [SupportTicketsController::class, 'dataProcessing'])->name('dataProcessing');
    Route::get('/create', [SupportTicketsController::class, 'create'])->name('create');
    Route::post('/store/{id}', [SupportTicketsController::class, 'store'])->name('store');
    Route::get('/invoice/{supportticket:id}', [SupportTicketsController::class, 'invoice'])->name('invoice');
    Route::get('/edit/{supportticket:id}', [SupportTicketsController::class, 'edit'])->name('edit');
    Route::post('/update/{supportticket:id}', [SupportTicketsController::class, 'update'])->name('update');
    Route::get('/delete/{supportticket:id}', [SupportTicketsController::class, 'destroy'])->name('destroy');
    Route::get('/user-Details', [SupportTicketsController::class, 'userDetails'])->name('userdetails');
    Route::get('/status/{supportticket:id}', [SupportTicketsController::class, 'status'])->name('status');
    Route::post('/status-update/{supportticket:id}', [SupportTicketsController::class, 'statusupdate'])->name('statusupdate');
});
