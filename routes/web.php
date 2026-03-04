<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SellerRequestController;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [AuctionController::class, 'landing'])->name('home');
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Auth
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Auctions (Only for Sellers/Admins)
    Route::middleware([\App\Http\Middleware\CheckSellerRole::class])->group(function () {
        Route::resource('auctions', AuctionController::class)->except(['index', 'show']);
    });

    // Seller Request
    Route::post('/seller/request', [SellerRequestController::class, 'store'])->name('seller.request');

    // Bids
    Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->name('bids.store');
    Route::post('/auctions/{auction}/proxy-bid', [BidController::class, 'setProxyBid'])->name('bids.proxy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}/check-status', [TransactionController::class, 'checkStatus'])->name('transactions.checkStatus');
    Route::post('/transactions/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit');
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('transactions.pay');

    // Watchlist
    Route::post('/auctions/{auction}/watchlist', [\App\Http\Controllers\WatchlistController::class, 'toggle'])->name('watchlist.toggle');
});

Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{slug}', [AuctionController::class, 'show'])->name('auctions.show');
Schedule::command('auctions:close-expired')->everyMinute();

require __DIR__.'/auth.php';