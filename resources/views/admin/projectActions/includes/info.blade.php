<div class="card">
    <div class="card-header">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        Project Info
        <div id="showInfo" class="show">
            <i class="fa fa-angle-double-down" aria-hidden="true"></i>
            Show
        </div>
    </div>
    <div class="card-body hidden project_info">
        <strong>Assigned Premedia</strong>
        <ul class="userList">
            @foreach($project->order->admins as $admin)
                <li>
                    @if($admin->admin->user->picture == null)
                        <div class="navPic">
                            {{mb_substr($admin->admin->user->first_name,0,1) . mb_substr($admin->admin->user->last_name,0,1)}}
                        </div>
                    @endif
                    {{$admin->admin->user->first_name}}
                </li>
            @endforeach
        </ul>
        <strong>Assigned Customers</strong>
        <ul class="userList">
            @foreach($project->order->users as $user)
                <li>
                    @if($user->user->picture == null)
                        <div class="navPic">
                            {{mb_substr($user->user->first_name,0,1) . mb_substr($user->user->last_name,0,1)}}
                        </div>
                    @endif
                    {{$user->user->first_name}}
                </li>
            @endforeach
        </ul>
    </div>
</div>