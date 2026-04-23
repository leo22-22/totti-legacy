<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->category)->first();
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('team')) {
            $query->where('team', $request->team);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $sorted = match($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'featured'   => $query->orderByDesc('is_featured'),
            default      => $query->orderByDesc('created_at'),
        };

        // Times da categoria ativa com total de estoque
        $teams = [];
        if ($activeCategory) {
            $teams = Product::active()
                ->where('category_id', $activeCategory->id)
                ->whereNotNull('team')
                ->selectRaw('team, SUM(stock) as total_stock')
                ->groupBy('team')
                ->orderBy('team')
                ->get();
        }

        return view('shop.index', [
            'products'       => $sorted->paginate(12)->withQueryString(),
            'categories'     => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'activeCategory' => $activeCategory,
            'teams'          => $teams,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        $product->increment('views');

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)->get();

        return view('shop.show', compact('product', 'related'));
    }
}
