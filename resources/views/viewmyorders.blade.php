@extends('maindesign')

@section('title', 'My Orders — Giftos')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Order history</span>
                <h1>My orders</h1>
                <p>Track every successfully paid order and its current fulfilment status.</p>
            </div>
            <a class="button button-light button-small" href="{{ route('viewallproducts') }}">Shop again</a>
        </div>

        @if($orders->isNotEmpty())
            <div class="order-list">
                @foreach($orders as $order)
                    @php
                        $status = strtolower($order->status ?? 'pending');
                        $paymentStatus = strtolower($order->payment_status ?? 'paid');
                        $quantity = (int) ($order->quantity ?? 1);
                        $unitPrice = (float) (($order->unit_price ?? 0) > 0 ? $order->unit_price : ($order->product->product_prices ?? 0));
                        $lineTotal = (float) (($order->total_price ?? 0) > 0 ? $order->total_price : $unitPrice * $quantity);
                    @endphp
                    <article class="order-card">
                        <div class="order-image">
                            @if($order->product)
                                <img src="/products/{{ $order->product->product_image }}" alt="{{ $order->product->product_title }}">
                            @endif
                        </div>

                        <div class="order-main">
                            <span class="product-category">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $order->product->product_title ?? 'Product unavailable' }}</h3>
                            <p>Quantity: {{ $quantity }} × ${{ number_format($unitPrice, 2, '.', ',') }}</p>
                            <div class="order-price">${{ number_format($lineTotal, 2, '.', ',') }}</div>
                        </div>

                        <div class="order-shipping">
                            <strong>Delivery details</strong>
                            <p>{{ $order->receiver_name ?: ($order->user->name ?? '') }}</p>
                            <p>{{ $order->receiver_phone }}</p>
                            <p>{{ $order->receiver_address }}</p>
                        </div>

                        <div class="order-status">
                            <span class="status-badge status-{{ $status }}">{{ $order->status ?? 'Pending' }}</span>
                            <span class="status-badge status-{{ $paymentStatus }}">Payment {{ $order->payment_status ?? 'Paid' }}</span>
                            <span class="order-date">{{ optional($order->created_at)->format('d M Y, H:i') }}</span>
                            @if(
                                $order->payment_status === 'paid'
                                && $order->invoice_number
                            )
                                <a
                                    href="{{ route(
                                        'invoice.download',
                                        $order->invoice_number
                                    ) }}"
                                    class="button button-light button-small"
                                >
                                    Download Invoice
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 3h12v18H6z"/><path d="M9 7h6M9 11h6M9 15h4"/></svg>
                </div>
                <h2>No orders yet</h2>
                <p>Your successfully paid orders will appear here.</p>
                <a class="button button-dark" href="{{ route('viewallproducts') }}">Start shopping</a>
            </div>
        @endif
    </div>
</section>
@endsection
