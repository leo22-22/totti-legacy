<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        return view('cart.index', ['cart' => $this->cart->get(), 'subtotal' => $this->cart->getSubtotal()]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size' => 'required|string',
            'color' => 'nullable|string',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $this->cart->add(
            $request->product_id,
            $request->size,
            $request->color ?? 'Único',
            $request->quantity
        );

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function update(Request $request)
    {
        $this->cart->update($request->key, $request->quantity);
        return back();
    }

    public function remove(Request $request)
    {
        $this->cart->remove($request->key);
        return back()->with('success', 'Produto removido do carrinho.');
    }
}
