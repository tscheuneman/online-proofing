<?php

namespace App\Http\Controllers;

use App\Services\Entry\EntryLogic;
use App\Services\Project\ProjectLogic;
use Illuminate\Http\Request;
use App\Project;
use App\Entry;

use Illuminate\Support\Facades\Auth;
use App\Jobs\UserEntry;

use Mockery\Exception;

use App\Services\Project\UserProjectLogic;
use App\Services\Approval\ApprovalLogic;
use App\Services\Activity\ActivityLogic;


use Storage;

use Validator;
use File;

class UserProjectController extends UserSideParentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'dataArray' => 'required|json',
            'projectID' => 'required|exists:projects,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Input error';
            return json_encode($returnData);
        }

        $project = UserProjectLogic::find($request->projectID);
        ActivityLogic::create($project->get(), Auth::user(), 'Submitted a Revision');
        $latest = $project->getLatestEntry();


        if(!$latest->admin_entries[0]->admin) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have already received your submission';
            return json_encode($returnData);
        }
        if(!$project->isApproved()) {
            if ($project->createDirectory($request)) {
                $returnData['status'] = 'Success';
                $returnData['message'] = 'Uploaded';
                return json_encode($returnData);

            }
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to upload';
        return json_encode($returnData);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function userFiles(Request $request) {
        $rules = array(
            'project_id' => 'required|exists:projects,id',
            'comments' => 'required|string',
            'files' => 'required',
            'files.*' => 'file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,application/pdf,application/illustrator,application/x-adobe-indesign|max:50000'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = $validator->messages();
            return json_encode($returnData);
        }

        $savedFiles = array();

        $files = $request->file('files');


        if($project = UserProjectLogic::find($request->project_id)) {
            if(!$project->isApproved()) {
                foreach ($files as $file) {
                    $path = $project->saveFileToDropbox($file);
                    $savedFiles[] = $path;
                }
                ActivityLogic::create($project->get(), Auth::user(), 'Uploaded Files');

                $entry = EntryLogic::createUser($project->id(), Auth::id(), null);

                $entry->updateEntry($savedFiles, $request->comments);

                $project->mailFileUpload();

                \Session::flash('flash_created', 'File for ' . $project->getName() . ' has been uploaded');
                return redirect('/');
            }
            \Session::flash('flash_deleted', 'Failed to upload files.  Project is already approved');
            return redirect('/');
        }

        return $request;
    }

}
