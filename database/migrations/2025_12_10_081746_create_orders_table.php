<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // KOLOM BARU YANG WAJIB ADA (Biar gak error kayak tadi)
            $table->string('invoice_number')->unique();
            $table->string('status')->default('Menunggu Pembayaran');
            $table->unsignedBigInteger('total_amount');
            $table->string('payment_method');

            // Detail Pengiriman (Auto-fill dari controller)
            $table->string('shipping_name');
            $table->string('shipping_address');
            $table->string('shipping_phone');

            // Bukti Bayar
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
