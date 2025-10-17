<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentFeedback;
use App\Models\StudentFeedbackHistory;
use DataTables;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = StudentFeedback::with('student')->whereIn('status', ['pending', 'initiated','draft'])->get();
        return view('lms.sections.feedback.feedback')->with(compact('feedbacks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $student_feedbacks = StudentFeedback::with(['session', 'subject'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['pending', 'initiated', 'scheduled'])
            ->get();

        $student_complete_feedbacks = StudentFeedback::with(['session', 'subject'])
            ->where('student_id', $student->id)
            ->whereIn('status', [ 'approved', 'draft','rejected'])
            ->get();

        $feedbackIds = $student_complete_feedbacks->pluck('id');

        $feedback_histories = StudentFeedbackHistory::with('subject')
            ->whereIn('student_feedback_id', $feedbackIds)
            ->get();

        $history_status = collect(StudentFeedbackHistory::getStatusOptions())->except('draft')->toArray();
        $feedback_history_status = collect(StudentFeedbackHistory::getStatusOptions());
        
        return view('lms.sections.feedback.edit', compact('student', 'student_feedbacks', 'feedback_histories','history_status','feedback_history_status'));
    }
    public function ajaxList(Request $request)
    {
        $feedbacks = StudentFeedback::with('student')->latest();

        return DataTables::of($feedbacks)
            ->addColumn('student', function ($feedback) {
                if ($feedback->student && $feedback->student->profile_photo) {
                    $img = asset('storage/' . $feedback->student->profile_photo);
                    return "<img src='{$img}' class='me-2 rounded-circle' width='40'>";
                }
                return "<span class='text-danger small'>No Image</span>";
            })
            ->addColumn('name', function ($feedback) {
                return $feedback->student
                    ? $feedback->student->first_name . ' ' . $feedback->student->last_name
                    : '<span class="text-danger small">No Student</span>';
            })
            ->addColumn('status', function ($feedback) {
                return match($feedback->status) {
                    'initiated' => "<span class='badge bg-primary'>Initiated</span>",
                    'draft'     => "<span class='badge bg-secondary'>Draft</span>",
                    default     => "<span class='badge bg-danger'>Pending</span>",
                };
            })
            ->addColumn('action', function ($feedback) {
                if (!auth()->user()->hasPermission('feedback.update')) return '';

                $route = route('lms.edit.feedback', $feedback->student_id);
                return "<a href='{$route}' class='action-icon'><i class='mdi mdi-square-edit-outline'></i></a>";
            })
            ->rawColumns(['student', 'name', 'status', 'action'])
            ->make(true);
    }

}
