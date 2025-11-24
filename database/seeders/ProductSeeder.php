<?php

namespace Database\Seeders;

use App\Models\Product;   // <--- INI PENTING (Baris yang kurang tadi)
use App\Models\Category;  // <--- INI PENTING (Baris yang bikin error)
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Dulu
        // Kita pakai firstOrCreate biar aman kalau dijalankan ulang
        $catMerch = Category::firstOrCreate(
            ['slug' => 'merchandise'],
            ['name' => 'Merchandise']
        );

        $catFashion = Category::firstOrCreate(
            ['slug' => 'fashion'],
            ['name' => 'Fashion']
        );

        // 2. Masukkan Produk (Keychain, Sticker, TUS Shirt)

        // Produk 1: TUS Shirt
        Product::create([
            'category_id' => $catFashion->id,
            'name' => 'TUS Shirt Limited Edition',
            'slug' => 'tus-shirt-limited',
            'description' => 'Kaos eksklusif Telkom University Surabaya. Bahan Cotton Combed 30s.',
            'price' => 85000,
            'stock' => 50,
            'image' => 'products/shirt.jpg',
            'is_active' => true,
        ]);

        // Produk 2: Keychain
        Product::create([
            'category_id' => $catMerch->id,
            'name' => 'Keychain Akrilik Ekraf',
            'slug' => 'keychain-akrilik-ekraf',
            'description' => 'Gantungan kunci akrilik desain lucu maskot Ekraf.',
            'price' => 15000,
            'stock' => 100,
            'image' => 'products/keychain.jpg',
            'is_active' => true,
        ]);

        // Produk 3: Sticker Pack
        Product::create([
            'category_id' => $catMerch->id,
            'name' => 'Sticker Pack Edisi Kampus',
            'slug' => 'sticker-pack-kampus',
            'description' => 'Satu pack isi 5 stiker vinyl anti air.',
            'price' => 12000,
            'stock' => 75,
            'image' => 'products/sticker.jpg',
            'is_active' => true,
        ]);
    }
}
