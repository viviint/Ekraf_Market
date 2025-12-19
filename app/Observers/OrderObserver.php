<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Create notification for new order
        Notification::create([
            'type' => 'new_order',
            'title' => 'Pesanan Baru',
            'message' => "Pesanan baru #{$order->invoice_number} dari {$order->user->name}",
            'data' => [
                'order_id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'total_amount' => $order->total_amount,
            ],
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // If payment proof is uploaded
        if ($order->wasChanged('payment_proof') && $order->payment_proof) {
            Notification::create([
                'type' => 'payment_uploaded',
                'title' => 'Bukti Pembayaran Di-upload',
                'message' => "Pesanan #{$order->invoice_number} telah mengupload bukti pembayaran",
                'data' => [
                    'order_id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                ],
            ]);
        }
    }
}
