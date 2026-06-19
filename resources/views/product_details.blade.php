@extends('maindesign')

@section('title', $product->product_title . ' — Giftos')

@section('content')
<div class="container">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('viewallproducts') }}">Products</a>
        @if($product->product_category)
            <span>/</span>
            <a href="{{ route('viewallproducts', ['category' => $product->product_category]) }}">{{ $product->product_category }}</a>
        @endif
        <span>/</span>
        <span>{{ $product->product_title }}</span>
    </nav>
</div>

<section class="page-section" style="padding-top: 12px;">
    <div class="container">
        <div class="product-detail-grid">
            <div class="product-gallery">
                <div class="product-main-image">
                    <img src="/products/{{ $product->product_image }}" alt="{{ $product->product_title }}">
                </div>
            </div>

            <div class="product-info-panel">
                <span class="eyebrow">{{ $product->product_category ?: 'Product' }}</span>
                <h1>{{ $product->product_title }}</h1>
                <div class="detail-price">${{ number_format($product->product_prices, 2, '.', ',') }}</div>
                <span class="stock-pill">In stock — {{ $product->product_quantity }} available</span>

                <p class="detail-description">
                    {{ \Illuminate\Support\Str::limit(strip_tags($product->product_description), 220) }}
                </p>

                <form class="purchase-box" action="{{ route('add_to_cart', $product->id) }}" method="POST">
                    @csrf
                    <label class="quantity-label" for="quantity">Quantity</label>
                    <div class="quantity-selector" data-quantity>
                        <button type="button" data-decrease aria-label="Decrease quantity">−</button>
                        <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $product->product_quantity }}">
                        <button type="button" data-increase aria-label="Increase quantity">+</button>
                    </div>

                    <div class="purchase-actions">
                        <button class="button button-light" type="submit" name="action" value="cart">
                            <svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                            Add to cart
                        </button>
                        <button class="button button-dark" type="submit" name="action" value="checkout">Buy now</button>
                    </div>
                </form>

                <div class="trust-list">
                    <div class="trust-item"><strong>Secure payment</strong><span>Protected by Stripe</span></div>
                    <div class="trust-item"><strong>Stock verified</strong><span>Updated at checkout</span></div>
                    <div class="trust-item"><strong>Order tracking</strong><span>Check from your account</span></div>
                </div>
            </div>
        </div>

        <div class="product-description-section">
            <span class="eyebrow">Product information</span>
            <h2>Description</h2>
            <div class="product-description-content">{{ $product->product_description }}</div>
        </div>
    </div>
</section>
@endsection
