<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\SmsService;
use App\Mail\PasswordResetEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendOTP(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string'
            ]);

            $phoneNumber = $request->phone_number;

            $user = User::where('username', $phoneNumber)->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No account found with this phone number'
                ], 404);
            }

            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $resetToken = Str::random(60);

            DB::table('password_resets')->updateOrInsert(
                ['phone_number' => $phoneNumber],
                [
                    'otp' => $otp,
                    'reset_token' => $resetToken,
                    'is_verified' => false,
                    'expires_at' => Carbon::now()->addMinutes(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );

            $smsSent = $this->smsService->sendOTP($phoneNumber, $otp);

            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new PasswordResetEmail($user, $otp));
                    \Log::info('Password reset email sent to: ' . $user->email);
                } catch (\Exception $e) {
                    \Log::error('Failed to send password reset email: ' . $e->getMessage());
                }
            }

            if ($smsSent) {
                return response()->json([
                    'status' => 200,
                    'message' => 'OTP sent successfully to your phone' . ($user->email ? ' and email' : ''),
                    'reset_token' => $resetToken
                ]);
            } else {
                if ($user->email) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'OTP sent successfully to your email',
                        'reset_token' => $resetToken
                    ]);
                }
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to send OTP. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Send OTP Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while sending OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string',
                'otp' => 'required|string|size:6',
                'reset_token' => 'required|string'
            ]);

            $phoneNumber = $request->phone_number;
            $otp = $request->otp;
            $resetToken = $request->reset_token;

            $resetRecord = DB::table('password_resets')
                ->where('phone_number', $phoneNumber)
                ->where('reset_token', $resetToken)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid or expired reset token'
                ], 400);
            }

            if ($resetRecord->otp !== $otp) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid OTP. Please try again.'
                ], 400);
            }

            DB::table('password_resets')
                ->where('phone_number', $phoneNumber)
                ->where('reset_token', $resetToken)
                ->update([
                    'is_verified' => true,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'status' => 200,
                'message' => 'OTP verified successfully',
                'reset_token' => $resetToken
            ]);
        } catch (\Exception $e) {
            \Log::error('Verify OTP Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while verifying OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string',
                'otp' => 'required|string|size:6',
                'reset_token' => 'required|string',
                'password' => 'required|string|min:6|confirmed'
            ]);

            $phoneNumber = $request->phone_number;
            $otp = $request->otp;
            $resetToken = $request->reset_token;

            $resetRecord = DB::table('password_resets')
                ->where('phone_number', $phoneNumber)
                ->where('reset_token', $resetToken)
                ->where('otp', $otp)
                ->where('is_verified', true)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid or expired reset request. Please start over.'
                ], 400);
            }

            $user = User::where('username', $phoneNumber)->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found'
                ], 404);
            }

            $user->setPasswordAttribute($request->password);
            $user->save();

            DB::table('password_resets')
                ->where('phone_number', $phoneNumber)
                ->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Password reset successfully. You can now login with your new password.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Reset Password Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while resetting password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

