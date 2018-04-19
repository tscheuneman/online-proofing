@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <div class="topStats">
        <div class="stat maroon">
            <div class="icon">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
            <div class="content">
                <span class="title">
                    Users
                </span>
                <span class="number">
                    {{$user_count}}
                </span>
            </div>
        </div>

        <div class="stat green">
            <div class="icon">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </div>
            <div class="content">
                <span class="title">
                    Projects
                </span>
                <span class="number">
                    {{$proj_count}}
                </span>
            </div>
        </div>

        <div class="stat blue">
            <div class="icon">
                <i class="fa fa-pause" aria-hidden="true"></i>
            </div>
            <div class="content">
                <span class="title">
                    User Pending
                </span>
                <span class="number">
                    {{$pending['userPending']}}
                </span>
            </div>
        </div>
        <div class="stat orange">
            <div class="icon">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            </div>
            <div class="content">
                <span class="title">
                    Us Pending
                </span>
                <span class="number">
                    {{$pending['adminPending']}}
                </span>
            </div>
        </div>
    </div>

    <br><br>
    <h2>Your Active Projects</h2>
    <hr>
    <div class="userProjects">
        @foreach($userProjects as $order)
            @include('admin.index.includes.userActiveProjects')
        @endforeach
    <br class="clear" />
    </div>
    <h3>
        Other Active Projects
    </h3>
    <hr>
    <div class="userProjects other">
        @foreach($otherProjects as $order)
            @include('admin.index.includes.userActiveProjects')
        @endforeach
        <br class="clear" />
    </div>
@endsection