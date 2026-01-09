<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Item
    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        // Ini asumsi abang punya tabel pivot 'order_items' yang nyimpen product_id
        // withPivot buat ambil data jumlah (quantity) dan harga pas beli
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
}
