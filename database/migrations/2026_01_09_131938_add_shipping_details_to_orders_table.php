<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kita tambah 3 kolom penting ini
            $table->string('recipient_name')->after('user_id')->nullable(); // Nama Penerima
            $table->string('phone_number')->after('recipient_name')->nullable(); // No WA
            $table->text('address')->after('phone_number')->nullable(); // Alamat Lengkap
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kalau di-rollback, kolomnya dihapus
            $table->dropColumn(['recipient_name', 'phone_number', 'address']);
        });
    }
};
