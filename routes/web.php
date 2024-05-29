<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialInventoryController;
use App\Http\Controllers\TransactionController;

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
    Route::post('/submit/outgoing/data', [TransactionController::class, 'submitOutgoingData'])->name('submitOutgoingData');
});
