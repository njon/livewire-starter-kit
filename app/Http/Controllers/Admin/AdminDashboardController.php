<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Lunar\Models\CartLine;
use Illuminate\Support\Facades\Auth;
use Lunar\Models\Channel;
use Lunar\Models\Language;
use Lunar\Models\OrderLine;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::ownedByUser()->count();
        $products = Product::ownedByUser()->count();
        $customers = User::ownedByUser()->count();

        $totalRevenue = Order::ownedByUser()->where('status', 'payment-received')->sum('total');
        $totalRevenue = format_price($totalRevenue)->formatted();

        $latestOrders = Order::ownedByUser()->latest()->take(5)->get();
        $pendingOrders = Order::ownedByUser()->where('status', 'pending')->count();

        // Average Order Value
        $averageOrderValue = Order::ownedByUser()->where('status', 'payment-received')->avg('total') ?? 0;
        $averageOrderValue = $orders > 0 ? format_price($averageOrderValue)->formatted() : 0;

        // Sales Overview (Yearly)
        $salesYear = date('Y');
        $salesMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $salesPerMonth = [];
        foreach (range(1, 12) as $month) {
            $salesPerMonth[] = (float) Order::whereYear('created_at', $salesYear)
                ->whereMonth('created_at', $month)
                ->sum('total');
        }


        $bestSellers = OrderLine::ownedByUser()->with(['purchasable.product.variants'])
        ->whereHas('order', function($query) {
            $query->where('created_at', '>=', now()->subMonths(12))
                ->whereNotIn('status', ['cancelled', 'failed']);
        })
        ->select('purchasable_type', 'purchasable_id')
        ->selectRaw('SUM(quantity) as total_quantity')
        ->groupBy('purchasable_type', 'purchasable_id')
        ->orderByDesc('total_quantity')
        ->limit(10)
        ->get()
        ->map(function($item) {
            if (!$item->purchasable || !$item->purchasable->product) {
                return null;
            }
            return [
                'product_link' => route('admin.products.edit', $item->purchasable->product->id),
                'product' => $item->purchasable->product,
                'variant' => $item->purchasable,
                'quantity' => $item->total_quantity,
            ];
        })
        ->filter();

        return view('admin.dashboard.index', compact(
            'orders',
            'products',
            'customers',
            'totalRevenue',
            'latestOrders',
            'pendingOrders',
            'averageOrderValue',
            'salesYear',
            'salesMonths',
            'salesPerMonth',
            'bestSellers',
        ));
    }
}
