<?php
use App\Http\Controllers\{AdminController, AuthController, DashboardController, HomeController, MapController, ProductController, ReportController, SellerController};
use App\Http\Controllers\Admin\{CategoryController, MarketController, ProductController as AdminProductController};
use App\Http\Middleware\{EnsureRole, SetLocale};
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function () {

    // Lugha: sw | en
    Route::get('/lang/{locale}', function (string $locale) {
        abort_unless(in_array($locale, SetLocale::LOCALES, true), 404);
        session(['locale' => $locale]);
        return redirect()->back(fallback: '/');
    })->name('lang');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [MapController::class, 'index'])->name('map');
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

        // ---------- Muuzaji ----------
        Route::middleware(EnsureRole::class.':seller')->prefix('seller')->name('seller.')->group(function () {
            Route::get('/', [SellerController::class, 'dashboard'])->name('dashboard');
            Route::get('/shops/create', [SellerController::class, 'createShop'])->name('shops.create');
            Route::post('/shops', [SellerController::class, 'storeShop'])->name('shops.store');
            Route::get('/shops/{shop}/edit', [SellerController::class, 'editShop'])->name('shops.edit');
            Route::put('/shops/{shop}', [SellerController::class, 'updateShop'])->name('shops.update');
            Route::get('/markets/create', [SellerController::class, 'createMarket'])->name('markets.create');
            Route::post('/markets', [SellerController::class, 'storeMarket'])->middleware('throttle:10,1')->name('markets.store');
            Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
            Route::post('/products', [SellerController::class, 'storeProduct'])->middleware('throttle:20,1')->name('products.store');
            Route::post('/price', [SellerController::class, 'storePrice'])->middleware('throttle:60,1')->name('price');
        });

        // ---------- Admin ----------
        Route::middleware(EnsureRole::class.':admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'overview'])->name('overview');

            Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
            Route::post('/reports/{report}/approve', [AdminController::class, 'approve'])->name('approve');
            Route::post('/reports/{report}/reject', [AdminController::class, 'reject'])->name('reject');

            Route::get('/shops', [AdminController::class, 'shops'])->name('shops');
            Route::post('/shops/{shop}/verify', [AdminController::class, 'toggleVerify'])->name('shops.verify');
            Route::post('/shops/{shop}/status', [AdminController::class, 'toggleShopStatus'])->name('shops.status');

            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::post('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
            Route::post('/users/{user}/active', [AdminController::class, 'toggleActive'])->name('users.active');

            Route::get('/records', [AdminController::class, 'records'])->name('records');
            Route::delete('/records/{record}', [AdminController::class, 'destroyRecord'])->name('records.destroy');
            Route::get('/export/prices.csv', [AdminController::class, 'exportPrices'])->name('export.prices');

            Route::resource('markets', MarketController::class)->except('show');
            Route::post('/markets/{market}/approve', [MarketController::class, 'approve'])->name('markets.approve');

            Route::resource('products', AdminProductController::class)->except('show');
            Route::post('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');

            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });
    });
});
