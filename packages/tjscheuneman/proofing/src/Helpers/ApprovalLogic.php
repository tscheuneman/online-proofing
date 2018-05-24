<?php

namespace Tjscheuneman\Proofing\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\AdminAssign;
use App\Mail\UserApproval;
use App\Services\Users\UserLogic;

class ApprovalLogic
{

    /**
     * Send approval email
     *
     * @param Project $project
     * @return void
     */

    public static function mail(Project $project)
    {
        $orderVals = Project::with('order')->where('id',$project->id)->first();

        if ($orderVals->order->notify_admins) {
            $users = AdminAssign::with('admin.user')->where('order_id', $orderVals->order->id)->get();
            foreach ($users as $user) {
                $adminUser = UserLogic::findUser($user->admin->user->id);
                $approver = UserLogic::findUser(Auth::id());

                Mail::to($user->admin->user->email)->send(new UserApproval($adminUser->user(), $project, $approver->user()));
            }
        }
    }
}