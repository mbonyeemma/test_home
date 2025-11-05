<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Role;

class RoleSwitchController extends Controller
{
    /**
     * Switch to a different role for the authenticated user
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = Auth::user();
        $roleId = $request->role_id;

        // Check if user has this role
        if (!$user->hasRole($roleId)) {
            return response()->json([
                'status' => 400,
                'message' => 'User does not have this role'
            ], 400);
        }

        // Store the switched role in session
        session(['current_role_id' => $roleId]);

        return response()->json([
            'status' => 200,
            'message' => 'Role switched successfully',
            'current_role' => Role::find($roleId)
        ]);
    }

    /**
     * Get current active role
     */
    public function getCurrentRole()
    {
        $user = Auth::user();
        $currentRoleId = session('current_role_id');
        
        if ($currentRoleId) {
            $role = Role::find($currentRoleId);
            if ($role && $user->hasRole($role->id)) {
                return response()->json([
                    'status' => 200,
                    'current_role' => $role
                ]);
            }
        }

        // Return primary role if no specific role is set
        $primaryRole = $user->roles->first();
        return response()->json([
            'status' => 200,
            'current_role' => $primaryRole
        ]);
    }

    /**
     * Get all available roles for the user
     */
    public function getUserRoles()
    {
        $user = Auth::user();
        $roles = $user->roles;
        
        return response()->json([
            'status' => 200,
            'roles' => $roles
        ]);
    }
}
