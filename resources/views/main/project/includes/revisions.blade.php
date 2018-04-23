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
                    <div data-id="{{$entry->id}}" class="entryNav wide active approval">
                        @if(!empty($project->approval))
                                Approval
                                <br> {{  date('Y-m-d g:ia', strtotime($project->approval->created_at)) }}
                        @endif
                    </div>
                @endif
            @endif
            @if($enCnt == 1)
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
            @else
                <div class="navPic pic" style="background:url({{url('/') . '/storage/' . $entry->user->picture}}) center center no-repeat;">

                </div>
            @endif
               {{$entry->user->first_name . ' ' . $entry->user->last_name}}
               <br>
               <small>{{date('Y-m-d g:ia', strtotime($entry->created_at))}}</small>
               </div>
        @endforeach
    </div>
</div>