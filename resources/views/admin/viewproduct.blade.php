@extends('admin.maindesign')

@section('title', 'Products')
@section('page_title', 'Products')

@section('page_actions')
    <a href="{{ route('admin.addproduct') }}" class="admin-button admin-button-primary">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        Add product
    </a>
@endsection

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Product catalog</h2>
            <p>Review product details, stock levels, pricing, categories, and storefront images.</p>
        </div>
    </div>

    <form class="filter-bar" action="{{ route('admin.searchproduct') }}" method="GET">
        <div class="filter-field grow">
            <label for="product-search">Search catalog</label>
            <div class="admin-input-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input
                    class="admin-input"
                    id="product-search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by title, description, or category"
                >
            </div>
        </div>
        <button type="submit" class="admin-button admin-button-primary">Search</button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">Clear</a>
        @endif
    </form>

    <div class="admin-table-card">
        <div class="table-toolbar">
            <div class="table-toolbar-copy">
                <h3>{{ request()->filled('search') ? 'Search results' : 'All products' }}</h3>
                <p>
                    @if(method_exists($products, 'total'))
                        {{ number_format($products->total()) }} product{{ $products->total() === 1 ? '' : 's' }} found.
                    @else
                        {{ number_format($products->count()) }} product{{ $products->count() === 1 ? '' : 's' }} found.
                    @endif
                </p>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="empty-state">
                <div class="empty-state-inner">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z"/></svg>
                    </div>
                    <h3>{{ request()->filled('search') ? 'No matching products' : 'No products yet' }}</h3>
                    <p>{{ request()->filled('search') ? 'Try a different keyword or clear the search.' : 'Create your first product to begin building the catalog.' }}</p>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">Clear search</a>
                    @else
                        <a href="{{ route('admin.addproduct') }}" class="admin-button admin-button-primary">Add product</a>
                    @endif
                </div>
            </div>
        @else
            <div class="table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Inventory</th>
                            <th>Updated</th>
                            <th class="actions-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            @php
                                $stock = (int) $product->product_quantity;
                                $stockClass = $stock <= 0 ? 'status-out-stock' : ($stock <= 5 ? 'status-low-stock' : 'status-in-stock');
                                $stockText = $stock <= 0 ? 'Out of stock' : ($stock <= 5 ? 'Low stock' : 'In stock');
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-thumbnail">
                                            @if($product->product_image)
                                                <img src="/products/{{ $product->product_image }}" alt="{{ $product->product_title }}">
                                            @else
                                                <span class="product-thumbnail-placeholder">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m4 17 5-4 4 3 3-2 4 3"/></svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="product-cell-copy">
                                            <strong>{{ $product->product_title }}</strong>
                                            <span>{{ \Illuminate\Support\Str::limit($product->product_description, 62) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="status-badge status-neutral">{{ $product->product_category ?: 'Uncategorized' }}</span></td>
                                <td><strong>${{ number_format($product->product_prices, 2, '.', ',') }}</strong></td>
                                <td>
                                    <div class="customer-cell">
                                        <strong>{{ number_format($stock) }} units</strong>
                                        <span><span class="status-badge {{ $stockClass }}">{{ $stockText }}</span></span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $product->updated_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="actions-cell">
                                    <div class="table-actions">
                                        <a href="{{ route('product_details', $product->id) }}" class="admin-icon-button" title="View storefront product" aria-label="View {{ $product->product_title }}">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                                        </a>
                                        <a href="{{ route('admin.updateproduct', $product->id) }}" class="admin-icon-button" title="Edit product" aria-label="Edit {{ $product->product_title }}">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 4.2-1 10.4-10.4a2 2 0 0 0-2.8-2.8L5.4 16.2 4 20Z"/><path d="m14.5 7.1 2.8 2.8"/></svg>
                                        </a>
                                        <form
                                            action="{{ route('admin.deleteproduct', $product->id) }}"
                                            method="POST"
                                            data-confirm
                                            data-confirm-title="Delete this product?"
                                            data-confirm-text="This removes the product and its image. This action cannot be undone."
                                            data-confirm-button="Delete product"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-icon-button danger" title="Delete product" aria-label="Delete {{ $product->product_title }}">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M7 7l1 13h8l1-13M10 11v5M14 11v5"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($products, 'links'))
                <div class="pagination-wrap">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection