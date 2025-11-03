<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Services\SmsService;
use App\Mail\RiderPickupRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SamplePickupRequestController extends Controller
{
    protected $smsService;
    protected $notificationService;

    public function __construct(SmsService $smsService, NotificationService $notificationService)
    {
        $this->middleware('auth')->except(['requestRiderApi']);
        $this->smsService = $smsService;
        $this->notificationService = $notificationService;
    }

    public function viewPreparedSamples()
    {
        if (!Auth::user()->hasRole(['hub_coordinator', 'national_hub_coordinator', 'regional_hub_coordinator'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access');
        }

        $hubId = Auth::user()->hubid;

        $query = "SELECT p.id, p.barcode, p.status, p.facilityid, p.hubid, p.numberofsamples, 
                  p.created_at, p.date_picked, p.test_type,
                  f.name as facility_name, 
                  h.name as hub_name,
                  tt.name as test_type_name
                  FROM package p
                  INNER JOIN facility f ON p.facilityid = f.id
                  INNER JOIN facility h ON p.hubid = h.id
                  LEFT JOIN testtypes tt ON p.test_type = tt.id
                  WHERE p.hubid = ? AND p.status = 0
                  ORDER BY p.created_at DESC";

        $preparedSamples = DB::select($query, [$hubId]);

        return view('samples.prepared_samples', compact('preparedSamples'));
    }

    public function requestRider(Request $request, $packageId)
    {
        if (!Auth::user()->hasRole(['hub_coordinator', 'national_hub_coordinator', 'regional_hub_coordinator'])) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            $hubId = Auth::user()->hubid;
            
            Log::info('Rider request initiated', [
                'package_id' => $packageId,
                'hub_id' => $hubId,
                'user_id' => Auth::id()
            ]);

            $package = DB::table('package')
                ->leftJoin('facility as f', 'package.facilityid', '=', 'f.id')
                ->leftJoin('facility as h', 'package.hubid', '=', 'h.id')
                ->leftJoin('testtypes as tt', 'package.test_type', '=', 'tt.id')
                ->where('package.id', $packageId)
                ->where('package.hubid', $hubId)
                ->select(
                    'package.*',
                    'f.name as facility_name',
                    'h.name as hub_name',
                    'tt.name as test_type_name'
                )
                ->first();

            if (!$package) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Package not found or does not belong to your hub'
                ], 404);
            }

            $riders = DB::table('users')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->leftJoin('staff', 'users.staff_id', '=', 'staff.id')
                ->where('users.hubid', $hubId)
                ->whereIn('roles.name', ['sample_transporter', 'special_sample_transportor', 'private_rider'])
                ->where('users.isactive', 1)
                ->select(
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.email',
                    DB::raw('COALESCE(staff.telephonenumber, 
                             CASE 
                                 WHEN users.username REGEXP "^[0-9+]" THEN users.username
                                 ELSE NULL 
                             END) as phone_number')
                )
                ->get();

            if ($riders->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No active riders found for your hub'
                ], 404);
            }

            $packageData = [
                'barcode' => $package->barcode,
                'facility' => $package->facility_name,
                'hub' => $package->hub_name,
                'samples' => $package->numberofsamples,
                'test_type' => $package->test_type_name ?? 'N/A',
                'date_prepared' => date('Y-m-d H:i', strtotime($package->created_at)),
            ];

            $emailCount = 0;
            $smsCount = 0;
            $appNotificationCount = 0;

            foreach ($riders as $rider) {
                if ($rider->email && filter_var($rider->email, FILTER_VALIDATE_EMAIL) && !str_contains($rider->email, '@dev.com')) {
                    try {
                        $riderUser = (object)[
                            'name' => $rider->name,
                            'email' => $rider->email,
                        ];
                        Mail::to($rider->email)->send(new RiderPickupRequest($riderUser, $packageData));
                        $emailCount++;
                        Log::info('Pickup request email sent to rider', [
                            'rider_id' => $rider->id,
                            'email' => $rider->email,
                            'package_id' => $packageId
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send email to rider', [
                            'rider_id' => $rider->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                if ($rider->phone_number) {
                    try {
                        $smsMessage = "RESTRACK: New sample pickup request!\n\n";
                        $smsMessage .= "Package: {$packageData['barcode']}\n";
                        $smsMessage .= "Facility: {$packageData['facility']}\n";
                        $smsMessage .= "Samples: {$packageData['samples']}\n";
                        $smsMessage .= "Test: {$packageData['test_type']}\n\n";
                        $smsMessage .= "Please pick up this package at your earliest convenience.";

                        $this->smsService->sendSMS($rider->phone_number, $smsMessage);
                        $smsCount++;
                        Log::info('Pickup request SMS sent to rider', [
                            'rider_id' => $rider->id,
                            'phone' => $rider->phone_number,
                            'package_id' => $packageId
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send SMS to rider', [
                            'rider_id' => $rider->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                try {
                    $this->notificationService->sendNotification(
                        $rider->username,
                        "New sample pickup request! Package {$packageData['barcode']} from {$packageData['facility']} is ready for pickup. {$packageData['samples']} samples.",
                        'APP',
                        'PICKUP_REQUEST'
                    );
                    $appNotificationCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send app notification to rider', [
                        'rider_id' => $rider->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            try {
                $existingRequest = DB::table('package_pickup_requests')
                    ->where('package_id', $packageId)
                    ->where('created_at', '>', now()->subHours(24))
                    ->first();
                
                if ($existingRequest) {
                    DB::table('package_pickup_requests')
                        ->where('id', $existingRequest->id)
                        ->update([
                            'riders_notified' => $riders->count(),
                            'emails_sent' => $emailCount,
                            'sms_sent' => $smsCount,
                            'app_notifications_sent' => $appNotificationCount,
                            'updated_at' => now(),
                        ]);
                    Log::info('Updated existing pickup request', ['package_id' => $packageId]);
                } else {
                    DB::table('package_pickup_requests')->insert([
                        'package_id' => $packageId,
                        'requested_by' => Auth::id(),
                        'hub_id' => $hubId,
                        'riders_notified' => $riders->count(),
                        'emails_sent' => $emailCount,
                        'sms_sent' => $smsCount,
                        'app_notifications_sent' => $appNotificationCount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('Created new pickup request', ['package_id' => $packageId]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to save pickup request to database', [
                    'package_id' => $packageId,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Pickup request sent successfully',
                'summary' => [
                    'total_riders' => $riders->count(),
                    'emails_sent' => $emailCount,
                    'sms_sent' => $smsCount,
                    'app_notifications' => $appNotificationCount,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending pickup request', [
                'package_id' => $packageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'Error sending pickup request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPickupRequests()
    {
        if (!Auth::user()->hasRole(['hub_coordinator', 'national_hub_coordinator', 'regional_hub_coordinator'])) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $hubId = Auth::user()->hubid;

        $requests = DB::table('package_pickup_requests as ppr')
            ->join('package as p', 'ppr.package_id', '=', 'p.id')
            ->join('users as u', 'ppr.requested_by', '=', 'u.id')
            ->join('facility as f', 'p.facilityid', '=', 'f.id')
            ->where('ppr.hub_id', $hubId)
            ->select(
                'ppr.*',
                'p.barcode',
                'p.numberofsamples',
                'f.name as facility_name',
                'u.name as requested_by_name'
            )
            ->orderBy('ppr.created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    public function requestRiderApi(Request $request, $packageId)
    {
        try {
            $package = DB::table('package')
                ->leftJoin('facility as f', 'package.facilityid', '=', 'f.id')
                ->leftJoin('facility as h', 'package.hubid', '=', 'h.id')
                ->leftJoin('testtypes as tt', 'package.test_type', '=', 'tt.id')
                ->where('package.id', $packageId)
                ->select(
                    'package.*',
                    'f.name as facility_name',
                    'h.name as hub_name',
                    'tt.name as test_type_name'
                )
                ->first();

            if (!$package) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Package not found'
                ], 404);
            }

            $hubId = $package->hubid;

            $riders = DB::table('staff')
                ->join('users', 'staff.user_id', '=', 'users.id')
                ->where('staff.hubid', $hubId)
                ->where('staff.designation', 1)
                ->where('staff.isactive', 1)
                ->select(
                    'staff.id as staff_id',
                    'staff.firstname',
                    'staff.lastname',
                    'staff.emailaddress',
                    'staff.telephonenumber',
                    'users.id as user_id',
                    'users.username'
                )
                ->get();

            if ($riders->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No active riders found for this hub'
                ], 404);
            }

            $pickupRequest = DB::table('package_pickup_requests')->insertGetId([
                'package_id' => $packageId,
                'hub_id' => $hubId,
                'requested_by' => 1,
                'riders_notified' => 0,
                'emails_sent' => 0,
                'sms_sent' => 0,
                'app_notifications_sent' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $emailsSent = 0;
            $smsSent = 0;
            $appNotifications = 0;

            foreach ($riders as $rider) {
                if ($rider->emailaddress) {
                    try {
                        $this->notificationService->sendNotification(
                            $rider->username,
                            "Pickup request for package {$package->barcode} at {$package->facility_name}. Contact hub for details.",
                            'EMAIL',
                            'PICKUP_REQUEST'
                        );
                        $emailsSent++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send email to rider {$rider->staff_id}: " . $e->getMessage());
                    }
                }

                if ($rider->telephonenumber) {
                    try {
                        $this->smsService->sendSMS(
                            $rider->telephonenumber,
                            "RESTRACK: Pickup needed for package {$package->barcode} at {$package->facility_name}"
                        );
                        $smsSent++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send SMS to rider {$rider->staff_id}: " . $e->getMessage());
                    }
                }

                try {
                    $this->notificationService->sendNotification(
                        $rider->username,
                        "Pickup request for package {$package->barcode} at {$package->facility_name}",
                        'APP',
                        'PICKUP_REQUEST'
                    );
                    $appNotifications++;
                } catch (\Exception $e) {
                    Log::error("Failed to send app notification to rider {$rider->staff_id}: " . $e->getMessage());
                }
            }

            DB::table('package_pickup_requests')
                ->where('id', $pickupRequest)
                ->update([
                    'riders_notified' => $riders->count(),
                    'emails_sent' => $emailsSent,
                    'sms_sent' => $smsSent,
                    'app_notifications_sent' => $appNotifications
                ]);

            return response()->json([
                'status' => 200,
                'message' => 'Pickup request sent successfully',
                'summary' => [
                    'total_riders' => $riders->count(),
                    'emails_sent' => $emailsSent,
                    'sms_sent' => $smsSent,
                    'app_notifications' => $appNotifications
                ],
                'package' => [
                    'id' => $package->id,
                    'barcode' => $package->barcode,
                    'facility' => $package->facility_name
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in requestRiderApi: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error sending pickup request: ' . $e->getMessage()
            ], 500);
        }
    }
}

