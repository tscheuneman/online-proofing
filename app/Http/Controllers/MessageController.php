<?php

namespace App\Http\Controllers;

use App\Services\Message\MessageLogic;
use App\Services\Project\ProjectLogic;
use Illuminate\Http\Request;
use Validator;

class MessageController extends Controller
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
        //
    }

    public function storeThread(Request $request)
    {
        $rules = array(
            'thread_name' => 'required|string',
            'project_id' => 'required|exists:projects,file_path'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Failed to create message thread';
            return json_encode($returnData);
        }

        if($project = ProjectLogic::find_path($request->project_id)) {
            if(MessageLogic::createThread($project->get(), $request->thread_name)) {
                $returnData['status'] = 'Success';
                $returnData['message'] = 'Created Thread';
                return json_encode($returnData);
            }
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to create message thread';
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
        if($thread = MessageLogic::findThread($id)) {
            $messages = MessageLogic::getData($thread);

            $returnData['status'] = 'Success';
            $returnData['message'] = $messages;

            return json_encode($returnData);
        }
        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to display thread';
        return json_encode($returnData);
    }

    /**
     * Show threads for given project
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showThread($id)
    {
        if($project = ProjectLogic::find_path($id)) {
            $messages = MessageLogic::getThreads($project->get());

            $returnData['status'] = 'Success';
            $returnData['message'] = $messages;

            return json_encode($returnData);
        }
        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to display thread';
        return json_encode($returnData);
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
