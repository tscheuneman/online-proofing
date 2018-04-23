@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                    Attention Required
                </div>
                <div class="card-body">
                    <ul>
                            @foreach(json_decode($orders) as $order)
                                @foreach($order->projects as $proj)
                                    @if(isset($proj->admin_entries[0]))
                                        @if($proj->admin_entries[0]->admin)
                                            <li>
                                                <a class="pendingLink" href="{{url('/project') . '/' . $proj->file_path}}">{{$order->job_id}} - {{$proj->project_name}}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Active Projects
                </div>

                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
