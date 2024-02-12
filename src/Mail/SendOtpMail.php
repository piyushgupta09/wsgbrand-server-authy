<?php

namespace Fpaipl\Authy\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The OTP value.
     *
     * @var string
     */
    public $otp;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param  string  $otp
     * @return void
     */
    public function __construct(string $otp, string $subject = 'OTP Verification Code')
    {
        $this->otp = $otp;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->view('authy::emails.otp_verification')
            ->with([
                'otp' => $this->otp,
            ]);
    }
}
