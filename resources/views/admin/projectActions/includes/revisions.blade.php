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
            @if($project->completed)
                @if($enCnt++ == 0)
                    @if(!empty($project->approval))
                        <div data-id="{{$project->approval->id}}" class="entryNav wide active approval">
                            @if($project->approval->user->picture == null)
                                <div class="navPic">
                                    {{mb_substr($project->approval->user->first_name,0,1) . mb_substr($project->approval->user->last_name,0,1)}}
                                </div>
                                {{$project->approval->user->first_name }} {{  date('Y-m-d g:ia', strtotime($project->approval->created_at)) }}
                            @endif
                        </div>
                    @endif
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