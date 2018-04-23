@foreach($order->admins as $admin)
    @php
        $class = '';
    @endphp
    @if($admin->admin->user_id == Auth::id())
        @php
            $class = 'belongs';
        @endphp
        @break
    @endif
@endforeach
@if(isset($order->projects[0]->id))
     <div class="order {{$class}}">
         <p class="title">{{$order->job_id}}</p>
         @if($order->notify_users)
             <span class="option">Notify Users</span>
         @endif
         @if($order->notify_admins)
             <span class="option">Notify Admins</span>
         @endif
         @if(!$order->hidden)
             <span class="option">Public</span>
         @endif
         <br class="clear" />
         <br>
         @foreach($order->projects as $proj)
             <a href="{{ url('/admin/project') . '/' . $proj->file_path }}">
                 <div class="project">
                     <p class="projectTitle">{{$proj->project_name}}</p>
                     <p class="statusText">
                         <strong>
                             @if(isset($proj->admin_entries[0]))
                                 @if(!$proj->admin_entries[0]->active)
                                     Waiting on Output
                                 @else
                                     @if($proj->completed)
                                         <strong>Approved</strong>
                                     @else
                                         @if($proj->admin_entries[0]->admin)
                                             Awaiting User Response
                                         @else
                                             <strong> Awaiting Premedia Response </strong>
                                         @endif
                                     @endif
                                 @endif
                             @else
                                 @if(!$proj->active)
                                     Awaiting Initial Upload
                                 @endif
                             @endif
                         </strong>
                     </p>
                 </div>
             </a>
         @endforeach
     </div>
 @endif
