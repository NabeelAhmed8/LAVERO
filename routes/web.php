<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home & Catalog
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Lookbook, About, Concierge, Newsletter
Route::get('/lookbook', [PageController::class, 'lookbook'])->name('lookbook');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::post('/newsletter', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Policy, Terms, Legal
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');
Route::get('/legal-notice', [PageController::class, 'legal'])->name('legal');

// Authentication & Email Verification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.submit');
Route::get('/verify-email', [AuthController::class, 'showVerify'])->name('verify.show');
Route::post('/verify-email', [AuthController::class, 'verifyCode'])->middleware('throttle:10,1')->name('verify.submit');
Route::post('/verify-email/resend', [AuthController::class, 'resendCode'])->middleware('throttle:5,1')->name('verify.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Client Profile
Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address');
    Route::put('/profile/payment', [ProfileController::class, 'updatePayment'])->name('profile.payment');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Shopping Bag
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/stripe-success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
Route::get('/checkout/stripe-cancel', [CheckoutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Admin CMS Atelier
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('inquiries');
});
