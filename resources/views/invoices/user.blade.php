<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>{{ $invoiceNumber }}</title>

    <style>
        body {
            margin: 0;
            color: #222;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #111;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
        }

        .invoice-title {
            float: right;
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 22px;
        }

        .invoice-title p {
            margin: 5px 0 0;
            color: #666;
        }

        .clearfix {
            clear: both;
        }

        .information {
            width: 100%;
            margin-bottom: 28px;
        }

        .information td {
            width: 50%;
            vertical-align: top;
        }

        .information h3 {
            margin: 0 0 8px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .information p {
            margin: 3px 0;
            color: #555;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th {
            padding: 11px;
            color: white;
            text-align: left;
            background: #111;
        }

        .items td {
            padding: 11px;
            border-bottom: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .total-table {
            width: 42%;
            margin-top: 20px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .total-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
        }

        .paid {
            color: #16855b;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            padding-top: 18px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="header">
        <span class="brand">Giftos</span>

        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>{{ $invoiceNumber }}</p>
        </div>

        <div class="clearfix"></div>
    </div>

    <table class="information">
        <tr>
            <td>
                <h3>Billed to</h3>

                <p>
                    {{ $firstOrder->receiver_name
                        ?: ($firstOrder->user->name ?? '') }}
                </p>

                <p>{{ $firstOrder->receiver_phone }}</p>
                <p>{{ $firstOrder->receiver_address }}</p>
            </td>

            <td class="text-right">
                <h3>Invoice information</h3>

                <p>
                    Date:
                    {{ $firstOrder->created_at->format('d M Y, H:i') }}
                </p>

                <p>
                    Payment:
                    <span class="paid">PAID</span>
                </p>

                <p>
                    Order status:
                    {{ ucfirst($firstOrder->status) }}
                </p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>
                        {{ $order->product->product_title
                            ?? 'Product unavailable' }}
                    </td>

                    <td class="text-right">
                        ${{ number_format(
                            $order->unit_price,
                            2,
                            '.',
                            ','
                        ) }}
                    </td>

                    <td class="text-right">
                        {{ $order->quantity }}
                    </td>

                    <td class="text-right">
                        ${{ number_format(
                            $order->total_price,
                            2,
                            '.',
                            ','
                        ) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-table">
        <tr class="grand-total">
            <td>Total</td>

            <td class="text-right">
                ${{ number_format($total, 2, '.', ',') }}
            </td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with Giftos.
        This invoice was generated automatically after successful payment.
    </div>
</body>
</html>