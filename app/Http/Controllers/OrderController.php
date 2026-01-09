<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // 1. Menampilkan Daftar Pesanan User (History)
    public function index()
    {
        // TAMBAHAN: ->with('products')
        // Ini wajib biar kita bisa meloop barang apa aja yg dibeli di view nanti
        $orders = Order::where('user_id', Auth::id())
                        ->with('products')
                        ->latest()
                        ->get();

        return view('orders.index', compact('orders'));
    }

    // 2. Menampilkan Form Upload Bukti Bayar
    public function showPaymentForm(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'Menunggu Pembayaran' && $order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah diproses.');
        }

        return view('orders.payment', compact('order'));
    }

    // 3. Proses Simpan Gambar Bukti Bayar
    public function uploadPaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            $order->update([
                'payment_proof' => $path,
                'status' => 'Menunggu Verifikasi',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi admin ya.');
    }
}
