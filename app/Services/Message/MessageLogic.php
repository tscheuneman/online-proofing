<?php

namespace App\Services\Message;

use App\Project;
use App\MessageThread;
use App\Message;

class MessageLogic {
    protected $message;

    /**
     * MessageLogic Constructor
     *
     * @param \App\Text $text
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Create all message threads in a project
     *
     * @param \App\Project $project
     * @return \App\MessageThread[]
     */

    public static function getThreads(Project $project) {

        $search = MessageThread::withCount('msg_cnt')->where('project_id', $project->id)->get();

        return $search;
    }

    public static function createThread(Project $project, $thread_name) {
        try {
            $message = new MessageThread();
            $message->project_id = $project->id;
            $message->subject = $thread_name;
            $message->save();

            return true;

        } catch(\Exception $e) {
            return false;
        }
    }

    public static function findThread($id) {
        $message = MessageThread::find($id);
        if($message !== null) {
            return $message;
        }
        return false;
    }

    public static function getData(MessageThread $thread) {
        return Message::where('thread_id', $thread->id)->get();
    }


}

?>