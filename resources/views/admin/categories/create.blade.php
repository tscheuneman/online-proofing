@extends('layouts.admin.app')

@section('title', 'Create Project Category')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Project Category
    </h2>
    <br>
    <form method="POST" action="{{ url('/admin/categories') }}" enctype="multipart/form-data" id="submit">
        {{csrf_field()}}


        <div class="form-group">
            <label for="name">Nane</label>
            <input type="text" class="form-control" id="name" name="name" value="{{Request::old('name')}}" required>
        </div>
        <hr>
        <button type="submit" class="btn btn-submission">Submit</button>
        <br>
        @include('layouts.errors')

    </form>
@endsection