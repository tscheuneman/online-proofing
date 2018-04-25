<?php

namespace App\Services\Project;

use App\Project;
use App\AdminAssign;

use File;
use Storage;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserApproval;
use App\Mail\AdminNotify;

use Illuminate\Support\Facades\Auth;

use App\Services\Entry\EntryLogic;
use App\Jobs\UserEntry;

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
     * Approve Project
     *
     * @return boolean
     */
    public function approve() {
        $this->project->completed = true;
        $this->project->save();

        return true;
    }

    /**
     * Send approval email
     *
     * @return void
     */

    public function mail() {
        $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();

        if($orderVals->order->notify_admins) {
            $users = AdminAssign::with('admin.user')->where('order_id', $orderVals->order->id)->get();
            foreach($users as $user) {
                $adminUser = UserLogic::findUser($user->admin->user->id);
                $approver = UserLogic::findUser(Auth::id());

                Mail::to($user->admin->user->email)->send(new UserApproval($adminUser->user(), $this->project, $approver->user()));
            }
        }
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
     * Create user entry directory.  Gather image data, send to be processed
     *
     * @return boolean
     */
    public function createDirectory($request) {
        $rand = str_random(12);
        $proj_year = date('Y', strtotime($this->project->created_at));
        $proj_month = date('F', strtotime($this->project->created_at));
        $projectPath = $proj_year . '/' . $proj_month . '/' . $this->project->file_path;

        if(File::makeDirectory(public_path('/storage/' . 'projects/' . $projectPath . '/' . $rand), 0777, true)) {
            try {
                $dir = 'projects/' . $projectPath . '/' . $rand;

                $files = [];
                $comments = [];
                $data = json_decode($request->dataArray);

                foreach($data as $val) {
                    $files[] = $val->data;
                    $comments[] = $val->comments;
                }

                $entry = EntryLogic::createUser($this->project->id, Auth::id(), $rand);

                UserEntry::dispatch($comments, $files, $entry->get(), $dir, $this->project);

                return true;
            } catch (Exception $e) {
                return false;
            }


        }
        return false;
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