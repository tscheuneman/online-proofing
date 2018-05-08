@extends('layouts.admin.app')

@section('title', 'Create Specification')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Create Specification
    </h2>
    <br>
    <div id="mask">
    </div>
    <form method="POST" action="{{ url('/admin/specifications') }}" enctype="multipart/form-data" id="submit" autocomplete="nope">
        {{csrf_field()}}

        <div class="form-group">
            <label for="spec_name">Specification Name</label>
            <input type="text" class="form-control" id="spec_name" name="spec_name" value="{{Request::old('spec_name')}}" required>
        </div>


        <div class="form-group">
            <label for="content_type">Content Type</label>
            <select class="form-control" name="content_type" id="content_type">
                <option value="">----SELECT----</option>
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="textarea">Text Area</option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_value">Default Value</label>
            <input type="text" class="form-control" id="default_value" name="default_value" value="{{Request::old('default_value')}}">
        </div>

        <button id="submit" type="submit" class="btn btn-submission">
            Submit
        </button>

        <br>
        @include('layouts.errors')
    </form>


@endsection