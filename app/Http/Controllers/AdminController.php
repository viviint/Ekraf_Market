<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 1. Dashboard Admin: Menampilkan Semua Pesanan
    public function index()
    {
        // Ambil data order beserta usernya, urutkan dari yang terbaru
        $orders = Order::with('user')->latest()->get();
        return view('admin.dashboard', compact('orders'));
    }

    // 2. Logika Verifikasi Pembayaran
    public function verifyPayment(Order $order)
    {
        // Update status pesanan
        $order->update([
            'status' => 'Diproses', // Status maju ke tahap selanjutnya
            'paid_at' => now(),     // Catat tanggal pembayaran lunas
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi! Pesanan siap diproses.');
    }
}
