<?php

namespace App\Services\Project;

use App\Project;
use App\Services\Order\OrderLogic;
use File;
use Storage;

use Illuminate\Support\Facades\Auth;

use App\Services\Entry\EntryLogic;
use App\Jobs\ConvertPDF;


class ProjectLogic {
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public static function find($id) {
        $project = Project::find($id);
        if($project) {
            return new ProjectLogic($project);
        }
        return false;
    }

    public function getProductsInOrder() {
        $returnArray = array();
        $otherOrders = $this->project->with('admin_entries')->where('id', '!=', $this->project->id)->get();
        foreach($otherOrders as $ord) {
            if(!isset($ord->admin_entries[0])) {
                $returnArray[] = $ord;
            }
        }
        return $returnArray;
    }

    public static function admin_entries($id) {
        return Project::where('file_path', '=', $id)->with('admin_entries')->first();
    }

    public static function find_path($id) {
        $project = Project::where('file_path', $id)->first();

        return new ProjectLogic($project);
    }

    public static function count() {
        return Project::where('active', '=', true)->where('completed', '=', false)->count();
    }

    public function isActive() {
        return $this->project->active;
    }

    public function created() {
        return $this->project->created_at;
    }

    public function path() {
        return $this->project->file_path;
    }

    public function getName() {
        return $this->project->project_name;
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

    public function makeFolder($request) {
        $proj_year = date('Y', strtotime($this->project->created_at));
        $proj_month = date('F', strtotime($this->project->created_at));
        $projectPath = $proj_year . '/' . $proj_month . '/' . $this->project->file_path;

        $rand = str_random(12);

        $folderPath = '/storage/' . 'projects/' . $projectPath . '/' . $rand;

        if(File::makeDirectory(public_path($folderPath), 0775, true)) {
            $dir = 'projects/' . $projectPath . '/' . $rand;
            return $this->storeFile($request, $dir, $folderPath, $rand);
        }
        return false;

    }
    public function storeFile($request, $dir, $folderPath, $rand) {
        if($path = Storage::disk('public')->put($dir . '/pdf', $request->file('pdf'), 'public')) {
            $storageName = basename($path);
            $entry = EntryLogic::createAdmin($this->project->id, Auth::id(), $rand, $request->comments);

            ConvertPDF::dispatch($dir, $storageName, 500, $this->get(), $entry->get());

            return true;
        }
        File::deleteDirectory(public_path($folderPath));
        return false;
    }

}

?>