<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orderIds = OrderLine::ownedByUser()
            ->pluck('order_id')
            ->unique();

        $orders = Order::whereIn('id', $orderIds)
            ->with(['lines' => function ($query) {
                $query->ownedByUser()
                      ->orderByDesc('id');
            }])
            ->orderByDesc('id')
            ->paginate(25);
        
        $orders = generate_order_prices($orders);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $orderLine = $order->lines->ownedByUser()->first();

        $this->authorize('view', $orderLine);

        $lines = $order->lines()->ownedByUser()
            ->with(['purchasable' => function($query) {
                $query->withTrashed(); 
            }])
        ->get();

        $order->owner_subtotal = $lines->sum('total.value') - $lines->sum('tax_total.value');
        $order->owner_total = $lines->sum('total.value');
        $order->owner_vat = $lines->sum('tax_total.value');
        $order->owner_discount = $lines->sum('discount_total.value');
  
        return view('admin.orders.show', compact('order', 'lines'));
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