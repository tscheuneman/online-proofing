<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Entry;

use Illuminate\Support\Facades\Auth;
use App\Jobs\UserEntry;

use Mockery\Exception;

use App\Services\Project\UserProjectLogic;

use Validator;
use File;

class UserProjectController extends Controller
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
            return "failure";
        }

        $project = UserProjectLogic::find($request->projectID);

        $latest = $project->getLatestEntry();


        if(!$latest->admin_entries[0]->admin) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have already received your submission';
            return json_encode($returnData);
        }

        if($project->createDirectory($request)) {
            $returnData['status'] = 'Success';
            $returnData['message'] = 'Uploaded';
            return json_encode($returnData);

        }



        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to upload';
        return json_encode($returnData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::where('file_path', '=', $id)->first();
        if($project != null) {
            if($project->active) {
                $thisProject = Project::where('file_path', '=', $id)->with('order.projects', 'entries.user', 'order.users.user', 'order.admins.admin.user')->first();
                return view('main.project.index',
                    [
                        'project' => $thisProject,
                        'val'=> json_encode($thisProject->entries[0], JSON_UNESCAPED_SLASHES ),

                ]
                );
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
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

    public function approve(Request $request) {
        $rules = array(
            'projectID' => 'required|exists:projects,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have failed to approve the project, please try again';
            return json_encode($returnData);
        }

        if($project = UserProjectLogic::find($request->projectID)) {

            if(!$project->isApproved()) {
                $project->approve();
                $project->mail();

                $returnData['status'] = 'Success';
                $returnData['message'] = 'Hey it works bruh';
                return json_encode($returnData);
            }

            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have failed to approve the project, please try again';
            return json_encode($returnData);

        }
        $returnData['status'] = 'Failure';
        $returnData['message'] = 'We have failed to approve the project, please try again';
        return json_encode($returnData);

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
}
