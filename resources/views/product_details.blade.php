@extends('maindesign')
<base href="/public">
<link rel="stylesheet" href="{{ asset('front_end/css/product-detail.css') }}">

@section('product_details')

    @if(session('cart_message'))
        <div style="border: 1px solid blue; color: white; border-radius: 4px rounded; padding: 10
            px; background-color: green; margin-bottom: 10px;">
                {{ session('cart_message') }}
        </div>
    @endif

<div class="product-container">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        Home / Products / {{ $product->product_title }}
    </div>

    {{-- Product Detail --}}
    <section class="product-detail">

        <div class="product-gallery">

            <div class="main-image">
                <img src="{{ asset('products/'.$product->product_image) }}" alt="">
            </div>

        </div>

        <div class="product-info">

            <h1>{{ $product->product_title }}</h1>

            <div class="price">
                ${{$product->product_prices}}
            </div>

            <div class="stock">
                Stock : {{ $product->product_quantity }}
            </div>

            <div class="short-description">
                {{ \Illuminate\Support\Str::limit(strip_tags($product->product_description),150) }}
            </div>

            <div class="quantity-box">

                <button type="button" onclick="decreaseQty()">
                    -
                </button>

                <input
                    type="number"
                    id="qty"
                    value="1"
                    min="1">

                <button type="button" onclick="increaseQty()">
                    +
                </button>

            </div>

            <div class="action-buttons">

                <a href="{{route('add_to_cart',$product->id )}}"class="btn-cart">
                    ADD TO CART
                </a>

                <a href="{{route('stripe',$product->product_prices )}}"class="btn-cart">
                    Buy Now
                </a>


            </div>

        </div>

    </section>

    {{-- Description --}}
    <section class="description-section">

        <h2>DESCRIPTION</h2>

        <div class="description-content">
            {!! $product->product_description !!}
        </div>

    </section>

</div>

<script src="{{ asset('front_end/js/product-detail.js') }}"></script>

@endsection