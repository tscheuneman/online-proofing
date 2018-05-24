<?php

namespace App\Http\Controllers;

use App\Message;
use App\Services\Specification\SpecificationSchemaLogic;
use Illuminate\Http\Request;

use App\Services\Project\ProjectLogic;
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


}
