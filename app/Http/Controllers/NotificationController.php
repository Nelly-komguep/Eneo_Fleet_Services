<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            'notifications' => Notification::latest()->take(10)->get(),
            'unread_count' => Notification::where('is_read', false)->count(),
        ]);
    }

    public function markAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}
