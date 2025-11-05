<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $client;
    private $apiKey;
    private $username;
    private $from;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('AFRICASTALKING_API_KEY');
        $this->username = env('AFRICASTALKING_USERNAME');
        $this->from = env('AFRICASTALKING_FROM');
    }

    public function sendOTP($phoneNumber, $otp)
    {
        try {
            $message = "Your RESTRACK password reset OTP is: {$otp}. Valid for 10 minutes. Do not share this code.";
            
            return $this->sendSMS($phoneNumber, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP SMS', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendSMS($phoneNumber, $message)
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $endpoint = 'https://api.africastalking.com/version1/messaging';

            $formParams = [
                'username' => $this->username,
                'to' => $phoneNumber,
                'message' => $message
            ];

            if (!empty($this->from)) {
                $formParams['from'] = $this->from;
            }

            $response = $this->client->post($endpoint, [
                'headers' => [
                    'apiKey' => $this->apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json'
                ],
                'form_params' => $formParams
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('SMS sent successfully', [
                'phone' => $phoneNumber,
                'response' => $result
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/\s+/', '', $phoneNumber);
        
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '+256' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 3) !== '+256' && substr($phoneNumber, 0, 3) !== '256') {
            $phoneNumber = '+256' . $phoneNumber;
        } elseif (substr($phoneNumber, 0, 3) === '256') {
            $phoneNumber = '+' . $phoneNumber;
        }
        
        return $phoneNumber;
    }
}

