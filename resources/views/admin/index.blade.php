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
                    {{$user_count}}
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
                    {{$user_count}}
                </span>
            </div>
        </div>
    </div>

    <br><br>
    <h2>Your Projects</h2>
    <hr>
    <div class="userProjects">
        @foreach($userProjects as $proj)
            <a class="projectLink" href="{{ url('/admin/project') }}/{{$proj->file_path}}">
                @if($proj->entries[0]->admin)
                    <div class="project">
                @else
                    <div class="project active">
                @endif
                    <span class="name">
                        {{$proj->project_name}}
                    </span>
                    <br>
                    <span class="lastAction">
                        Last Action:
                        <br>
                        {{date('Y-m-d g:ia', strtotime($proj->entries[0]->created_at))}}
                        <br>
                        {{$proj->entries[0]->user->first_name . ' ' . $proj->entries[0]->user->last_name}}
                    </span>
                </div>
            </a>
        @endforeach

        <br class="clear" />
    </div>
    <h3>
        Awaiting Customer Response
    </h3>
    <hr>
    <h3>
        Pending Premedia Changes
    </h3>
    <hr>
@endsection