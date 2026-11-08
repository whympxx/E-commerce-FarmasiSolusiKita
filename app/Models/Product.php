<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'status',
        'images',
        'brand',
        'manufacturer',
        'expiry_date',
        'batch_number',
        'prescription_required',
        'dosage_instructions',
        'side_effects',
        'contraindications',
        'weight',
        'unit',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'images' => 'array',
        'prescription_required' => 'boolean',
        'is_featured' => 'boolean',
        'manage_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'expiry_date' => 'date'
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the effective price (sale price if available, otherwise regular price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Check if product is in stock
     */
    public function getInStockAttribute()
    {
        if (!$this->manage_stock) {
            return true;
        }
        
        return $this->stock_quantity > 0;
    }

    /**
     * Format price in Indonesian Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Format sale price in Indonesian Rupiah
     */
    public function getFormattedSalePriceAttribute()
    {
        if (!$this->sale_price) {
            return null;
        }
        
        return 'Rp ' . number_format($this->sale_price, 0, ',', '.');
    }

    /**
     * Format effective price in Indonesian Rupiah
     */
    public function getFormattedEffectivePriceAttribute()
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }

    /**
     * Get the first image from the images array
     */
    public function getImageAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return null;
        }
        
        return $this->images[0] ?? null;
    }

    /**
     * Get the public URL of the first image, or null when there is none
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get all images as array
     */
    public function getImagesArrayAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return $this->images;
    }
}
