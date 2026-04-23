<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'featured' => Product::active()->featured()->take(8)->get(),
            'newArrivals' => Product::active()->where('is_new', true)->latest()->take(4)->get(),
            'categories' => Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->take(6)->get(),
        ]);
    }
}
