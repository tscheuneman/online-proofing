@extends('layouts.admin.app')

@section('title', 'Project Categories')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Project Categories
    </h2>

    <a class="btn btn-main" href="/admin/categories/create"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</a>
    <br>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">slug</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cats as $cat)
            <tr>
                <td>{{$cat->name}}</td>
                <td>{{$cat->slug}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection