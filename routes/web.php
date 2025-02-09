<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntriesController;
use App\Http\Controllers\ExitsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;

// Route::get('/welcome1', function () {
//     return view('welcome');
// });

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/entries', [EntriesController::class, 'index'])->name('Entries');
    Route::get('/exits', [ExitsController::class, 'index'])->name('Exits');
    Route::get('/profile', [ProfileController::class, 'index'])->name('Profile');
    Route::get('/report', [ReportController::class, 'index'])->name('Report');


    Route::put('/profil/{user}', [App\Http\Controllers\ProfilController::class, 'update'])->name('profil.update');

});