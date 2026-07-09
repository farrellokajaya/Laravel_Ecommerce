<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\Storefront\CheckoutPaymentRequest;

class CheckoutController extends Controller
{
    public function show()
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

    public function store(CheckoutPaymentRequest $request)
    {

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
                    ->with(
                        'cart_message',
                        'Some products are no longer available.'
                    );
            }

            if ($product->product_quantity < $quantity) {
                return redirect()
                    ->route('cartproduct')
                    ->with(
                        'cart_message',
                        $product->product_title .
                        ' does not have enough stock.'
                    );
            }

            $total += $product->product_prices * $quantity;
        }

        $invoiceNumber = $this->generateInvoiceNumber();

        try {
            \Stripe\Stripe::setApiKey(
                config('services.stripe.secret')
            );

            $charge = \Stripe\Charge::create([
                'amount' => (int) round($total * 100),
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Payment for ' . $invoiceNumber,
                'metadata' => [
                    'invoice_number' => $invoiceNumber,
                    'user_id' => (string) $userId,
                ],
            ]);

            DB::transaction(function () use (
                $cartProducts,
                $request,
                $charge,
                $userId,
                $invoiceNumber
            ) {
                foreach ($cartProducts as $cartProduct) {
                    $product = Product::where(
                        'id',
                        $cartProduct->product_id
                    )
                        ->lockForUpdate()
                        ->first();

                    $quantity = (int) (
                        $cartProduct->quantity ?? 1
                    );

                    if (!$product) {
                        throw new \Exception(
                            'Product is no longer available.'
                        );
                    }

                    if ($product->product_quantity < $quantity) {
                        throw new \Exception(
                            $product->product_title .
                            ' does not have enough stock.'
                        );
                    }

                    $unitPrice = $product->product_prices;
                    $totalPrice = $unitPrice * $quantity;

                    $order = new Order();
                    $order->receiver_name = $request->receiver_name;
                    $order->receiver_address =
                        $request->receiver_address;
                    $order->receiver_phone =
                        $request->receiver_phone;
                    $order->user_id = $userId;
                    $order->product_id = $product->id;
                    $order->quantity = $quantity;
                    $order->unit_price = $unitPrice;
                    $order->total_price = $totalPrice;
                    $order->payment_status = 'paid';
                    $order->status = 'pending';
                    $order->stripe_payment_id = $charge->id;
                    $order->invoice_number = $invoiceNumber;
                    $order->save();

                    $product->product_quantity -= $quantity;
                    $product->save();
                }

                ProductCart::where('user_id', $userId)
                    ->delete();
            });

            return redirect()->route('payment.success', [
                'invoiceNumber' => $invoiceNumber,
            ]);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('checkout')
                ->withInput(
                    $request->only([
                        'receiver_name',
                        'receiver_address',
                        'receiver_phone',
                    ])
                )
                ->with(
                    'error',
                    'Payment failed: ' . $exception->getMessage()
                );
        }
    }

    public function success(string $invoiceNumber)
    {
        $orders = Order::where(
            'invoice_number',
            $invoiceNumber
        )
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->with(['product', 'user'])
            ->orderBy('id')
            ->get();

        abort_if($orders->isEmpty(), 404);

        $firstOrder = $orders->first();

        $total = $orders->sum(function ($order) {
            return (float) $order->total_price;
        });

        return view('payment-success', compact(
            'orders',
            'firstOrder',
            'invoiceNumber',
            'total'
        ));
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'INV-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));
        } while (
            Order::where(
                'invoice_number',
                $invoiceNumber
            )->exists()
        );

    return $invoiceNumber;
    }
}
