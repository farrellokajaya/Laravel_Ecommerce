@extends('admin.maindesign')

@section('view_orders')
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Customer Name
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Address
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Phone Number
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product Price
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product Image
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Status
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    PDF
                </th>
            </tr>
        </thead>
    <tbody>
        @foreach($orders as $order)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px;">{{$order->user->name}}</td>
            <td style="padding: 12px;">{{$order->receiver_address}}</td>
            <td style="padding: 12px;">{{$order->receiver_phone}}</td>
            <td style="padding: 12px;">{{$order->product->product_title}}</td>
            <td style="padding: 12px;">{{$order->product->product_prices}}</td>
            <td style="padding: 12px;">
                <img style="width: 150px"; src="{{asset('products/'.$order->product->product_image)}}">
            </td>
            <td style="padding: 12px;">
                <form action="{{route('admin.change_status', $order->id)}}" method="post">
                    @csrf
                    <select name="status" id="">
                        <option value="{{$order->status}}">{{$order->status}}</option>
                        <option value="Delivered">Delivered</option>
                        <option value="pending">pending</option>
                    </select>
                    <input type="submit" name="submit" value="submit" onclick="return confirm('Are You Sure?')">
                </form>
            </td>
            <td style="border-bottom: 1px solid #ddd;">
                <a href="{{route('admin.downloadpdf', $order->id)}}" class="btn btn-primary">Download PDF</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection