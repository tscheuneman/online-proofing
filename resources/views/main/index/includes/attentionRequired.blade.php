<ul>
    @php
        $key = 0;
    @endphp
    @foreach(json_decode($orders) as $order)
        @foreach($order->projects as $proj)
            @if(isset($proj->admin_entries[0]))
                @if($proj->admin_entries[0]->admin)
                    <li>
                        <a class="linkNum_{{$key++}} pendingLink" href="{{url('/project') . '/' . $proj->file_path}}">{{$order->job_id}} - {{$proj->project_name}}</a>
                    </li>
                @endif
            @endif
        @endforeach
    @endforeach
</ul>
@if($key == 0)
    <strong>
        Nothing Pending
    </strong>
@endif