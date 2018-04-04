@extends('layouts.admin.normal')

@section('title', 'Set Password')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Hi {{$user->first_name}}, set your password
    </h2>
    <br>
    <form method="POST" action="{{ url('/password') }}" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}

        <input type="hidden" name="user_id" value="{{$user->id}}">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <hr>

        <button type="submit" class="btn btn-submission">Submit</button>
        <br>
        @include('layouts.errors')

    </form>
@endsection