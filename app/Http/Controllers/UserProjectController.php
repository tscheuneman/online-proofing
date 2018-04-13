<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Entry;

use Illuminate\Support\Facades\Auth;
use App\Jobs\UserEntry;

use Mockery\Exception;

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
        $rand = str_random(12);

        $project = Project::find($request->projectID);

        $latest = Project::with('admin_entries')->where('id', $request->projectID)->first();


        if(!$latest->admin_entries[0]->admin) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'We have already received your submission';
            return json_encode($returnData);
        }

        $proj_year = date('Y', strtotime($project->created_at));
        $proj_month = date('F', strtotime($project->created_at));

        $projectPath = $proj_year . '/' . $proj_month . '/' . $project->file_path;

        if(File::makeDirectory(public_path('/storage/' . 'projects/' . $projectPath . '/' . $rand), 0777, true)) {
            try {
                $dir = 'projects/' . $projectPath . '/' . $rand;

                $files = [];
                $comments = [];

                $data = json_decode($request->dataArray);

                foreach($data as $val) {
                    $files[] = $val->data;
                    $comments[] = $val->comments;
                }

                $entry = new Entry();
                $entry->project_id = $project->id;
                $entry->user_id = Auth::id();
                $entry->path = $rand;
                $entry->pdf_name = null;
                $entry->user_notes = null;
                $entry->notes = null;
                $entry->save();

                UserEntry::dispatch($comments, $files, $entry, $dir);

                $returnData['status'] = 'Success';
                $returnData['message'] = 'Uploaded';
                return json_encode($returnData);

            } catch (Exception $e) {
                return $e;
            }


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
