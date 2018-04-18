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

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public static function find($id) {
        $project = Project::find($id);
        if($project) {
            return new UserProjectLogic($project);
        }
        return false;
    }

    public function id() {
        return $this->project->id;
    }

    public function get() {
        return $this->project;
    }

    public function getName() {
        return $this->project->project_name;
    }

    public function getLatestEntry() {
        return Project::with('admin_entries')->where('id', $this->project->id)->first();
    }

    public function isApproved() {
        return $this->project->completed;
    }

    public function approve() {
        $this->project->completed = true;
        $this->project->save();

        return true;
    }

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