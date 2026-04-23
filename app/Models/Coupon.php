<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value', 'minimum_order',
        'maximum_discount', 'usage_limit', 'usage_limit_per_user',
        'times_used', 'is_active', 'free_shipping', 'starts_at', 'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'free_shipping' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->times_used >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->minimum_order && $subtotal < $this->minimum_order) return 0;

        $discount = $this->type === 'percentage'
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->maximum_discount) {
            $discount = min($discount, $this->maximum_discount);
        }

        return round(min($discount, $subtotal), 2);
    }
}
