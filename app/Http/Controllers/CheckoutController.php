<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    public function showCheckoutPage()
    {
        $user = Auth::user();
        $cart = session()->get('cart', []);

        // Retrieve the event details (replace 'Event' with your actual event model)

        // Initialize an array to store combined information
        $checkoutItems = [];
        $notifications = $user ? $user->notifications : [];

        foreach ($cart as $ticketTypeId => $quantity) {
            // Retrieve the TicketType model
            $ticketType = TicketType::find($ticketTypeId);
            $event = Event::find($ticketType->event_id); // Assuming you're retrieving the event by ID
            // Add information to the array
            $checkoutItems[] = [
                'ticketType' => $ticketType,
                'quantity' => $quantity,
                'eventName' => $event->name,
            ];
        }

        return view('pages.checkout', compact('checkoutItems', 'notifications'));
    }
}
