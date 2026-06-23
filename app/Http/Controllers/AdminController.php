<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
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

    public function addCategory()
    {
        return view('admin.addcategory');
    }

    public function postAddCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255|unique:categories,category',
        ]);

        $category = new Category();
        $category->category = $request->category;
        $category->save();

        return redirect()
            ->back()
            ->with('category_message', 'Category added successfully!');
    }

    public function viewCategory()
    {
        $categories = Category::latest()->get();

        return view('admin.viewcategory', compact('categories'));
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        $usedByProduct = Product::where('product_category', $category->category)->exists();

        if ($usedByProduct) {
            return redirect()
                ->back()
                ->with('deletecategory_message', 'Category cannot be deleted because it is still used by products.');
        }

        $category->delete();

        return redirect()
            ->back()
            ->with('deletecategory_message', 'Category deleted successfully!');
    }

    public function updateCategory($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.updatecategory', compact('category'));
    }

    public function postUpdateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category' => 'required|string|max:255|unique:categories,category,' . $category->id,
        ]);

        $oldCategoryName = $category->category;

        $category->category = $request->category;
        $category->save();

        Product::where('product_category', $oldCategoryName)
            ->update(['product_category' => $request->category]);

        return redirect()
            ->back()
            ->with('category_updated_message', 'Category updated successfully!');
    }

    public function addProduct()
    {
        $categories = Category::orderBy('category')->get();

        return view('admin.addproduct', compact('categories'));
    }

    public function postAddProduct(Request $request)
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
            $image = $request->file('product_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('products'), $imageName);

            $product->product_image = $imageName;
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message', 'Product added successfully!');
    }

    public function viewProduct()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.viewproduct', compact('products'));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if (Order::where('product_id', $product->id)->exists()) {
            return redirect()
                ->back()
                ->with('deleteproduct_message', 'Product cannot be deleted because it is linked to an existing order.');
        }

        if ($product->product_image) {
            $imagePath = public_path('products/' . $product->product_image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()
            ->back()
            ->with('deleteproduct_message', 'Product deleted successfully!');
    }

    public function updateProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('category')->get();

        return view('admin.updateproduct', compact('product', 'categories'));
    }

    public function postUpdateProduct(Request $request, $id)
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
            if ($product->product_image) {
                $oldImagePath = public_path('products/' . $product->product_image);

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('product_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('products'), $imageName);

            $product->product_image = $imageName;
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message', 'Product updated successfully!');
    }

    public function searchProduct(Request $request)
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

    public function viewOrder(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));
        $paymentStatus = trim((string) $request->query('payment_status', ''));

        $orders = Order::with(['user', 'product'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($orderQuery) use ($search) {
                    $orderQuery
                        ->where('receiver_name', 'like', '%' . $search . '%')
                        ->orWhere('receiver_address', 'like', '%' . $search . '%')
                        ->orWhere('receiver_phone', 'like', '%' . $search . '%')
                        ->orWhere('stripe_payment_id', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery
                                ->where('product_title', 'like', '%' . $search . '%')
                                ->orWhere('product_category', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($paymentStatus !== '', fn ($query) => $query->where('payment_status', $paymentStatus))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.vieworders', compact('orders'));
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in progress,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()
            ->back()
            ->with('status_message', 'Order status updated successfully!');
    }

    public function downloadPDF($id)
    {
        $data = Order::with(['user', 'product'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.invoice', compact('data'));

        return $pdf->download('giftos-invoice-' . str_pad($data->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
