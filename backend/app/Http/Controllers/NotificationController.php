<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::forUser($request->user()->id)
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::forUser($request->user()->id)
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = Notification::forUser($request->user()->id)
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::forUser($request->user()->id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}