<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $orderCount = Order::count();
        $pendingOrderCount = Order::whereIn('status', ['pending', 'in progress'])->count();
        $lowStockCount = Product::where('product_quantity', '<=', 5)->count();
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_price');

        $recentOrders = Order::with(['user', 'product'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'productCount',
            'categoryCount',
            'orderCount',
            'pendingOrderCount',
            'lowStockCount',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
