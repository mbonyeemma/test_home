<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MobileAppRegistration;

class MobileAppRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $user = new MobileAppRegistration;
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->setPasswordAttribute($request->password);
            $user->hubid = $request->hubid;
            $user->telephone_number = $request->telephone_number;
            $user->driving_permit = $request->driving_permit;
            $user->defensive_driving = $request->defensive_driving;
            $user->bb_training = $request->bb_training;
            $user->hep_b_immunisation = $request->hep_b_immunisation;
            $user->role = $request->role;
            $user->isactive = 0;
            $user->save();
            
        $ret['status'] = 200;
        $ret['status_desc'] = 'The User Saved has been successfully, Awaiting Approval';
        return response()->json($ret);
    } catch (\Exception $e) {
        //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        $ret['status'] = 501;
        $ret['status_desc'] = $e->getMessage();
        return response()->json($ret);
    }
    }

    public function storeUser(Request $request)
    {
        //
        try {
            $user = new MobileAppRegistration;
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->setPasswordAttribute($request->password);
            $user->hubid = $request->hubid;
            $user->telephone_number = $request->telephone_number;
            $user->driving_permit = $request->driving_permit;
            $user->defensive_driving = $request->defensive_driving;
            $user->bb_training = $request->bb_training;
            $user->hep_b_immunisation = $request->hep_b_immunisation;
            $user->role = $request->role;
            $user->isactive = 0;
            $user->save();
            
        $ret['status'] = 200;
        $ret['status_desc'] = 'The User Saved has been successfully, Awaiting Approval';
        return response()->json($ret);
    } catch (\Exception $e) {
        //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        $ret['status'] = 501;
        $ret['status_desc'] = $e->getMessage();
        return response()->json($ret);
    }
    }

    /**
     * Approve user and transfer to users table
     */
    public function approveUser($id)
    {
        try {
            // Get the registration record
            $registration = MobileAppRegistration::findOrFail($id);
            
            // Check if user already exists in users table
            $existingUser = \App\User::where('username', $registration->username)->first();
            if ($existingUser) {
                return response()->json([
                    'status' => 400,
                    'status_desc' => 'User already exists in system'
                ]);
            }

            // Create new user in users table
            $user = new \App\User();
            $user->username = $registration->username;
            $user->name = $registration->name;
            $user->email = $registration->email;
            // Transfer the password hash directly without re-bcrypting
            $user->password = $registration->password;
            $user->hubid = $registration->hubid;
            // Note: telephone_number column doesn't exist in users table, so we skip it
            
            // Set default values for required fields
            $user->facilityid = $registration->hubid; // Use hubid as facilityid if not specified
            $user->ref_lab = null; // Set to null if not specified
            $user->isactive = 1; // Set user as active
            
            $user->save();

            // Assign role to user if role exists in registration
            if ($registration->role) {
                // Map registration roles to database roles
                $roleMapping = [
                    'rider' => 'sample_transporter',
                    'driver' => 'driver',
                    'data_collector' => 'community_user',
                    'hub_cordinator' => 'hub_coordinator'
                ];
                
                $mappedRoleName = $roleMapping[$registration->role] ?? $registration->role;
                $role = \App\Models\Role::where('name', $mappedRoleName)->first();
                
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }

            // Update registration status
            $registration->isactive = 1;
            $registration->save();

            return response()->json([
                'status' => 200,
                'status_desc' => 'User approved and transferred successfully. User can now login.',
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $registration->role,
                'mapped_role' => $mappedRoleName ?? null,
                'note' => 'telephone_number stored in registration table only'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 501,
                'status_desc' => 'Error approving user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get pending registrations
     */
    public function getPendingRegistrations()
    {
        try {
            $registrations = MobileAppRegistration::where('isactive', 0)->get();
            return response()->json([
                'status' => 200,
                'data' => $registrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 501,
                'status_desc' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if user exists in users table (for login verification)
     */
    public function checkUserStatus($username)
    {
        try {
            // Check in registration table
            $registration = MobileAppRegistration::where('username', $username)->first();
            
            // Check in users table
            $user = \App\User::where('username', $username)->first();
            
            $status = [
                'username' => $username,
                'in_registration_table' => $registration ? true : false,
                'registration_status' => $registration ? ($registration->isactive ? 'approved' : 'pending') : 'not_found',
                'in_users_table' => $user ? true : false,
                'can_login' => $user ? true : false
            ];
            
            return response()->json([
                'status' => 200,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 501,
                'status_desc' => 'Error checking user status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function show(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }
}
