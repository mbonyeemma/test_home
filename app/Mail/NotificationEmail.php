<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $messageContent;
    public $operation;

    public function __construct($user, $messageContent, $operation = 'GENERAL')
    {
        $this->user = $user;
        $this->messageContent = $messageContent;
        $this->operation = $operation;
    }

    public function build()
    {
        $subject = $this->getSubjectFromOperation($this->operation);
        
        return $this->from(env('MAIL_FROM_ADDRESS', 'noreply@restrack.com'), env('MAIL_FROM_NAME', 'RESTRACK System'))
            ->subject($subject)
            ->view('emails.notification')
            ->with([
                'userName' => $this->user->name,
                'messageContent' => $this->messageContent,
                'operation' => $this->operation,
            ]);
    }

    private function getSubjectFromOperation($operation)
    {
        $subjects = [
            'ACCOUNT_APPROVAL' => 'Account Approval Notification',
            'PACKAGE_CREATED' => 'New Package Created',
            'PACKAGE_DELIVERED' => 'Package Delivered',
            'PACKAGE_RECEIVED' => 'Package Received',
            'PACKAGE_INVITATION' => 'Package Invitation',
            'PICKUP_REQUEST' => 'Sample Pickup Request - Action Required',
            'PASSWORD_RESET' => 'Password Reset Request',
            'GENERAL' => 'Notification from RESTRACK',
        ];

        return $subjects[$operation] ?? 'Notification from RESTRACK';
    }
}

