<?php

namespace App\Services\Project;

use App\Project;
use App\Services\Order\OrderLogic;
use File;

class ProjectLogic {
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }


    public static function find_path($id) {
        $project = Project::where('file_path', $id)->first();

        return new ProjectLogic($project);
    }

    public function isActive() {
        return $this->project->active;
    }

    public function get() {
        return $this->project;
    }

    public function dataReturn() {
        return Project::where('file_path', '=', $this->project->file_path)->with('order.projects', 'entries.user', 'order.users.user', 'order.admins.admin.user')->first();
    }

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
            }

        } catch(\Exception $e) {
            return false;
        }
    }

}

?>