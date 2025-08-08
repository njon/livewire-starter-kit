<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use \Lunar\Models\Product\Lines;
use App\Models\User;
use Lunar\Models\CartLine;
use Illuminate\Support\Facades\Auth;
use Lunar\Models\Channel;
use Lunar\Models\Language;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::count();
        $products = Product::count();
        $customers = User::count();

        $totalRevenue = Order::where('status', 'payment-received')->sum('total');
        $totalRevenue = format_price($totalRevenue)->formatted();

        $latestOrders = Order::latest()->take(5)->get();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Average Order Value
        $averageOrderValue = $orders > 0 ? format_price(Order::where('status', 'payment-received')->avg('total'))->formatted() : 0;

        // Sales Overview (Yearly)
        $salesYear = date('Y');
        $salesMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $salesPerMonth = [];
        foreach (range(1, 12) as $month) {
            $salesPerMonth[] = (float) Order::whereYear('created_at', $salesYear)
                ->whereMonth('created_at', $month)
                ->where('status', 'payment-received')
                ->sum('total');
        }


        $bestSellers = \Lunar\Models\OrderLine::with(['purchasable.product.variants'])
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
