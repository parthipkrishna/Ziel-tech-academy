<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFeedbackSession;
use App\Models\StudentFeedback;
use Illuminate\Support\Facades\Auth;
class FeedbackSessionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'feedback_id'     => 'required|exists:student_feedbacks,id',
            'scheduled_at'    => 'required|date|after_or_equal:today',
            'meeting_link'    => 'required|url',
            'qc_user_id'      => 'nullable|exists:qcs,id',
        ]);

        $feedback = StudentFeedback::findOrFail($validated['feedback_id']);
        $qcId = $request->qc_user_id ?? Auth::id();

        StudentFeedbackSession::create([
            'student_feedback_id' => $feedback->id,
            'qc_user_id'          => $qcId,
            'scheduled_at'        => $validated['scheduled_at'],
            'meeting_link'        => $validated['meeting_link'],
            'status'              => 'pending',
        ]);

        $feedback->status = 'initiated';
        $feedback->save();

        return redirect()->back()->with('status', 'Feedback session created successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after_or_equal:today',
            'meeting_link' => 'required|url',
        ]);

        $session = StudentFeedbackSession::findOrFail($id);
        $session->scheduled_at = $request->input('scheduled_at');
        $session->meeting_link = $request->input('meeting_link');

        $session->save();
        return redirect()->back()->with('success', 'Feedback session updated successfully.');
    }

}
