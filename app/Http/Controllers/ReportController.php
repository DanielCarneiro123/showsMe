<?php

namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /*public function deleteReport(Report $report)
    {
        // Delete associated notifications first
        $report->notifications()->delete();

        // Now, delete the report
        $report->delete();

        // Redirect or respond as needed
    }*/

    public function submitReport(Request $request)
    {
        // Validate the report data
        $request->validate([
            'reportReason' => 'required',
        ]);

      
        $report = new Report();
        $report->type = $request->input('reportReason');
        $report->comment_id = $request->input('comment_id');
        $report->author_id = auth()->user()->user_id; 
        $report->save();

       
        return redirect()->route('view-event', ['id' => $request->input('event_id')]);
    }
}
