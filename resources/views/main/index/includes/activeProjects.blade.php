@foreach(json_decode($orders) as $order)
    <div class="order belongs">
        <p class="title">{{$order->job_id}}</p>
        @foreach($order->projects as $proj)
            <a href="{{ url('/project') . '/' . $proj->file_path }}">
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
                                            Awaiting Your Response
                                        @else
                                            <strong> Awaiting Premedia Response </strong>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </strong>
                    </p>
                </div>
            </a>
        @endforeach
    </div>
@endforeach