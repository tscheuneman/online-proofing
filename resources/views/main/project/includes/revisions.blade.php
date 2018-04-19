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