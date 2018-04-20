<?php

namespace App\Services\Activity;

use App\Activity;
use App\Project;
use App\User;

class ActivityLogic {
    protected $activity;

    /**
     * ActivityLogic Constructor
     *
     * @param  \App\Activity $activity
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Create an activity
     *
     * @param  \App\Project $project
     * @param  \App\User $user
     * @param   string $action
     * @return \App\Services\Activity\ActivityLogic
     */
    public static function create(Project $project, User $user, $action = 'N/A') {
        $activity = new Activity();
            $activity->project_id = $project->id;
            $activity->user_id = $user->id;
            $activity->action = $action;
        $activity->save();

        return new ActivityLogic($activity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $user
     * @return \App\Activity
     */
    public static function getAllFromUser($user) {
        return Activity::with('project.order_name')->where('user_id', $user)->take(10)->latest()->get(['project_id', 'user_id', 'action', 'created_at']);
    }




}

?>