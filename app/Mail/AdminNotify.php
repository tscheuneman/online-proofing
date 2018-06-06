<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Project;
use App\User;
class AdminNotify extends Mailable
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

        $this->subject('A change has been made to a project');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.adminNotify', [
            'user' => $this->user,
            'project' => $this->project
        ]);
    }
}
