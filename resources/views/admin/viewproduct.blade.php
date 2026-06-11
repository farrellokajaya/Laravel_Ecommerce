@extends('admin.maindesign')

<base href="/public">
@section('view_category')

@if(@session('deletecategory_message'))
    <div style="margin-bottom: 10px; color: black; background-color: orangered;">
        {{session('deletecategory_message')}}
    </div>
@endif

@if(@session('deleteproduct_message'))
    <div style="margin-bottom: 10px; color: black; background-color: orangered;">
        {{session('deleteproduct_message')}}
    </div>
@endif

 <div class="list-inline-item">
    <form action="{{route('admin.searchproduct')}}" method="post">
        @csrf
        <div class="form-group">
            <input type="search" name="search" placeholder="What are you searching for...">
            <button type="submit" class="submit">Search</button>
        </div> 
    </form>
</div> 
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Ttitle
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Description
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Quantity
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Prices
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Image
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Product Category
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px;">{{$product->product_title}}</td>
            <td style="padding: 12px;">{{Str::limit($product->product_description,50,'....')}}</td>
            <td style="padding: 12px;">{{$product->product_quantity}}</td>
            <td style="padding: 12px;">{{$product->product_prices}}</td>
            <td style="padding: 12px;">
                <img style="width: 150px"; src="{{asset('products/'.$product->product_image)}}">
            </td>
            <td style="padding: 12px;">{{$product->product_category}}</td>
            <td style="padding: 12px;">
                <a href="{{route('admin.updateproduct',$product->id)}}" style="color: green">Update</a> 
                <a href="{{route('admin.deleteproduct',$product->id)}}" onclick="return confirm('Are You Sure?')">Delete</a> 
            </td>
        </tr>
        @endforeach

        {{$products->links()}}
    </tbody>
</table>

@endsection