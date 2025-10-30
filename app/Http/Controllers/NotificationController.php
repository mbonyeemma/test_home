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

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
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

        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $notifications->count(),
            'data' => $notifications
        ]);
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

        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount
        ]);
    }
}

