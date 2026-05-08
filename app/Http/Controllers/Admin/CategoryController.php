<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['children', 'products'])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'parent_id', 'description', 'sort_order']);
        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'parent_id', 'description', 'sort_order']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Não é possível excluir: categoria possui produtos vinculados.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Categoria excluída.');
    }

    // Subcategories section
    public function subcategories(Category $category)
    {
        $subcategories = $category->children()->withCount('products')->orderBy('name')->get();
        return view('admin.categories.subcategories', compact('category', 'subcategories'));
    }

    public function storeSubcategory(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'parent_id' => $category->id,
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Subcategoria criada!');
    }

    public function destroySubcategory(Category $subcategory)
    {
        if ($subcategory->products()->count() > 0) {
            return back()->with('error', 'Não é possível excluir: subcategoria possui produtos vinculados.');
        }
        $subcategory->delete();
        return back()->with('success', 'Subcategoria excluída.');
    }
}
