<?php

namespace App\Http\Livewire;

use App\Services\CartService;
use Livewire\Component;

class Cart extends Component
{
    public array $cart = [];
    public float $subtotal = 0;

    protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount(CartService $cartService): void
    {
        $this->refreshCart($cartService);
    }

    public function refreshCart(CartService $cartService): void
    {
        $this->cart = $cartService->get();
        $this->subtotal = $cartService->getSubtotal();
    }

    public function updateQuantity(string $key, int $quantity, CartService $cartService): void
    {
        $cartService->update($key, $quantity);
        $this->refreshCart($cartService);
        $this->dispatch('cart-count-updated', count: $cartService->count());
    }

    public function removeItem(string $key, CartService $cartService): void
    {
        $cartService->remove($key);
        $this->refreshCart($cartService);
        $this->dispatch('cart-count-updated', count: $cartService->count());
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
