<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use App\User;

class NotificationService
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendNotification($username, $message, $channel = 'EMAIL', $operation = 'ACCOUNT_APPROVAL')
    {
        try {
            if ($channel === 'ALL') {
                $this->sendEmailNotification($username, $message, $operation);
                $this->sendPushNotification($username, $message, $operation);
            } elseif ($channel === 'APP') {
                $this->sendPushNotification($username, $message, $operation);
            } else {
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
            $user = User::where('username', $username)
                ->orWhere('email', $username)
                ->first();

            if (!$user || !$user->email) {
                Log::warning('User email not found for notification', [
                    'username' => $username,
                    'operation' => $operation,
                ]);
                return;
            }

            Mail::to($user->email)->send(new NotificationEmail($user, $message, $operation));

            Log::info('Email notification sent successfully', [
                'username' => $username,
                'email' => $user->email,
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
            $user = User::where('username', $username)
                ->orWhere('email', $username)
                ->first();

            if (!$user) {
                Log::warning('User not found for push notification', [
                    'username' => $username,
                ]);
                return;
            }

            $notificationData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'message' => $message,
                'operation' => $operation,
                'created_at' => now(),
            ];

            \DB::table('notifications')->insert($notificationData);

            Log::info('App notification saved to database', [
                'username' => $username,
                'operation' => $operation,
            ]);

            if ($user->fcm_token) {
                $title = $this->getTitleFromOperation($operation);
                
                $this->fcmService->sendPushNotification(
                    $user->fcm_token,
                    $title,
                    $message,
                    [
                        'operation' => $operation,
                        'username' => $username,
                    ]
                );

                Log::info('FCM push notification sent', [
                    'username' => $username,
                    'operation' => $operation,
                ]);
            } else {
                Log::info('User has no FCM token, skipping push notification', [
                    'username' => $username,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Push notification failed', [
                'username' => $username,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function getTitleFromOperation($operation)
    {
        $titles = [
            'ACCOUNT_APPROVAL' => 'Account Approved',
            'PACKAGE_CREATED' => 'New Package',
            'PACKAGE_DELIVERED' => 'Package Delivered',
            'PACKAGE_RECEIVED' => 'Package Received',
            'PACKAGE_INVITATION' => 'Package Ready',
            'PICKUP_REQUEST' => 'Pickup Request',
            'PASSWORD_RESET' => 'Password Reset',
            'GENERAL' => 'Notification',
        ];

        return $titles[$operation] ?? 'RESTRACK Notification';
    }
}

