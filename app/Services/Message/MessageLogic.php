<?php

namespace App\Services\Message;

use App\Project;
use App\MessageThread;
use App\Message;

use Illuminate\Support\Facades\Auth;

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


    public static function createMessage(MessageThread $thread, $message) {
        try {
            $theMessage = new Message();
            $theMessage->thread_id = $thread->id;
            $theMessage->user_id = Auth::id();
            $theMessage->message = $message;
            $theMessage->save();

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
        return Message::with('user')->where('thread_id', $thread->id)->latest()->get();
    }


}

?>