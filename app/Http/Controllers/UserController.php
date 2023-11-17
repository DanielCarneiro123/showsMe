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
        return view('pages.admin');
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

}
