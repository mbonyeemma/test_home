<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FcmService
{
    private $fcmUrl = 'https://fcm.googleapis.com/v1/projects/restrack-f90a3/messages:send';
    private $serviceAccountPath;

    public function __construct()
    {
        $this->serviceAccountPath = storage_path('app/firebase-service-account.json');
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

            $notification = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
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
            if (!file_exists($this->serviceAccountPath)) {
                Log::error('Firebase service account file not found', [
                    'path' => $this->serviceAccountPath
                ]);
                return null;
            }

            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
            
            if (!$serviceAccount) {
                Log::error('Failed to parse service account JSON');
                return null;
            }

            // Fix private key - replace literal \n with actual newlines
            $privateKeyFixed = str_replace('\\n', "\n", $serviceAccount['private_key']);

            $now = time();
            $exp = $now + 3600;

            $jwtHeader = $this->base64UrlEncode(json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ]));

            $jwtClaim = $this->base64UrlEncode(json_encode([
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $exp,
            ]));

            $jwtSignature = '';
            $privateKey = openssl_pkey_get_private($privateKeyFixed);
            
            if (!$privateKey) {
                Log::error('Failed to parse private key from service account', [
                    'key_length' => strlen($privateKeyFixed),
                    'has_begin' => strpos($privateKeyFixed, '-----BEGIN') !== false
                ]);
                return null;
            }
            
            $success = openssl_sign(
                $jwtHeader . '.' . $jwtClaim,
                $jwtSignature,
                $privateKey,
                OPENSSL_ALGO_SHA256
            );

            if (!$success) {
                Log::error('Failed to sign JWT');
                return null;
            }

            $jwtSignature = $this->base64UrlEncode($jwtSignature);
            $jwt = $jwtHeader . '.' . $jwtClaim . '.' . $jwtSignature;

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['access_token'])) {
                Log::info('FCM access token obtained successfully');
                return $data['access_token'];
            }

            Log::error('Failed to get access token from Google', [
                'http_code' => $httpCode,
                'response' => $data
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error getting FCM access token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

