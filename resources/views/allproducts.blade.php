@extends('maindesign')

@section('all_products')

@extends('maindesign')

    <div class="container">
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
                <a href="{{route('add_to_cart',$product->id )}}" style="background:blue; color: white; padding: 12px; border-radius: 12px;">Add To Cart</a>
                <a href="{{route('stripe',$product->product_prices)}}" style="background:blue; color: white; padding: 12px; border-radius: 12px;">Pay Now</a>
              </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

@endsection