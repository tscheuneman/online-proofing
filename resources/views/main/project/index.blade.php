@extends('layouts.app')

@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>

    <div id="mask">
    </div>

    @if($project->entries[0]->admin)
        <div id="customerFiles">
            <div id="closeFiles"><i class="fa fa-times"></i></div>
            <h4>
                Upload Files
            </h4>
            <hr>
            <form enctype="multipart/form-data" action="{{url('/user/files')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <input class="form-control" type="file" id="files" name="files[]" multiple required />
                <br />
                <textarea name="comments" class="form-control" id="" cols="30" rows="10"></textarea>
                <br>
                <button id="submitFileUpload" class="btn btn-secondary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
            </form>

        </div>
    @endif



    @if($project->entries[0]->admin)
        <div class="comment" id="comment">
            <i class="fa fa-commenting-o" aria-hidden="true"></i> Comment Image
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($project->entries[0]->admin)
                    @if(!$project->completed)
                        <div class="status waiting">
                            Waiting on approval
                        </div>
                    @else
                        <div class="status looked">
                            <strong>Project has been approved!</strong>
                        </div>
                    @endif
                @else
                    <div class="status looked">
                        Your revision is being reviewed by our premedia team!
                    </div>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        Project Info
                    </div>
                    <div class="card-body">
                        <strong>Assigned Premedia</strong>
                        <ul class="userList">
                            @foreach($project->order->admins as $admin)
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
                            @foreach($project->order->users as $user)
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
                                @if($project->completed)
                                    <div data-id="{{$entry->id}}" class="entryNav active approval">
                                        @if(!empty($project->approval))
                                            @if($project->approval->user->picture == null)
                                                Approval
                                               <br> {{  date('Y-m-d g:ia', strtotime($project->approval->created_at)) }}
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endif
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
                                {{$entry->user->first_name . ' ' . $entry->user->last_name}}
                                    <br>
                                    <small>{{date('Y-m-d g:ia', strtotime($entry->created_at))}}</small>
                                 </div>
                        @endforeach
                                </div>
                    </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <button id="prev" class="btn btn-outline-secondary left"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</button>
                        <button id="next" class="btn btn-outline-secondary right"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Next</button>
                        <span style="text-align:center; display:block;">
                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                        To Proof
                            <div class="counter">Page <span class="current"></span> of <span class="max"></span></div>
                        </span>

                    </div>

                    <div class="card-body">
                        @foreach($project->entries as $enCnt => $entry)
                            @if($entry->path != '0')
                                @if($enCnt == 0)
                                    <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active submissionEntry" id="entry_{{$enCnt++}}">
                                @else
                                    <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                                @endif

                                @foreach(json_decode($entry->files) as $key => $file)
                                    @if($project->entries[0]->admin && !$project->completed)
                                        @if($key == 0)
                                            @if($entry->admin)
                                                <div data-num="{{$key}}" class="image active proj_{{$key++}}" style="width:{{$file->width + 61}}px; height:{{$file->height + 31}}px; margin:0 auto;">
                                            @else
                                                <div data-num="{{$key}}" class="image active proj_{{$key++}}" style="width:{{$file->width + 61}}px; height:{{$file->height + 31}}px; margin:0 auto;">
                                            @endif
                                        @else
                                             <div data-num="{{$key}}" class="image proj_{{$key++}}" style="width:{{$file->width + 20}}px; margin:0 auto;">
                                        @endif
                                    @else
                                        @if($key == 0)
                                            @if($entry->admin)
                                                <div data-num="{{$key}}" class="image imageAdmin active proj_{{$key++}}" style="width:{{$file->width + 20}}px; margin:0 auto;">
                                            @else
                                                <div data-num="{{$key}}" class="image imageAdmin active proj_{{$key++}}" style="width:{{$file->width + 20}}px; margin:0 auto;">
                                            @endif
                                        @else
                                            <div data-num="{{$key}}" class="image imageAdmin proj_{{$key++}}" style="width:{{$file->width + 20}}px; margin:0 auto;">
                                        @endif
                                    @endif
                                    @if($enCnt == 1)
                                        @if($entry->admin && !$project->completed)
                                            <div class="textboxHolder">
                                                <span class="closeText">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </span>
                                                <textarea class="form-control" id="" cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="convasContainer" data-id="{{$key - 1}}" id="canvas_{{$key - 1}}" style="height:{{$file->height}}px;">

                                            </div>
                                        @else
                                            <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file->file)}}" alt="">
                                        @endif
                                    @else
                                        <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file->file)}}" alt="">
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
                                            @if(Auth::id() == $entry->user->id)
                                                You submitted {{count(json_decode($entry->files))}} file(s)
                                            @else
                                                {{$entry->user->first_name . ' ' . $entry->user->last_name}} submitted {{count(json_decode($entry->files))}} file(s)
                                            @endif
                                        </p>
                                        <p class="date">
                                            on {{date('l, F, jS', strtotime($entry->created_at))}}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
      <br><br><br><br>

    @if($project->entries[0]->admin && !$project->completed)
        <footer>
            <div class="container">
                <button id="submit" class="btn btn-primary">
                    <i class="fa fa-share-square-o" aria-hidden="true"></i>
                    Submit Revisions
                </button>
                &nbsp;&nbsp;&nbsp;
                |
                &nbsp;&nbsp;&nbsp;
                <button id="new" class="btn btn-secondary">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    Upload new Files
                </button>
                &nbsp;&nbsp;&nbsp;
                |
                &nbsp;&nbsp;&nbsp;
                <button id="approve" class="btn btn-submission">
                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                    Approve
                </button>
            </div>
        </footer>
    @endif

@include('layouts/includes/scripts/viewProjScript')
  <script>
    let returnValues = {};
    window.items = [];
    window.bounds = [];
    @if($project->entries[0]->admin && !$project->completed)
    returnValues['linkAddy'] = "{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $project->entries[0]->path . '/images/')}}";
    returnValues['data'] = {!! $project->entries[0]->files !!};
    @else
        returnValues = false;
    @endif
    $(document).ready(function() {
        populateCanvas(returnValues);
        $('div#comment').on('click', function() {
            let currEntry = $('div.entry.submissionEntry');
            let currImg = $('div.image.active', currEntry);
            $('#mask').fadeIn(500, function() {
                $('.textboxHolder', currImg).slideToggle(500);
            });

        });

        $('span.closeText').on('click', function() {
            let thisElm = $(this).parent();
            $(thisElm).slideToggle(500, function() {
                $('#mask').fadeOut(500);
            });
        });

        $('button#submit').on('click', function() {
            let images = [];
            let canvas = null;
            let comments = null;
            let activeElm = null;
            let x = 0;
            window.items.forEach(function() {
                let thisElm = {};
                canvas = window.items[x].getImage({rect: window.bounds[x]}).toDataURL();
                activeElm = $('div.submissionEntry .proj_'+x);
                comments = $('textarea', activeElm).val();
                thisElm['data'] = canvas;
                thisElm['comments'] = comments;
                images.push(thisElm);
                x++;
            });
            submitRevision(images, '{{$project->id}}');
        });

        $('button#submitFileUpload').on('click', function() {
            $('#loader').fadeIn(200);
        });

        $('#closeFiles').on('click', function() {
            $('#customerFiles').fadeOut(250, function() {
                $('#mask').fadeOut(250);
            });
        });

        $('button#approve').on('click', function() {
            let projectID = '{{$project->id}}';

            approveRevision(projectID);
        });

        $('button#new').on('click', function() {
            $('#mask').fadeIn(250, function() {
                $('#customerFiles').stop().fadeIn(250);
            })
        });

    });

  </script>
@endsection
