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
    
     
        $comment->load('author');
    
        return response()->json(['message' => $comment]);
    }

    public function editComment(Request $request)
    {
        $commentID = $request->input('comment_id');
        $newCommentText = $request->input('newCommentText');
    
        $comment = Comment::find($commentID);
        if ($comment) {
            $comment->text = $newCommentText;
            $comment->save();
        }
    
        
        return response()->json(['message' => $comment]);
    }

    public function deleteComment(Request $request)
{
    $commentID = $request->input('comment_id');

    $comment = Comment::find($commentID);

    if ($comment) {
        // Delete reports associated with the comment
        $comment->reports()->each(function ($report) {
            // Delete notifications associated with the report
            $report->notifications()->delete();

            // Delete the report
            $report->delete();
        });

        // Delete notifications associated with the comment
        $comment->notifications()->delete();

        // Perform the deletion logic for the comment
        $comment->delete();

        return response()->json(['message' => $comment]);
    }

    return response()->json(['message' => 'Comment not found'], 404);
}


    
    
}
