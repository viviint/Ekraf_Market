<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        // Relasi ke User (Siapa yang review)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        // Relasi ke Produk (Apa yang direview)
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        // Relasi ke Order (Berdasarkan pesanan mana)
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();

        $table->integer('rating'); // Bintang 1-5
        $table->text('comment')->nullable(); // Komentarnya
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
