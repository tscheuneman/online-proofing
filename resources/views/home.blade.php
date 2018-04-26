@extends('layouts.app')

@section('content')

<div class="container">
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    @if(isset($orders[0]))
            @include('main.index.includes.activeProjects')
    @else
        <h2 class="titleHeading">No Active Projects</h2>
    @endif

</div>
@endsection
