<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController; // <--- JANGAN LUPA INI BANG (Penting buat Sprint 2)
use Illuminate\Support\Facades\Route;

// --- Rute Umum (Bisa diakses Tamu) ---

// Halaman Utama: Katalog Produk
Route::get('/', [ProductController::class, 'index'])->name('home');

// --- Rute Yang Wajib Login (Auth Middleware) ---
Route::middleware('auth')->group(function () {

    // 1. Rute Tambahan (Opsional)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 2. Rute Keranjang (Sprint 1)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // 3. Rute Checkout (Sprint 2 - SEKARANG SUDAH AMAN DI DALAM SINI)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // 4. Rute Order & Upload Pembayaran (Sprint 2)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/payment', [OrderController::class, 'showPaymentForm'])->name('orders.payment');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload');

    // 5. Rute Profile User (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // <--- Tutup Group Middleware di sini

// Load rute Login/Register/Logout bawaan Breeze
require __DIR__.'/auth.php';
