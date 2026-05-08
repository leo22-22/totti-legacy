<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $showcase2Enabled = Setting::get('showcase2_enabled', '0') === '1';
        $showcase2Products = collect();

        if ($showcase2Enabled) {
            $catId = Setting::get('showcase2_category_id', '');
            $limit = (int) Setting::get('showcase2_limit', '4');
            $q = Product::active();
            if ($catId) $q->where('category_id', $catId);
            $showcase2Products = $q->latest()->take($limit)->get();
        }

        return view('home', [
            'featured'          => Product::active()->featured()->take(8)->get(),
            'newArrivals'       => Product::active()->where('is_new', true)->latest()->take(4)->get(),
            'categories'        => Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'showcase2Products' => $showcase2Products,
            'showcase2Dark'     => Setting::get('showcase2_dark_bg', '1') === '1',
            'showcase2Title'    => Setting::get('showcase2_title', 'Coleção Especial'),
            'showcase2Sub'      => Setting::get('showcase2_subtitle', ''),
        ]);
    }
}
