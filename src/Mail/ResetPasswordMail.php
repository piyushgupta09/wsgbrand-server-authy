<?php

namespace Fpaipl\Authy\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('authy::emails.reset_password')->with([
            'email' => $this->email,
            'token' => $this->token
        ]);
    }
}
