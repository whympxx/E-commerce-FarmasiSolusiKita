<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Halaman utama portal pelanggan (setelah login).
 */
class CustomerDashboardController extends Controller
{
    /** Tips kesehatan; satu tips ditampilkan per hari. */
    private const HEALTH_TIPS = [
        ['icon' => 'fa-glass-water', 'title' => 'Cukupi cairan tubuh', 'text' => 'Minum 8 gelas air putih sehari membantu menjaga konsentrasi dan fungsi ginjal.'],
        ['icon' => 'fa-bed', 'title' => 'Tidur yang cukup', 'text' => 'Tidur 7–8 jam per malam membantu menjaga daya tahan tubuh.'],
        ['icon' => 'fa-person-walking', 'title' => 'Bergerak setiap hari', 'text' => 'Jalan kaki 30 menit sehari baik untuk jantung dan suasana hati.'],
        ['icon' => 'fa-capsules', 'title' => 'Habiskan antibiotik', 'text' => 'Selalu habiskan antibiotik sesuai resep dokter meskipun gejala sudah membaik.'],
        ['icon' => 'fa-temperature-half', 'title' => 'Simpan obat dengan benar', 'text' => 'Simpan obat di tempat sejuk, kering, dan terhindar dari sinar matahari langsung.'],
        ['icon' => 'fa-apple-whole', 'title' => 'Perbanyak sayur & buah', 'text' => 'Sayur dan buah berwarna-warni kaya vitamin serta serat untuk pencernaan.'],
        ['icon' => 'fa-hand-sparkles', 'title' => 'Cuci tangan', 'text' => 'Cuci tangan dengan sabun minimal 20 detik untuk mencegah penularan penyakit.'],
    ];

    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => $user->orders()->count(),
            'active' => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
            'delivered' => $user->orders()->where('status', 'delivered')->count(),
            'spent' => (float) $user->orders()->where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        // Pesanan aktif terbaru untuk ditampilkan di pelacak
        $activeOrder = $user->orders()
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->with('orderItems.product')
            ->latest()
            ->first();

        $recentOrders = $user->orders()
            ->withCount('orderItems')
            ->latest()
            ->take(5)
            ->get();

        $recommended = Product::where('status', 'active')
            ->where(fn ($q) => $q->where('manage_stock', false)->orWhere('stock_quantity', '>', 0))
            ->orderByDesc('is_featured')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $tip = self::HEALTH_TIPS[now()->dayOfYear % count(self::HEALTH_TIPS)];

        return view('customer.dashboard', compact('user', 'stats', 'activeOrder', 'recentOrders', 'recommended', 'tip'));
    }
}
