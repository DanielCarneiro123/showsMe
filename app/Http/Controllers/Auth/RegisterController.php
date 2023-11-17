<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'required|string|max:20|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'promotor_code' => NULL,
            'phone_number' => $request->phone_number,
            'is_admin' => false, // Assuming default value is false
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('allevents')->withSuccess('You have successfully registered & logged in!');
        }

        return back()->withErrors(['email' => 'Registration failed.'])->withInput();
    }
}
