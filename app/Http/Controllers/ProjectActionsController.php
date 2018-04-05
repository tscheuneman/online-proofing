<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Jobs\ConvertPDF;

use App\Project;
use App\Entry;

use Validator;
use Redirect;
use Storage;
use File;
use Imagick;

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

        $project = Project::find($request->project_id);


        if($project->active) {
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

            $proj_year = date('Y', strtotime($project->created_at));
            $proj_month = date('F', strtotime($project->created_at));

            $projectPath = $proj_year . '/' . $proj_month . '/' . $project->file_path;

            $rand = str_random(12);



            if(File::makeDirectory(public_path('/storage/' . 'projects/' . $projectPath . '/' . $rand), 0777, true)) {
                $dir = 'projects/' . $projectPath . '/' . $rand;

                if($path = Storage::disk('public')->put($dir . '/pdf', $request->file('pdf'), 'public')) {
                    $storageName = basename($path);

                    $entry = new Entry();
                        $entry->project_id = $project->id;
                        $entry->user_id = Auth::id();
                        $entry->path = $storageName;
                        $entry->admin = true;
                        $entry->notes = $request->comments;
                    $entry->save();

                    ConvertPDF::dispatch($dir, $storageName, 500, $project, $entry);

                    \Session::flash('flash_created','Wow');
                    return redirect('/admin');
                }
                else {
                    File::deleteDirectory(public_path('/storage/' . 'projects/' . $projectPath . '/' . $rand));
                    return redirect()->back()->withErrors(array('failed'))->withInput($request->all());
                }

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
        $project = Project::where('file_path', '=', $id)->first();
        if($project != null) {
            if($project->active) {

            } else {
                return view('admin.projectActions.create',
                    [
                        'project' => $project,
                    ]
                );
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
