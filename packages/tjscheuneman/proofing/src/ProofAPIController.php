<?php

namespace Tjscheuneman\Proofing;

use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\Project\UserProjectLogic;
use App\Services\Project\ProjectLogic;
use App\Project;


use Tjscheuneman\ActivityEvents\ActivityEvent;
use Tjscheuneman\Proofing\Helpers\ApprovalLogic;
use Tjscheuneman\Proofing\Helpers\EntryLogic;
use Tjscheuneman\Proofing\Helpers\LinkManagment;


class ProofAPIController extends Controller {

    public function getProjectData($id) {
        return Project::where('file_path', '=', $id)->with('order', 'entries.user', 'order.users.user', 'order.admins.admin.user', 'approval.user')->first();
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
                ActivityEvent::create($project->get(), Auth::user(), "Approved Project");
                $project->approve();
                ApprovalLogic::mail($project->get());

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
                ActivityEvent::create($project->get(), Auth::user(), 'Uploaded Files');

                $entry = EntryLogic::createUser($project->id(), Auth::id(), null);

                $entry->updateEntry($savedFiles, $request->comments);

                $project->mailFileUpload();

                $request->session()->flash('flash_created', 'File for ' . $project->getName() . ' has been uploaded');
                return redirect('/');
            }
            $request->session()->flash('flash_deleted', 'Failed to upload files.  Project is already approved');
            return redirect('/');
        }

        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getLink(Request $request) {
        $rules = array(
            'val' => 'required|string',
            'project_id' => 'required|exists:projects,file_path'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }


        if($link = LinkManagment::getDropboxLink($request->val)) {

            $project = ProjectLogic::find_path($request->project_id);
            if($project->isApproved()) {
                ActivityEvent::create($project->get(), Auth::user(), 'Downloaded Final Proof');
            }
            else {
                ActivityEvent::create($project->get(), Auth::user(), 'Downloaded User File');
            }

            $returnData['status'] = 'Success';
            $returnData['message'] = $link;
            return json_encode($returnData);
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to generate link';
        return json_encode($returnData);
    }

}