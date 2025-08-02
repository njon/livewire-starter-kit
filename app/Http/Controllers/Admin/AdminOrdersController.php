<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \Lunar\Models\Order;
use \Lunar\Models\CartLine;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'transactions', 'addresses'])->findOrFail($id);
                // $order = Order::with(['customer', 'transactions', 'addresses'])->get();
        // dd($order);
        
        return view('admin.orders.show', compact('order'));
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