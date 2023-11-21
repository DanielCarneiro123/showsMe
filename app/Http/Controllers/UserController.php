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
           //$this->authorize('updateProfile', Auth::user());
   
           Auth::user()->update([
            'email' => $request->input('edit_email'),
            'name' => $request->input('edit_name'),
            'promotor_code' => $request->input('edit_promotor_code'),
            'phone_number' => $request->input('edit_phone_number'),
        ]);

   
           return response()->json(['message' => 'Perfil atualizado com sucesso']);
   }

}
