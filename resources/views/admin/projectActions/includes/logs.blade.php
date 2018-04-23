<div class="card">
    <div class="card-header">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        Logs
        <div id="showLogs" class="show">
            <i class="fa fa-angle-double-down" aria-hidden="true"></i>
            Show
        </div>
    </div>
    <div class="card-body hidden project_logs">
        <ol reversed>
            @foreach($logs as $log)
                <li><strong>{{$log->user->first_name . ' ' . $log->user->last_name}}</strong> - {{$log->action}} - {{date('Y-m-d g:i a', strtotime($log->created_at))}} </li>
            @endforeach
        </ol>
    </div>
</div>
