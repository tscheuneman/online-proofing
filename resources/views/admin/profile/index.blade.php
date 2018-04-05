@extends('layouts.admin.app')

@section('title', 'User Profile')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <div class="picture">
    @if($admin->picture == null)
        {{mb_substr($admin->first_name,0,1) . mb_substr($admin->last_name,0,1)}}
    @else
    @endif
    </div>
    <div class="titleElement">
        <div class="title">
            {{$admin->first_name . ' ' . $admin->last_name}}
        </div>
    </div>
    <br class="clear" />

    <div class="profileContent">
        Test
    </div>

    <br class="clear"><br><br><br>
    {{$admin}}
@endsection