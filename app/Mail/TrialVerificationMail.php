<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $toolName;
    public $verificationUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $toolName, $verificationUrl)
    {
        $this->user = $user;
        $this->toolName = $toolName;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Xác thực đăng ký dùng thử ' . $this->toolName . ' - ' . config('app.name'))
                    ->view('emails.trial-verification');
    }
}
