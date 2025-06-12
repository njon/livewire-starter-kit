<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Notifications\OrderConfirmation;

class SendOrderConfirmationEmail
{
    public function handle(OrderCompleted $event)
    {
        // Send to customer
        $event->order->customer->notify(
            new OrderConfirmation($event->order)
        );

        // Optional: Send to admin
        // User::find(1)->notify(new OrderConfirmation($event->order));
    }
}