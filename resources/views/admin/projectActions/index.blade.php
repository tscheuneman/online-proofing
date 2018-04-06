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
                        @if($entry->admin)
                            <div data-id="{{$entry->id}}" class="entryNav admin">
                        @else
                            <div data-id="{{$entry->id}}" class="entryNav">
                        @endif
                        @if($entry->user->picture == null)
                            <div class="navPic">
                                {{mb_substr($entry->user->first_name,0,1) . mb_substr($entry->user->last_name,0,1)}}
                            </div>
                        @endif
                        {{$entry->user->first_name . ' ' . $entry->user->last_name}}
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
                    </span>
                </div>
                <div class="card-body">
                    @foreach($project->entries as $enCnt => $entry)
                        @if($enCnt == 0)
                            <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
                        @else
                            <div data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                        @endif
                             <div class="counter">Page <span class="current"></span> of <span class="max"></span></div>
                            @foreach(json_decode($entry->files) as $key => $file)
                                @if($key == 0)
                                    <div data-num="{{$key}}" class="image active proj_{{$key++}}">
                                @else
                                    <div data-num="{{$key}}" class="image proj_{{$key++}}">
                                @endif
                                        <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file)}}" alt="">
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
    <script>
        $(document).ready(function() {
            updatePageCount();
            $('#next').on('click', function() {
                goNext();
            });
            $('#prev').on('click', function() {
                goPrev();
            });
            $('.entryNav').on('click', function() {
                let activeElm = $('div.entry.active').data('id');
                let thisElm = $(this).data('id');
                if(activeElm === thisElm) {
                    return false;
                }
                alert(activeElm);
            });
        });
        function goNext() {
            let currEntry = $('div.entry.active');
                let maxElm = currEntry.data('numelm');
            let currImg = $('div.image.active', currEntry);

            let currImgNum = currImg.data('num');
            let nextImg = currImgNum + 1;
                if(parseInt(nextImg) >= parseInt(maxElm)) {
                    nextImg = 0;
                }

            currImg.fadeOut(200, function() {
                $(this).removeClass('active');
                $('div.image.proj_' + nextImg, currEntry).fadeIn(200, function() {
                    $(this).addClass('active');
                    updatePageCount();
                });
            });
        }

        function goPrev() {
            let currEntry = $('div.entry.active');
            let maxElm = parseInt(currEntry.data('numelm')) - 1;
            let currImg = $('div.image.active', currEntry);

            let currImgNum = currImg.data('num');
            let nextImg = currImgNum - 1;
            if(parseInt(nextImg) < 0) {
                nextImg = maxElm;
            }

            currImg.fadeOut(200, function() {
                $(this).removeClass('active');
                $('div.image.proj_' + nextImg, currEntry).fadeIn(200, function() {
                    $(this).addClass('active');
                    updatePageCount();
                });
            });
        }

        function updatePageCount() {
            let currEntry = $('div.entry.active');
            let maxElm = parseInt(currEntry.data('numelm'));
            let currImg = $('div.image.active', currEntry);
            let currImgNum = parseInt(currImg.data('num')) + 1;

            $('div.counter span.current').html(currImgNum);
            $('div.counter span.max').html(maxElm);
        }
    </script>
@endsection