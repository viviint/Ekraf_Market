<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

        protected $fillable = [
            'user_id',
            'product_id',
            'order_id',
            'rating',
            'comment',
        ];

    // Review ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Review ini buat produk apa?
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
