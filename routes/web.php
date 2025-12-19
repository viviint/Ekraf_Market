<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController; // <--- PENTING: Tambahkan Import ini buat Admin
use Illuminate\Support\Facades\Route;

// --- Rute Umum (Bisa diakses Tamu) ---

// Halaman Utama: Katalog Produk
Route::get('/', [ProductController::class, 'index'])->name('home');

// --- Rute Yang Wajib Login (Auth Middleware) ---
Route::middleware('auth')->group(function () {

    // 1. Rute Tambahan
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 2. Rute Keranjang (Sprint 1)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // 3. Rute Checkout (Sprint 2)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // 4. Rute Order & Upload Pembayaran User (Sprint 2)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/payment', [OrderController::class, 'showPaymentForm'])->name('orders.payment');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload');

});

// --- AREA KHUSUS ADMIN (Dilindungi Gate 'admin') ---
// Ini yang bikin cuma admin yang bisa masuk /admin
// Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {

//     // Dashboard Admin
//     Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

//     // Tombol Verifikasi Pembayaran
//     Route::post('/orders/{order}/verify', [AdminController::class, 'verifyPayment'])->name('admin.orders.verify');
// });
