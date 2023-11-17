<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketInstance;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    
    public function myTickets(): View
    {
        
        $ticketInstances = TicketInstance::with(['order', 'ticketType.event'])
            ->whereHas('order', function ($query) {
                $query->where('buyer_id', Auth::user()->user_id);
            })->get();

        return view('pages.my_tickets', compact('ticketInstances'));
    }
    
}
?>