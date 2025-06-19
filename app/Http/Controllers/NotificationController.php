<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 1. عرض جميع الإشعارات لمستخدم معيّن
    public function index($userId)
    {
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // 2. إنشاء إشعار جديد (يدويًا)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'message' => 'Notification created successfully.',
            'data' => $notification,
        ], 201);
    }

    // 3. حذف إشعار واحد
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully.']);
    }

    // 4. حذف جميع الإشعارات لمستخدم
    public function clearUserNotifications($userId)
    {
        Notification::where('user_id', $userId)->delete();

        return response()->json(['message' => 'All notifications cleared for this user.']);
    }
}
