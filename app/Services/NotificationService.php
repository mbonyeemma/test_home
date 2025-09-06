<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $client;
    protected $apiUrl = 'https://api.cphl.site/idp/send-notification';
    protected $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im1ib255ZWVtbWFAeW1haWwuY29tIiwiYXV0aCI6ImNwaGwiLCJkYXRlIjoiMjAyNS0wNC0wN1QwODo0Mjo0Ny43MzRaIiwiaWF0IjoxNzQ0MDE1MzY3fQ.mMh61xjsVC_ybPQo1bpZtcegvU0Rzk8L1iBMI--bZ54';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendNotification($username, $message, $channel = 'EMAIL', $operation = 'ACCOUNT_APPROVAL')
    {
        try {
            // Handle different channel types
            if ($channel === 'ALL') {
                // Send both EMAIL and APP notifications
                $this->sendEmailNotification($username, $message, $operation);
                $this->sendPushNotification($username, $message, $operation);
            } elseif ($channel === 'APP') {
                // Send push notification only
                $this->sendPushNotification($username, $message, $operation);
            } else {
                // Default to EMAIL (backward compatibility)
                $this->sendEmailNotification($username, $message, $operation);
            }
        } catch (\Exception $e) {
            Log::error('Notification sending failed', [
                'username' => $username,
                'channel'  => $channel,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function sendEmailNotification($username, $message, $operation)
    {
        try {
            $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'username'    => $username,
                    'message'     => $message,
                    'sendChannel' => 'EMAIL',
                    'operation'   => $operation,
                ],
            ]);
            Log::info('Email notification sent successfully', [
                'username' => $username,
                'operation' => $operation,
            ]);
        } catch (\Exception $e) {
            Log::error('Email notification failed', [
                'username' => $username,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function sendPushNotification($username, $message, $operation)
    {
        try {
            $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'username'    => $username,
                    'message'     => $message,
                    'sendChannel' => 'APP',
                    'operation'   => $operation,
                ],
            ]);
            Log::info('Push notification sent successfully', [
                'username' => $username,
                'operation' => $operation,
            ]);
        } catch (\Exception $e) {
            Log::error('Push notification failed', [
                'username' => $username,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
