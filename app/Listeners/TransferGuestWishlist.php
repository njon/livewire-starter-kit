<?php

// app/Listeners/TransferGuestWishlist.php
namespace App\Listeners;

use App\Http\Controllers\WishlistController;
use Illuminate\Auth\Events\Registered;

class TransferGuestWishlist
{
    public function handle(Registered $event)
    {
        $wishlistController = new WishlistController();
        $wishlistController->transferGuestWishlist($event->user);
    }
}