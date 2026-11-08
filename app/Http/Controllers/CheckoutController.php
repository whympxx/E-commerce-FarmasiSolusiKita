<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Exceptions\CheckoutException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $cartTotal = Cart::summarize($cartItems);
        $user = Auth::user();
        $lastOrder = $user->orders()->latest()->first();
        $paymentMethods = Order::PAYMENT_METHODS;

        return view('checkout.index', compact('cartItems', 'cartTotal', 'user', 'lastOrder', 'paymentMethods'));
    }

    /**
     * Process the checkout
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        try {
            $order = DB::transaction(function () use ($cartItems, $validated) {
                // Cek ulang ketersediaan & stok dengan data terbaru
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product || $product->status !== 'active') {
                        throw new CheckoutException("Produk {$item->product->name} sudah tidak tersedia. Silakan hapus dari keranjang.");
                    }

                    if ($product->manage_stock && $item->quantity > $product->stock_quantity) {
                        throw new CheckoutException("Stok tidak mencukupi untuk produk {$product->name}. Tersisa {$product->stock_quantity} {$product->unit}.");
                    }

                    $item->setRelation('product', $product);
                }

                $totals = Cart::summarize($cartItems);

                $address = [
                    'address' => $validated['shipping_address'],
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'] ?? null,
                    'postal_code' => $validated['shipping_postal_code'],
                    'country' => 'Indonesia',
                ];

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $address,
                    'billing_address' => $address,
                    'payment_method' => $validated['payment_method'],
                    'notes' => $validated['notes'] ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'subtotal' => $totals['subtotal'],
                    'discount_amount' => $totals['discount'],
                    'shipping_amount' => $totals['shipping'],
                    'tax_amount' => $totals['tax'],
                    'total_amount' => $totals['grand_total'],
                ]);

                foreach ($cartItems as $item) {
                    $product = $item->product;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item->quantity,
                        'unit_price' => $product->effective_price,
                        'total_price' => $product->effective_price * $item->quantity,
                    ]);

                    if ($product->manage_stock) {
                        $product->decrement('stock_quantity', $item->quantity);
                    }
                }

                $this->clearCart();

                return $order;
            });

            return redirect()->route('checkout.success', $order);

        } catch (CheckoutException $e) {
            return back()->withInput()->withErrors(['stock' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Checkout gagal: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.']);
        }
    }

    /**
     * Display checkout success page
     */
    public function success(Order $order)
    {
        // Ensure user can only see their own orders
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('checkout.success', compact('order'));
    }

    /**
     * Get cart items for the logged-in user
     */
    private function getCartItems()
    {
        return Cart::with('product.category')
            ->where('user_id', Auth::id())
            ->get()
            ->filter(fn ($item) => $item->product !== null)
            ->values();
    }

    /**
     * Clear cart for the logged-in user
     */
    private function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
    }
}

