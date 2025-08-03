<?php

namespace App\Http\Controllers\Admin;

use Lunar\Models\CartLine;
use Lunar\Models\Product\Lines;
use App\Http\Controllers\Controller;
use App\Models\Order as DefaultOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $orders = DefaultOrder::where('owner_id', auth()->user()->id)->count();
        $products = Product::where('owner_id', auth()->user()->id)->count();
        $customers = User::where('owner_id', auth()->user()->id)->count();
        // dd($orders, $products, $customers);
        // $validated['owner_id'] = auth()->user()->owner_id;

        $totalRevenue = DefaultOrder::where('status', 'payment-received')->where('owner_id', auth()->user()->id)->sum('total');
        $totalRevenue = format_price($totalRevenue)->formatted();

        $latestOrders = DefaultOrder::latest()->where('owner_id', auth()->user()->id)->take(5)->get();
        $pendingOrders = DefaultOrder::where('status', 'pending')->where('owner_id', auth()->user()->id)->count();

        // Average Order Value
        // dd(DefaultOrder::where('status', 'payment-received')->avg('total'));
        $averageOrderValue = $orders > 0 ? DefaultOrder::where('status', 'payment-received')->where('owner_id', auth()->user()->id)->avg('total') : 0;

        // Sales Overview (Yearly)
        $salesYear = date('Y');
        $salesMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $salesPerMonth = [];
        foreach (range(1, 12) as $month) {
            $salesPerMonth[] = (float) DefaultOrder::whereYear('created_at', $salesYear)->where('owner_id', auth()->user()->id)
                ->whereMonth('created_at', $month)
                ->where('status', 'payment-received')
                ->sum('total');
        }


        $bestSellers = \Lunar\Models\OrderLine::with(['purchasable.product.variants'])
        ->whereHas('order', function($query) {
            $query->where('created_at', '>=', now()->subMonths(12))->where('owner_id', auth()->user()->id)
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
            'bestSellers'
        ));
    }
}
