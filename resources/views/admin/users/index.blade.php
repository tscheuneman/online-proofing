@extends('layouts.admin.app')

@section('title', 'Customers')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Customers
    </h2>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Organization</th>
        </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->first_name . ' ' . $user->last_name}} </td>
                    <td>{{$user->email}} </td>
                    <td>{{$user->org}} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection