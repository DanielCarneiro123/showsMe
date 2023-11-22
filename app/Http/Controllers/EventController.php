<?php 
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\TicketOrder;
use App\Models\TicketInstance;
use Illuminate\Auth\Access\AuthorizationException; 

use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function view($id): View
    { 
        $event = Event::findOrFail($id);

        return view('pages.event', compact('event'));
    }

    public function index(): View
    {
        $user = Auth::user();

        if ($user && $user->is_admin) {
            $events = Event::paginate(8);
        } else {
            $events = Event::where('private', false)->paginate(8);
        }

        return view('pages.all_events', compact('events', 'user'));
    }

    public function myEvents(): View
    {
        if (Auth::check()) {
            $events = Event::where('creator_id', Auth::user()->user_id)->get();
        } else {
            $events = collect();
        }
        return view('pages.my_events', compact('events'));
    }
    

    public function createEvent(Request $request)
    {
        $this->authorize('createEvent', Event::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'start_timestamp' => 'required|date',
            'end_timestamp' => 'required|date|after:start_timestamp',
        ]);

        $event = new Event();
        $event->name = $request->input('name');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->start_timestamp = $request->input('start_timestamp');
        $event->end_timestamp = $request->input('end_timestamp');
        $event->creator_id = Auth::user()->user_id; 

        $event->save();

        return redirect('/my-events');
    }

    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'edit_name' => 'required|string|max:255',
            'edit_description' => 'nullable|string',
            'edit_location' => 'required|string',
            'edit_start_timestamp' => 'required|date',
            'edit_end_timestamp' => 'required|date|after:edit_start_timestamp',
           
        ], [
            'edit_end_timestamp.after' => 'The end timestamp must be a date after the start timestamp.',
         
        ]);
    
        $event = Event::findOrFail($id);

        $this->authorize('updateEvent', $event); 
    
        $event->name = $request->input('edit_name');
        $event->description = $request->input('edit_description');
        $event->location = $request->input('edit_location');
        $event->start_timestamp = $request->input('edit_start_timestamp');
        $event->end_timestamp = $request->input('edit_end_timestamp');
    
        $event->save();
    
        return redirect()->route('view-event', ['id' => $id]);
    }

    public function deactivateEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        $event->private = true;
        $event->save();
        return response()->json(['message' => 'deactivated successfully']);
       // return redirect()->back()->with('success', 'Event deactivated successfully.');
    }

    public function activateEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $event->private = false;
        $event->save();
        return response()->json(['message' => 'activated successfully']);
//        return redirect()->back()->with('success', 'Event activated successfully.');
    }

    public function createTicketType(Request $request, Event $event)
    {
        $this->authorize('updateEvent', $event);

        $request->validate([
            'ticket_name' => 'required|string|max:255',
            'ticket_stock' => 'required|integer|min:0',
            'ticket_description' => 'nullable|string',
            'ticket_person_limit' => 'required|integer|min:0',
            'ticket_price' => 'required|numeric|min:0',
            'ticket_start_timestamp' => 'required|date',
            'ticket_end_timestamp' => 'required|date|after:ticket_start_timestamp',
        ]);


        $ticketType = new TicketType();
        $ticketType->name = $request->input('ticket_name');
        $ticketType->stock = $request->input('ticket_stock');
        $ticketType->description = $request->input('ticket_description');
        $ticketType->person_buying_limit = $request->input('ticket_person_limit');
        $ticketType->price = $request->input('ticket_price');
        $ticketType->start_timestamp = $request->input('ticket_start_timestamp');
        $ticketType->end_timestamp = $request->input('ticket_end_timestamp');

        $ticketType->event()->associate($event);
        $ticketType->save();

        return response()->json(['message' => 'TicketType created successfully', 'ticketType' => $ticketType]);
    }



    
    
    public function showCreateEvent()
    {
    
        return view('pages.create_event');
    }

    public function purchaseTickets(Request $request, $eventId)
{
    try {
        $event = Event::findOrFail($eventId);

        $this->authorize('purchaseTickets', $event);

        $quantities = $request->input('quantity', []);

        if (empty(array_filter($quantities, function ($quantity) {
            return $quantity > 0;
        }))) {
            return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Select at least one ticket type.');
        }

        $buyer = Auth::user();

        $order = new TicketOrder();
        $order->timestamp = now();
        $order->promo_code = null;
        $order->buyer_id = $buyer->user_id;
        $order->save();

        foreach ($quantities as $ticketTypeId => $quantity) {
            if ($quantity > 0) {
                // Obtenha o TicketType associado ao ticketTypeId
                $ticketType = TicketType::findOrFail($ticketTypeId);

                // Determine o mínimo entre stock e person_buying_limit
                $minQuantity = min($ticketType->stock, $ticketType->person_buying_limit);

                // Verifique se a quantidade é um número inteiro positivo e menor que o mínimo
                if (is_numeric($quantity) && $quantity == (int) $quantity && $quantity > 0 && $quantity <= $minQuantity) {
                    for ($i = 0; $i < $quantity; $i++) {
                        $ticketInstance = new TicketInstance();
                        $ticketInstance->ticket_type_id = $ticketTypeId;
                        $ticketInstance->order_id = $order->order_id;
                        $ticketInstance->save();
                    }
                } else {
                    return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Invalid quantity for ticket type.');
                }
            }
        }

        return redirect()->route('my-tickets')->with('success', 'Tickets purchased successfully.');
    } catch (AuthorizationException $e) {
        return redirect()->route('login')->with('error', 'You must be logged in to purchase tickets.');
    } catch (ModelNotFoundException $e) {
        return redirect()->route('view-event', ['id' => $eventId])->with('error', 'Invalid ticket type.');
    }
}

    
    public function searchEvents(Request $request)
{
    $query = $request->input('query');

    $events = Event::whereRaw('tsvectors @@ plainto_tsquery(?)', [$query])
        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(?)) DESC', [$query])
        ->paginate(10);

    return view('pages.all_events', compact('events'));
}

    

}
?>