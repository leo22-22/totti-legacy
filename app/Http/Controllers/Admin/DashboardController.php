<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue'         => Order::where('payment_status', 'paid')->sum('total'),
            'orders_today'    => Order::whereDate('created_at', today())->count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock'       => Product::where('is_active', true)->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'total_users'     => User::count(),
        ];

        $recent_orders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
