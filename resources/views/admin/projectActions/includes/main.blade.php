<div class="card">
    <div class="card-header">
        <button id="prev" class="btn btn-outline-secondary left"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</button>
        <button id="next" class="btn btn-outline-secondary right"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Next</button>
        <span style="text-align:center; display:block;">
            <i class="fa fa-picture-o" aria-hidden="true"></i>
            Images
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
                            <button data-proj="{{$project->file_path}}" data-addy="{{$project->entries[0]->pdf_path}}" class="getLink btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Download PDF</button>
                        </div>
                    </div>
                @endif
            @endif
            @if($entry->path != null)
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
                        <p>
                            {{json_decode($entry->user_notes)[$key - 1]}}
                        </p>
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
                                <button data-proj="{{$project->file_path}}" data-addy="{{$file->path}}" class="getLink btn btn-primary btn_{{$key++}}">{{$file->name}}</button>
                            @endforeach
                        </div>
                </div>
            @endif
        @endforeach
    </div>
</div>