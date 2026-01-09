<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // 1. Tampilkan Halaman Checkout
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjangmu kosong!');
        }

        return view('checkout.index', compact('carts'));
    }

    // 2. Proses Checkout (Simpan ke Database + Kurangi Stok)
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $carts = Cart::with('product')->where('user_id', Auth::id())->get();

                // Hitung Total Bayar
                $totalPrice = 0;
                foreach($carts as $cart) {
                    $totalPrice += $cart->product->price * $cart->quantity;
                }

                // 2. Buat Order Baru
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => 'INV-' . time(),
                    'status' => 'Menunggu Pembayaran',
                    'total_amount' => $totalPrice,
                    'payment_method' => $request->payment_method,

                    // --- KOLOM BARU (Sesuai Migrasi) ---
                    'recipient_name' => $request->recipient_name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,

                    // --- SOLUSI ERROR 1364 ---
                    // Kita isi kolom lama pakai data dari inputan baru biar Database gak marah
                    'shipping_name' => $request->recipient_name,
                    'shipping_phone' => $request->phone_number,
                    'shipping_address' => $request->address,
                ]);

                // 3. Proses Item & MANAJEMEN STOK
                foreach($carts as $cart) {

                    // A. CEK STOK DULU
                    if ($cart->product->stock < $cart->quantity) {
                        throw new \Exception("Stok produk '{$cart->product->name}' tidak cukup. Sisa: {$cart->product->stock}");
                    }

                    // B. Simpan ke Order Items
                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'quantity' => $cart->quantity,
                        'price' => $cart->product->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // C. KURANGI STOK (DECREMENT)
                    $cart->product->decrement('stock', $cart->quantity);
                }

                // 4. Hapus Keranjang setelah sukses
                Cart::where('user_id', Auth::id())->delete();
            });

            // Kalau lancar, redirect sukses
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil! Stok produk sudah diamankan.');

        } catch (\Exception $e) {
            // Balikin ke halaman cart kalau ada error (misal stok habis)
            return redirect()->route('cart.index')->with('error', 'Gagal Checkout: ' . $e->getMessage());
        }
    }
}
