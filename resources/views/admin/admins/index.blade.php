@extends('layouts.admin.app')

@section('title', 'Premedia Team')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Premedia Team
    </h2>
    <a class="btn btn-main" href="/admin/users/create"><i class="fa fa-plus" aria-hidden="true"></i> Add Premedia Member</a>
    <br>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
        </tr>
        </thead>
        <tbody>
        @foreach($admins as $user)
            <tr>
                <td>{{$user->user->first_name . ' ' . $user->user->last_name}} </td>
                <td>{{$user->user->email}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection