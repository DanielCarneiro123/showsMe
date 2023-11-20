<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function getCurrentUser()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

   // UserController.php
    public function updateProfile(Request $request)
    {
        $this->authorize('updateProfile', Auth::user());

        Auth::user()->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'promotor_code' => $request->input('promotor_code'),
            'phone_number' => $request->input('phone_number'),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

}
