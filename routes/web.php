<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntriesController;
use App\Http\Controllers\ExitsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;

// Authentication Routes
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Main Navigation Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/entries', [EntriesController::class, 'index'])->name('Entries');
    Route::get('/exits', [ExitsController::class, 'index'])->name('Exits');
    Route::get('/profile', [ProfileController::class, 'index'])->name('Profile');
    Route::get('/report', [ReportController::class, 'index'])->name('Report');
    Route::get('/stock', [StockController::class, 'index'])->name('Stock');
    Route::get('/supplier', [SupplierController::class, 'index'])->name('Supplier'); // Corrected 'Supplier' to 'supplier' for consistency

    // Profile Update Route
    Route::put('/profil/{user}', [ProfileController::class, 'update'])->name('profil.update');

    // Report Specific Routes (Order matters: most specific to most general)
    Route::get('/report/entries', [ReportController::class, 'entries'])->name('report.entries');
    Route::get('/report/exits', [ReportController::class, 'exits'])->name('report.exits');
    Route::get('/report/{type}', [ReportController::class, 'show'])->name('report.show');
});