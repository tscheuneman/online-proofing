<?php

namespace Tjscheuneman\Messaging;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Project\ProjectLogic;


use Validator;

class Messaging extends Controller
{
    public function store(Request $request)
    {
        $rules = array(
            'message' => 'required|string',
            'thread' => 'required|exists:message_threads,id'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Failed to create message';
            return json_encode($returnData);
        }


        if ($thread = MessageLogic::findThread($request->thread)) {
            if (MessageLogic::createMessage($thread, $request->message)) {
                $returnData['status'] = 'Success';
                $returnData['message'] = 'Created Thread';
                return json_encode($returnData);
            }
        }

        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to create message message';
        return json_encode($returnData);
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

        if ($project = ProjectLogic::find_path($request->project_id)) {
            if (MessageLogic::createThread($project->get(), $request->thread_name)) {
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($thread = MessageLogic::findThread($id)) {
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showThread($id)
    {
        if ($project = ProjectLogic::find_path($id)) {
            $messages = MessageLogic::getThreads($project->get());

            $returnData['status'] = 'Success';
            $returnData['message'] = $messages;

            return json_encode($returnData);
        }
        $returnData['status'] = 'Failure';
        $returnData['message'] = 'Failed to display thread';
        return json_encode($returnData);
    }
}