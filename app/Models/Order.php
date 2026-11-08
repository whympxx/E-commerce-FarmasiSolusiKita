<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    public const PAYMENT_STATUSES = ['pending', 'paid', 'failed', 'refunded'];
    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Transfer Bank',
        'ewallet' => 'E-Wallet',
        'cod' => 'Bayar di Tempat (COD)',
    ];

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'payment_status',
        'payment_method',
        'shipping_method',
        'tracking_number',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'shipping_address',
        'billing_address',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias yang dipakai banyak view.
     */
    public function items(): HasMany
    {
        return $this->orderItems();
    }

    /*
     * Data pelanggan: pakai yang tersimpan di pesanan, kalau kosong ambil dari akun.
     */
    public function getCustomerNameAttribute($value)
    {
        return $value ?: $this->user?->name;
    }

    public function getCustomerEmailAttribute($value)
    {
        return $value ?: $this->user?->email;
    }

    /*
     * Alamat disimpan sebagai JSON: address, city, state, postal_code, country.
     */
    public function getShippingStreetAttribute()
    {
        return $this->addressPart('shipping_address', 'address');
    }

    public function getShippingCityAttribute()
    {
        return $this->addressPart('shipping_address', 'city');
    }

    public function getShippingStateAttribute()
    {
        return $this->addressPart('shipping_address', 'state');
    }

    public function getShippingPostalCodeAttribute()
    {
        return $this->addressPart('shipping_address', 'postal_code');
    }

    public function getShippingCountryAttribute()
    {
        return $this->addressPart('shipping_address', 'country');
    }

    public function getBillingStreetAttribute()
    {
        return $this->addressPart('billing_address', 'address');
    }

    public function getBillingCityAttribute()
    {
        return $this->addressPart('billing_address', 'city');
    }

    public function getBillingStateAttribute()
    {
        return $this->addressPart('billing_address', 'state');
    }

    public function getBillingPostalCodeAttribute()
    {
        return $this->addressPart('billing_address', 'postal_code');
    }

    public function getBillingCountryAttribute()
    {
        return $this->addressPart('billing_address', 'country');
    }

    /**
     * Alamat pengiriman lengkap dalam satu baris.
     */
    public function getFullShippingAddressAttribute()
    {
        return collect([
            $this->shipping_street,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
        ])->filter()->implode(', ');
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'secondary',
        ][$this->status] ?? 'secondary';
    }

    public function getPaymentStatusLabelAttribute()
    {
        return [
            'pending' => 'Belum Dibayar',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ][$this->payment_status] ?? ucfirst((string) $this->payment_status);
    }

    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst(str_replace('_', ' ', (string) $this->payment_method));
    }

    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    private function addressPart(string $column, string $key)
    {
        $address = $this->{$column};

        if (is_array($address)) {
            return $address[$key] ?? null;
        }

        // Data lama yang tersimpan sebagai teks biasa
        return $key === 'address' ? $address : null;
    }
}
