<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Katalog Produk).
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari URL (misalnya ?search=baju)
        $search = $request->query('search');

        // Mulai Query Builder
        $query = Product::with('category');

        // Jika ada input pencarian, terapkan filter
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        // Ambil data produk
        $products = $query->latest()->get();

        return view('products.index', compact('products'));
    }

    // ... (method lain di bawahnya)
}
