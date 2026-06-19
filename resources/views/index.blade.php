@extends('maindesign')

@section('title', 'Giftos — Modern Shopping')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-card">
            <div class="hero-copy">
                <span class="eyebrow">Curated essentials</span>
                <h1>Simple products. Better everyday.</h1>
                <p>Discover thoughtfully selected products with a clean shopping experience, secure payment, and straightforward delivery.</p>
                <div class="hero-actions">
                    <a class="button button-dark" href="{{ route('viewallproducts') }}">Shop all products</a>
                    <a class="button button-light" href="#categories">Browse categories</a>
                </div>
            </div>
            <div class="hero-visual">
                <img src="/front_end/images/image3.png" alt="Featured products">
                <div class="hero-note">
                    <strong>Fresh arrivals</strong>
                    <span>Explore our newest products, updated regularly.</span>
                </div>
            </div>
        </div>
    </div>
</section>

@if(($categories ?? collect())->isNotEmpty())
<section class="page-section" id="categories">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Browse by category</span>
                <h2>Find what fits your day</h2>
            </div>
            <a class="button button-light button-small" href="{{ route('viewallproducts') }}">View all</a>
        </div>

        <div class="category-grid">
            @foreach($categories as $category)
                <a class="category-card" href="{{ route('viewallproducts', ['category' => $category]) }}">
                    <span class="category-icon">{{ strtoupper(substr($category, 0, 1)) }}</span>
                    <div>
                        <h3>{{ $category }}</h3>
                        <span>Explore collection →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="page-section soft">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Just added</span>
                <h2>Latest products</h2>
                <p>New selections chosen for quality, usefulness, and everyday style.</p>
            </div>
            <a class="button button-dark button-small" href="{{ route('viewallproducts') }}">All products</a>
        </div>

        @if($products->isNotEmpty())
            <div class="product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 8h12l1 12H5L6 8Z"/><path d="M9 10V7a3 3 0 0 1 6 0v3"/></svg>
                </div>
                <h2>No products available yet</h2>
                <p>New products will appear here as soon as they are added.</p>
            </div>
        @endif
    </div>
</section>
@endsection