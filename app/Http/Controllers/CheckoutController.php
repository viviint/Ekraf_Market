<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // 2. Proses Checkout (Simpan ke Database)
    public function store(Request $request)
    {
        // 1. VALIDASI DILONGGARKAN
        // Kita cuma validasi payment_method karena cuma itu yang ada di form
        $request->validate([
            'payment_method' => 'required',
        ]);

        $carts = Cart::where('user_id', Auth::id())->get();

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

            // AUTO FILL: Karena di form gak ada inputnya, kita isi otomatis dulu
            'shipping_name' => Auth::user()->name,
            'shipping_address' => 'Alamat sesuai profil (Belum diisi)', // Default dulu biar gak error
            'shipping_phone' => '-', // Default strip dulu
        ]);

        // 3. Pindahkan Item ke OrderItems
        foreach($carts as $cart) {
            \Illuminate\Support\Facades\DB::table('order_items')->insert([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Hapus Keranjang
        Cart::where('user_id', Auth::id())->delete();

        // 5. Redirect ke Riwayat Pesanan
        return redirect()->route('orders.index')->with('success', 'Checkout Berhasil! Silakan upload bukti pembayaran.');
    }
}
