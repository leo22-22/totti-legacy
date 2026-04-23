<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'team', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'sku', 'images', 'main_image',
        'sizes', 'colors', 'composition', 'is_active', 'is_featured',
        'is_new', 'stock', 'views', 'gender',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'composition' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'TL-' . strtoupper(Str::random(8));
            }
        });

        static::updated(function ($product) {
            if ($product->isDirty('stock') && $product->stock <= 0 && $product->is_active) {
                $product->updateQuietly(['is_active' => false]);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->is_on_sale) return 0;
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/placeholder.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
