@extends('layouts.admin.app')

@section('title', $project->project_name)

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    @if($project->completed)
        <div class="row">
            <h1>
                {{$project->project_name}} - <span class="approved">Approved</span>
            </h1>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($project->entries[0]->admin)
                    <div class="status waiting">
                        Waiting on user action
                    </div>
                @else
                    <div class="status looked">
                        Proof Ready to View!
                    </div>
                    <a class="btn btn-submission" href="{{url('admin/project/add') . '/' . $project->file_path}}"><i class="fa fa-plus"></i> Create Revision </a>
                    <br />
                    <br>
                @endif
            </div>
        </div>
        <div class="row">
            <h1>
                {{$project->project_name}}
            </h1>
        </div>
    @endif
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    Project Info
                    <div id="showInfo" class="show">
                        <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                        Show
                    </div>
                </div>
                <div class="card-body hidden project_info">
                    <strong>Assigned Premedia</strong>
                    <ul class="userList">
                        @foreach($project->order->admins as $admin)
                            <li>
                                @if($admin->admin->user->picture == null)
                                    <div class="navPic">
                                        {{mb_substr($admin->admin->user->first_name,0,1) . mb_substr($admin->admin->user->last_name,0,1)}}
                                    </div>
                                @endif
                                {{$admin->admin->user->first_name}}
                            </li>
                        @endforeach
                    </ul>
                    <strong>Assigned Customers</strong>
                    <ul class="userList">
                        @foreach($project->order->users as $user)
                            <li>
                                @if($user->user->picture == null)
                                    <div class="navPic">
                                        {{mb_substr($user->user->first_name,0,1) . mb_substr($user->user->last_name,0,1)}}
                                    </div>
                                @endif
                                {{$user->user->first_name}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Revisions
                    <div id="showProd" class="show">
                        <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                        Show
                    </div>
                </div>
                <div class="card-body hidden revisions">
                    @foreach($project->entries as $enCnt => $entry)
                        @if($enCnt++ == 0)
                            @if($project->completed)
                                @if(!empty($project->approval))
                                <div data-id="{{$project->approval->id}}" class="entryNav wide active approval">
                                        @if($project->approval->user->picture == null)
                                            <div class="navPic">
                                                {{mb_substr($project->approval->user->first_name,0,1) . mb_substr($project->approval->user->last_name,0,1)}}
                                            </div>
                                            {{$project->approval->user->first_name }} {{  date('Y-m-d g:ia', strtotime($project->approval->created_at)) }}
                                        @endif
                                    @endif
                                </div>
                            @endif
                        @endif
                        @if($enCnt++ == 0)
                            @if($entry->admin)
                                <div data-id="{{$entry->id}}" class="entryNav wide active admin">
                                    @else
                                        <div data-id="{{$entry->id}}" class="entryNav wide active">
                                            @endif
                                            @else
                                                @if($entry->admin)
                                                    <div data-id="{{$entry->id}}" class="entryNav wide admin">
                                                        @else
                                                            <div data-id="{{$entry->id}}" class="entryNav wide">
                                                                @endif
                                                                @endif
                                                                @if($entry->user->picture == null)
                                                                    <div class="navPic">
                                                                        {{mb_substr($entry->user->first_name,0,1) . mb_substr($entry->user->last_name,0,1)}}
                                                                    </div>
                                                                @endif
                                                                {{$entry->user->first_name }} {{  date('Y-m-d g:ia', strtotime($entry->created_at)) }}
                                                            </div>
                                                            @endforeach
                                                    </div>
                                        </div>
                                </div>
    </div>
                <br>
    <div class="row justify-content-center">
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
                            @if($project->completed && !empty($project->approval))
                                <div data-numelm="1" data-id="{{$project->approval->id}}" class="entry active" id="entry_{{$enCnt++}}">
                                    <div data-num="0" class="image active proj_0 submitted userFile">
                                        <p class="title">
                                            Approved by {{$project->approval->user->first_name . ' ' . $project->approval->user->last_name}}
                                        </p>
                                        <p class="date">
                                            on {{date('l, F, jS', strtotime($project->approval->created_at))}}
                                        </p>
                                        <br>
                                        <button data-addy="{{$project->entries[0]->pdf_path}}" class="getLink btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Download PDF</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if($entry->path != '0')
                            @if($enCnt == 0)
                                <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
                            @else
                                <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                            @endif
                                @foreach(json_decode($entry->files) as $key => $file)
                                    @if($key == 0)
                                        <div data-num="{{$key}}" class="image imageAdmin active proj_{{$key++}}" style="width:{{$file->width + 20}}; margin:0 auto;">
                                    @else
                                        <div data-num="{{$key}}" class="image imageAdmin proj_{{$key++}}" style="width:{{$file->width + 20}}; margin:0 auto;">
                                    @endif
                                            <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file->file)}}" alt="">
                                       @if(!$entry->admin)
                                       <br>
                                       <br>
                                      <h3>User Comments</h3>
                                      <p>{{json_decode($entry->user_notes)[$key - 1]}}</p>
                                       @endif

                                        </div>

                                @endforeach
                            </div>
                        @else
                            @if($enCnt == 0)
                                <div data-numelm="1" data-id="{{$entry->id}}" class="entry active submissionEntry" id="entry_{{$enCnt++}}">
                            @else
                                <div data-numelm="1" data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                            @endif
                                <div data-num="0" class="image active proj_0 submitted userFile">
                                    <p class="title">
                                        {{$entry->user->first_name . ' ' . $entry->user->last_name}} submitted {{count(json_decode($entry->files))}} file(s)
                                    </p>
                                    <p class="date">
                                        on {{date('l, F, jS', strtotime($entry->created_at))}}
                                    </p>
                                    <br>
                                    @foreach(json_decode($entry->files) as $key => $file)
                                        <button data-addy="{{$file->path}}" class="getLink btn btn-primary btn_{{$key++}}">{{$file->name}}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            Comments
                        </div>
                        <div class="card-body">
                            
                            @if(!$project->entries[0]->admin )
                                @if($project->entries[0]->path == '0')
                                    <div class="comments">
                                        {{$project->entries[0]->notes}}
                                    </div>
                                @else
                                    @foreach(json_decode($project->entries[0]->user_notes) as $key => $note)
                                        @if($note != '')
                                            <div data-num="{{$key}}" class="pageComment">
                                                <strong>Page {{$key + 1}}</strong>
                                                <br>
                                                {{$note}}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif

                        </div>
                    </div>

                </div>
    </div>
    <br>
    <br>
<script src="{{ asset('js/project.js') }}"></script>
@include('layouts/includes/scripts/viewProjScript')
   <script>
      $(document).ready(function() {
          $('.pageComment').on('click', function() {
              let thisVal = $(this).data('num');
              goToElementFromPageComment(thisVal);
          });
          $('.getLink').on('click', function() {
            let value = $(this).data('addy');
            getLinkValue(value);
          });
          $('#showProd').on('click', function() {
              $('.revisions').slideToggle(500);
          });
          $('#showInfo').on('click', function() {
              $('.project_info').slideToggle(500);
          });
      });
   </script>
@endsection