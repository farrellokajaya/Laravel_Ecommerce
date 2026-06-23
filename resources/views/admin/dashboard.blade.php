@extends('admin.maindesign')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('page_actions')
    <a href="{{ route('admin.addproduct') }}" class="admin-button admin-button-primary">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        Add product
    </a>
@endsection

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Good to see you, {{ auth()->user()->name }}.</h2>
            <p>Here is a clear overview of your catalog, inventory, and recent store activity.</p>
        </div>
    </div>

    <section class="stats-grid" aria-label="Store statistics">
        <article class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon accent">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z"/><path d="M12 11v10"/></svg>
                </div>
            </div>
            <span class="stat-label">Total products</span>
            <strong class="stat-value">{{ number_format($productCount) }}</strong>
            <span class="stat-caption">{{ number_format($categoryCount) }} active categories</span>
        </article>

        <article class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon warning">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 2.8 19h18.4L12 3Z"/><path d="M12 9v4M12 17h.01"/></svg>
                </div>
            </div>
            <span class="stat-label">Low stock</span>
            <strong class="stat-value">{{ number_format($lowStockCount) }}</strong>
            <span class="stat-caption">Products with 5 items or fewer</span>
        </article>

        <article class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l2 5H4l2-5Z"/><path d="M5 8v13h14V8M9 12h6"/></svg>
                </div>
            </div>
            <span class="stat-label">Total orders</span>
            <strong class="stat-value">{{ number_format($orderCount) }}</strong>
            <span class="stat-caption">{{ number_format($pendingOrderCount) }} orders need attention</span>
        </article>

        <article class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon success">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20M17 6.5c0-1.7-1.8-3-4-3s-4 1.3-4 3 1.8 3 4 3 4 1.3 4 3-1.8 3-4 3-4-1.3-4-3"/></svg>
                </div>
            </div>
            <span class="stat-label">Paid revenue</span>
            <strong class="stat-value">${{ number_format($totalRevenue, 2, '.', ',') }}</strong>
            <span class="stat-caption">From successfully paid orders</span>
        </article>
    </section>

    <section class="dashboard-grid">
        <div class="admin-table-card">
            <div class="table-toolbar">
                <div class="table-toolbar-copy">
                    <h3>Recent orders</h3>
                    <p>Your latest customer transactions.</p>
                </div>
                <a href="{{ route('admin.vieworder') }}" class="admin-button admin-button-secondary admin-button-small">View all orders</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-inner">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l2 5H4l2-5Z"/><path d="M5 8v13h14V8M9 12h6"/></svg>
                        </div>
                        <h3>No orders yet</h3>
                        <p>New customer orders will appear here after successful checkout.</p>
                    </div>
                </div>
            @else
                <div class="table-scroll">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                @php
                                    $statusClass = match(strtolower($order->status ?? 'pending')) {
                                        'delivered' => 'status-delivered',
                                        'in progress' => 'status-progress',
                                        'canceled' => 'status-canceled',
                                        default => 'status-pending',
                                    };
                                    $lineTotal = (float) (($order->total_price ?? 0) > 0
                                        ? $order->total_price
                                        : (($order->unit_price ?? $order->product?->product_prices ?? 0) * ($order->quantity ?? 1)));
                                @endphp
                                <tr>
                                    <td><strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                    <td>
                                        <div class="customer-cell">
                                            <strong>{{ $order->receiver_name ?: ($order->user?->name ?? 'Customer') }}</strong>
                                            <span>{{ $order->receiver_phone }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $order->product?->product_title ?? 'Product unavailable' }}</td>
                                    <td><strong>${{ number_format($lineTotal, 2, '.', ',') }}</strong></td>
                                    <td><span class="status-badge {{ $statusClass }}">{{ $order->status ?? 'pending' }}</span></td>
                                    <td class="text-muted">{{ $order->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <aside class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3>Quick actions</h3>
                    <p>Common store management tasks.</p>
                </div>
            </div>
            <div class="admin-card-body quick-actions">
                <a href="{{ route('admin.addproduct') }}" class="quick-action">
                    <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></span>
                    <span class="quick-action-copy"><strong>Add a new product</strong><span>Create a catalog listing</span></span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <a href="{{ route('admin.viewproduct') }}" class="quick-action">
                    <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z"/></svg></span>
                    <span class="quick-action-copy"><strong>Manage inventory</strong><span>Review stock and pricing</span></span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <a href="{{ route('admin.addcategory') }}" class="quick-action">
                    <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z"/></svg></span>
                    <span class="quick-action-copy"><strong>Create a category</strong><span>Keep products organized</span></span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>

                <a href="{{ route('admin.vieworder') }}" class="quick-action">
                    <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l2 5H4l2-5Z"/><path d="M5 8v13h14V8"/></svg></span>
                    <span class="quick-action-copy"><strong>Process orders</strong><span>Update fulfillment status</span></span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
        </aside>
    </section>
@endsection
