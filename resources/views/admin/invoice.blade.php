<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 36px;
            color: #1b1b1a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.55;
        }
        .header {
            width: 100%;
            margin-bottom: 34px;
            border-bottom: 1px solid #deded9;
            padding-bottom: 22px;
        }
        .brand {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: -1px;
        }
        .brand span {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-right: 9px;
            border-radius: 8px;
            background: #171716;
            color: #fff;
            line-height: 34px;
            text-align: center;
        }
        .invoice-meta {
            float: right;
            margin-top: -38px;
            text-align: right;
        }
        .invoice-meta strong {
            display: block;
            font-size: 17px;
        }
        .muted { color: #74746f; }
        .section-table {
            width: 100%;
            margin-bottom: 28px;
            border-collapse: collapse;
        }
        .section-table td {
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            margin-bottom: 8px;
            color: #777772;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .detail-block strong {
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
        }
        .items th {
            padding: 10px 8px;
            border-top: 1px solid #deded9;
            border-bottom: 1px solid #deded9;
            background: #f7f7f5;
            color: #666660;
            font-size: 9px;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
        }
        .items td {
            padding: 14px 8px;
            border-bottom: 1px solid #e8e8e4;
            vertical-align: top;
        }
        .items .right { text-align: right; }
        .total-box {
            width: 280px;
            margin-top: 24px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-box td {
            padding: 8px 0;
        }
        .total-box .grand-total td {
            padding-top: 12px;
            border-top: 1px solid #1b1b1a;
            font-size: 15px;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            background: #e9f5ef;
            color: #237a57;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 54px;
            padding-top: 16px;
            border-top: 1px solid #deded9;
            color: #777772;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $quantity = (int) ($data->quantity ?? 1);
        $unitPrice = (float) (($data->unit_price ?? 0) > 0
            ? $data->unit_price
            : ($data->product?->product_prices ?? 0));
        $lineTotal = (float) (($data->total_price ?? 0) > 0
            ? $data->total_price
            : ($unitPrice * $quantity));
    @endphp

    <div class="header">
        <div class="brand"><span>G</span>Giftos</div>
        <div class="invoice-meta">
            <strong>INVOICE #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</strong>
            <span class="muted">{{ $data->created_at?->format('F d, Y') }}</span>
        </div>
    </div>

    <table class="section-table">
        <tr>
            <td>
                <div class="section-title">Billed to</div>
                <div class="detail-block">
                    <strong>{{ $data->receiver_name ?: ($data->user?->name ?? 'Customer') }}</strong>
                    <div>{{ $data->receiver_address }}</div>
                    <div>{{ $data->receiver_phone }}</div>
                    @if($data->user?->email)
                        <div>{{ $data->user->email }}</div>
                    @endif
                </div>
            </td>
            <td>
                <div class="section-title">Payment details</div>
                <div class="detail-block">
                    <strong><span class="status">{{ $data->payment_status ?? 'Paid' }}</span></strong>
                    <div>Order status: {{ ucfirst($data->status ?? 'pending') }}</div>
                    @if($data->stripe_payment_id)
                        <div>Payment ID: {{ $data->stripe_payment_id }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th class="right">Unit price</th>
                <th class="right">Quantity</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $data->product?->product_title ?? 'Product unavailable' }}</strong></td>
                <td>{{ $data->product?->product_category ?? '—' }}</td>
                <td class="right">${{ number_format($unitPrice, 2, '.', ',') }}</td>
                <td class="right">{{ $quantity }}</td>
                <td class="right"><strong>${{ number_format($lineTotal, 2, '.', ',') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="total-box">
        <tr>
            <td class="muted">Subtotal</td>
            <td style="text-align: right;">${{ number_format($lineTotal, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td class="muted">Shipping</td>
            <td style="text-align: right;">Free</td>
        </tr>
        <tr class="grand-total">
            <td>Total</td>
            <td style="text-align: right;">${{ number_format($lineTotal, 2, '.', ',') }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with Giftos. This invoice was generated automatically for your order.
    </div>
</body>
</html>
