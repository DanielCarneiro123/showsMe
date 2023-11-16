<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketInstance;
use Illuminate\View\View;

class TicketController extends Controller
{
    
    public function myTickets(): View
    {
        // Fetch ticket instances by buyer_id = 1 with eager loading
        $ticketInstances = TicketInstance::with(['order', 'ticketType.event'])
            ->whereHas('order', function ($query) {
                $query->where('buyer_id', 1);
            })->get();

        return view('pages.my_tickets', compact('ticketInstances'));
    }
}
?>