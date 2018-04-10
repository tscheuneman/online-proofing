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
    <a class="btn btn-main" href="/admin/projects/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Project</a>
    <a class="btn btn-second" href="/admin/categories/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Category</a>
    <br class="clear" />
    <br />
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Project Name</th>
            <th scope="col">Categories</th>
            <th scope="col">Assigned Customers</th>
            <th scope="col">Assigned PreMedia</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @php
            $class = '';
        @endphp
        @foreach($projects as $proj)
            @foreach($proj->admins as $admin)
                @if($admin->admin->user_id == Auth::id())
                    @php
                        $class = 'belongs';
                    @endphp
                    @break
                 @endif
         @endforeach
            <tr class="{{$class}}">
                <td>{{$proj->project_name}}</td>
                <td>{{$proj->category->name}}</td>
                <td>

                    @foreach($proj->users as $key => $user)
                        <strong>
                            @if($key++ > 0)
                                ,
                            @endif
                            {{$user->user->first_name . ' ' . $user->user->last_name}}
                        </strong>
                    @endforeach
                </td>
                <td>
                    @foreach($proj->admins as $key => $admin)
                        <strong> {{$admin->admin->userSearch->first_name . ' ' . $admin->admin->userSearch->last_name}} </strong>
                        @if($key++ > 1)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
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

                </td>
                <td>
                    <a class="btn btn-dark" href="/admin/project/{{$proj->file_path}}"><i class="fa fa-arrow-right" aria-hidden="true"></i> Go to project</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection