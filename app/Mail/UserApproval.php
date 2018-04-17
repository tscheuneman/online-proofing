<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Project;

class UserApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Project $project, User $approver)
    {
        $theProj = $project->with('entryInfo')->where('id', $project->id)->first();

        $theLink = null;

        foreach($theProj->entryInfo as $entry) {
            if($entry->admin && $entry->active) {
                $theLink = $entry->pdf_path;
                break;
            }
        }

        $this->user = $user;
        $this->project = $project;
        $this->approver = $approver;
        $this->link = $theLink;
        $this->subject('Project Approval');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.userApproval', [
            'user' => $this->user,
            'project' => $this->project,
            'approver' => $this->approver,
            'link' => $this->link
        ]);
    }
}
