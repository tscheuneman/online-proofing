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
        @foreach($order->admins as $admin)
            @php
                $class = '';
            @endphp
            @if($admin->admin->user_id == Auth::id())
                @php
                    $class = 'belongs';
                @endphp
                @break
            @endif
        @endforeach
        <div class="order {{$class}}">
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
                            <strong>
                                @if(isset($proj->admin_entries[0]))
                                    @if(!$proj->admin_entries[0]->active)
                                        Waiting on Output
                                    @else
                                        @if($proj->completed)
                                            <strong>Approved</strong>
                                        @else
                                            @if($proj->admin_entries[0]->admin)
                                                Awaiting User Response
                                            @else
                                                <strong> Awaiting Premedia Response </strong>
                                            @endif
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
    @endforeach
@endsection