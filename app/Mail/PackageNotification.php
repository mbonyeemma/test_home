<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $packageData;
    public $notificationType;

    public function __construct($user, $packageData, $notificationType = 'CREATED')
    {
        $this->user = $user;
        $this->packageData = $packageData;
        $this->notificationType = $notificationType;
    }

    public function build()
    {
        $subjects = [
            'CREATED' => 'New Package Created',
            'DELIVERED' => 'Package Delivered',
            'RECEIVED' => 'Package Received',
            'INVITATION' => 'Package Pickup Invitation',
        ];

        $subject = $subjects[$this->notificationType] ?? 'Package Notification';

        return $this->from(env('MAIL_FROM_ADDRESS', 'noreply@restrack.com'), env('MAIL_FROM_NAME', 'RESTRACK System'))
            ->subject($subject)
            ->view('emails.package_notification')
            ->with([
                'userName' => $this->user->name,
                'packageData' => $this->packageData,
                'notificationType' => $this->notificationType,
            ]);
    }
}

