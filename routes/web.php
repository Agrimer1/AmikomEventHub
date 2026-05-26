<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;

// ======================
// RUTE USER AREA
// ======================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{id}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// ======================
// RUTE ADMIN AREA
// ======================
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');

    // Transactions
    Route::get('/transactions', [DashboardController::class,'indexTransaction'])->name('transactions.index');

    // Resource Events (CRUD otomatis)
    Route::resource('events', EventAdminController::class);

    // Resource Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Resource Partners
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
});