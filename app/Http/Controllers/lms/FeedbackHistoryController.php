<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFeedbackHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentFeedback;
use Illuminate\Support\Facades\Log;
use App\Models\StudentFeedbackSession;

class FeedbackHistoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_feedback_id'  => 'required|integer',
            'module_id'            => 'required|integer',
            'student_summary'      => 'required|string',
            'qc_feedback_summary'  => 'required|string',
            'video_rating'         => 'required|integer|min:1|max:5',
            'practical_rating'     => 'required|integer|min:1|max:5',
            'understanding_rating' => 'required|integer|min:1|max:5',
            'history_status'       => 'nullable|string',
        ]);

        $feedback = StudentFeedback::findOrFail($validated['student_feedback_id']);
        $qcId = $request->qc_user_id ?? Auth::id();
        $validated['history_status'] = !empty($validated['history_status']) ? $validated['history_status'] : 'draft';

        try {
            StudentFeedbackHistory::create([
                'student_feedback_id'    => $validated['student_feedback_id'],
                'module_id'              => $validated['module_id'],
                'qc_user_id'             => $qcId,
                'student_summary'        => $validated['student_summary'],
                'qc_feedback_summary'    => $validated['qc_feedback_summary'],
                'video_rating'           => $validated['video_rating'],
                'practical_rating'       => $validated['practical_rating'],
                'understanding_rating'   => $validated['understanding_rating'],
                'status'                 => $validated['history_status'],
            ]);

            $allowedStatuses = array_keys(StudentFeedback::getFeedbackStatusOptions());
            if (in_array($validated['history_status'], $allowedStatuses) && $validated['history_status'] !== $feedback->status) {
                $feedback->status = $validated['history_status'];
                $feedback->save();
            }

            return response()->json(['message' => 'Feedback history created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save feedback history.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $history = StudentFeedbackHistory::find($id);

        if (!$history) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'id' => $history->id,
            'student_summary' => $history->student_summary,
            'qc_feedback_summary' => $history->qc_feedback_summary,
            'video_rating' => $history->video_rating,
            'practical_rating' => $history->practical_rating,
            'understanding_rating' => $history->understanding_rating,
            'status' => strtolower($history->status),
        ]);
    }

    public function update(Request $request, $id)
    {
        $history = StudentFeedbackHistory::findOrFail($id);
        $qcId = $request->qc_user_id ?? Auth::id();

        $history->update([
            'qc_user_id' =>$qcId,
            'student_summary' => $request->student_summary ?? $history->student_summary,
            'qc_feedback_summary' => $request->qc_feedback_summary ?? $history->qc_feedback_summary,
            'video_rating' => $request->video_rating ?? $history->video_rating,
            'practical_rating' => $request->practical_rating ?? $history->practical_rating,
            'understanding_rating' => $request->understanding_rating ?? $history->understanding_rating,
            'status' => $request->history_status ?? $history->status,
        ]);

        $allowedStatuses = array_keys(StudentFeedback::getFeedbackStatusOptions());

        $feedback = StudentFeedback::find($history->student_feedback_id);
        if ($feedback && in_array($request->history_status, $allowedStatuses) && $request->history_status !== $feedback->status) {
            $feedback->status = $request->history_status;
            $feedback->save();
        }

        $session = StudentFeedbackSession::where('student_feedback_id',$history->student_feedback_id)->first();
        Log::info('message: ' . $session);
        if ($session) {
            $statusMap = [
                'approved' => 'completed',
                'rejected' => 'cancelled',
            ];
            if (isset($statusMap[$request->history_status])) {
                $session->status = $statusMap[$request->history_status];
                $session->save();
            }
        }
        return response()->json(['message' => 'Feedback updated successfully.']);
    }

}
