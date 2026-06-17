@extends('maindesign')

@section('index')

    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Latest Products
        </h2>
      </div>
      @if(session('cart_message'))
        <div style="margin-bottom: 10px; padding: 10px; background-color: lightgreen; color: black;">
          {{ session('cart_message') }}
        </div>
      @endif
      <div class="row">
        @foreach ($products as $product)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box">
            <a href="{{route('product_details',$product->id)}}">
              <div class="img-box" style="100px";>
                <img src="{{asset('products/'.$product->product_image)}}" alt="">
              </div>
              <div class="detail-box">
                <h6>
                  {{$product->product_title}}
                </h6>
                <h6>
                  Price
                  <span>
                    ${{$product->product_prices}}
                  </span>
                </h6>
              </div>
              <div class="new">
                <span>
                  New
                </span>
              </div>
            </a>
            <!-- add to cart -->
              <div style="display: flex; justify-content: space-between;">
                <form action="{{ route('add_to_cart', $product->id) }}" method="POST">
                  @csrf

                  <button type="submit" class="btn btn-primary">
                    Add To Cart
                  </button>
                </form>
              </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="btn-box">
        <a href="{{route('viewallproducts')}}">
          View All Products
        </a>
      </div>
    </div>

@endsection