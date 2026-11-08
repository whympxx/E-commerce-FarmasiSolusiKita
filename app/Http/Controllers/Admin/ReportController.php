<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Catatan: dulu ada "kunci" reports_processing di session. Kalau request terputus,
        // kunci itu tidak pernah dilepas dan halaman laporan terkunci selamanya.
        // Pembatasan beban sudah ditangani middleware throttle di route.
        try {
            // Get filter parameters
            $reportType = $request->get('report_type', 'sales');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Build date range
            $dateRange = $this->buildDateRange($dateFrom, $dateTo);

            // Get summary statistics with error handling
            try {
                $summary = $this->getSummaryStatistics($dateRange);
            } catch (\Exception $e) {
                Log::error('Error getting summary statistics: ' . $e->getMessage());
                $summary = [
                    'total_revenue' => 0,
                    'revenue_growth' => 0,
                    'total_orders' => 0,
                    'completed_orders' => 0,
                    'total_products_sold' => 0,
                    'unique_products' => 0,
                    'new_customers' => 0,
                    'total_customers' => 0,
                ];
            }

            // Get data based on report type with error handling
            try {
                $data = $this->getReportData($reportType, $dateRange);
            } catch (\Exception $e) {
                Log::error('Error getting report data: ' . $e->getMessage());
                $data = [
                    'topProducts' => collect(),
                    'recentOrders' => collect(),
                ];
            }

            session()->forget('reports_processing');
            
            return view('admin.reports.index', compact('summary', 'data', 'reportType', 'dateFrom', 'dateTo'));
        } catch (\Exception $e) {
            session()->forget('reports_processing');
            Log::error('Error in reports index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat laporan: ' . $e->getMessage());
        }
    }

    private function buildDateRange($dateFrom, $dateTo)
    {
        if ($dateFrom && $dateTo) {
            return [
                'from' => Carbon::parse($dateFrom)->startOfDay(),
                'to' => Carbon::parse($dateTo)->endOfDay()
            ];
        }

        // Default to last 30 days if no dates provided
        return [
            'from' => Carbon::now()->subDays(30)->startOfDay(),
            'to' => Carbon::now()->endOfDay()
        ];
    }

    private function getSummaryStatistics($dateRange)
    {
        // Total Revenue
        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->sum('total_amount');

        // Previous period revenue for growth calculation
        $previousPeriodFrom = $dateRange['from']->copy()->subDays($dateRange['from']->diffInDays($dateRange['to']));
        $previousPeriodTo = $dateRange['from']->copy()->subDay();
        
        $previousRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$previousPeriodFrom, $previousPeriodTo])
            ->sum('total_amount');

        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        // Total Orders
        $totalOrders = Order::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])->count();
        $completedOrders = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->count();

        // Products Sold - Fixed field names to match database schema
        $totalProductsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']])
            ->sum('order_items.quantity');

        $uniqueProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']])
            ->distinct('order_items.product_id')
            ->count('order_items.product_id');

        // New Customers
        $newCustomers = User::where('is_admin', false)
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->count();

        $totalCustomers = User::where('is_admin', false)->count();

        return [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => round($revenueGrowth, 1),
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'total_products_sold' => $totalProductsSold,
            'unique_products' => $uniqueProducts,
            'new_customers' => $newCustomers,
            'total_customers' => $totalCustomers,
        ];
    }

    private function getReportData($reportType, $dateRange)
    {
        switch ($reportType) {
            case 'sales':
                return $this->getSalesData($dateRange);
            case 'products':
                return $this->getProductsData($dateRange);
            case 'customers':
                return $this->getCustomersData($dateRange);
            case 'inventory':
                return $this->getInventoryData($dateRange);
            default:
                return $this->getSalesData($dateRange);
        }
    }

    private function getSalesData($dateRange)
    {
        // Top Products - Fixed field names to match database schema
        $topProducts = Product::select(
                'products.id',
                'products.name',
                'products.slug',
                'products.sku',
                'products.price',
                'products.sale_price',
                'products.stock_quantity',
                'products.status',
                'products.category_id',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            )
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function($query) use ($dateRange) {
                $query->whereNull('orders.created_at')
                    ->orWhere(function($q) use ($dateRange) {
                        $q->where('orders.status', '!=', 'cancelled')
                          ->whereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']]);
                    });
            })
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ];
    }

    private function getProductsData($dateRange)
    {
        // Products by category
        $productsByCategory = Category::select(
                'categories.id',
                'categories.name',
                'categories.slug',
                'categories.description',
                'categories.is_active',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(CASE WHEN products.stock_quantity <= 10 THEN 1 ELSE 0 END) as low_stock_count')
            )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.id')
            ->orderBy('product_count', 'desc')
            ->get();

        // Low stock products
        $lowStockProducts = Product::with('category')
            ->where('manage_stock', true)
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        return [
            'productsByCategory' => $productsByCategory,
            'lowStockProducts' => $lowStockProducts,
        ];
    }

    private function getCustomersData($dateRange)
    {
        // Top customers by order value
        $topCustomers = User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.is_admin',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('AVG(orders.total_amount) as avg_order_value')
            )
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('users.is_admin', false)
            ->where(function($query) use ($dateRange) {
                $query->whereNull('orders.created_at')
                    ->orWhereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']]);
            })
            ->groupBy('users.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // New customers
        $newCustomers = User::where('is_admin', false)
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'topCustomers' => $topCustomers,
            'newCustomers' => $newCustomers,
        ];
    }

    private function getInventoryData($dateRange)
    {
        // Inventory summary
        $inventorySummary = Product::select(
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(CASE WHEN stock_quantity = 0 THEN 1 ELSE 0 END) as out_of_stock'),
                DB::raw('SUM(CASE WHEN stock_quantity <= 10 AND stock_quantity > 0 THEN 1 ELSE 0 END) as low_stock'),
                DB::raw('SUM(CASE WHEN stock_quantity > 10 THEN 1 ELSE 0 END) as in_stock'),
                DB::raw('SUM(stock_quantity) as total_stock')
            )
            ->first();

        // Products by stock level
        $productsByStock = Product::with('category')
            ->select('*')
            ->orderBy('stock_quantity', 'asc')
            ->limit(20)
            ->get();

        return [
            'inventorySummary' => $inventorySummary,
            'productsByStock' => $productsByStock,
        ];
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');
        $format = $request->get('format', 'pdf');

        // Get data for export
        $dateRange = $this->buildDateRange(
            $request->get('date_from'),
            $request->get('date_to')
        );

        $data = $this->getReportData($type, $dateRange);
        $summary = $this->getSummaryStatistics($dateRange);
        $table = $this->buildExportTable($type, $data);

        // PDF: halaman siap cetak (gunakan "Save as PDF" di dialog cetak browser)
        if ($format === 'pdf') {
            return view('admin.reports.print', compact('table', 'summary', 'dateRange', 'type'));
        }

        // Excel/CSV: file CSV yang bisa dibuka di Excel
        $filename = "laporan_{$type}_" . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($table) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8 dengan benar
            fputcsv($file, $table['headers']);
            foreach ($table['rows'] as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Ubah data laporan menjadi tabel (judul, header, baris) untuk export.
     */
    private function buildExportTable(string $type, array $data): array
    {
        switch ($type) {
            case 'products':
                return [
                    'title' => 'Laporan Produk per Kategori',
                    'headers' => ['Kategori', 'Jumlah Produk', 'Stok Menipis (<= 10)', 'Status'],
                    'rows' => collect($data['productsByCategory'] ?? [])->map(fn ($c) => [
                        $c->name, (int) $c->product_count, (int) $c->low_stock_count, $c->is_active ? 'Aktif' : 'Nonaktif',
                    ])->all(),
                ];

            case 'customers':
                return [
                    'title' => 'Laporan Pelanggan Teratas',
                    'headers' => ['Nama', 'Email', 'Jumlah Pesanan', 'Total Belanja', 'Rata-rata per Pesanan'],
                    'rows' => collect($data['topCustomers'] ?? [])->map(fn ($c) => [
                        $c->name, $c->email, (int) $c->total_orders, (float) $c->total_spent, round((float) $c->avg_order_value),
                    ])->all(),
                ];

            case 'inventory':
                return [
                    'title' => 'Laporan Inventaris',
                    'headers' => ['Produk', 'SKU', 'Kategori', 'Stok', 'Status'],
                    'rows' => collect($data['productsByStock'] ?? [])->map(fn ($p) => [
                        $p->name, $p->sku, $p->category->name ?? '-', $p->stock_quantity, $p->status,
                    ])->all(),
                ];

            default:
                return [
                    'title' => 'Laporan Penjualan - Produk Terlaris',
                    'headers' => ['Produk', 'SKU', 'Terjual', 'Pendapatan'],
                    'rows' => collect($data['topProducts'] ?? [])->map(fn ($p) => [
                        $p->name, $p->sku, (int) $p->total_sold, (float) $p->total_revenue,
                    ])->all(),
                ];
        }
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', 7);
        $dateRange = $this->buildDateRange(
            $request->get('date_from'),
            $request->get('date_to')
        );

        // Generate sales data for chart
        $salesData = $this->generateSalesChartData($period, $dateRange);
        $categoryData = $this->generateCategoryChartData($dateRange);

        return response()->json([
            'sales' => $salesData,
            'category' => $categoryData
        ]);
    }

    private function generateSalesChartData($period, $dateRange)
    {
        $data = [];
        $labels = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $dailyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            
            $data[] = $dailyRevenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function generateCategoryChartData($dateRange)
    {
        $categoryData = Category::select(
                'categories.name',
                DB::raw('COUNT(order_items.id) as total_sold')
            )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function($query) use ($dateRange) {
                $query->whereNull('orders.created_at')
                    ->orWhere(function($q) use ($dateRange) {
                        $q->where('orders.status', '!=', 'cancelled')
                          ->whereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']]);
                    });
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return [
            'labels' => $categoryData->pluck('name')->toArray(),
            'data' => $categoryData->pluck('total_sold')->toArray()
        ];
    }
} 