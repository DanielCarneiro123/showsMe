<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;

class AdminController extends Controller
{
    public function showAdminPage()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        $this->authorize('verifyAdmin', Admin::class);
        $activeUsers = User::where('active', true)->get();
        $inactiveUsers = User::where('active', false)->get();

        $reportedComments = DB::table('Comment_')
            ->join('Report', 'Comment_.comment_id', '=', 'Report.comment_id')
            ->select('Comment_.*')
            ->distinct()
            ->get();

        return view('pages.admin', compact('activeUsers', 'inactiveUsers','notifications', 'reportedComments'));
    }

    public function deactivateUser($id)
{

    $user = User::findOrFail($id);

    $this->authorize('verifyAdmin', Admin::class);

    $user->active = false;
    $user->save();

    $user->own_events()->update(['private' => true]);

    return response()->json(['user_id' => $user->user_id]);
}


    public function activateUser($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('verifyAdmin', Admin::class);

        $user->active = true;
        $user->save();

        $user->own_events()->update(['private' => false]);

        return response()->json(['user_id' => $user->user_id]);
    }
}
