<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
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
        $category->category=$request->category;
        $category->save();

        return redirect()
            ->back()
            ->with('category_message', 'Category added successfully!');
    }

    public function viewCategory()
    {
        $categories = Category::latest()->get();

        return view('admin.viewCategory',compact('categories'));
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        $usedByProduct = Product::where('product_category', $category->category)->exists();

        if($usedByProduct){
            return redirect()
                ->back()
                ->with('deletecategory_message', 'Category cannot be deleted because it is still used by products.');
        }

        $category->delete();

        return redirect()
            ->back()
            ->with('deletecategory_message', 'Deleted Successfully!');
    }

    public function updateCategory($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.updatecategory',compact('category'));

    }

    public function postupdateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category' => 'required|string|max:255|unique:categories,category,' . $category->id,
        ]);

        $oldCategoryName = $category->category;

        $category->category=$request->category;
        $category->save();

        Product::where('product_category', $oldCategoryName)
            ->update(['product_category' => $request->category]);

        return redirect()
            ->back()
            ->with('category_updated_message', 'Category updated successfully!');
    }

    public function addProduct()
    {
        $categories = Category::all();

        return view('admin.addproduct',compact('categories'));
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
            $imagename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('products'), $imagename);

            $product->product_image = $imagename;
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message','Product Added Successfully!');
    }

    public function viewProduct()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.viewproduct',compact('products'));
        
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

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
        $categories = Category::all();

        return view('admin.updateproduct',compact('product','categories'));
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
            $imagename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('products'), $imagename);

            $product->product_image = $imagename;
        }

        $product->save();

        return redirect()
            ->back()
            ->with('product_message', 'Product updated successfully!');
    }

    public function searchProduct(Request $request){

        $search = $request->search;
        $products = Product::where('product_title', 'LIKE','%'.$search.'%')
                        ->orWhere('product_description', 'LIKE','%'.$search.'%')
                        ->orWhere('product_category', 'LIKE','%'.$search.'%')
                        ->latest()
                        ->paginate(2);

        return view('admin.viewproduct', compact('products'));
    }

    public function viewOrder()
    {
        $orders=Order::latest()->all();

        return view('admin.vieworders',compact('orders'));

    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:in progress,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status=$request->status;
        $order->save();

        return redirect()
        ->back()
        ->with('status_message', 'Order status updated successfully!');
    }

    public function downloadPDF($id){

        $data = order::findOrFail($id);

        $pdf = Pdf::loadView('admin.invoice',compact('data'));

        return $pdf->download('CustomerOrder.pdf');
    }

}
