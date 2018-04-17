<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Project\ProjectLogic;

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
            return $request;
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
                return view('admin.projectActions.index',
                    [
                        'project' => $projectData,
                    ]
                );
            } else {
                $thisProj = Project::where('file_path', '=', $id)->with('admin_entries')->first();
                if(isset($thisProj->admin_entries[0])) {
                    return 'Output Pending';
                }
                else {
                    $projectData = $project->get();
                    return view('admin.projectActions.create',
                        [
                            'project' => $projectData,
                        ]
                    );
                }


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
