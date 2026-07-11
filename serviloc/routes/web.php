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
    return view('welcome');
});
// ============================================
// AUTH ROUTES (Breeze)
// ============================================

// Redirect /dashboard to the correct role-based dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'provider') {
        return redirect()->route('provider.dashboard');
    }
    return redirect()->route('customer.dashboard');
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
    
    // Offers and Reviews
    Route::get('/offers', [DashboardController::class, 'offers'])->name('offers');
    Route::get('/reviews', [DashboardController::class, 'reviews'])->name('reviews');

    // Nearby providers (location-based matching)
    Route::get('/requests/{serviceRequest}/nearby-providers', [App\Http\Controllers\NearbyProvidersController::class, 'show'])->name('requests.nearby');
    Route::post('/requests/{serviceRequest}/contact-provider', [App\Http\Controllers\NearbyProvidersController::class, 'contact'])->name('requests.contact-provider');
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
    Route::get('/offers', [App\Http\Controllers\Provider\DashboardController::class, 'offers'])->name('offers');
    Route::get('/requests', [App\Http\Controllers\Provider\DashboardController::class, 'requests'])->name('requests');
    Route::get('/reviews', [App\Http\Controllers\Provider\DashboardController::class, 'reviews'])->name('reviews');
});

// ============================================
// ADMIN ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/requests', [App\Http\Controllers\Admin\DashboardController::class, 'requests'])->name('requests');
    Route::get('/offers', [App\Http\Controllers\Admin\DashboardController::class, 'offers'])->name('offers');
    Route::get('/reviews', [App\Http\Controllers\Admin\DashboardController::class, 'reviews'])->name('reviews');
    Route::get('/users', [App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users');
    // User management actions
    Route::patch('/users/{user}/role', [App\Http\Controllers\Admin\DashboardController::class, 'updateUserRole'])->name('users.update-role');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\DashboardController::class, 'destroyUser'])->name('users.destroy');
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

// Removed - now handled by Customer/DashboardController@reviews


// ============================================
// SEARCH ROUTE
// ============================================

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

// ============================================
// PORTFOLIO ROUTES
// ============================================

// Public: view any provider's portfolio
Route::get('/provider/{provider}/portfolio', [App\Http\Controllers\PortfolioController::class, 'show'])
    ->name('portfolio.show');

// Provider: manage own portfolio (auth required)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/portfolio', [App\Http\Controllers\PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/create', [App\Http\Controllers\PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio', [App\Http\Controllers\PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolioItem}/edit', [App\Http\Controllers\PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolioItem}', [App\Http\Controllers\PortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolioItem}', [App\Http\Controllers\PortfolioController::class, 'destroy'])->name('portfolio.destroy');
});

// ============================================
// MESSAGING ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Inbox view with conversations
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'inbox'])->name('messages.inbox');
    
    // Show conversation thread for a request
    Route::get('/messages/{serviceRequest}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    
    // Store new message
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    
    // Mark conversation as read
    Route::patch('/messages/{serviceRequest}/mark-read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.mark-read');
    
    // API: Get unread count for UI badge
    Route::get('/api/messages/unread-count', [App\Http\Controllers\MessageController::class, 'unreadCount'])->name('api.messages.unread-count');
});

// ============================================
// PAYMENT ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->prefix('payments')->name('payments.')->group(function () {
    // Customer: checkout page for an accepted offer
    Route::get('/checkout/{offer}', [App\Http\Controllers\PaymentController::class, 'show'])->name('checkout');
    // Customer: process payment
    Route::post('/pay/{offer}', [App\Http\Controllers\PaymentController::class, 'pay'])->name('pay');
    // Shared: receipt
    Route::get('/receipt/{payment}', [App\Http\Controllers\PaymentController::class, 'receipt'])->name('receipt');
    // Customer: mark service complete → release payment
    Route::post('/release/{payment}', [App\Http\Controllers\PaymentController::class, 'release'])->name('release');
    // Customer: payment history
    Route::get('/my-payments', [App\Http\Controllers\PaymentController::class, 'customerHistory'])->name('customer-history');
    // Provider: earnings history
    Route::get('/my-earnings', [App\Http\Controllers\PaymentController::class, 'providerHistory'])->name('provider-history');
    // Admin: all payments
    Route::get('/admin/overview', [App\Http\Controllers\PaymentController::class, 'adminOverview'])->name('admin-overview');
});

// ============================================
// NOTIFICATION ROUTES
// ============================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Notification list page
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Mark single notification as read and redirect to action URL
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    
    // Mark all notifications as read
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    
    // Delete single notification
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // API: Get unread notification count for badge
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
});
