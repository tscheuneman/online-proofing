<?php

namespace App\Services\Activity;

use App\Activity;
use App\Project;
use App\User;

class ActivityLogic {
    protected $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public static function create(Project $project, User $user, $action = 'N/A') {
        $activity = new Activity();
            $activity->project_id = $project->id;
            $activity->user_id = $user->id;
            $activity->action = $action;
        $activity->save();

        return new ActivityLogic($activity);
    }

    public static function getAllFromUser($user) {
        return Activity::with('project.order_name')->where('user_id', $user)->take(10)->latest()->get(['project_id', 'user_id', 'action', 'created_at']);
    }




}

?>