<div class="card">
    <div class="card-header">
        <i class="fa fa-comment" aria-hidden="true"></i>
        Comments
    </div>
    <div class="card-body">
        @foreach($project->entries as $cmtCnt => $entry)
            @if($project->completed)
                @if($cmtCnt++ == 0)
                    @if(!empty($project->approval))
                        <div data-id="{{$project->approval->id}}" class="commentContainer active cmt_{{$cmtCnt}}">
                            <div class="comments">
                                Approved!
                            </div>
                        </div>
                    @endif
                @endif
            @endif
            @if($cmtCnt++ == 0)
                <div data-id="{{$entry->id}}" class="commentContainer active cmt_{{$cmtCnt}}">
            @else
                <div data-id="{{$entry->id}}" class="commentContainer cmt_{{$cmtCnt}}">
            @endif
            @if(!$entry->admin)
                @if($entry->path == null)
                    <div class="comments">
                     {{$entry->notes}}
                    </div>
                @else
                    @foreach(json_decode($entry->user_notes) as $key => $note)
                        @if($note != '')
                            <div data-num="{{$key}}" class="pageComment">
                                <strong>Page {{$key + 1}}</strong>
                                <br>
                                {{$note}}
                            </div>
                        @endif
                    @endforeach
                @endif
            @else
                <div class="comments">
                    {{$entry->notes}}
                </div>
            @endif
            </div>
        @endforeach
     </div>
</div>