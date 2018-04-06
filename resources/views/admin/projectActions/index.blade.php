@extends('layouts.admin.app')

@section('title', $project->project_name)

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    {{$project}}
    <div class="row">
        <h1>
            {{$project->project_name}}
        </h1>
    </div>
    <br>
    @foreach($project->entries as $entry)
        <div class="entry">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Project Info
                        </div>
                        <div class="card-body">
                            <strong>Assigned Premedia</strong>

                            <br><br>
                            <strong>Assigned Customers</strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Images
                        </div>
                        <div class="card-body">
                            @foreach(json_decode($entry->files) as $key => $file)
                                <div class="image" id="proj_{{$key++}}">
                                    <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file)}}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection