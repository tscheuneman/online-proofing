<?php

namespace Tjscheuneman\Proofing;

use App\Message;
use App\Services\Specification\SpecificationSchemaLogic;
use Illuminate\Http\Request;

use App\Services\Project\ProjectLogic;

use App\Services\Message\MessageLogic;

use Tjscheuneman\ActivityEvents\ActivityEvent;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Validator;
use Redirect;


class AdminProofController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = ProjectLogic::find_path($id);
        if ($project != null) {
            if ($project->isActive()) {
                $projectData = $project->dataReturn();

                $logs = ActivityEvent::getFromProject($project);

                $messages = MessageLogic::getThreads($project->get());

                return view('proof::admin_project.index',
                    [
                        'project' => $projectData,
                        'logs' => $logs,
                        'messages' => $messages
                    ]
                );
            } else {
                $thisProj = ProjectLogic::admin_entries($id);
                if (isset($thisProj->admin_entries[0])) {
                    return 'Output Pending';
                } else {

                    $otherProjects = $project->getProductsInOrder();
                    $projectData = $project->get();
                    $specs = SpecificationSchemaLogic::getAllName();

                    return view('proof::admin_project.create',
                        [
                            'project' => $projectData,
                            'otherProjects' => $otherProjects,
                            'specs' => $specs
                        ]
                    );
                }


            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function createRevision($id) {
        $project = ProjectLogic::find_path($id);
        if($project->readyForAdmin()) {
            $projectData = $project->get();

            return view('proof::admin_project.add',
                [
                    'project' => $projectData,
                ]
            );
        }
        return 'fail';
    }

}