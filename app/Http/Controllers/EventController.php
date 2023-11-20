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

        // Check if the user is an admin
        if ($user && $user->is_admin) {
            // Admins can see all events
            $events = Event::paginate(8);
        } else {
            // Regular users can see only public events
            $events = Event::where('private', false)->paginate(8);
        }

        return view('pages.allevents', compact('events', 'user'));
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
        $this->authorize('createEvent', Auth::user());

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
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
        // Validate the form data
        $request->validate([
            'edit_name' => 'required|string|max:255',
            'edit_description' => 'nullable|string',
            'edit_location' => 'nullable|string',
            'edit_start_timestamp' => 'required|date',
            'edit_end_timestamp' => 'required|date|after:edit_start_timestamp',
            // Adicione outras regras de validação conforme necessário
        ]);
    
        // Find the event by ID
        $event = Event::findOrFail($id);

        // Verificar a autorização usando a política
        $this->authorize('updateEvent', $event); //se não for o user_id quem criou o evento, a action nao é autrizada
    
        // Update the event with the provided data
        $event->name = $request->input('edit_name');
        $event->description = $request->input('edit_description');
        $event->location = $request->input('edit_location');
        $event->start_timestamp = $request->input('edit_start_timestamp');
        $event->end_timestamp = $request->input('edit_end_timestamp');
    
        // Save the updated event
        $event->save();
    
        // Redirect back to the event page or any other page
        return response()->json(['message' => 'Atualização bem-sucedida']);
    }

    /*public function deleteEvent(Event $event)
    {
        // Check if the user is an admin
        if (auth()->user() && auth()->user()->is_admin) {
            // Delete comments and reports associated with the event
            $event->comments()->each(function ($comment) {
                $comment->reports()->delete();
                $comment->delete();
            });

            // Delete ratings associated with the event
            $event->ratings()->delete();

            // Delete the event
            $event->delete();

            // Redirect to a page after deletion (you can customize this)
            return redirect()->route('allevents')->with('success', 'Event deleted successfully.');
        } else {
            // Redirect if the user is not an admin
            return redirect()->route('allevents')->with('error', 'You do not have permission to delete this event.');
        }
    }*/

    public function deactivateEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Update the 'private' field to deactivate the event
        $event->private = true;
        $event->save();

        return redirect()->route('allevents')->with('success', 'Event deactivated successfully.');
    }

    public function activateEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Update the 'private' field to activate the event
        $event->private = false;
        $event->save();

        return redirect()->route('allevents')->with('success', 'Event activated successfully.');
    }

    public function createTicketType(Request $request, Event $event)
    {
        $this->authorize('createTicketType', $event);

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

        return redirect('/view-event/'.$event->event_id);
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

            if (empty($quantities)) {
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
                    for ($i = 0; $i < $quantity; $i++) {
                        $ticketInstance = new TicketInstance();
                        $ticketInstance->ticket_type_id = $ticketTypeId;
                        $ticketInstance->order_id = $order->order_id;
                        $ticketInstance->save();
                    }
                }
            }

            return redirect()->route('view-event', ['id' => $eventId])->with('success', 'Tickets purchased successfully.');
        } 
        
        catch (AuthorizationException $e) 
            {
                return redirect()->route('login')->with('error', 'You must be logged in to purchase tickets.');
            }
    }

    

}
?>