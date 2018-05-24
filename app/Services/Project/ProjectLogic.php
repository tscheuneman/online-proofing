<?php

namespace App\Services\Project;

use App\Project;
use App\Services\Order\OrderLogic;
use File;
use Storage;


class ProjectLogic {
    protected $project;

    /**
     * ProjectLogic Controller
     *
     * @param \App\Project $project
     * @return void
     */

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Find a project with a given id
     *
     * @param string $id
     * @return mixed
     */

    public static function find($id) {
        $project = Project::find($id);
        if($project) {
            return new ProjectLogic($project);
        }
        return false;
    }

    /**
     * Get all projects in an order
     *
     * @return array
     */
    public function getProductsInOrder() {
        $returnArray = array();

        $order = OrderLogic::find($this->project->ord_id);
           $projects = $order->getOtherProducts($this->project->id);

        foreach($projects->projects as $ord) {
            if(!isset($ord->admin_entries[0])) {
                $returnArray[] = $ord;
            }
        }
        return $returnArray;
    }

    /**
     * Get Project with entries with a given file_path
     *
     * @param string $id
     * @return \App\Project
     */
    public static function admin_entries($id) {
        return Project::where('file_path', '=', $id)->with('admin_entries')->first();
    }

    /**
     * Check if user made last entry
     *
     * @return boolean
     */

    public function readyForAdmin() {
        $vals = $this->admin_entries($this->project->file_path);

        if(isset($vals->admin_entries[0])) {
            if(!$vals->admin_entries[0]->admin) {
                return true;
            }
            return false;
        }
        return false;
    }
    /**
     * Get project from path
     *
     * @param string $id
     * @return \App\Services\Project\ProjectLogic
     */
    public static function find_path($id) {
        $project = Project::where('file_path', $id)->first();

        return new ProjectLogic($project);
    }

    /**
     * Get number of active projects
     *
     * @return int
     */
    public static function count() {
        return Project::where('active', '=', true)->where('completed', '=', false)->count();
    }

    /**
     * Get entries waiting for user and admin actions
     *
     * @return array
     */
    public static function pendingEntries() {
        $projects = Project::where('active', '=', true)->where('completed', '=', false)->with('admin_entries')->get();

        $returnVal = array();

        $userPend = 0;
        $adminPend = 0;

        foreach($projects as $proj) {
            if(isset($proj->admin_entries[0])) {
                if($proj->admin_entries[0]->admin) {
                    $userPend++;
                }
                else {
                    $adminPend++;
                }
            }
        }

        $returnVal['userPending'] = $userPend;
        $returnVal['adminPending'] = $adminPend;

        return $returnVal;
    }

    /**
     * Check if project is active
     *
     * @return boolean
     */
    public function isActive() {
        return $this->project->active;
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
     * Get project file path
     *
     * @return string
     */
    public function path() {
        return $this->project->file_path;
    }

    /**
     * Get project ID
     *
     * @return string
     */
    public function id() {
        return $this->project->id;
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
     * Check if project has been approved
     *
     * @return boolean
     */
    public function isApproved() {
        return $this->project->completed;
    }

    /**
     * Get the project
     *
     * @return \App\Project
     */
    public function get() {
        return $this->project;
    }

    /**
     *  Get all the info for a project given its filepath
     *
     * @return \App\Project
     */
    public function dataReturn() {
        return Project::where('file_path', '=', $this->project->file_path)->with('order', 'entries.user', 'order.users.user', 'order.admins.admin.user', 'approval.user')->first();
    }

    /**
     * Create a project
     *
     * @param \App\Services\Order\OrderLogic $order
     * @param string $name
     * @return boolean
     */
    public static function create(OrderLogic $order, $name) {
        try {
            $rand = str_random(12);
            $path = date('Y') . '/' . date('F') . '/' . $rand;

            if(File::makeDirectory(public_path('storage/projects/' . $path), 0775, true)) {
                $project = new Project();
                $project->project_name = $name;
                $project->file_path = $rand;
                $project->ord_id = $order->getID();
                $project->save();
                return true;
            }
            return false;

        } catch(\Exception $e) {
            return false;
        }
    }


}

?>