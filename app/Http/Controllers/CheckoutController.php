<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class CheckoutController extends Controller
{
    public function showCheckoutPage($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('pages.checkout', compact('event'));
    }

}
