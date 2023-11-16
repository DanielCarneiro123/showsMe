<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\View\View;

class AllEventsController extends Controller
{
    public function index(): View
    {
        $events = Event::all();
        return view('pages.allevents', compact('events'));
    }
}
