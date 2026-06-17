@extends('admin.maindesign')
<base href="/public">
@section('add_product')

    @if(session('product_message'))
        <div style="border: 1px solid blue; color: white; border-radius: 4px rounded; padding: 10
            px; background-color: green; margin-bottom: 10px;">
                {{ session('product_message') }}
        </div>
    @endif
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('admin.postupdateproduct',$product->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="product_title" value="{{$product->product_title}}"> <br> <br>
            <textarea name="product_description"> 
                {{$product->product_description}}"
            </textarea> <br> <br>
            <input type="numeber" name="product_quantity" value="{{$product->product_quantity}}"> <br> <br>
            <input type="numeber" name="product_prices" value="{{$product->product_prices}}"> <br> <br>
            <img  style="width: 100px;"src="{{asset('products/'.$product->product_image)}}"><label>Old Image</label>
            <input type="file" name="product_image"><label>Add new image here!</label> <br> <br>
            <select name="product_category">
                <option value="{{$product->product_category}}">
                    {{$product->product_category}}
                </option>
                @foreach($categories as $category)
                    <option value="{{$category->category}}">{{$category->category}}</option>
                @endforeach
            </select><label> Select A Category</label> <br> <br>
            <input type="submit" name="submit" value="Add Product"> <br> <br>
        </form>
    </div>
@endsection