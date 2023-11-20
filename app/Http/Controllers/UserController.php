<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

use Illuminate\Validation\Rule;


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
        $user = Auth::user();

    $request->validate([
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),


        ],
        'name' => 'required|string|max:255',
        'promotor_code' => 'nullable|string|max:255',
        'phone_number' => [
            'required',
            'string',
            'max:20',
            'regex:/^[0-9]+$/',
        ],
    ], [
        'email.unique' => 'The email address is already in use.',
        'phone_number.numeric' => 'The phone number must be a number.',
        'phone_number.regex' => 'The phone number must only contain numbers.',
    ]);

        $user->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'promotor_code' => $request->input('promotor_code'),
            'phone_number' => $request->input('phone_number'),
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

}
