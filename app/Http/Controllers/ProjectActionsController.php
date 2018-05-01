<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use App\Services\Project\ProjectLogic;
use App\Services\Activity\ActivityLogic;
use App\Services\Message\MessageLogic;

use Auth;

use Validator;
use Redirect;


class ProjectActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->back();
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
                if($project->makeFolder($request, true)) {
                    ActivityLogic::create($project->get(), Auth::user(), 'Created Revision');
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
                ActivityLogic::create($project->get(), Auth::user(), 'Created Initial Upload');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = ProjectLogic::find_path($id);
        if($project != null) {
            if($project->isActive()) {
                $projectData = $project->dataReturn();

                $logs = ActivityLogic::getFromProject($project);

                $messages = MessageLogic::getThreads($project->get());

                return view('admin.projectActionsSecondary.index',
                    [
                        'project' => $projectData,
                        'logs' => $logs,
                        'messages' => $messages
                    ]
                );
            } else {
                $thisProj = ProjectLogic::admin_entries($id);
                if(isset($thisProj->admin_entries[0])) {
                    return 'Output Pending';
                }
                else {
                    $otherProjects = $project->getProductsInOrder();

                    $projectData = $project->get();

                    return view('admin.projectActions.create',
                        [
                            'project' => $projectData,
                            'otherProjects' => $otherProjects
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


        if($link = ProjectLogic::getDropboxLink($request->val)) {

            $project = ProjectLogic::find_path($request->project_id);
            if($project->isApproved()) {
                ActivityLogic::create($project->get(), Auth::user(), 'Downloaded Final Proof');
            }
            else {
                ActivityLogic::create($project->get(), Auth::user(), 'Downloaded User File');
            }

            $returnData['status'] = 'Success';
            $returnData['message'] = $link;
            return json_encode($returnData);
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to generate link';
        return json_encode($returnData);
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
            return view('admin.projectActions.add',
                [
                    'project' => $projectData,
                ]
            );
        }
        return 'fail';
    }
}
