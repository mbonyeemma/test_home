<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'getUnread', 'markAsRead', 'getCount']);
    }

    public function index(Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID required'
            ], 400);
        }

        try {
            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'data' => [],
                        'total' => 0,
                        'current_page' => 1,
                        'per_page' => 20,
                        'last_page' => 1
                    ],
                    'message' => 'Notifications table not yet initialized'
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notifications'
            ], 500);
        }
    }

    public function getUnread(Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID required'
            ], 400);
        }

        try {
            $notifications = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'count' => $notifications->count(),
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                return response()->json([
                    'status' => 'success',
                    'count' => 0,
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch unread notifications'
            ], 500);
        }
    }

    public function markAsRead($id, Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID required'
            ], 400);
        }

        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read'
        ]);
    }

    public function delete($id, Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification deleted'
        ]);
    }

    public function getCount(Request $request)
    {
        $userId = $request->input('user_id') ?? Auth::id();
        
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID required'
            ], 400);
        }

        try {
            $unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'status' => 'success',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                return response()->json([
                    'status' => 'success',
                    'unread_count' => 0
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notification count'
            ], 500);
        }
    }
}

