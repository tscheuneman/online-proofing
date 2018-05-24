<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use Illuminate\Support\Facades\Auth;

use App\Services\Project\UserProjectLogic;

use Tjscheuneman\ActivityEvents\ActivityEvent;
use Tjscheuneman\Proofing\Helpers\EntryManagement;

use Validator;

class UserProofController extends Controller {

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
                $thisProject = Project::where('file_path', '=', $id)->first();

                if(\Auth::check()) {
                    $isUser = false;
                    foreach($thisProject->order->users as $user) {
                        if($user->user->id == Auth::id()) {
                            $isUser = true;
                            break;
                        }
                    }
                    if($isUser) {
                        ActivityEvent::create($thisProject, Auth::user(), 'Viewed Project');
                        return view('proof::user_project.index',
                            [
                                'project' => $thisProject,

                            ]);
                    }

                }
                if(!$thisProject->order->hidden) {
                    return view('proof::user_project.guest',
                        [
                            'project' => $thisProject,

                        ]);
                }
                return abort(404, 'cannot find');

            }
        } else {
            return redirect()->back();
        }
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
        ActivityEvent::create($project->get(), Auth::user(), 'Submitted a Revision');
        $latest = $project->getLatestEntry();


        if(!$latest->admin_entries[0]->admin) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have already received your submission';
            return json_encode($returnData);
        }
        if(!$project->isApproved()) {
            if(EntryManagement::createDirectory($request, $project)) {
                $returnData['status'] = 'Success';
                $returnData['message'] = 'Uploaded';
                return json_encode($returnData);

            }
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to upload';
        return json_encode($returnData);
    }
}