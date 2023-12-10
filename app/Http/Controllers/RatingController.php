<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function submitRating(Request $request, $eventId)
    {
      
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

      
        $rating = new Rating();
        $rating->event_id = $eventId;
        $rating->author_id = auth()->user()->user_id;
        $rating->rating = $request->input('rating');
        $rating->save();

      
        return redirect()->back()->with('success', 'Rating submitted successfully');
    }
}
