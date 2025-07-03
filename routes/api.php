<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

// Customer Routes
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'list'])->name('customers.list');
    Route::get('/{customerId}', [CustomerController::class, 'show'])->name('customers.list');
});
