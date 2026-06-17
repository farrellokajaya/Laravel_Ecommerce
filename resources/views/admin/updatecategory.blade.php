@extends('admin.maindesign')

<base href="/public">
@section('update_category')

    @if(session('category_updated_message'))
        <div style="border: 1px solid blue; color: white; border-radius: 4px rounded; padding: 10
            px; background-color: blue; margin-bottom: 10px;">
                {{ session('category_updated_message') }}
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
        <form action="{{route('admin.postupdatecategory',$category->id)}}" method="POST">
            @csrf
            <input type="text" name="category" value="{{$category->category}}">
            <input type="submit" name="submit" value="Update Category">
        </form>
    </div>
@endsection