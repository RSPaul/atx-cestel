<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $link;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
				 return $this->from(env('FROM_EMAIL'),env('FROM_NAME'))
                ->subject('Verify your email')
                ->view('emails.verify');
    }
}
