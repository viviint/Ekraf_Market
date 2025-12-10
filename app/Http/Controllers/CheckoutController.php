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
        // Validasi input pembayaran
        $request->validate([
            'payment_method' => 'required|in:cod,qris,bank,ewallet',
        ]);

        $carts = Cart::where('user_id', Auth::id())->get();

        // Hitung Total Bayar
        $totalPrice = 0;
        foreach($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->quantity;
        }

        // A. Buat Order Baru
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'total_price' => $totalPrice,
        ]);

        // B. Pindahkan Item Keranjang ke OrderItems
        foreach($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);
        }

        // C. Kosongkan Keranjang (Stok sudah dikurangi saat Add to Cart di Sprint 1)
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('products.index')->with('success', 'Checkout Berhasil! Pesanan sedang diproses.');
    }
}
