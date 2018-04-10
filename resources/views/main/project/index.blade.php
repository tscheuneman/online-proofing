@extends('layouts.app')

@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    <div id="mask"></div>
    <div class="comment" id="comment">
        <i class="fa fa-commenting-o" aria-hidden="true"></i> Comment Image
    </div>
    <div class="container">
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
                            @if($enCnt == 0)
                                <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active submissionEntry" id="entry_{{$enCnt++}}">
                            @else
                                <div data-id="{{$entry->id}}" class="entry" id="entry_{{$enCnt++}}">
                            @endif

                            @foreach(json_decode($entry->files) as $key => $file)
                                @if($key == 0)
                                    <div data-num="{{$key}}" class="image active proj_{{$key++}}">
                                @else
                                     <div data-num="{{$key}}" class="image proj_{{$key++}}">
                                @endif
                                @if($enCnt == 1)
                                    <div class="textboxHolder">
                                        <span class="closeText">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </span>
                                        <textarea class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                @endif
                                 <div class="convasContainer" data-id="{{$key - 1}}" id="canvas_{{$key - 1}}" style="height:{{$file->height}}px;">

                                 </div>
                              </div>
                            @endforeach
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
      <br><br><br><br>
    <footer>
        <div class="container">
            <button id="submit" class="btn btn-primary">
                Submit
            </button>
        </div>
    </footer>

@include('layouts/includes/scripts/viewProjScript')
  <script>
    let entryValues = [];
    let holder = null;
    let returnValues = {};
    window.items = [];
    window.bounds = [];
    @foreach(json_decode($project->entries[0]->files) as $key => $file)
        holder = {};
            holder['file'] = '{{$file->file}}';
            holder['width'] = {{$file->width}};
            holder['height'] = {{$file->height}};
            entryValues.push(holder);
    @endforeach
    returnValues['linkAddy'] = "{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/')}}";
    returnValues['data'] = entryValues;

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
            window.items.forEach(function(elm) {
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

    });

  </script>
@endsection
