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
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cart.index', compact('carts'));
    }

    // Logic Tambah Barang (Create & Update/Edit) + Validasi Stok
    public function store(Request $request, Product $product)
    {
        // 1. Validasi Stok
        if($product->stock < 1) {
            return back()->with('error', 'Yah, stoknya habis nih!');
        }

        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingCart) {
            // Kalau sudah ada, update jumlahnya (+1)
            $existingCart->increment('quantity');
            $product->decrement('stock', 1); // <-- PENAMBAHAN 1: Kurangi stok saat item ditambah
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
            $product->decrement('stock', 1); // <-- PENAMBAHAN 2: Kurangi stok saat item baru dibuat
        }

        return redirect()->route('cart.index')->with('success', 'Berhasil masuk keranjang!');
    }

    // Logic Hapus Barang (Delete)
    public function destroy(Cart $cart)
    {
        if ($cart->user_id == Auth::id()) {
            // LOGIKA PENGEMBALIAN STOK (Wajib): Kembalikan stok saat user menghapus item dari keranjang
            $product = $cart->product;
            $product->increment('stock', $cart->quantity);

            $cart->delete();
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
