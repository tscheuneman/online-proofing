<?php

namespace App\Services\Approval;

use Tjscheuneman\Proofing\Approval;
use App\User;
use App\Project;


class ApprovalLogic {
    protected $approval;

    /**
     * ApprovalLogic constructor
     *
     * @param  \App\Approval $approval
     * @return void
     */
    public function __construct(Approval $approval)
    {
        $this->approval = $approval;
    }

    /**
     * Get all approvals
     *
     * @return \App\Approval
     */
    public static function getAll() {
        $cat = Approval::get();
        return $cat;
    }

    /**
     * Create an approval
     *
     * @param  \App\User $user
     * @param  \App\Project $project
     * @return void
     */

    public static function create(User $user, Project $project) {
        $approval = new Approval;
            $approval->project_id = $project->id;
            $approval->user_id = $user->id;
        $approval->save();
    }


}

?>