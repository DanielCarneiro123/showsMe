<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\TicketOrder;
use App\Models\TicketInstance;
use Illuminate\Auth\Access\AuthorizationException; 

class StripeController extends Controller
{
    public function showPaymentForm()
    {
    
        return view('pages.payment');
    }


public function processPayment(Request $request, $eventId)
    {
        try {
            // ... (Your existing code for finding the event and setting up Stripe)
    
            $quantities = $request->input('quantity', []);
    
            if (empty(array_filter($quantities, function ($quantity) {
                return $quantity > 0;
            }))) {
                // Handle the case where no tickets are selected
                return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Select at least one ticket type.');
            }
    
            $lineItems = [];
    
            foreach ($quantities as $ticketTypeId => $quantity) {
                if ($quantity > 0) {
                    $ticketType = TicketType::findOrFail($ticketTypeId);
    
                    // Add the ticket type to line_items
                    $lineItems[] = [
                        "quantity" => $quantity,
                        "price_data" => [
                            "currency" => "eur",
                            "unit_amount" => $ticketType->price * 100, // Convert to cents
                            "product_data" => [
                                "name" => $ticketType->name,
                            ],
                        ],
                    ];
                }
            }


            session(['purchase_event_id' => $eventId]);
            session(['purchase_quantities' => $quantities]);
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $checkoutSession = Session::create([
                "mode" => "payment",
                "success_url" => url('/purchase-tickets/'),
                "cancel_url" => url('/view-event/' . $eventId),
                "line_items" => $lineItems,
            ]);
    
            return redirect()->to($checkoutSession->url);
    
        } catch (AuthorizationException $e) {
            return redirect()->route('login')->with('error', 'You must be logged in to purchase tickets.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Invalid ticket type.');
        }
    }
} 
