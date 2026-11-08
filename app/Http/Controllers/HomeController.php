<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Ambil kategori utama (aktif dan terbatas)
            $categories = Category::where('is_active', true)
                ->orderBy('name')
                ->take(8)
                ->get();

            // Hanya produk aktif yang tersedia (stok > 0, atau stok tidak dikelola)
            $available = fn () => Product::where('status', 'active')
                ->where(function ($q) {
                    $q->where('manage_stock', false)->orWhere('stock_quantity', '>', 0);
                });

            // Ambil produk featured
            $featuredProducts = $available()
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Ambil produk terbaru
            $latestProducts = $available()
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Jika tidak ada produk featured, gunakan produk terbaru
            if ($featuredProducts->isEmpty()) {
                $featuredProducts = $latestProducts;
            }

            return view('home', compact('categories', 'featuredProducts', 'latestProducts'));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in HomeController@index: ' . $e->getMessage());
            
            // Return view dengan data kosong jika terjadi error
            return view('home', [
                'categories' => collect(),
                'featuredProducts' => collect(),
                'latestProducts' => collect()
            ]);
        }
    }
}
