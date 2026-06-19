<article class="product-card">
    <a class="product-image-link" href="{{ route('product_details', $product->id) }}">
        <img src="/products/{{ $product->product_image }}" alt="{{ $product->product_title }}">
        <span class="product-badge">New</span>
    </a>
    <div class="product-card-body">
        <span class="product-category">{{ $product->product_category ?: 'Product' }}</span>
        <h3 class="product-title">
            <a href="{{ route('product_details', $product->id) }}">{{ $product->product_title }}</a>
        </h3>
        <div class="product-meta">
            <span class="product-price">${{ number_format($product->product_prices, 2, '.', ',') }}</span>
            @auth
                <form action="{{ route('add_to_cart', $product->id) }}" method="POST">
                    @csrf
                    <button class="card-action" type="submit" aria-label="Add {{ $product->product_title }} to cart">
                        <svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><path d="M12 9v5M9.5 11.5h5"/></svg>
                    </button>
                </form>
            @else
                <a class="card-action" href="{{ route('login') }}" aria-label="Log in to add product to cart">
                    <svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 7H6"/><path d="M12 9v5M9.5 11.5h5"/></svg>
                </a>
            @endauth
        </div>
    </div>
</article>
