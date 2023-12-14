<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $notifications = Notification::with(['event', 'report.comment.event']) 
            ->where('notified_user', auth()->id())
            ->latest('timestamp')
            ->get();

        $transformedNotifications = $notifications->map(function ($notification) {
            $eventName = null;
        
            if ($notification->notification_type === 'Event' || $notification->notification_type === 'Comment') {
                $eventName = $notification->event ? $notification->event->name : null;
                $notificationId = $notification->event_id;
            } elseif ($notification->notification_type === 'Report') {
                $eventName = $notification->report->comment->event->name;
                $notificationId = $notification->report->comment->event->event_id;
            }
        
            return [
                'event_id' => $notificationId,
                'event_name' => $eventName,
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
