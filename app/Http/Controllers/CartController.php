<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $cartTotal = $this->calculateCartTotal($cartItems);
        
        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:999'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia.'
            ]);
        }

        if (!$product->in_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sedang tidak tersedia.'
            ]);
        }

        // Check stock quantity
        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi. Tersisa {$product->stock_quantity} {$product->unit}."
            ]);
        }

        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        // Check if item already exists in cart
        $existingCartItem = Cart::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            
            // Check stock for new quantity
            if ($product->manage_stock && $newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menambah lebih banyak. Maksimal {$product->stock_quantity} {$product->unit}."
                ]);
            }

            $existingCartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:999'
        ]);

        $cartItem = $this->findCartItem($cartId);
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.'
            ]);
        }

        $product = $cartItem->product;
        
        // Check stock
        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi. Tersisa {$product->stock_quantity} {$product->unit}."
            ]);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        
        $cartItems = $this->getCartItems();
        $cartTotal = $this->calculateCartTotal($cartItems);
        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui.',
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
            'item_total' => $cartItem->quantity * $product->effective_price
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy($cartId)
    {
        $cartItem = $this->findCartItem($cartId);
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.'
            ]);
        }

        $cartItem->delete();
        
        $cartItems = $this->getCartItems();
        $cartTotal = $this->calculateCartTotal($cartItems);
        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang.',
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        Cart::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan.',
            'cart_count' => 0
        ]);
    }

    /**
     * Get cart count for AJAX
     */
    public function count()
    {
        $cartCount = $this->getCartCount();
        
        return response()->json([
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Get cart items for current user/session
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        return Cart::with('product.category')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * Find cart item by ID for current user/session
     */
    private function findCartItem($cartId)
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        return Cart::where('id', $cartId)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();
    }

    /**
     * Get cart count for current user/session
     */
    private function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : session()->getId();

        return Cart::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->sum('quantity');
    }

    /**
     * Calculate cart total
     */
    private function calculateCartTotal($cartItems)
    {
        return Cart::summarize($cartItems);
    }
}
