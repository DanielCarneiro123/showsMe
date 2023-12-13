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


    public function processPayment(Request $request, $eventId){
        if (Auth::guest()) {
            $user = EventController.createTemporaryAccount($request);
        } else {
            $user = Auth::user();
        }
        $quantities = $request->input('quantity', []);

        if (empty(array_filter($quantities, function ($quantity) {
            return $quantity > 0;
        }))) {
            return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Select at least one ticket type.');
        }

        $lineItems = [];

        foreach ($quantities as $ticketTypeId => $quantity) {
            if ($quantity > 0) {
                $ticketType = TicketType::findOrFail($ticketTypeId);

                // Determine o mínimo entre stock e person_buying_limit
                $minQuantity = min($ticketType->stock, $ticketType->person_buying_limit);

                // Verifique se a quantidade é um número inteiro positivo e menor que o mínimo
                if (is_numeric($quantity) && $quantity == (int) $quantity && $quantity > 0 && $quantity <= $minQuantity) {

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
                else {
                    return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Invalid quantity for ticket type');
                }
            }
        }

        session(['purchase_user' => $user]);
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
    } 
}
