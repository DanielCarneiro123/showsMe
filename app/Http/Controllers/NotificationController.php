<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /*public function getNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())->get();

        return response()->json(['notifications' => $notifications]);
    }*/

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index');
    }

}
