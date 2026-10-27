<?php
use App\Http\Controllers\{AdminController, AuthController, DashboardController, HomeController, ProductController, ReportController, SellerController};
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->middleware('throttle:20,1')->name('report.store');

    Route::middleware(EnsureRole::class.':seller')->prefix('seller')->group(function () {
        Route::get('/', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        Route::post('/profile', [SellerController::class, 'storeProfile'])->name('seller.profile');
        Route::post('/price', [SellerController::class, 'storePrice'])->middleware('throttle:60,1')->name('seller.price');
    });

    Route::middleware(EnsureRole::class.':admin')->prefix('admin')->group(function () {
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::post('/reports/{report}/approve', [AdminController::class, 'approve'])->name('admin.approve');
        Route::post('/reports/{report}/reject', [AdminController::class, 'reject'])->name('admin.reject');
    });
});
