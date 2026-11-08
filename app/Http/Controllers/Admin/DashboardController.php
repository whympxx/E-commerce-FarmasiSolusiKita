<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'out_of_stock' => Product::where('status', 'active')
                ->where('manage_stock', true)
                ->where('stock_quantity', 0)
                ->count(),
            'low_stock' => Product::where('status', 'active')
                ->where('manage_stock', true)
                ->where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 10)
                ->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_users' => User::count(),
            // Jumlah keranjang (per user / per tamu), bukan jumlah baris item
            'active_carts' => Cart::whereNotNull('user_id')->distinct()->count('user_id')
                + Cart::whereNull('user_id')->whereNotNull('session_id')->distinct()->count('session_id'),
        ];

        // Recent products
        $recentProducts = Product::with('category')
            ->latest()
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('status', 'active')
            ->where('manage_stock', true)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->with('category')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();

        // Pendapatan 6 bulan terakhir (pesanan yang tidak dibatalkan)
        $salesData = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $salesData['labels'][] = $month->translatedFormat('M Y');
            $salesData['data'][] = (float) Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->sum('total_amount');
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recentProducts', 
            'recentOrders', 
            'lowStockProducts',
            'salesData'
        ));
    }
}
