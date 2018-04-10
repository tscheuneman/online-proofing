@extends('layouts.admin.app')

@section('title', $project->project_name)

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <div class="row">
        <h1>
            {{$project->project_name}}
        </h1>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    Project Info
                </div>
                <div class="card-body">
                    <strong>Assigned Premedia</strong>
                    <ul class="userList">
                        @foreach($project->admins as $admin)
                            <li>
                                @if($admin->admin->user->picture == null)
                                    <div class="navPic">
                                        {{mb_substr($admin->admin->user->first_name,0,1) . mb_substr($admin->admin->user->last_name,0,1)}}
                                    </div>
                                @endif
                                {{$admin->admin->user->first_name . ' ' . $admin->admin->user->last_name}}
                            </li>
                        @endforeach
                    </ul>
                    <strong>Assigned Customers</strong>
                    <ul class="userList">
                        @foreach($project->users as $user)
                            <li>
                                @if($user->user->picture == null)
                                    <div class="navPic">
                                        {{mb_substr($user->user->first_name,0,1) . mb_substr($user->user->last_name,0,1)}}
                                    </div>
                                @endif
                                {{$user->user->first_name . ' ' . $user->user->last_name}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card topSpacing">
                <div class="card-header">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Revisions
                </div>
                <div class="card-body">
                    @foreach($project->entries as $enCnt => $entry)
                        @if($enCnt++ == 0)
                            @if($entry->admin)
                                <div data-id="{{$entry->id}}" class="entryNav active admin">
                            @else
                                 <div data-id="{{$entry->id}}" class="entryNav active">
                            @endif
                        @else
                             @if($entry->admin)
                                <div data-id="{{$entry->id}}" class="entryNav admin">
                             @else
                                <div data-id="{{$entry->id}}" class="entryNav">
                             @endif
                        @endif
                        @if($entry->user->picture == null)
                            <div class="navPic">
                                {{mb_substr($entry->user->first_name,0,1) . mb_substr($entry->user->last_name,0,1)}}
                            </div>
                        @endif
                        {{$entry->user->first_name . ' ' . $entry->user->last_name . ' - ' . date('Y-m-d g:ia', strtotime($entry->created_at))}}
                         </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">

                    <button id="prev" class="btn btn-outline-secondary left"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</button>
                    <button id="next" class="btn btn-outline-secondary right"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Next</button>
                    <span style="text-align:center; display:block;">
                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                        Images
                        <div class="counter">Page <span class="current"></span> of <span class="max"></span></div>
                    </span>
                </div>
                <div class="card-body">
                    @foreach($project->entries as $enCnt => $entry)
                        @if($enCnt == 0)
                            <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
                        @else
                            <div data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                        @endif
                            @foreach(json_decode($entry->files) as $key => $file)
                                @if($key == 0)
                                    <div data-num="{{$key}}" class="image imageAdmin active proj_{{$key++}}" style="width:{{$file->width + 20}}; margin:0 auto;">
                                @else
                                    <div data-num="{{$key}}" class="image imageAdmin proj_{{$key++}}">
                                @endif
                                        <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file->file)}}" alt="">
                                    </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
<script src="{{ asset('js/project.js') }}"></script>
@include('layouts/includes/scripts/viewProjScript')
@endsection