<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Fungsi untuk menyimpan Review
    public function store(Request $request)
    {
        // 1. Validasi input biar aman
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        // 2. Cek apakah user SUDAH PERNAH review produk ini di order ini?
        // (Biar gak bisa spam review berkali-kali di barang yang sama)
        $sudahReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($sudahReview) {
            return back()->with('error', 'Ups! Kamu sudah mengulas produk ini sebelumnya.');
        }

        // 3. Simpan Review ke Database
        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // 4. Balikin ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Terima kasih! Ulasan berhasil dikirim.');
    }
}
