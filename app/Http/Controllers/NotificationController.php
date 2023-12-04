<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
    
        
       
        
       
    }
}
