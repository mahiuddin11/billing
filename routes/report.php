<?php

use App\Http\Controllers\Report\AccountReportController;
use App\Http\Controllers\Report\LedgerReportController;
use App\Http\Controllers\Report\BalanceSheetController;
use App\Http\Controllers\Report\TrialBalanceController;
use App\Http\Controllers\Report\IncomeStatementController;
use App\Http\Controllers\Admin\Device\DeviceController;
use App\Http\Controllers\Admin\MacReseller\MacResellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('Admin')->middleware(['auth'])->group(function () {
    //account report start
    Route::name('report.')->prefix('report')->group(function () {
        Route::get('/cashbook', [AccountReportController::class, 'cashbook'])->name('cashbook');
        Route::get('/create-report/cashbook', [AccountReportController::class, 'createReport'])->name('cashbooksearch');

        Route::get('/ledger', [LedgerReportController::class, 'index'])->name('ledger');
        Route::post('/create-report/ledger', [LedgerReportController::class, 'index'])->name('ledgersearch');

        Route::any('/trialbalance', [TrialBalanceController::class, 'index'])->name('trialbalance');
        Route::any('/incomestatement', [IncomeStatementController::class, 'index'])->name('incomestatement');
        Route::any('/balancesheet', [BalanceSheetController::class, 'index'])->name('balancesheet');
    });
    //account report end
});
require __DIR__ . '/auth.php';
