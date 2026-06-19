<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }

    public function home()
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

    public function productDetails($id)
    {
        $product = Product::where('product_quantity', '>', 0)
            ->findOrFail($id);

        return view('product_details', compact('product'));
    }

    public function allProducts(Request $request)
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

    public function addToCart(Request $request, $id)
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

    public function cartProduct()
    {
        $cart = ProductCart::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('viewcartproducts', compact('cart'));
    }

    public function removeCartProducts($id)
    {
        $cartProduct = ProductCart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartProduct->delete();

        return redirect()
            ->back()
            ->with('cart_message', 'Product removed from your cart.');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['product', 'user'])
            ->latest()
            ->get();

        return view('viewmyorders', compact('orders'));
    }

    public function checkout()
    {
        $cart = ProductCart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cart->isEmpty()) {
            return redirect()
                ->route('cartproduct')
                ->with('cart_message', 'Your cart is empty.');
        }

        $total = 0;

        foreach ($cart as $cartProduct) {
            if (!$cartProduct->product) {
                return redirect()
                    ->route('cartproduct')
                    ->with('cart_message', 'Some products in your cart are no longer available.');
            }

            $quantity = (int) ($cartProduct->quantity ?? 1);

            if ($cartProduct->product->product_quantity < $quantity) {
                return redirect()
                    ->route('cartproduct')
                    ->with('cart_message', $cartProduct->product->product_title . ' does not have enough stock.');
            }

            $total += $cartProduct->product->product_prices * $quantity;
        }

        return view('stripe', compact('cart', 'total'));
    }

    public function checkoutPayment(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_address' => 'required|string|max:500',
            'receiver_phone' => 'required|string|max:20',
            'stripeToken' => 'required|string',
        ]);

        $userId = Auth::id();

        $cartProducts = ProductCart::where('user_id', $userId)
            ->with('product')
            ->get();

        if ($cartProducts->isEmpty()) {
            return redirect()
                ->route('cartproduct')
                ->with('cart_message', 'Your cart is empty.');
        }

        $total = 0;

        foreach ($cartProducts as $cartProduct) {
            $product = $cartProduct->product;
            $quantity = (int) ($cartProduct->quantity ?? 1);

            if (!$product) {
                return redirect()
                    ->route('cartproduct')
                    ->with('cart_message', 'Some products are no longer available.');
            }

            if ($product->product_quantity < $quantity) {
                return redirect()
                    ->route('cartproduct')
                    ->with('cart_message', $product->product_title . ' does not have enough stock.');
            }

            $total += $product->product_prices * $quantity;
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $charge = \Stripe\Charge::create([
                'amount' => (int) round($total * 100),
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Ecommerce payment for user ID ' . $userId,
            ]);

            DB::transaction(function () use ($cartProducts, $request, $charge, $userId) {
                foreach ($cartProducts as $cartProduct) {
                    $product = Product::where('id', $cartProduct->product_id)
                        ->lockForUpdate()
                        ->first();

                    $quantity = (int) ($cartProduct->quantity ?? 1);

                    if (!$product) {
                        throw new \Exception('Product is no longer available.');
                    }

                    if ($product->product_quantity < $quantity) {
                        throw new \Exception($product->product_title . ' does not have enough stock.');
                    }

                    $unitPrice = $product->product_prices;
                    $totalPrice = $unitPrice * $quantity;

                    $order = new Order();
                    $order->receiver_name = $request->receiver_name;
                    $order->receiver_address = $request->receiver_address;
                    $order->receiver_phone = $request->receiver_phone;
                    $order->user_id = $userId;
                    $order->product_id = $product->id;
                    $order->quantity = $quantity;
                    $order->unit_price = $unitPrice;
                    $order->total_price = $totalPrice;
                    $order->payment_status = 'paid';
                    $order->status = 'pending';
                    $order->stripe_payment_id = $charge->id;
                    $order->save();

                    $product->product_quantity -= $quantity;
                    $product->save();
                }

                ProductCart::where('user_id', $userId)->delete();
            });

            return redirect()
                ->route('home')
                ->with('payment_success', 'Your payment was successful and your order has been created.');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('checkout')
                ->withInput($request->only('receiver_name', 'receiver_address', 'receiver_phone'))
                ->with('error', 'Payment failed: ' . $exception->getMessage());
        }
    }
}