@foreach(json_decode($orders) as $order)
    <div class="col-md-4">
        <div class="card userShow">
            <div class="card-header bg-brand userShow">
                {{$order->job_id}}
            </div>
            <div class="card-body">
                @foreach($order->projects as $proj)
                    <a class="userShowCard" href="{{ url('/project') . '/' . $proj->file_path }}">
                        @if(isset($proj->admin_entries[0]))
                            @php $addClass = ""; $text = "" @endphp
                            @if(!$proj->admin_entries[0]->active)
                                @php $addClass .= "border-light"; $text = "Waiting on Output" @endphp
                            @else
                                @if($proj->completed)
                                    @php $addClass .= "border-primary"; $text = "Approved" @endphp
                                @else
                                    @if($proj->admin_entries[0]->admin)
                                        @php $addClass .= "border-primary"; $text = "Awaiting Your Response" @endphp
                                    @else
                                        @php $addClass .= "border-dark"; $text = "Awaiting Premedia Response" @endphp
                                    @endif
                                @endif
                            @endif
                        @endif
     <div class="card {{$addClass}}">
         <h5 class="card-title">{{$proj->project_name}}</h5>
         <span class="statusText">
             <strong>
                 {{$text}}
             </strong>
         </span>
     </div>
 </a>
@endforeach
</div>
</div>
</div>
@endforeach