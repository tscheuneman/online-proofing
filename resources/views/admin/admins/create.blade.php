@extends('layouts.admin.app')

@section('title', 'Create Premedia Member')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Premedia Member
    </h2>
    <br>
    <form method="POST" action="{{ url('/admin/users') }}" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}


        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{Request::old('first_name')}}" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{Request::old('last_name')}}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{Request::old('email')}}" required>
        </div>

        <hr>

        <button type="submit" class="btn btn-submission">Submit</button>
        <br>
        @include('layouts.errors')

    </form>
@endsection