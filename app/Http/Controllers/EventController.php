<?php 
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function view($id): View
    {
        $event = Event::findOrFail($id);

        return view('pages.event', compact('event'));
    }

    public function index(): View
    {
        $events = Event::all();
        return view('pages.allevents', compact('events'));
    }
    public function myEvents(): View
    {
        // Fetch events by creator_id = 1, temos que mudar depois para ser do user logged in
        $events = Event::where('creator_id', 1)->get();

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
        $event->creator_id = 1; // Assuming creator_id is set to 1 for now

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


}
?>