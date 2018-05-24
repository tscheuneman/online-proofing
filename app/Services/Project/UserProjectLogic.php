<?php

namespace App\Services\Project;

use App\Project;
use App\AdminAssign;

use File;
use Storage;

use Illuminate\Support\Facades\Mail;

use App\Mail\AdminNotify;

use Illuminate\Support\Facades\Auth;
use App\Services\Approval\ApprovalLogic;

use App\Services\Users\UserLogic;

class UserProjectLogic {
    protected $project;

    /**
     * UserProjectLogic Constructor
     *
     * @param \App\Project $project
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Find project from id
     *
     * @param string id
     * @return mixed
     */
    public static function find($id) {
        $project = Project::find($id);
        if($project) {
            return new UserProjectLogic($project);
        }
        return false;
    }

    /**
     * Get entries waiting for user and admin actions
     *
     * @return array
     */
    public static function getUserActionRequired($id) {
        $projects = Project::whereHas('order.users.user', function($query) use($id) {
            $query->where('id', '=', $id);
        })->where('active', '=', true)->where('completed', '=', false)->with('admin_entries', 'order')->get();


        $returnArray = [];
        foreach($projects as $proj) {
            if(isset($proj->admin_entries[0])) {
                if($proj->admin_entries[0]->admin) {
                    $returnArray[] = $proj;
                }
            }
        }

        return json_encode($returnArray);
    }

    /**
     * Get project id
     *
     * @return string
     */
    public function id() {
        return $this->project->id;
    }

    /**
     * Get created date
     *
     * @return string
     */
    public function created() {
        return $this->project->created_at;
    }

    /**
     * Get project
     *
     * @return \App\Project
     */
    public function get() {
        return $this->project;
    }

    /**
     * Get project name
     *
     * @return string
     */
    public function getName() {
        return $this->project->project_name;
    }

    /**
     * Get latest entry of project
     *
     * @return \App\Project
     */
    public function getLatestEntry() {
        return Project::with('admin_entries')->where('id', $this->project->id)->first();
    }

    /**
     * See if project is approved
     *
     * @return boolean
     */
    public function isApproved() {
        return $this->project->completed;
    }

    /**
     * Get project file path
     *
     * @return string
     */
    public function path() {
        return $this->project->file_path;
    }

    /**
     * Approve Project
     *
     * @return boolean
     */
    public function approve() {
        $this->project->completed = true;
        $this->project->save();

        ApprovalLogic::create(Auth::user(), $this->project);

        return true;
    }

    /**
     * Send user revision email
     *
     * @return void
     */
    public function mailFileUpload() {
        $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();

        if($orderVals->order->notify_admins) {
            $users = AdminAssign::with('admin.user')->where('order_id', $orderVals->order->id)->get();
            foreach($users as $user) {
                $adminUser = UserLogic::findUser($user->admin->user->id);

                Mail::to($user->admin->user->email)->send(new AdminNotify($adminUser->returnID(), $this->project));
            }
        }
    }




    /**
     * Save user uploaded files to dropbox
     *
     * @param resource $file
     * @return array
     */
    public function saveFileToDropbox($file) {
        $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();
        $proj_year = date('Y', strtotime($this->project->created_at));
        $proj_month = date('F', strtotime($this->project->created_at));


        $proj = $this->project->with('admin_entries')->where('id', $this->project->id)->first();

        $projectPath = 'user_files/' . $proj_year . '/' . $proj_month . '/' . $orderVals->order->job_id . '/' . $this->project->project_name;

        $path = Storage::disk('dropbox')->put($projectPath, $file);

        $array['name'] = $file->getClientOriginalName();
        $array['path'] = $path;
        return $array;
    }


}

?>