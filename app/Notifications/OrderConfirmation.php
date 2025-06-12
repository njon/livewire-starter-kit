<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Lunar\Models\Order;

class OrderConfirmation extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation #'.$this->order->reference)
            ->markdown('emails.orders.confirmation', [
                'order' => $this->order,
                'customer' => $notifiable
            ]);
    }
}