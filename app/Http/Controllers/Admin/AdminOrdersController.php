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


        // $orders = Order::with('customer')->latest()->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function generatePrices($order)
    {
        $subTotal = 0;
        $vatTotal = 0;
        $totalTotal = 0;
        $total = 0;
        $paid = 0;
        $lines = $order->lines->where('owner_id', auth()->user()->id);

        // Calculate totals from order lines
        foreach ($lines as $line) {
            if (!$line->owner_id) {
                continue;  // Skip lines without owner if needed
            }
            $lineTotal = $line->unit_price->value * $line->quantity;
            $subTotal += $lineTotal;
            
            $vatAmount = $line->tax_total->value * $line->quantity;
            $vatTotal += $vatAmount;

            $total = $line->total->value * $line->quantity;
            $totalTotal += $total;
        }
        
        // Calculate grand total
        $total = $subTotal + $vatTotal;
        
        // Return all calculated values
        return [
            'sub_total' => formatted_price($subTotal),
            'vat_total' => formatted_price($vatTotal),
            'total' => formatted_price($totalTotal),
            'paid' => formatted_price($paid),
            'balance' => formatted_price($total - $paid),
        ];
    }

    public function show($id)
    {
        $orders = Order::all();
        $order = Order::with([
            'customer',
            'transactions',
            'addresses',
            'lines' => function ($query) {
            $query->where('owner_id', auth()->user()->id);
            }
        ])->findOrFail($id);
        $prices = $this->generatePrices($order);

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