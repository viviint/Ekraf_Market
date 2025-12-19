<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Notification;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $record): void
    {
        // Check if stock was changed and is now low
        if ($record->wasChanged('stock') && $record->stock > 0 && $record->stock < 10) {
            // Check if notification already exists for this product
            $existingNotification = Notification::where('type', 'low_stock')
                ->where('data->product_id', $record->id)
                ->where('is_read', false)
                ->first();
            
            // Only create if no unread notification exists
            if (!$existingNotification) {
                Notification::create([
                    'type' => 'low_stock',
                    'title' => 'Stok Menipis',
                    'message' => "Produk {$record->name} stok tinggal {$record->stock}",
                    'data' => [
                        'product_id' => $record->id,
                        'product_name' => $record->name,
                        'current_stock' => $record->stock,
                    ],
                ]);
            }
        }
        
        // Check if stock is out
        if ($record->wasChanged('stock') && $record->stock === 0) {
            Notification::create([
                'type' => 'out_of_stock',
                'title' => 'Stok Habis',
                'message' => "Produk {$record->name} telah habis!",
                'data' => [
                    'product_id' => $record->id,
                    'product_name' => $record->name,
                ],
            ]);
        }
    }
}
