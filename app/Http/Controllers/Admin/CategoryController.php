<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.addcategory');
    }

    public function store(Request $request)
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

    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.viewcategory', compact('categories'));
    }

    public function destroy($id)
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

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.updatecategory', compact('category'));
    }

    public function update(Request $request, $id)
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
}
