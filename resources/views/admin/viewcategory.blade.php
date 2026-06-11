@extends('admin.maindesign')


@section('view_category')

@if(@session('deletecategory_message'))
    <div style="margin-bottom: 10px; color: black; background-color: orangered;">
        {{session('deletecategory_message')}}
    </div>
@endif
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Category ID
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Category Name
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Action
            </th>
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                Edit
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px;">{{$category->id}}</td>
            <td style="padding: 12px;">{{$category->category}}</td>
            <td style="padding: 12px;">
                <a href="{{route('admin.categoryupdate', $category->id)}}" style="color: green">Update</a> 
            </td>
            <td style="padding: 12px;">
                <a href="{{route('admin.categorydelete', $category->id)}}" onclick="return confirm('Are You Sure?')">Delete</a> 
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection