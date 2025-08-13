<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Lunar\Models\Order;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orderIds = \Lunar\Models\OrderLine::where('owner_id', auth()->user()->id)
            ->pluck('order_id')
            ->unique();

        $orders = Order::whereIn('id', $orderIds)->orderByDesc('id')->paginate(25);
        
        foreach($orders as $order) {
            $order->price_array = generate_order_prices($order);
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $orders = Order::all();
        $order = Order::with(['customer', 'transactions', 'addresses', 'lines' => 
            function ($query) {
                $query->where('owner_id', auth()->user()->id);
            }
        ])->findOrFail($id);

        $prices = generate_order_prices($order);

        return view('admin.orders.show', compact('order', 'prices'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'Order status updated');
    }

    public function refund(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        // Implement refund logic here
        
        return back()->with('success', 'Refund processed');
    }

    public function downloadPdf($id)
    {
        $order = Order::findOrFail($id);
        // Implement PDF generation logic here
        
        // return PDF download response
    }
}