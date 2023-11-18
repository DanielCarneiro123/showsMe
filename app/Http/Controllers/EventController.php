<?php 
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketOrder;
use App\Models\TicketInstance;


class EventController extends Controller
{
    public function view($id): View
    {
        $event = Event::findOrFail($id);

        return view('pages.event', compact('event'));
    }

    public function index(): View
    {
        $events = Event::paginate(8);
        return view('pages.allevents', compact('events'));
    }
    public function myEvents(): View
    {
        
        $events = Event::where('creator_id', Auth::user()->user_id)->get();

        return view('pages.my_events', compact('events'));
    }

    public function createEvent(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'start_timestamp' => 'required|date',
            'end_timestamp' => 'required|date|after:start_timestamp',
            // Add other validation rules for your fields
        ]);

        // Create a new event with the provided data
        $event = new Event();
        $event->name = $request->input('name');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->start_timestamp = $request->input('start_timestamp');
        $event->end_timestamp = $request->input('end_timestamp');
        $event->creator_id = Auth::user()->user_id; 

        // Save the event
        $event->save();

        // Redirect back to the My Events page or any other page
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
        $this->authorize('update', $event); //se não for o user_id quem criou o evento, a action nao é autrizada
    
        // Update the event with the provided data
        $event->name = $request->input('edit_name');
        $event->description = $request->input('edit_description');
        $event->location = $request->input('edit_location');
        $event->start_timestamp = $request->input('edit_start_timestamp');
        $event->end_timestamp = $request->input('edit_end_timestamp');
    
        // Save the updated event
        $event->save();
    
        // Redirect back to the event page or any other page
        return redirect()->route('view-event', ['id' => $id]);
    }
    
    
    public function showCreateEvent()
    {
    
        return view('pages.create_event');
    }

    public function purchaseTickets(Request $request, $eventId)
{
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

    

}
?>