<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Events\PostComment;


class CommentController extends Controller
{

    function comment(Request $request) {
        event(new PostComment($request->id));
    }



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

    public function hideComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Comment not found']);
        }
        $comment->update(['private' => true]);

        return response()->json(['status' => 'success', 'message' => 'Comment hidden successfully']);
    }

    public function showComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Comment not found']);
        }
        $comment->update(['private' => false]);

        return response()->json(['status' => 'success', 'message' => 'Comment shown successfully']);
    }


}
