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
        // Kode untuk MENAMBAH kolom 'role'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('mahasiswa')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kode untuk MENGHAPUS kolom 'role' (jika migrasi di-rollback)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
