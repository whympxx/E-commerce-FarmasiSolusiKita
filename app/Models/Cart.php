<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity'
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this cart item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Pindahkan keranjang tamu (berdasarkan session) ke akun user setelah login/daftar.
     * Jumlah dibatasi stok yang tersedia.
     */
    public static function mergeGuestCart(?string $sessionId, ?int $userId): void
    {
        if (!$sessionId || !$userId) {
            return;
        }

        $guestItems = static::with('product')->where('session_id', $sessionId)->whereNull('user_id')->get();

        foreach ($guestItems as $guestItem) {
            $existing = static::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            $quantity = $guestItem->quantity + ($existing->quantity ?? 0);

            $product = $guestItem->product;
            if ($product && $product->manage_stock) {
                $quantity = min($quantity, max($product->stock_quantity, 0));
            }

            if ($existing) {
                $guestItem->delete();
                $quantity > 0 ? $existing->update(['quantity' => $quantity]) : $existing->delete();
            } elseif ($quantity > 0) {
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null,
                    'quantity' => $quantity,
                ]);
            } else {
                $guestItem->delete();
            }
        }
    }

    /**
     * Ringkasan harga keranjang. Dipakai halaman keranjang dan checkout supaya angkanya sama.
     * - subtotal  : total harga normal
     * - discount  : potongan dari harga promo (sale_price)
     * - total     : total harga barang setelah diskon
     * - shipping / tax / grand_total : ongkir dan pajak dari Admin > Settings
     */
    public static function summarize($cartItems): array
    {
        $subtotal = 0;
        $total = 0;
        $itemsCount = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
            $total += $item->product->effective_price * $item->quantity;
            $itemsCount += $item->quantity;
        }

        $shipping = $itemsCount > 0 ? (float) Setting::get('shipping_cost', 0) : 0;
        $taxRate = (float) Setting::get('tax_rate', 0);
        $tax = round($total * $taxRate / 100);

        return [
            'subtotal' => $subtotal,
            'discount' => $subtotal - $total,
            'total' => $total,
            'items_count' => $itemsCount,
            'shipping' => $shipping,
            'tax_rate' => $taxRate,
            'tax' => $tax,
            'grand_total' => $total + $shipping + $tax,
        ];
    }

    /**
     * Get the total price for this cart item
     */
    public function getTotalPriceAttribute()
    {
        return $this->product->effective_price * $this->quantity;
    }

    /**
     * Get formatted total price in Indonesian Rupiah
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }
}
