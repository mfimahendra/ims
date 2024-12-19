<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialInventoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\VehicleController;

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

    // Incoming
    Route::get('/incoming', [WarehouseController::class, 'incomingIndex'])->name('incomingIndex');    
    Route::get('/fetch/incoming/data', [WarehouseController::class, 'fetchIncomingData'])->name('fetchIncomingData');
    Route::post('/submit/incoming/data', [WarehouseController::class, 'submitIncomingData'])->name('submitIncomingData');

    // Outgoing
    Route::get('/outgoing', [WarehouseController::class, 'outgoingIndex'])->name('outgoingIndex');
    Route::get('/fetch/outgoing/data', [WarehouseController::class, 'fetchOutgoingData'])->name('fetchOutgoingData');    
    Route::get('/fetch/outgoing/uncompleted', [WarehouseController::class, 'fetchUncompleted'])->name('fetchUncompleted');    
    Route::post('/submit/outgoing/data', [WarehouseController::class, 'submitOutgoingData'])->name('submitOutgoingData');
    Route::post('/submit/outgoing/complete', [WarehouseController::class, 'commitOutgoingData'])->name('commitOutgoingData');    
    Route::post('/delete/outgoing/data', [WarehouseController::class, 'deleteOutgoingData'])->name('deleteOutgoingData');

    // Transaction Logs
    Route::get('/logs', [WarehouseController::class, 'transactionLogsIndex'])->name('transactionLogsIndex');    
    Route::get('/fetch/transaction/logs', [WarehouseController::class, 'fetchTransactionLogs'])->name('fetchTransactionLogs');
});

// FINANCIAL ADMIN ROUTES
// 1. CREATE   -> create financial account
// 2. READ     -> view financial account, view financial transactions
// 3. UPDATE   -> update financial account, delete financial transactions
// 4. DELETE   -> delete financial account, delete financial transcations

Route::prefix('financial')->name('financial.')->middleware('auth')->group(function () {
    Route::get('/', [FinancialController::class, 'index'])->name('index');

    // Jurnal Transaksi
    Route::get('/account', [FinancialController::class, 'accountIndex'])->name('accountIndex');
    Route::get('/jurnal_transaksi', [FinancialController::class, 'jurnalIndex'])->name('jurnalIndex');


    // FETCH    
    Route::get('/fetch/account/data', [FinancialController::class, 'fetchAccountData'])->name('fetchAccountData');
    Route::get('fetch/journal/data', [FinancialController::class, 'fetchJournalData'])->name('fetchJournalData');

    // Transaction    
    Route::post('/account/update', [FinancialController::class, 'updateAccount'])->name('updateAccount');
});


Route::prefix('vehicle')->name('vehicle.')->middleware('auth')->group(function () {
    Route::get('/', [VehicleController::class, 'index'])->name('index');        
});