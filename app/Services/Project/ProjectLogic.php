<?php

namespace App\Services\Project;

use App\Order;
use App\Project;
use App\Services\Order\OrderLogic;
use File;
use Storage;

use Illuminate\Support\Facades\Auth;

use App\Services\Entry\EntryLogic;
use App\Jobs\ConvertPDF;
use App\Jobs\ConvertPDFSecondary;

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

        $order = OrderLogic::find($this->project->ord_id);
           $projects = $order->getOtherProducts($this->project->id);

        foreach($projects->projects as $ord) {
            if(!isset($ord->admin_entries[0])) {
                $returnArray[] = $ord;
            }
        }
        return $returnArray;
    }

    public static function admin_entries($id) {
        return Project::where('file_path', '=', $id)->with('admin_entries')->first();
    }

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

    public static function find_path($id) {
        $project = Project::where('file_path', $id)->first();

        return new ProjectLogic($project);
    }

    public static function count() {
        return Project::where('active', '=', true)->where('completed', '=', false)->count();
    }

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
        return Project::where('file_path', '=', $this->project->file_path)->with('order', 'entries.user', 'order.users.user', 'order.admins.admin.user', 'approval.user')->first();
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

    public function makeFolder($request, $secondary = false) {
        $proj_year = date('Y', strtotime($this->project->created_at));
        $proj_month = date('F', strtotime($this->project->created_at));
        $projectPath = $proj_year . '/' . $proj_month . '/' . $this->project->file_path;

        $rand = str_random(12);

        $folderPath = '/storage/' . 'projects/' . $projectPath . '/' . $rand;

        if(File::makeDirectory(public_path($folderPath), 0775, true)) {
            $dir = 'projects/' . $projectPath . '/' . $rand;

            return $this->storeFile($request, $dir, $folderPath, $rand, $secondary);
        }
        return false;

    }
    public function storeFile($request, $dir, $folderPath, $rand, $secondary) {
        if($path = Storage::disk('public')->put($dir . '/pdf', $request->file('pdf'), 'public')) {
            $storageName = basename($path);
            $entry = EntryLogic::createAdmin($this->project->id, Auth::id(), $rand, $request->comments);

            if($secondary) {
                ConvertPDFSecondary::dispatch($dir, $storageName, 500, $this->get(), $entry->get());
            }
            else {
                ConvertPDF::dispatch($dir, $storageName, 500, $this->get(), $entry->get());
            }


            return true;
        }
        File::deleteDirectory(public_path($folderPath));
        return false;
    }

    public static function getDropboxLink($val) {
        return Storage::disk('dropbox')->getAdapter()->getTemporaryLink($val);
    }

}

?>