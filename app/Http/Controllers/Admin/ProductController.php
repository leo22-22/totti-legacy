<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($sq) => $sq
                ->where('name', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%")
                ->orWhere('team', 'like', "%{$q}%")
            );
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        match ($request->status) {
            'active'      => $query->where('is_active', true),
            'inactive'    => $query->where('is_active', false),
            'low_stock'   => $query->where('stock', '<=', 5)->where('stock', '>', 0),
            'out_of_stock'=> $query->where('stock', 0),
            'featured'    => $query->where('is_featured', true),
            default       => null,
        };

        $products   = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:0',
            'main_image'  => 'nullable|image|max:10240',
            'images.*'    => 'nullable|image|max:10240',
        ]);

        $data = $request->only([
            'name', 'team', 'description', 'short_description',
            'price', 'sale_price', 'sale_ends_at', 'sku', 'stock',
            'category_id', 'subcategory_id', 'gender',
        ]);

        $data['slug']        = Str::slug($request->name);
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new']      = $request->boolean('is_new');
        $data['sizes']       = $request->input('sizes', []);

        $data['colors'] = collect($request->input('colors', []))
            ->filter()
            ->map(fn($c) => ['name' => $c])
            ->values()
            ->toArray();

        $composition = [];
        foreach ($request->input('composition_key', []) as $i => $key) {
            if ($key !== null && $key !== '') {
                $composition[$key] = $request->input('composition_value', [])[$i] ?? '';
            }
        }
        $data['composition'] = $composition ?: null;

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $gallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
        }
        $data['images'] = $gallery ?: null;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Product $product)
    {
        $categories    = Category::whereNull('parent_id')->orderBy('name')->get();
        $subcategories = $product->category_id
            ? Category::where('parent_id', $product->category_id)->get()
            : collect();

        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:0',
            'main_image'  => 'nullable|image|max:10240',
            'images.*'    => 'nullable|image|max:10240',
        ]);

        $data = $request->only([
            'name', 'team', 'description', 'short_description',
            'price', 'sale_price', 'sale_ends_at', 'sku', 'stock',
            'category_id', 'subcategory_id', 'gender',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new']      = $request->boolean('is_new');
        $data['sizes']       = $request->input('sizes', []);

        $data['colors'] = collect($request->input('colors', []))
            ->filter()
            ->map(fn($c) => ['name' => $c])
            ->values()
            ->toArray();

        $composition = [];
        foreach ($request->input('composition_key', []) as $i => $key) {
            if ($key !== null && $key !== '') {
                $composition[$key] = $request->input('composition_value', [])[$i] ?? '';
            }
        }
        $data['composition'] = $composition ?: null;

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach ($product->images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
            $gallery = [];
            foreach ($request->file('images') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $data['images'] = $gallery;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $product->delete();

        return back()->with('success', 'Produto excluído.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $msg = $product->is_active ? 'Produto ativado.' : 'Produto desativado.';
        return back()->with('success', $msg);
    }

    public function subcategories(Request $request)
    {
        $subs = Category::where('parent_id', $request->category_id)->orderBy('name')->get(['id', 'name']);
        return response()->json($subs);
    }
}
