<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminNotificationController extends Controller
{
    // Full notifications list page
    public function index(Request $request)
    {
        $type = $request->input('type');
        $query = AdminNotification::orderByDesc('created_at');
        if ($type) $query->where('type', $type);
        $notifications = $query->paginate(20);

        // Mark all as read when viewing full list
        AdminNotification::markAllRead();

        return view('admin.notifications.index', compact('notifications', 'type'));
    }

    // Bell dropdown — latest 10 (JSON)
    public function dropdown()
    {
        $items = AdminNotification::latest10();
        $unread = AdminNotification::unreadCount();
        return Response::json(['items' => $items, 'unread' => $unread]);
    }

    // Mark single as read + redirect to action_url
    public function markRead($id)
    {
        $n = AdminNotification::findOrFail($id);
        $n->markRead();
        $url = $n->action_url ?: route('admin.dashboard');
        return redirect($url);
    }

    // Mark all read (AJAX)
    public function markAllRead()
    {
        AdminNotification::markAllRead();
        return Response::json(['status' => 'success']);
    }

    // Delete single
    public function destroy($id)
    {
        AdminNotification::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Notification deleted.');
    }

    // Delete all read
    public function clearRead()
    {
        AdminNotification::where('is_read', 1)->delete();
        return redirect()->back()->with('success', 'Read notifications cleared.');
    }
}
