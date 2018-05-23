<?php

namespace Tjscheuneman\ActivityEvents;

use App\Project;
use App\Services\Project\ProjectLogic;
use App\User;

class ActivityEvent {
    protected $activity;

    /**
     * ActivityLogic Constructor
     *
     * @param  Activity $activity
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
     * @return ActivityEvent
     */
    public static function create(Project $project, User $user, $action = 'N/A') {
            $activity = new Activity();
            $activity->project_id = $project->id;
            $activity->user_id = $user->id;
            $activity->action = $action;
            $activity->save();

            return new ActivityEvent($activity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $user
     * @return /Activity
     */
    public static function getAllFromUser($user) {
        return Activity::with('project.order_name')->where('user_id', $user)->take(10)->latest()->get(['project_id', 'user_id', 'action', 'created_at']);
    }

    public static function getFromProject(ProjectLogic $project) {
        return Activity::where('project_id', $project->id())->with('user')->latest()->get(['project_id', 'user_id', 'action', 'created_at']);
    }




}

?>