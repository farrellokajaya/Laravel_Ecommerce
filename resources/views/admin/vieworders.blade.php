@extends('admin.maindesign')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Order management</h2>
            <p>Review customer details, payment status, fulfillment progress, and downloadable invoices.</p>
        </div>
    </div>

    <form class="filter-bar" action="{{ route('admin.vieworder') }}" method="GET">
        <div class="filter-field grow">
            <label for="order-search">Search orders</label>
            <div class="admin-input-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input
                    class="admin-input"
                    id="order-search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Customer, phone, product, or payment ID"
                >
            </div>
        </div>

        <div class="filter-field">
            <label for="status-filter">Order status</label>
            <select class="admin-select" id="status-filter" name="status">
                <option value="">All statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="in progress" @selected(request('status') === 'in progress')>In progress</option>
                <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                <option value="canceled" @selected(request('status') === 'canceled')>Canceled</option>
            </select>
        </div>

        <div class="filter-field">
            <label for="payment-filter">Payment</label>
            <select class="admin-select" id="payment-filter" name="payment_status">
                <option value="">All payments</option>
                <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                <option value="COD" @selected(request('payment_status') === 'COD')>COD</option>
            </select>
        </div>

        <button type="submit" class="admin-button admin-button-primary">Apply filters</button>

        @if(request()->hasAny(['search', 'status', 'payment_status']) && (request('search') || request('status') || request('payment_status')))
            <a href="{{ route('admin.vieworder') }}" class="admin-button admin-button-secondary">Clear</a>
        @endif
    </form>

    <div class="admin-table-card">
        <div class="table-toolbar">
            <div class="table-toolbar-copy">
                <h3>Customer orders</h3>
                <p>{{ number_format($orders->total()) }} order record{{ $orders->total() === 1 ? '' : 's' }} found.</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="empty-state">
                <div class="empty-state-inner">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12l2 5H4l2-5Z"/><path d="M5 8v13h14V8M9 12h6"/></svg>
                    </div>
                    <h3>No orders found</h3>
                    <p>{{ request()->hasAny(['search', 'status', 'payment_status']) ? 'Adjust or clear your filters to see more results.' : 'Successful customer orders will appear here.' }}</p>
                    @if(request()->hasAny(['search', 'status', 'payment_status']))
                        <a href="{{ route('admin.vieworder') }}" class="admin-button admin-button-secondary">Clear filters</a>
                    @endif
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
                            <th>Delivery</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Fulfillment</th>
                            <th class="actions-cell">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $statusValue = strtolower($order->status ?? 'pending');
                                $statusClass = match($statusValue) {
                                    'delivered' => 'status-delivered',
                                    'in progress' => 'status-progress',
                                    'canceled' => 'status-canceled',
                                    default => 'status-pending',
                                };
                                $paymentValue = strtolower($order->payment_status ?? 'cod');
                                $paymentClass = $paymentValue === 'paid' ? 'status-paid' : 'status-neutral';
                                $quantity = (int) ($order->quantity ?? 1);
                                $unitPrice = (float) (($order->unit_price ?? 0) > 0
                                    ? $order->unit_price
                                    : ($order->product?->product_prices ?? 0));
                                $lineTotal = (float) (($order->total_price ?? 0) > 0
                                    ? $order->total_price
                                    : ($unitPrice * $quantity));
                            @endphp
                            <tr>
                                <td>
                                    <div class="customer-cell">
                                        <strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                        <span>{{ $order->created_at?->format('M d, Y · H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-cell">
                                        <strong>{{ $order->receiver_name ?: ($order->user?->name ?? 'Customer') }}</strong>
                                        <span>{{ $order->receiver_phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-thumbnail">
                                            @if($order->product?->product_image)
                                                <img src="/products/{{ $order->product->product_image }}" alt="{{ $order->product->product_title }}">
                                            @else
                                                <span class="product-thumbnail-placeholder">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m4 17 5-4 4 3 3-2 4 3"/></svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="product-cell-copy">
                                            <strong>{{ $order->product?->product_title ?? 'Product unavailable' }}</strong>
                                            <span>{{ $quantity }} × ${{ number_format($unitPrice, 2, '.', ',') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="wrap-cell">
                                    <div class="customer-cell">
                                        <strong>{{ $order->receiver_address }}</strong>
                                        <span>{{ $order->receiver_phone }}</span>
                                    </div>
                                </td>
                                <td><strong>${{ number_format($lineTotal, 2, '.', ',') }}</strong></td>
                                <td>
                                    <div class="customer-cell">
                                        <span><span class="status-badge {{ $paymentClass }}">{{ $order->payment_status ?? 'COD' }}</span></span>
                                        @if($order->stripe_payment_id)
                                            <span title="{{ $order->stripe_payment_id }}">{{ \Illuminate\Support\Str::limit($order->stripe_payment_id, 18) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-cell" style="gap: 8px;">
                                        <span><span class="status-badge {{ $statusClass }}">{{ $order->status ?? 'pending' }}</span></span>
                                        <form
                                            class="order-status-form"
                                            action="{{ route('admin.change_status', $order->id) }}"
                                            method="POST"
                                            data-confirm
                                            data-confirm-title="Update order status?"
                                            data-confirm-text="The customer order will be updated to the selected fulfillment status."
                                            data-confirm-button="Update status"
                                        >
                                            @csrf
                                            <select class="admin-select" name="status" aria-label="Status for order {{ $order->id }}">
                                                <option value="pending" @selected($statusValue === 'pending')>Pending</option>
                                                <option value="in progress" @selected($statusValue === 'in progress')>In progress</option>
                                                <option value="delivered" @selected($statusValue === 'delivered')>Delivered</option>
                                                <option value="canceled" @selected($statusValue === 'canceled')>Canceled</option>
                                            </select>
                                            <button type="submit" class="admin-button admin-button-primary admin-button-small">Save</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="actions-cell">
                                    <a href="{{ route('admin.downloadpdf', $order->id) }}" class="admin-icon-button" title="Download invoice" aria-label="Download invoice for order {{ $order->id }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5"/><path d="M5 21h14"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection