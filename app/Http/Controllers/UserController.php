<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Order;
use Session;
use Stripe;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->user_type=="user"){
            return view('dashboard');
        } 

        if (Auth::check() && Auth::user()->user_type=="admin"){
            return view('admin.dashboard');
        }

        return redirect()->route('login');
    }

    public function home()
    {
        $count = $this->getCartCount();

        $products = Product::where('product_quantity', '>', 0)
            ->latest()
            ->take(2)
            ->get();

        return view('index', compact('products', 'count'));
    }

    public function productDetails($id)
    {
        $count = $this->getCartCount();

        $product = Product::where('product_quantity', '>', 0)
            ->findOrFail($id);

        return view('product_details', compact('product', 'count'));
    }

    public function allProducts()
    {
        $count = $this->getCartCount();

        $products = Product::where('product_quantity', '>', 0)
            ->latest()
            ->get();

        return view('allproducts', compact('products', 'count'));
    }

    public function addToCart($id)
    {
        $product = Product::where('product_quantity', '>', 0)
            ->findOrFail($id);
        
        $existingCart = ProductCart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
                
        if ($existingCart) {
            return redirect()
                ->back()
                ->with('cart_message', 'Product already exists in your cart.');
        }
        $product_cart = new ProductCart();
        $product_cart->user_id = Auth::id();
        $product_cart->product_id = $product->id;
        $product_cart->save();

        return redirect()
            ->back()
            ->with('cart_message','Added To The Cart');

    }

    public function cartProduct()
    {
        $count = $this->getCartCount();

        $cart = ProductCart::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('viewcartproducts', compact('count', 'cart'));
    }

    public function removeCartProducts($id)
    {
        $cart_product = ProductCart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cart_product->delete();

        return redirect()
            ->back()
            ->with('cart_message', 'Product removed from cart.');
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'receiver_address' => 'required|string|max:500',
            'receiver_phone' => 'required|string|max:20',
        ]);

        $cartProducts=ProductCart::where('user_id',Auth::id())->get();
                
        if ($cartProducts->isEmpty()) {
            return redirect()
                ->back()
                ->with('confirm_order', 'Your cart is empty.');
        }

        foreach ($cartProducts as $cart_product) {
            $product = Product::find($cart_product->product_id);

            if (!$product || $product->product_quantity <= 0) {
                continue;
            }

            $order = new Order();
            $order->receiver_address = $request->receiver_address;
            $order->receiver_phone = $request->receiver_phone;
            $order->user_id = Auth::id();
            $order->product_id = $cart_product->product_id;
            $order->save();

            $product->product_quantity = $product->product_quantity - 1;
            $product->save();
        }

        ProductCart::where('user_id', Auth::id())->delete();

        return redirect()
            ->back()
            ->with('confirm_order', 'Order confirmed successfully.');
    }

    public function myOrders(){
        $orders=Order::where('user_id',Auth::id())
            ->latest()
            ->get();

        return view('viewmyorders',compact('orders'));
    }

    public function stripe($price){
        $count = $this->getCartCount();

        return view('stripe', compact('count', 'price'));
    }

    public function stripePost(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    

        Stripe\Charge::create ([

                "amount" => 100 * 100,

                "currency" => "usd",

                "source" => $request->stripeToken,

                "description" => "Test payment from itsolutionstuff.com." 

        ]);
        $cartProducts=ProductCart::where('user_id',Auth::id())->get();

        foreach ($cartProducts as $cart_product) {
            $order = new Order();
            $order->receiver_address = $request->receiver_address;
            $order->receiver_phone = $request->receiver_phone;
            $order->user_id = Auth::id();
            $order->product_id = $cart_product->product_id;
            $order->payment_status = "paid";
            $order->save();
        }

        ProductCart::where('user_id', Auth::id())->delete();

        Session::flash('success', 'Payment successful!');

        return back();
    }

    private function getCartCount()
    {
        if (Auth::check()) {
            return ProductCart::where('user_id', Auth::id())->count();
        }

        return '';
    }

}
