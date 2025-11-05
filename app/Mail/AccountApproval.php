<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $approved;
    public $reason;

    public function __construct($user, $approved = true, $reason = null)
    {
        $this->user = $user;
        $this->approved = $approved;
        $this->reason = $reason;
    }

    public function build()
    {
        $subject = $this->approved ? 'Account Approved' : 'Account Registration Update';

        return $this->from(env('MAIL_FROM_ADDRESS', 'noreply@restrack.com'), env('MAIL_FROM_NAME', 'RESTRACK System'))
            ->subject($subject)
            ->view('emails.account_approval')
            ->with([
                'userName' => $this->user->name,
                'approved' => $this->approved,
                'reason' => $this->reason,
            ]);
    }
}

