<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $notifications = Notification::with('event') // Eager load the associated event
            ->where('notified_user', auth()->id())
            ->latest('timestamp')
            ->get();

        // Transform the notifications to include the event name
        $transformedNotifications = $notifications->map(function ($notification) {
            return [
                'event_id' => $notification->event_id,
                'event_name' => $notification->event ? $notification->event->name : null,
                'notification_type' => $notification->notification_type,
                'timestamp' => $notification->timestamp,
            ];
        });

        return response()->json(['notifications' => $transformedNotifications]);
    }

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index');
    }

}
