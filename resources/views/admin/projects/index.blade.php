@extends('layouts.admin.app')

@section('title', 'Projects')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Projects
    </h2>
    <a class="btn btn-main" href="/admin/orders/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Order</a>
    <a class="btn btn-second" href="/admin/categories/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Category</a>
    <br class="clear" />
    <br />
    @foreach($orders as $order)
        @include('admin.index.includes.userActiveProjects')
    @endforeach
@endsection