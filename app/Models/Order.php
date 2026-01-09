<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // GANTI $fillable JADI INI:
    // $guarded = [] artinya "Semua kolom boleh diisi, tidak ada yang dijaga".
    // Ini lebih praktis buat development.
    protected $guarded = [];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Produk (Lewat tabel pivot order_items)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
}
