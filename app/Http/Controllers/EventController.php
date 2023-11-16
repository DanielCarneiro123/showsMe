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
}
?>