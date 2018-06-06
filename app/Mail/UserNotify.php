<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Project;
use App\User;
class UserNotify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, Project $project)
    {
        $this->user = User::find($user);
        $this->project = $project;

        $this->subject('An update has been made to your project');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.userNotify', [
            'user' => $this->user,
            'project' => $this->project
        ]);
    }
}
