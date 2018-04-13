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
        @foreach($userProjects as $order)
            @if(isset($order->projects[0]->id))
                <div class="order belongs">
                    <p class="title">{{$order->job_id}}</p>
                    @if($order->notify_users)
                        <span class="option">Notify Users</span>
                    @endif
                    @if($order->notify_admins)
                        <span class="option">Notify Users</span>
                    @endif
                    @if(!$order->hidden)
                        <span class="option">Public</span>
                    @endif
                    <br class="clear" />
                    <br>
                    @foreach($order->projects as $proj)
                        <a href="{{ url('/admin/project') . '/' . $proj->file_path }}">
                            <div class="project">
                                <p class="projectTitle">{{$proj->project_name}}</p>
                                <p class="statusText">
                                    Status:
                                    <strong>
                                        @if(isset($proj->admin_entries[0]))
                                            @if(!$proj->admin_entries[0]->active)
                                                Waiting on Output
                                            @else
                                                @if($proj->admin_entries[0]->admin)
                                                    Awaiting User Response
                                                @else
                                                    <strong> Awaiting Premedia Response </strong>
                                                @endif
                                            @endif
                                        @else
                                            @if(!$proj->active)
                                                Awaiting Initial Upload
                                            @endif
                                        @endif
                                    </strong>
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
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