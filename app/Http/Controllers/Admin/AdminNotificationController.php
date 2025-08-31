<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    /**
     * Display notifications page
     */
    public function index()
    {
        $notifications = NotificationService::getRecent(50);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get recent notifications for dropdown (AJAX)
     */
    public function recent()
    {
        $notifications = NotificationService::getRecent(10);
        $unreadCount = NotificationService::getUnreadCount();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        NotificationService::markAsRead($id);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead();
        
        return response()->json(['success' => true]);
    }
}