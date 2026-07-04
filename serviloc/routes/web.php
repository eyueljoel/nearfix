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
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'provider') {
            return redirect()->route('provider.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    return redirect()->route('login');
});
// ============================================
// AUTH ROUTES (Breeze)
// ============================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// PROFILE ROUTES
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
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

// ============================================
// PROVIDER ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Provider\DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// ADMIN ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
});
// ============================================
// OFFER ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Provider: Create offer
    Route::get('/offers/create/{requestId}', [App\Http\Controllers\OfferController::class, 'create'])->name('offers.create');
    Route::post('/offers/store/{requestId}', [App\Http\Controllers\OfferController::class, 'store'])->name('offers.store');
    
    // Customer: View offers
    Route::get('/offers', [App\Http\Controllers\OfferController::class, 'index'])->name('offers.index');
    
    // Customer: Accept/Reject offer
    Route::put('/offers/accept/{offerId}', [App\Http\Controllers\OfferController::class, 'accept'])->name('offers.accept');
    Route::put('/offers/reject/{offerId}', [App\Http\Controllers\OfferController::class, 'reject'])->name('offers.reject');
});

// ============================================
// REVIEW ROUTES
// ============================================

// ============================================
// REVIEW ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/reviews/create/{requestId}', 'App\Http\Controllers\ReviewController@create')->name('reviews.create');
    Route::post('/reviews/store/{requestId}', 'App\Http\Controllers\ReviewController@store')->name('reviews.store');
});


// ============================================
// MY REVIEWS ROUTE
// ============================================

Route::get('/customer/reviews', function() {
    return view('customer.reviews.index');
})->name('customer.reviews')->middleware('auth');


// ============================================
// SEARCH ROUTE
// ============================================

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
