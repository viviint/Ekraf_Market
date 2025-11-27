<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController; // Import Controller Katalog
use App\Http\Controllers\CartController;    // Import Controller Keranjang
use Illuminate\Support\Facades\Route;

// --- Rute Umum ---

// 1. Halaman Utama: Mengarahkan ke Katalog Produk (Menggantikan welcome view)
Route::get('/', [ProductController::class, 'index'])->name('home');

// --- Rute Yang Wajib Login (Auth Middleware) ---

Route::middleware('auth')->group(function () {

    // Rute Keranjang (Feature 5)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Rute Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Catatan: Rute '/dashboard' bawaan Breeze dihilangkan karena tidak kita pakai.

// Load rute Login/Register/Logout bawaan Breeze
require __DIR__.'/auth.php';
