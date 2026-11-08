<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Riwayat pesanan milik pelanggan yang sedang login.
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders()
            ->withCount('orderItems')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => $user->orders()->count(),
            'active' => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
            'delivered' => $user->orders()->where('status', 'delivered')->count(),
        ];

        return view('orders.index', compact('orders', 'stats', 'user'));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeOwner($request, $order);

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Pelanggan boleh membatalkan pesanan yang masih pending dan belum dibayar.
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorizeOwner($request, $order);

        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak dapat dibatalkan. Silakan hubungi admin.');
        }

        DB::transaction(function () use ($order) {
            $order->load('orderItems.product');

            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->manage_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function authorizeOwner(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
