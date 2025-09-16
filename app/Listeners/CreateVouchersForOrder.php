<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Models\Voucher;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateVouchersForOrder implements ShouldQueue
{
    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;
        
        $order->lines()->each(function($line) use ($order) {
            for ($i = 0; $i < $line->quantity; $i++) {
                Voucher::create([
                    'code' => uniqid('VOUCHER-'),
                    'order_id' => $order->id,
                    'order_line_id' => $line->id,
                    'user_id' => $order->user_id,
                    'expires_at' => now()->addYear(),
                    'status' => Voucher::STATUS_ACTIVE,
                ]);
            }
        });
    }
}