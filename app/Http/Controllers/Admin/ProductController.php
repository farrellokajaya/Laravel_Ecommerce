<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Services\ProductImageService;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductImageService $productImageService
    ) {
    }

    public function create()
    {
        $categories = Category::orderBy('category')->get();

        return view('admin.addproduct', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_title' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_quantity' => 'required|integer|min:0',
            'product_prices' => 'required|integer|min:0',
            'product_category' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = new Product();
        $product->product_title = $request->product_title;
        $product->product_description = $request->product_description;
        $product->product_quantity = $request->product_quantity;
        $product->product_prices = $request->product_prices;
        $product->product_category = $request->product_category;

        if ($request->hasFile('product_image')) {
            $product->product_image = $this->productImageService->store(
                $request->file('product_image')
            );
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message', 'Product added successfully!');
    }

    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.viewproduct', compact('products'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Order::where('product_id', $product->id)->exists()) {
            return redirect()
                ->back()
                ->with(
                    'deleteproduct_message',
                    'Product cannot be deleted because it is linked to an existing order.'
                );
        }

        $this->productImageService->delete(
            $product->product_image
        );

        $product->delete();

        return redirect()
            ->back()
            ->with(
                'deleteproduct_message',
                'Product deleted successfully!'
            );
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('category')->get();

        return view('admin.updateproduct', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_title' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_quantity' => 'required|integer|min:0',
            'product_prices' => 'required|integer|min:0',
            'product_category' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $product->product_title = $request->product_title;
        $product->product_description = $request->product_description;
        $product->product_quantity = $request->product_quantity;
        $product->product_prices = $request->product_prices;
        $product->product_category = $request->product_category;

        if ($request->hasFile('product_image')) {
            $product->product_image = $this->productImageService->replace(
                $product->product_image,
                $request->file('product_image')
            );
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message', 'Product updated successfully!');
    }

    public function search(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $products = Product::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery
                        ->where('product_title', 'like', '%' . $search . '%')
                        ->orWhere('product_description', 'like', '%' . $search . '%')
                        ->orWhere('product_category', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.viewproduct', compact('products'));
    }

}
