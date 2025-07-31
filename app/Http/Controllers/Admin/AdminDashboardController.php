<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \Lunar\Models\Order;
use \Lunar\Models\Product;
use \Lunar\Models\Product\Lines;
use App\Models\User;
use Lunar\Models\CartLine;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::count();
        $products = Product::count();
        $customers = User::count();

        // $order = Order::with(['lines', 'customer', 'shippingAddress', 'billingAddress', 'transactions'])->first();

        // $cartLine = CartLine::where('purchasable_id', $order->lines->first()->purchasable_id)->get();
        // dd($cartLine);

        $totalRevenue = Order::where('status', 'payment-received')->sum('total');
        $totalRevenue = format_price($totalRevenue)->formatted(); // Assuming format_price returns a Price object with a formatted method
        $latestOrders = Order::latest()->take(5)->get();

        // Example stats, adjust as needed
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard.index', compact(
            'orders',
            'products',
            'customers',
            'totalRevenue',
            'latestOrders',
            'pendingOrders'
        ));
    }
}
