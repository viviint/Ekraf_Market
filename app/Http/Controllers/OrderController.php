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
        $orders = Order::where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('orders.index', compact('orders'));
    }

    // 2. Menampilkan Form Upload Bukti Bayar
    public function showPaymentForm(Order $order)
    {
        // Pastikan user cuma bisa lihat orderannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Kalau sudah dibayar, jangan kasih upload lagi
        if ($order->status !== 'Menunggu Pembayaran') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah diproses.');
        }

        return view('orders.payment', compact('order'));
    }

    // 3. Proses Simpan Gambar Bukti Bayar
    public function uploadPaymentProof(Request $request, Order $order)
    {
        // Validasi input harus gambar (jpg, png, jpeg) max 2MB
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan gambar ke folder 'public/payment_proofs'
        if ($request->hasFile('payment_proof')) {
            // Pastikan folder storage sudah di-link: php artisan storage:link
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Update Database
            $order->update([
                'payment_proof' => $path,
                'status' => 'Menunggu Verifikasi', // Ganti status biar Admin tahu
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi admin ya.');
    }
}
