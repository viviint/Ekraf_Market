<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Halaman Utama langsung menampilkan Katalog Produk
Route::get('/', [ProductController::class, 'index'])->name('home');
