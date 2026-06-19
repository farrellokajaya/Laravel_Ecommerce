@extends('maindesign')

@section('title', 'Shopping Cart — Giftos')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Your selection</span>
                <h1>Shopping cart</h1>
                <p>Review your products before continuing to secure checkout.</p>
            </div>
            <a class="button button-light button-small" href="{{ route('viewallproducts') }}">Continue shopping</a>
        </div>

        @php $total = 0; @endphp
        @foreach($cart as $cartProduct)
            @if($cartProduct->product)
                @php
                    $quantity = (int) ($cartProduct->quantity ?? 1);
                    $subtotal = $cartProduct->product->product_prices * $quantity;
                    $total += $subtotal;
                @endphp
            @endif
        @endforeach

        @if($cart->isNotEmpty())
            <div class="cart-layout">
                <div class="cart-list">
                    @foreach($cart as $cartProduct)
                        @if($cartProduct->product)
                            @php
                                $product = $cartProduct->product;
                                $quantity = (int) ($cartProduct->quantity ?? 1);
                                $subtotal = $product->product_prices * $quantity;
                            @endphp
                            <article class="cart-item">
                                <a class="cart-image" href="{{ route('product_details', $product->id) }}">
                                    <img src="/products/{{ $product->product_image }}" alt="{{ $product->product_title }}">
                                </a>
                                <div class="cart-product-info">
                                    <span class="product-category">{{ $product->product_category ?: 'Product' }}</span>
                                    <h3><a href="{{ route('product_details', $product->id) }}">{{ $product->product_title }}</a></h3>
                                    <p>Available stock: {{ $product->product_quantity }}</p>
                                    <div class="cart-item-meta">
                                        <span>Unit price<strong>${{ number_format($product->product_prices, 2, '.', ',') }}</strong></span>
                                        <span>Quantity<strong>{{ $quantity }}</strong></span>
                                    </div>
                                </div>
                                <div class="cart-item-end">
                                    <span class="cart-subtotal">${{ number_format($subtotal, 2, '.', ',') }}</span>
                                    <form action="{{ route('removecartproducts', $cartProduct->id) }}" method="POST" data-confirm-remove>
                                        @csrf
                                        @method('DELETE')
                                        <button class="remove-button" type="submit">
                                            <svg viewBox="0 0 24 24"><path d="M4 7h16M9 7V4h6v3M8 7l1 13h6l1-13"/></svg>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>

                <aside class="summary-card">
                    <h2>Order summary</h2>
                    <div class="summary-row"><span>Items</span><strong>{{ $navCartCount ?? $cart->sum('quantity') }}</strong></div>
                    <div class="summary-row"><span>Subtotal</span><strong>${{ number_format($total, 2, '.', ',') }}</strong></div>
                    <div class="summary-row"><span>Shipping</span><strong>Calculated later</strong></div>
                    <div class="summary-row summary-total"><span>Total</span><strong>${{ number_format($total, 2, '.', ',') }}</strong></div>
                    <div class="summary-note">
                        <svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <span>Your payment details are encrypted and processed securely by Stripe.</span>
                    </div>
                    <a class="button button-dark button-wide" href="{{ route('checkout') }}">Proceed to checkout</a>
                </aside>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                </div>
                <h2>Your cart is empty</h2>
                <p>Explore our latest products and add something you like.</p>
                <a class="button button-dark" href="{{ route('viewallproducts') }}">Browse products</a>
            </div>
        @endif
    </div>
</section>
@endsection
