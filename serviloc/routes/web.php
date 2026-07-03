<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', function () {
    return redirect()->route('customer.dashboard');
});

// ============================================
// AUTH ROUTES (Breeze)
// ============================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// CUSTOMER ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Service Requests
    Route::get('/requests', [DashboardController::class, 'requests'])->name('requests');
    Route::get('/requests/create', [DashboardController::class, 'createRequest'])->name('requests.create');
    Route::post('/requests', [DashboardController::class, 'storeRequest'])->name('requests.store');
    Route::get('/requests/{id}', [DashboardController::class, 'showRequest'])->name('requests.show');
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

require __DIR__.'/auth.php';