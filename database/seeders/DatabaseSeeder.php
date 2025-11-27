<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat User Admin/Test (Biar nanti bisa login)
        // Kita pakai 'firstOrCreate' biar tidak error kalau dijalankan berulang
        User::factory()->create([
            'name' => 'Admin Ekraf',
            'email' => 'admin@ekraf.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Memanggil Seeder Produk (Keychain, Stiker, Kaos)
        // Ini bagian penting yang kita tambahkan:
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
