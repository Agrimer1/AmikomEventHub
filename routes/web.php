<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// User Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\PublicOrganizerController;

// Admin & Multi-Tenant Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PengurusController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\OrganizerController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ======================
// RUTE USER & AUTHENTICATION AREA
// ======================

// Route login umum (menampilkan halaman login dengan Socialite Google)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Route logout umum (Mendukung GET dan POST agar tidak error 405 Method Not Allowed)
Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home')->with('success', 'Anda telah berhasil keluar dari sistem.');
})->name('logout');

// Google Socialite Login Routes
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/organizers/{organizer:slug}', [PublicOrganizerController::class, 'show'])->name('organizers.show');

Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Store Review & Rating
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// PROTECTED CHECKOUT ROUTES (WAJIB LOGIN GOOGLE / AUTHENTICATED USER)
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// ======================
// RUTE ADMIN & ORGANIZER PANEL
// ======================

Route::prefix('admin')->name('admin.')->group(function () {

    // AUTHENTICATION
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    // PROTECTED ROUTES: Bisa diakses oleh Super Admin dan Organizer
    Route::middleware(['auth', 'organizer'])->group(function () {

        // Dashboard (Scoped per Organizer / Super Admin)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Transactions (Scoped per Organizer / Super Admin)
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
            
        // CRUD Events (Scoped per Organizer / Super Admin via Policy)
        Route::resource('events', EventAdminController::class);
    });

    // PROTECTED ROUTES: Khusus Super Admin
    Route::middleware(['auth', 'admin'])->group(function () {

        // Kelola Tenant Organizers
        Route::resource('organizers', OrganizerController::class);

        // Master Data & Pengaturan Umum
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::resource('pengurus', PengurusController::class);
        Route::resource('promo-codes', PromoCodeController::class);
    });
});