<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request, $id)
    {
        $product = Product::where('product_quantity', '>', 0)
            ->findOrFail($id);

        $quantity = max(1, (int) $request->input('quantity', 1));
        $quantity = min($quantity, (int) $product->product_quantity);

        $existingCart = ProductCart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCart) {
            $existingCart->quantity = min(
                (int) $product->product_quantity,
                (int) $existingCart->quantity + $quantity
            );
            $existingCart->save();

            $message = 'Product quantity updated in your cart.';
        } else {
            ProductCart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);

            $message = 'Product added to your cart.';
        }

        if ($request->input('action') === 'checkout') {
            return redirect()
                ->route('checkout')
                ->with('cart_message', $message);
        }

        return redirect()
            ->back()
            ->with('cart_message', $message);
    }

    public function index()
    {
        $cart = ProductCart::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('viewcartproducts', compact('cart'));
    }

    public function destroy($id)
    {
        $cartProduct = ProductCart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartProduct->delete();

        return redirect()
            ->back()
            ->with('cart_message', 'Product removed from your cart.');
    }
}
