@extends('maindesign')

@section('title', 'Payment Successful — Giftos')

@section('content')
<section class="page-section soft">
    <div class="container">
        <div class="payment-success-card">
            <div class="payment-success-icon">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="m8 12 2.7 2.7L16.5 9"/>
                </svg>
            </div>

            <span class="eyebrow">
                Payment confirmed
            </span>

            <h1>Thank you for your order</h1>

            <p class="payment-success-copy">
                Your payment was successful. We have received
                your order and will begin processing it.
            </p>

            <div class="payment-success-meta">
                <div>
                    <span>Invoice number</span>
                    <strong>{{ $invoiceNumber }}</strong>
                </div>

                <div>
                    <span>Payment status</span>
                    <strong class="success-text">Paid</strong>
                </div>

                <div>
                    <span>Order date</span>
                    <strong>
                        {{ $firstOrder->created_at->format('d M Y, H:i') }}
                    </strong>
                </div>

                <div>
                    <span>Total payment</span>
                    <strong>
                        ${{ number_format($total, 2, '.', ',') }}
                    </strong>
                </div>
            </div>

            <div class="payment-success-items">
                @foreach($orders as $order)
                    <div class="payment-success-item">
                        <div>
                            <strong>
                                {{ $order->product->product_title
                                    ?? 'Product unavailable' }}
                            </strong>

                            <span>
                                Quantity {{ $order->quantity }}
                                ×
                                ${{ number_format(
                                    $order->unit_price,
                                    2,
                                    '.',
                                    ','
                                ) }}
                            </span>
                        </div>

                        <strong>
                            ${{ number_format(
                                $order->total_price,
                                2,
                                '.',
                                ','
                            ) }}
                        </strong>
                    </div>
                @endforeach
            </div>

            <div class="payment-success-actions">
                <a
                    href="{{ route(
                        'invoice.download',
                        $invoiceNumber
                    ) }}"
                    class="button button-dark"
                >
                    Download Invoice
                </a>

                <a
                    href="{{ route('myorders') }}"
                    class="button button-light"
                >
                    View My Orders
                </a>

                <a
                    href="{{ route('home') }}"
                    class="button button-light"
                >
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection