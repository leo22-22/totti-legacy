<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'totti_cart';

    public function get(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function add(int $productId, string $size, string $color, int $quantity = 1): void
    {
        $product = Product::findOrFail($productId);
        $cart = $this->get();
        $key = "{$productId}_{$size}_{$color}";

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $productId,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->current_price,
                'original_price' => (float) $product->price,
                'size' => $size,
                'color' => $color,
                'image' => $product->main_image,
                'quantity' => $quantity,
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->get();
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
            }
            session([self::SESSION_KEY => $cart]);
        }
    }

    public function remove(string $key): void
    {
        $cart = $this->get();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return empty($this->get());
    }

    public function count(): int
    {
        return array_sum(array_column($this->get(), 'quantity'));
    }

    public function getSubtotal(): float
    {
        return array_sum(array_map(
            fn ($item) => $item['price'] * $item['quantity'],
            $this->get()
        ));
    }
}
