<div class="card">
    <div class="card-header">
        <button id="prev" class="btn btn-outline-secondary left"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</button>
        <button id="next" class="btn btn-outline-secondary right"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Next</button>
        <span style="text-align:center; display:block;">
            <i class="fa fa-picture-o" aria-hidden="true"></i>
            To Proof
            <div class="counter">
                Page
                <span class="current">

                </span>
                of
                <span class="max">

                </span>
            </div>
        </span>
    </div>
    <div class="card-body">
        @foreach($project->entries as $enCnt => $entry)
            @if($entry->path != null)
                @if($enCnt == 0)
                    @if($project->completed)
                        <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
                    @else
                        <div data-numelm="{{count(json_decode($entry->files))}}" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
                    @endif
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
                        <img src="{{URL::to('/storage/projects/' . date('Y/F', strtotime($project->created_at)) . '/' . $project->file_path . '/' . $entry->path . '/images/' . $file->file)}}" alt="">
                    </div>
                @endforeach
                </div>
                    @else
                        @if($enCnt == 0)
                            <div data-numelm="1" data-id="{{$entry->id}}" class="entry active" id="entry_{{$enCnt++}}">
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