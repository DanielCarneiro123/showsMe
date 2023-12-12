<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showAdminPage()
    {
        $this->authorize('verifyAdmin', Admin::class);

        $userCount = null;
        $eventCount = null;
        $activeEventCount = null;
        $inactiveEventCount = null;
        $eventCountByMonth = Event::countEventsByMonth(date('n')); // Contagem para o mês atual
        $eventCountByDay = Event::countEventsByDay(date('j')); // Contagem para o dia atual
        $eventCountByYear = Event::countEventsByYear(date('Y')); // Contagem para o ano atual
        $activeUsers = [];
        $inactiveUsers = [];

        $userCount = User::countUsers();
        $eventCount = Event::countEvents();
        $activeEventCount = Event::countActiveEvents();
        $inactiveEventCount = Event::countInactiveEvents();
        $activeUsers = User::where('active', true)->get();
        $inactiveUsers = User::where('active', false)->get();

        // Passa as contagens para a view
        return view('pages.admin', compact('userCount', 'eventCount', 'activeEventCount', 'inactiveEventCount', 'eventCountByMonth', 'eventCountByDay', 'eventCountByYear', 'activeUsers', 'inactiveUsers'));
    }



    public function deactivateUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        $this->authorize('verifyAdmin', Admin::class);

        // Deactivate the user
        $user->active = false;
        $user->save();

        // Deactivate all events created by the user
        $user->own_events()->update(['private' => true]);

        // Return a JSON response
        return response()->json(['user_id' => $user->user_id]);
    }


    public function activateUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        $this->authorize('verifyAdmin', Admin::class);
        // Activate the user
        $user->active = true;
        $user->save();

        // Activate all events created by the user if needed
        $user->own_events()->update(['private' => false]);

        // Redirect back or to a specific route
        return response()->json(['user_id' => $user->user_id]);
    }

    public function showUserCount()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            $userCount = User::countUsers();
            return view('pages.admin', compact('userCount'));
        } else {
            return redirect()->route('login');
        }
    }

    public function getActiveUserCount()
    {
        $activeUserCount = User::where('active', true)->count();
        return response()->json(['count' => $activeUserCount]);
    }

    public function getInactiveUserCount()
    {
        $inactiveUserCount = User::where('active', false)->count();
        return response()->json(['count' => $inactiveUserCount]);
    }

    public function getActiveEventCount()
    {
        $activeEventCount = Event::countActiveEvents();
        return response()->json(['count' => $activeEventCount]);
    }

    public function getInactiveEventCount()
    {
        $inactiveEventCount = Event::countInactiveEvents();
        return response()->json(['count' => $inactiveEventCount]);
    }

    public function getEventCountByMonth($month)
    {
        $eventCount = Event::countEventsByMonth($month);
        return response()->json(['count' => $eventCount]);
    }

    public function getEventCountByDay($day)
    {
        $eventCount = Event::countEventsByDay($day);
        return response()->json(['count' => $eventCount]);
    }

    public function getEventCountByYear($year)
    {
        $eventCount = Event::countEventsByYear($year);
        return response()->json(['count' => $eventCount]);
    }
}