<?php

namespace App\Services\Approval;

use App\Approval;
use App\User;
use App\Project;


class ApprovalLogic {
    protected $approval;

    public function __construct(Approval $approval)
    {
        $this->approval = $approval;
    }

    public static function getAll() {
        $cat = Approval::get();
        return $cat;
    }

    public static function create(User $user, Project $project) {
        $approval = new Approval;
            $approval->project_id = $project->id;
            $approval->user_id = $user->id;
        $approval->save();
    }


}

?>