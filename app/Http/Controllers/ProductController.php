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
        $search = $request->query('search');

        // withAvg buat ngitung rata-rata bintang review
        $query = Product::with('category')
                        ->withAvg('reviews', 'rating');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $products = $query->latest()->get();

        // KEMBALI KE 'products.index'
        return view('products.index', compact('products'));
    }
}
