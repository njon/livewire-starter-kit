<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;
use App\Models\Voucher;
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

        $orders->each(function ($order) {
            $order->vouchers = Voucher::where('order_id', $order->id)->get();
        });

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if(auth()->user()->role != 'super_admin') {
            $orderLine = $order->lines->ownedByUser()->first();
            $this->authorize('view', $orderLine);
        } 

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
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,refunded,payment-received'
        ]);

        $order = Order::findOrFail($id);
        
        // Verify user has access to this order through order lines
        $orderLine = $order->lines->ownedByUser()->first();
        $this->authorize('update', $orderLine);
        
        $order->update(['status' => $request->status]);
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Order status updated successfully'),
                'status' => $request->status
            ]);
        }
        
        return back()->with('success', __('Order status updated successfully'));
    }

    public function refund(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500'
        ]);

        $order = Order::findOrFail($id);
        
        // Verify user has access to this order
        $orderLine = $order->lines->ownedByUser()->first();
        $this->authorize('update', $orderLine);
        
        // Calculate refundable amount for this owner's items only
        $ownerTotal = $order->lines()->ownedByUser()->sum('total.value');
        $refundAmount = $request->amount * 100; // Convert to cents
        
        if ($refundAmount > $ownerTotal) {
            return back()->withErrors(['amount' => __('Refund amount cannot exceed order total for your items')]);
        }
        
        // TODO: Implement actual payment gateway refund logic here
        // For now, just update the order status
        $order->update([
            'status' => 'refunded',
            'notes' => ($order->notes ?? '') . "\nRefund: {$request->amount} - {$request->reason}"
        ]);
        
        return back()->with('success', __('Refund processed successfully'));
    }

    public function downloadPdf($id)
    {
        $order = Order::findOrFail($id);
        
        // Verify user has access to this order
        $orderLine = $order->lines->ownedByUser()->first();
        $this->authorize('view', $orderLine);
        
        $lines = $order->lines()->ownedByUser()
            ->with(['purchasable' => function($query) {
                $query->withTrashed(); 
            }])
        ->get();

        // Calculate owner-specific totals
        $order->owner_subtotal = $lines->sum('total.value') - $lines->sum('tax_total.value');
        $order->owner_total = $lines->sum('total.value');
        $order->owner_vat = $lines->sum('tax_total.value');
        $order->owner_discount = $lines->sum('discount_total.value');
        
        // TODO: Implement actual PDF generation
        // For now, return a simple response
        return response()->json([
            'message' => __('PDF generation not yet implemented'),
            'order_id' => $order->id,
            'total' => $order->owner_total
        ]);
        
        // Example implementation with a PDF library:
        // $pdf = PDF::loadView('admin.orders.pdf', compact('order', 'lines'));
        // return $pdf->download('order-'.$order->reference.'.pdf');
    }
}