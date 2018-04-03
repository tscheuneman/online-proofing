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
            <th scope="col">Path</th>
            <th scope="col">Assigned Customers</th>
            <th scope="col">Assigned PreMedia</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $proj)
            <tr>
                <td>{{$proj->project_name}}</td>
                <td>{{$proj->category->name}}</td>
                <td>{{$proj->file_path}}</td>
                <td>
                    @foreach($proj->users as $user)
                        <strong> {{$user->user->first_name . ' ' . $user->user->last_name}}, </strong>
                    @endforeach
                </td>
                <td>
                    @foreach($proj->admins as $admin)
                        <strong> {{$admin->admin->userSearch->first_name . ' ' . $admin->admin->userSearch->last_name}}, </strong>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection