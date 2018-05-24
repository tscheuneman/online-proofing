<?php

namespace Tjscheuneman\Proofing\Helpers;

use App\Project;
use App\Services\Project\ProjectLogic;

use File;
use Storage;

use App\Services\Entry\EntryLogic;
use App\Jobs\ConvertPDF;
use App\Jobs\ConvertPDFSecondary;

use Auth;


class EntryManagement
{
    /**
     * Create folder for an entry
     *
     * @param \Illuminate\Http\Request $request
     * @pararm boolean $secondary
     * @return mixed
     */
    public static function makeFolder($request, $secondary = false, ProjectLogic $project) {
        $proj_year = date('Y', strtotime($project->created()));
        $proj_month = date('F', strtotime($project->created()));
        $projectPath = $proj_year . '/' . $proj_month . '/' . $project->path();

        $rand = str_random(12);

        $folderPath = '/storage/' . 'projects/' . $projectPath . '/' . $rand;

        if(File::makeDirectory(public_path($folderPath), 0775, true)) {
            $dir = 'projects/' . $projectPath . '/' . $rand;

            return (new EntryManagement)->storeFile($request, $dir, $folderPath, $rand, $secondary, $project);
        }
        return false;

    }

    /**
     * Store a file, create entry, and then run convert job
     *
     * @param \Illuminate\Http\Request $request
     * @param string $dir
     * @param string $rand
     * @param boolean $secondary
     * @return boolean
     */
    public function storeFile($request, $dir, $folderPath, $rand, $secondary, $project) {
        if($path = Storage::disk('public')->put($dir . '/pdf', $request->file('pdf'), 'public')) {
            $storageName = basename($path);
            $entry = EntryLogic::createAdmin($project->id(), Auth::id(), $rand, $request->comments);

            if($secondary) {
                ConvertPDFSecondary::dispatch($dir, $storageName, 500, $project->get(), $entry->get());
            }
            else {
                ConvertPDF::dispatch($dir, $storageName, 500, $project->get(), $entry->get());
            }


            return true;
        }
        File::deleteDirectory(public_path($folderPath));
        return false;
    }

}