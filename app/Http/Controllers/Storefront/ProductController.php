<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::where('product_quantity', '>', 0)
            ->findOrFail($id);

        return view('product_details', compact('product'));
    }

    public function index(Request $request)
    {
        $activeSearch = trim((string) $request->query('search', ''));
        $activeCategory = trim((string) $request->query('category', ''));

        $query = Product::query()
            ->where('product_quantity', '>', 0);

        if ($activeSearch !== '') {
            $query->where(function ($productQuery) use ($activeSearch) {
                $productQuery
                    ->where('product_title', 'like', '%' . $activeSearch . '%')
                    ->orWhere('product_description', 'like', '%' . $activeSearch . '%')
                    ->orWhere('product_category', 'like', '%' . $activeSearch . '%');
            });
        }

        if ($activeCategory !== '') {
            $query->where('product_category', $activeCategory);
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Product::where('product_quantity', '>', 0)
            ->whereNotNull('product_category')
            ->where('product_category', '!=', '')
            ->select('product_category')
            ->distinct()
            ->orderBy('product_category')
            ->pluck('product_category');

        return view('allproducts', compact(
            'products',
            'categories',
            'activeSearch',
            'activeCategory'
        ));
    }
}
