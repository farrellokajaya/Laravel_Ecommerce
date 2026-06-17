@extends('maindesign')

@section('viewcart_products')

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">

    @if(session('cart_message'))
        <div style="margin-bottom: 10px; padding: 10px; background-color: lightgreen; color: black;">
            {{ session('cart_message') }}
        </div>
    @endif

    @if(session('confirm_order'))
        <div style="border: 1px solid blue; color: white; border-radius: 4px; padding: 10px; background-color: green; margin-bottom: 10px;">
            {{ session('confirm_order') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product Title
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product Price
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Product Image
                </th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                    Action
                </th>
            </tr>
        </thead>

        <tbody>
            @php
                $price = 0;
            @endphp

            @forelse($cart as $cart_product)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px;">
                        {{ $cart_product->product->product_title }}
                    </td>

                    <td style="padding: 12px;">
                        Rp {{ number_format($cart_product->product->product_prices, 0, ',', '.') }}
                    </td>

                    <td style="padding: 12px;">
                        <img style="width: 150px;"
                             src="{{ asset('products/' . $cart_product->product->product_image) }}"
                             alt="{{ $cart_product->product->product_title }}">
                    </td>

                    <td style="padding: 12px;">
                        <form action="{{ route('removecartproducts', $cart_product->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to remove this product from cart?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                @php
                    $price += $cart_product->product->product_prices;
                @endphp
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center;">
                        Your cart is empty.
                    </td>
                </tr>
            @endforelse

            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 12px; font-weight: bold;">
                    Total Price
                </td>
                <td style="padding: 12px; font-weight: bold;">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    @if($cart->count() > 0)
        <form action="{{ route('confirm_order') }}" method="POST" style="margin-top: 20px;">
            @csrf

            <input type="text"
                   name="receiver_address"
                   placeholder="Enter Your Address"
                   required>

            <br><br>

            <input type="text"
                   name="receiver_phone"
                   placeholder="Enter Your Phone Number"
                   required>

            <br><br>

            <input class="btn btn-primary"
                   type="submit"
                   value="Confirm Order">

            <a href="{{ route('stripe', $price) }}"
               style="background: blue; color: white; padding: 12px; border-radius: 12px; text-decoration: none;">
                Pay Now
            </a>
        </form>
    @endif

</div>

@endsection