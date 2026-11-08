<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request)->with(['user', 'orderItems.product']);

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For manual order creation (if needed)
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('admin.orders.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Implementation for manual order creation would go here
        return redirect()->route('admin.orders.index')
            ->with('info', 'Fitur pembuatan pesanan manual akan segera tersedia.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product.category']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
            'payment_status' => ['required', Rule::in(Order::PAYMENT_STATUSES)],
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'shipping_method' => 'nullable|in:standard,express,overnight,pickup',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_country' => 'nullable|string|max:100',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_postal_code' => 'required|string|max:10',
            'billing_country' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $this->applyStatusChange($order, $validated['status']);

            $order->update([
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'shipping_method' => $validated['shipping_method'] ?? null,
                'tracking_number' => $validated['tracking_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'shipping_address' => $this->addressFrom($validated, 'shipping'),
                'billing_address' => $this->addressFrom($validated, 'billing'),
            ]);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Hanya pesanan yang dibatalkan yang dapat dihapus.');
        }

        try {
            $order->delete();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Pesanan tidak dapat dihapus.');
        }
    }

    /**
     * Update order status (AJAX dari daftar pesanan, atau form biasa dari halaman detail)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $this->applyStatusChange($order, $validated['status']);
        });

        $message = 'Status pesanan berhasil diperbarui.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $order->status,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', Rule::in(Order::PAYMENT_STATUSES)],
        ]);

        $order->update($validated);

        $message = 'Status pembayaran berhasil diperbarui.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'payment_status' => $validated['payment_status'],
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Generate invoice
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Print shipping label
     */
    public function shippingLabel(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.shipping-label', compact('order'));
    }

    /**
     * Export orders (CSV, mengikuti filter yang sedang aktif)
     */
    public function export(Request $request)
    {
        $orders = $this->filteredQuery($request)
            ->with(['user', 'orderItems'])
            ->latest()
            ->get();

        $filename = 'orders_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No. Pesanan', 'Tanggal', 'Pelanggan', 'Email', 'Telepon', 'Jumlah Item',
                'Subtotal', 'Ongkir', 'Pajak', 'Total', 'Status', 'Status Pembayaran',
                'Metode Pembayaran', 'Alamat Pengiriman', 'No. Resi',
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->orderItems->sum('quantity'),
                    $order->subtotal,
                    $order->shipping_amount,
                    $order->tax_amount,
                    $order->total_amount,
                    $order->status,
                    $order->payment_status,
                    $order->payment_method_label,
                    $order->full_shipping_address,
                    $order->tracking_number,
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Query pesanan dengan filter dari halaman daftar
     */
    private function filteredQuery(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_email', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%")
                               ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * Ganti status pesanan: isi tanggal kirim/sampai, dan kembalikan/potong stok
     * saat pesanan dibatalkan atau batal-dibatalkan.
     */
    private function applyStatusChange(Order $order, string $newStatus): void
    {
        $oldStatus = $order->status;

        if ($oldStatus === $newStatus) {
            return;
        }

        $updates = ['status' => $newStatus];

        if ($newStatus === 'shipped' && !$order->shipped_at) {
            $updates['shipped_at'] = now();
        }

        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        $order->loadMissing('orderItems.product');

        if ($newStatus === 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->manage_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }
        } elseif ($oldStatus === 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->manage_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }
        }

        $order->update($updates);
    }

    private function addressFrom(array $data, string $prefix): array
    {
        return [
            'address' => $data["{$prefix}_address"],
            'city' => $data["{$prefix}_city"],
            'state' => $data["{$prefix}_state"] ?? null,
            'postal_code' => $data["{$prefix}_postal_code"],
            'country' => $data["{$prefix}_country"] ?? 'Indonesia',
        ];
    }
}
