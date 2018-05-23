<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use Illuminate\Support\Facades\Auth;

use Tjscheuneman\ActivityEvents\ActivityEvent;
use Tjscheuneman\Proofing\Helpers\ApprovalLogic;

use App\Services\Project\UserProjectLogic;

use Validator;

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

}