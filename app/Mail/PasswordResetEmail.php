<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'noreply@restrack.com'), env('MAIL_FROM_NAME', 'RESTRACK System'))
            ->subject('Password Reset Request - OTP')
            ->view('emails.password_reset')
            ->with([
                'userName' => $this->user->name,
                'otp' => $this->otp,
            ]);
    }
}

