<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil produk yang aktif, urutkan terbaru
        $products = Product::where('is_active', true)->latest()->get();

        // Kirim data ke view (kita buat view-nya habis ini)
        return view('products.index', compact('products'));
    }
}
