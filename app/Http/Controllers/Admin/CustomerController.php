<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount(['orders', 'carts'])
            ->withSum('orders', 'total_amount');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
                case 'active':
                    $query->whereHas('orders');
                    break;
                case 'inactive':
                    $query->whereDoesntHave('orders');
                    break;
            }
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = strtolower($request->get('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        
        $allowedSortFields = ['name', 'email', 'created_at', 'orders_count', 'orders_sum_total_amount'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $customers = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total_customers' => User::where('is_admin', false)->count(),
            'verified_customers' => User::where('is_admin', false)->whereNotNull('email_verified_at')->count(),
            'active_customers' => User::where('is_admin', false)->whereHas('orders')->count(),
            'new_customers_this_month' => User::where('is_admin', false)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'email_verified' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // email_verified_at tidak ada di $fillable, jadi harus diisi dengan forceFill
        $user->forceFill([
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ])->save();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Ensure we're viewing a customer, not an admin
        if ($customer->is_admin) {
            abort(404);
        }

        $customer->load(['orders' => function($query) {
            $query->latest()->limit(10);
        }, 'carts.product']);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->sum('total_amount'),
            'average_order_value' => $customer->orders()->avg('total_amount'),
            'last_order_date' => $customer->orders()->latest()->first()?->created_at,
            'active_cart_items' => $customer->carts()->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(User $customer)
    {
        // Ensure we're editing a customer, not an admin
        if ($customer->is_admin) {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, User $customer)
    {
        // Ensure we're updating a customer, not an admin
        if ($customer->is_admin) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'email_verified' => 'boolean',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Pertahankan tanggal verifikasi lama kalau masih dicentang
        $customer->forceFill([
            'email_verified_at' => $request->boolean('email_verified')
                ? ($customer->email_verified_at ?? now())
                : null,
        ])->save();

        if ($request->filled('password')) {
            $customer->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(User $customer)
    {
        // Ensure we're deleting a customer, not an admin
        if ($customer->is_admin) {
            abort(404);
        }

        // Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Tidak dapat menghapus pelanggan yang memiliki pesanan.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Toggle email verification status
     */
    public function toggleVerification(User $customer)
    {
        if ($customer->is_admin) {
            abort(404);
        }

        $customer->forceFill([
            'email_verified_at' => $customer->email_verified_at ? null : now(),
        ])->save();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Status verifikasi email berhasil diperbarui.');
    }

    /**
     * Export customers data
     */
    public function export(Request $request)
    {
        $customers = User::where('is_admin', false)
            ->withCount(['orders', 'carts'])
            ->withSum('orders', 'total_amount')
            ->get();

        $filename = 'customers_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Nama', 'Email', 'Status Verifikasi', 'Total Pesanan', 
                'Total Belanja', 'Tanggal Registrasi', 'Pesanan Terakhir'
            ]);

            // Add data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
                    $customer->orders_count,
                    $customer->orders_sum_total_amount ?? 0,
                    $customer->created_at->format('d/m/Y H:i'),
                    $customer->orders()->latest()->first()?->created_at?->format('d/m/Y H:i') ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 