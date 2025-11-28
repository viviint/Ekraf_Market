<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Foreign Key ke tabel users (user_id)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Foreign Key ke tabel products (product_id)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Jumlah barang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
