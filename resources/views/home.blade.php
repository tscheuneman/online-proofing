@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Pending Changes</div>
                <div class="card-body">
                    @foreach($projects as $project)
                        @if($project->admin_entries[0]->admin)
                            <a href="{{ url('/project') . '/' .  $project->file_path }}">
                                <div class="pendingProject">
                                    {{$project->project_name}}
                                    <br>
                                    <small>{{date('Y-m-d g:ia', strtotime($project->admin_entries[0]->created_at))}}</small>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Active Projects</div>

                <div class="card-body">
                    @foreach($projects as $project)
                            <a href="{{ url('/project') . '/' .  $project->file_path }}">
                                <div class="pendingProject">
                                    {{$project->project_name}}
                                    <br>
                                    <small>{{date('Y-m-d g:ia', strtotime($project->admin_entries[0]->created_at))}}</small>
                                </div>
                            </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
