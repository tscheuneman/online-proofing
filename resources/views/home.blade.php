@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                    Pending Changes
                </div>
                <div class="card-body">

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
