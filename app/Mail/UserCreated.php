<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $pwReturn)
    {
        $this->user = $user;
        $this->pw = $pwReturn;

        $this->subject('Account Creation');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.userAccount', [
            'user' => $this->user,
            'pw' => $this->pw,
            'redirect' => ENV('APP_URL') . '/login'
        ]);
    }
}
