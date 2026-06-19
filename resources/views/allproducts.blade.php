@extends('maindesign')

@section('title', 'All Products — Giftos')

@section('content')
<section class="catalog-hero">
    <div class="container">
        <span class="eyebrow">Our collection</span>
        <div class="section-heading">
            <div>
                <h1>All products</h1>
                <p>Browse the complete collection and find the products that suit you.</p>
            </div>
        </div>

        <form class="catalog-toolbar" action="{{ route('viewallproducts') }}" method="GET">
            <label class="catalog-search">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="search" name="search" value="{{ $activeSearch ?? '' }}" placeholder="Search by product name, description, or category">
            </label>
            @if(!empty($activeCategory))
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <button class="button button-dark" type="submit">Search</button>
        </form>

        <div class="category-chips">
            <a class="category-chip {{ empty($activeCategory) ? 'active' : '' }}" href="{{ route('viewallproducts', array_filter(['search' => $activeSearch ?? null])) }}">All</a>
            @foreach(($categories ?? collect()) as $category)
                <a class="category-chip {{ ($activeCategory ?? '') === $category ? 'active' : '' }}"
                   href="{{ route('viewallproducts', array_filter(['category' => $category, 'search' => $activeSearch ?? null])) }}">
                    {{ $category }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="page-section" style="padding-top: 24px;">
    <div class="container">
        <div class="result-meta">
            <span>{{ $products->total() }} product{{ $products->total() === 1 ? '' : 's' }} found</span>
            @if(!empty($activeSearch) || !empty($activeCategory))
                <a href="{{ route('viewallproducts') }}">Clear filters</a>
            @endif
        </div>

        @if($products->isNotEmpty())
            <div class="product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pagination-wrap">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </div>
                <h2>No matching products</h2>
                <p>Try another keyword or remove the selected category filter.</p>
                <a class="button button-dark" href="{{ route('viewallproducts') }}">Reset filters</a>
            </div>
        @endif
    </div>
</section>
@endsection
