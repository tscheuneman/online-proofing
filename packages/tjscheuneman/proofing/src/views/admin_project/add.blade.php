@extends('proof::layouts.normal')

@section('title', 'Create Inital Project')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a class="btn btn-brand-primary" href="{{url('admin')}}"><i class="fa fa-home"></i> Home</a>
            <a class="btn btn-brand-primary" href="{{url('admin/projects')}}"><i class="fa fa-pencil-square-o"></i> Projects</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card topSpacing">
                <div class="card-body">
                    <h2>
                        Create Revision for <span class="name_bg">{{$project->project_name}}</span>
                    </h2>
                    <br>
                    <form method="POST" action="{{ url('/proof/admin/project') }}" enctype="multipart/form-data" id="submit">
                        {{csrf_field()}}

                        <input type="hidden" name="project_id" value="{{$project->id}}">

                        <div class="form-group">
                            <label for="pdf">PDF</label>
                            <input type="file" class="form-control" id="pdf" name="pdf" required>
                        </div>

                        <div class="form-group">
                            <label for="comments">Comments (Use Markdown)</label>
                            <textarea name="comments" id="comments" class="form-control" cols="30" rows="10" placeholder="Comments Here"></textarea>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-submission">Submit</button>
                        <br>
                        @include('proof::layouts.errors')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection