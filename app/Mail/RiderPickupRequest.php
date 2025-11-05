<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RiderPickupRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $rider;
    public $packageData;

    public function __construct($rider, $packageData)
    {
        $this->rider = $rider;
        $this->packageData = $packageData;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'noreply@restrack.com'), env('MAIL_FROM_NAME', 'RESTRACK System'))
            ->subject('Sample Pickup Request - Action Required')
            ->view('emails.rider_pickup_request')
            ->with([
                'riderName' => $this->rider->name,
                'packageData' => $this->packageData,
            ]);
    }
}

