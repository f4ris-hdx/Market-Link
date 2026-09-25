<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/products', [PublicController::class, 'products'])->name('products.index');
Route::get('/markets', [PublicController::class, 'markets'])->name('markets.index');
Route::get('/farmers', [PublicController::class, 'farmers'])->name('farmers.index');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    // Optional role-specific login entry points. They use the same authentication
    // logic and database, but reject credentials belonging to another role.
    Route::get('/admin/login', fn () => app(AuthController::class)->showLoginForRole('admin'))->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->defaults('login_role', 'admin')->name('admin.login.store');
    Route::get('/farmer/login', fn () => app(AuthController::class)->showLoginForRole('farmer'))->name('farmer.login');
    Route::post('/farmer/login', [AuthController::class, 'login'])->defaults('login_role', 'farmer')->name('farmer.login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile');
    Route::post('/favorites/{product}', [DashboardController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/orders/{order}/cancel', [DashboardController::class, 'cancelOrder'])->name('orders.cancel');

    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'place'])->name('checkout.place');
});

Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/', [FarmerController::class, 'index'])->name('home');
    Route::get('/dashboard', [FarmerController::class, 'index'])->name('dashboard');
    Route::get('/products', [FarmerController::class, 'products'])->name('products');
    Route::get('/products/new', [FarmerController::class, 'create'])->name('products.create');
    Route::post('/products', [FarmerController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [FarmerController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [FarmerController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [FarmerController::class, 'destroy'])->name('products.destroy');
    Route::get('/orders', [FarmerController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}', [FarmerController::class, 'updateOrder'])->name('orders.update');
    Route::get('/slots', [FarmerController::class, 'slots'])->name('slots');
    Route::post('/slots', [FarmerController::class, 'updateSlots'])->name('slots.update');
    Route::post('/profile', [FarmerController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/farmers', [AdminController::class, 'farmers'])->name('farmers');
    Route::get('/farmers/create', [AdminController::class, 'createFarmer'])->name('farmers.create');
    Route::post('/farmers', [AdminController::class, 'storeFarmer'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [AdminController::class, 'editFarmer'])->name('farmers.edit');
    Route::put('/farmers/{farmer}', [AdminController::class, 'updateFarmer'])->name('farmers.update');
    Route::post('/farmers/{farmer}/verify', [AdminController::class, 'verify'])->name('farmers.verify');
    Route::post('/farmers/{farmer}/suspend', [AdminController::class, 'suspend'])->name('farmers.suspend');
    Route::delete('/farmers/{farmer}', [AdminController::class, 'deleteFarmer'])->name('farmers.delete');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/markets', [AdminController::class, 'markets'])->name('markets');
    Route::post('/markets', [AdminController::class, 'storeMarket'])->name('markets.store');
    Route::put('/markets/{market}', [AdminController::class, 'updateMarket'])->name('markets.update');
    Route::delete('/markets/{market}', [AdminController::class, 'destroyMarket'])->name('markets.destroy');
    Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [AdminController::class, 'destroyAnnouncement'])->name('announcements.destroy');
});
