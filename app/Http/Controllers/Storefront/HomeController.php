<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('product_quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Product::where('product_quantity', '>', 0)
            ->whereNotNull('product_category')
            ->where('product_category', '!=', '')
            ->select('product_category')
            ->distinct()
            ->orderBy('product_category')
            ->take(8)
            ->pluck('product_category');

        return view('index', compact('products', 'categories'));
    }
}
