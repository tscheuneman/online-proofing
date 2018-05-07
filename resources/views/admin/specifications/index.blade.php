@extends('layouts.admin.app')

@section('title', 'Specifications')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Specifications
    </h2>
    <a class="btn btn-main" href="/admin/specifications/schema/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Specification Schema</a>
    <a class="btn btn-primary" href="/admin/specifications/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Specification</a>
    <br class="clear" />
    <br />

@endsection