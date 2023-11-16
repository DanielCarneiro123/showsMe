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

    public function createEvent()
{
    // Logic to create a new event with creator_id = 1

    // For example:
    $event = new Event();
    $event->name = 'Default Event';
    $event->description = 'Description of the default event';
    $event->location = 'Default Event Location';
    $event->start_timestamp = now(); // Set the start timestamp
    $event->end_timestamp = now()->addDays(7); // Set the end timestamp
    $event->creator_id = 1; // Set the creator_id

    $event->save();

    // Redirect back to the My Events page or any other page
    return redirect('/my-events');
}


}
?>