<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FcmService
{
    private $fcmUrl = 'https://fcm.googleapis.com/v1/projects/restrack-f90a3/messages:send';

    public function __construct()
    {
    }

    public function sendPushNotification($fcmToken, $title, $body, $data = [])
    {
        try {
            if (!$fcmToken) {
                Log::warning('FCM token is empty, skipping push notification');
                return false;
            }

            $accessToken = $this->getAccessToken();
            
            if (!$accessToken) {
                Log::error('Failed to get FCM access token');
                return false;
            }

            // Convert all data values to strings (FCM requirement)
            $stringData = [];
            foreach ($data as $key => $value) {
                $stringData[$key] = (string)$value;
            }
            
            $notification = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $stringData,
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'default',
                        ],
                    ],
                ],
            ];

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                Log::error('FCM cURL error', ['error' => $error]);
                return false;
            }
            
            curl_close($ch);

            $responseData = json_decode($response, true);

            if ($httpCode === 200) {
                Log::info('FCM notification sent successfully', [
                    'title' => $title,
                    'response' => $responseData,
                ]);
                return true;
            } else {
                Log::error('FCM notification failed', [
                    'http_code' => $httpCode,
                    'response' => $responseData,
                    'title' => $title,
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('FCM notification exception', [
                'error' => $e->getMessage(),
                'title' => $title,
            ]);
            return false;
        }
    }

    private function getAccessToken()
    {
        try {
            $clientEmail = env('FIREBASE_CLIENT_EMAIL');
            $privateKey = env('FIREBASE_PRIVATE_KEY');
            
            if (empty($clientEmail) || empty($privateKey)) {
                Log::error('Firebase credentials not found in .env');
                return null;
            }

            $privateKey = str_replace('\\n', "\n", $privateKey);

            $now = time();
            $exp = $now + 3600;

            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT'
            ];

            $payload = [
                'iss' => $clientEmail,
                'sub' => $clientEmail,
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $exp,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
            ];

            $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
            $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
            $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;

            $privateKeyResource = openssl_pkey_get_private($privateKey);
            if (!$privateKeyResource) {
                Log::error('Failed to parse private key');
                return null;
            }

            openssl_sign($signatureInput, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
            openssl_free_key($privateKeyResource);

            $base64UrlSignature = $this->base64UrlEncode($signature);
            $jwt = $signatureInput . '.' . $base64UrlSignature;

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                $token = $response->json()['access_token'] ?? null;
                if ($token) {
                    Log::info('FCM access token obtained successfully');
                    return $token;
                }
            }

            Log::error('Failed to get FCM access token', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error getting FCM access token', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

