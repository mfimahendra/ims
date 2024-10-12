<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialInventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinancialController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Route::prefix('warehouse')->name('warehouse.')->middleware('auth')->group(function () {
    Route::get('/', [MaterialInventoryController::class, 'index'])->name('index');    
    Route::get('/fetch/material/inventory', [MaterialInventoryController::class, 'fetchInventory'])->name('fetchInventory');
    Route::put('/update/material', [MaterialInventoryController::class, 'updateInventory'])->name('updateInventory');    
});

Route::prefix('transaction')->name('transaction.')->middleware('auth')->group(function () {
    // Incoming
    Route::get('/incoming', [TransactionController::class, 'incomingIndex'])->name('incomingIndex');    
    Route::get('/fetch/incoming/data', [TransactionController::class, 'fetchIncomingData'])->name('fetchIncomingData');
    Route::post('/submit/incoming/data', [TransactionController::class, 'submitIncomingData'])->name('submitIncomingData');

    // Outgoing
    Route::get('/outgoing', [TransactionController::class, 'outgoingIndex'])->name('outgoingIndex');
    Route::get('/fetch/outgoing/data', [TransactionController::class, 'fetchOutgoingData'])->name('fetchOutgoingData');    
    Route::get('/fetch/outgoing/uncompleted', [TransactionController::class, 'fetchUncompleted'])->name('fetchUncompleted');    
    Route::post('/submit/outgoing/data', [TransactionController::class, 'submitOutgoingData'])->name('submitOutgoingData');
    Route::post('/submit/outgoing/complete', [TransactionController::class, 'commitOutgoingData'])->name('commitOutgoingData');    
    Route::post('/delete/outgoing/data', [TransactionController::class, 'deleteOutgoingData'])->name('deleteOutgoingData');

    // Transaction Logs
    Route::get('/logs', [TransactionController::class, 'transactionLogsIndex'])->name('transactionLogsIndex');    
    Route::get('/fetch/transaction/logs', [TransactionController::class, 'fetchTransactionLogs'])->name('fetchTransactionLogs');
});

// FINANCIAL ADMIN ROUTES
// 1. CREATE   -> create financial account
// 2. READ     -> view financial account, view financial transactions
// 3. UPDATE   -> update financial account, delete financial transactions
// 4. DELETE   -> delete financial account, delete financial transcations

Route::prefix('financial')->name('financial.')->middleware('auth')->group(function () {
    Route::get('/', [FinancialController::class, 'index'])->name('index');

    // Jurnal Transaksi
    Route::get('/jurnal_transaksi', [FinancialController::class, 'jurnalIndex'])->name('jurnalIndex');
});