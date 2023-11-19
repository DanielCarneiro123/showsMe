<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showAdminPage()
    {
        $activeUsers = User::where('active', true)->get();
        $inactiveUsers = User::where('active', false)->get();
        return view('pages.admin', compact('activeUsers', 'inactiveUsers'));
    }

    public function deactivateUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Deactivate the user
        $user->active = false;
        $user->save();

        // Deactivate all events created by the user
        $user->own_events()->update(['private' => true]);
        // Redirect back or to a specific route
        return redirect()->route('admin')->with('success', 'User deactivated successfully');
    }

    public function activateUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Activate the user
        $user->active = true;
        $user->save();

        // Activate all events created by the user if needed
        $user->own_events()->update(['private' => false]);

        // Redirect back or to a specific route
        return redirect()->route('admin')->with('success', 'User activated successfully');
    }
}
