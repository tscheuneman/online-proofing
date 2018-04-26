<?php

namespace App\Services\Message;

use App\Project;
use App\MessageThread;

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
     * Create text entry
     *
     * @param \App\Project $project
     * $param string $data
     * @return mixed
     */
    public static function getThreads(Project $project) {

        $search = MessageThread::where('project_id', $project->id)->get();

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


}

?>