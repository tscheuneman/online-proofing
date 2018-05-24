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

use Tjscheuneman\Proofing\Helpers\EntryManagement;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = array(
            'project_id' => 'required|exists:projects,id',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if(!$project = ProjectLogic::find($request->project_id)) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }


        if($project->isActive()) {
            if($project->readyForAdmin()) {
                $rules = array(
                    'comments' => 'required|string',
                    'pdf' => 'required|file|mimes:pdf',
                );
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }
                if(EntryManagement::makeFolder($request, true, $project)) {
                    ActivityEvent::create($project->get(), Auth::user(), 'Created Revision');
                    \Session::flash('flash_created','Upload was created for ' . $project->getName());
                    return redirect('/admin');
                }

                return redirect()->back()->withErrors(array('failed'))->withInput($request->all());
            }
            return redirect()->back()->withErrors(array('Cannot accept a revision at this time'))->withInput($request->all());
        }
        else {
            $rules = array(
                'comments' => 'required|string',
                'pdf' => 'required|file|mimes:pdf',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }

            if($project->makeFolder($request)) {

                $otherProjects = $project->getProductsInOrder();
                ActivityEvent::create($project->get(), Auth::user(), 'Created Initial Upload');
                if(!empty($otherProjects)) {
                    \Session::flash('flash_created','Initial Upload was created for ' . $project->getName());
                    return redirect('/admin/project/' . $otherProjects[0]->file_path);
                }
                \Session::flash('flash_created','Wow');
                return redirect('/admin');
            }

            return redirect()->back()->withErrors(array('failed'))->withInput($request->all());
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