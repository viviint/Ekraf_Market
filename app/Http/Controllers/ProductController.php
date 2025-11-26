<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request; // Pastikan baris ini ada!

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai query dasar (ambil semua produk aktif)
        $query = Product::where('is_active', true);

        // 2. Cek apakah ada pencarian?
        if ($request->has('search')) {
            // Filter produk yang namanya mirip dengan input user
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. Eksekusi query (ambil datanya)
        $products = $query->latest()->get();

        // 4. Kirim ke view
        return view('products.index', compact('products'));
    }
}
