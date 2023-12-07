<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    /*public function deleteComment(Comment $comment)
    {
        // Delete reports associated with the comment
        $comment->reports()->each(function ($report) {
            // Delete notifications associated with the report
            $report->notifications()->delete();

            // Delete the report
            $report->delete();
        });

        // Perform the deletion logic for the comment
        $comment->delete();

        // Redirect or respond as needed
    }*/
    public function submitComment(Request $request)
    {
       

         $comment = new Comment();
         $comment->text = $request->input('newCommentText');
  
         $comment->event_id = $request->input('event_id'); 
         $comment->author_id = auth()->user()->user_id;
         $comment->save();
 
        
        return redirect()->back();
    }

}
