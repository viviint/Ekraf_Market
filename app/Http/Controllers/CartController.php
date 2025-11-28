<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan isi keranjang User
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', Auth::id()) // Ambil punya user yang login aja
            ->latest()
            ->get();

        return view('cart.index', compact('carts'));
    }

    // Logic Tambah Barang (Create & Update/Edit) + Validasi Stok
    public function store(Request $request, Product $product)
    {
        // 1. Cek Stok Produk
        if($product->stock < 1) {
            return back()->with('error', 'Yah, stoknya habis nih!');
        }

        // 2. Cek apakah barang ini sudah ada di keranjang user?
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingCart) {
            // Kalau sudah ada, update jumlahnya (+1)
            $existingCart->increment('quantity');
        } else {
            // Kalau belum, bikin data baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Berhasil masuk keranjang!');
    }

    // Logic Hapus Barang (Delete)
    public function destroy(Cart $cart)
    {
        // Pastikan user cuma bisa hapus keranjang miliknya sendiri (Security)
        if ($cart->user_id == Auth::id()) {
            $cart->delete();
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
