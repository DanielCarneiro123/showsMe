<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    

    public function showAdminPage()
    {
        $activeUsers = User::where('active', true)->get();
        $inactiveUsers = User::where('active', false)->get();
        return view('pages.admin', compact('activeUsers', 'inactiveUsers'));
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

   // UserController.php
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'promotor_code' => $request->input('promotor_code'),
            'phone_number' => $request->input('phone_number'),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
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
