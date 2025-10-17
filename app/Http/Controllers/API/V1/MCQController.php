<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamParticipant;
use App\Models\StudentScore;
use App\Models\StudentAnswer;
use App\Models\StudentFeedback;
use App\Models\StudentProgress;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MCQController extends Controller
{
    /**
     * Handle exam participant log (join, left, complete).
     */
    public function log(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id'    => 'required|integer|exists:exams,id',
                'student_id' => 'required|integer|exists:students,id',
                'status'     => 'required|in:Join,Left,Completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $data = $validator->validated();

            // 1️⃣ Check if student already passed
            $passedAttempt = ExamAttempt::where('exam_id', $data['exam_id'])
                ->where('student_id', $data['student_id'])
                ->where('status', 'Passed')
                ->first();

            if ($passedAttempt) {
                return response()->json([
                    'status'  => false,
                    'message' => 'You have already passed this exam and cannot retake it.',
                ], 403);
            }

            // 2️⃣ Get or create an active attempt
            $attempt = ExamAttempt::where('exam_id', $data['exam_id'])
                ->where('student_id', $data['student_id'])
                ->where('status', 'In Progress')
                ->latest('attempt_count')
                ->first();

            if (!$attempt && $data['status'] === 'Join') {
                $lastAttemptCount = ExamAttempt::where('exam_id', $data['exam_id'])
                    ->where('student_id', $data['student_id'])
                    ->max('attempt_count') ?? 0;

                $attempt = ExamAttempt::create([
                    'exam_id'       => $data['exam_id'],
                    'student_id'    => $data['student_id'],
                    'attempt_count' => $lastAttemptCount + 1,
                    'status'        => 'In Progress',
                    'unique_id'     => uniqid('', true),
                ]);
            }

            if (!$attempt) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No active attempt found to update.',
                ], 404);
            }

            // 3️⃣ Handle Join
            if ($data['status'] === 'Join') {
                $participant = ExamParticipant::firstOrNew([
                    'exam_id' => $data['exam_id'],
                    'student_id' => $data['student_id'],
                    'exam_attempt_id' => $attempt->unique_id,
                    'status' => 'Join',
                ]);

                $participant->joined_at = now();
                $participant->save();

                return response()->json([
                    'status'     => true,
                    'message'    => 'Exam joined successfully.',
                    'attempt_id' => $attempt->unique_id,
                ], 200);
            }

            // 4️⃣ Handle Left / Completed
            $updateData = ['status' => $data['status']];
            if ($data['status'] === 'Left') {
                $updateData['left_at'] = now();
            }
            if ($data['status'] === 'Completed') {
                $updateData['completed_at'] = now();
            }

            $attempt->update($updateData);

            // Create participant entry only for history
            ExamParticipant::create([
                'exam_id'       => $data['exam_id'],
                'student_id'    => $data['student_id'],
                'exam_attempt_id' => $attempt->unique_id,
                'status'        => $data['status'],
                'left_at'       => $data['status'] === 'Left' ? now() : null,
                'completed_at'  => $data['status'] === 'Completed' ? now() : null,
            ]);

            return response()->json([
                'status'     => true,
                'message'    => "Exam attempt status updated to {$data['status']}.",
                'attempt_id' => $attempt->unique_id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created Student Score.
     */
    public function storeScore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id'            => 'required|integer|exists:exams,id',
                'student_id'         => 'required|integer|exists:students,id',
                'exam_attempt_id'    => 'required|string|exists:exam_attempts,unique_id',
                'total_score'        => 'required|integer|min:0',
                'correct_answers'    => 'required|integer|min:0',
                'incorrect_answers'  => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                Log::warning('Student score validation failed', $request->all());
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                ], 422);
            }

            $data = $validator->validated();

            // Fetch the attempt
            $attempt = ExamAttempt::where('unique_id', $data['exam_attempt_id'])->first();
            if (!$attempt) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam attempt not found',
                ], 404);
            }

            // Prevent duplicate score
            $existingScore = StudentScore::where('exam_id', $data['exam_id'])
                ->where('student_id', $data['student_id'])
                ->where('exam_attempt_id', $attempt->unique_id)
                ->first();

            if ($existingScore) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This exam is already completed for this attempt.',
                    'score'   => $existingScore
                ], 201); // Conflict
            }

            // Use transaction to ensure consistency
            DB::beginTransaction();

            // 1️⃣ Create Student Score
            $score = StudentScore::create([
                'exam_id'           => $data['exam_id'],
                'student_id'        => $data['student_id'],
                'exam_attempt_id'   => $attempt->unique_id,
                'total_score'       => $data['total_score'],
                'correct_answers'   => $data['correct_answers'],
                'incorrect_answers' => $data['incorrect_answers'],
            ]);

            // Determine pass/fail status
            $passStatus = $score->pass_status; // Assuming your model calculates this

            // 2️⃣ Update ExamAttempt status & completed_at
            $attempt->update([
                'status'       => $passStatus
            ]);

            // 3️⃣ Update ExamParticipant completed_at for this attempt
            ExamParticipant::where('exam_attempt_id', $attempt->unique_id)
                ->update([
                    'status'       => 'Completed',
                    'completed_at' => now(),
                ]);

            // 1️⃣ Handle final exam → create student feedback
            if ($attempt->exam->type === 'Exam' && $passStatus === 'pass') {
                $this->createStudentFeedback($score);
            }

            // 2️⃣ Handle assessments → unlock next module/session
            if ($attempt->exam->type === 'Assessment') {
                $this->updateStudentProgressAfterAssessment($score);
            }

            DB::commit();

            Log::info('Student score recorded and attempt marked completed', [
                'student_id' => $data['student_id'],
                'exam_id'    => $data['exam_id'],
                'attempt_id' => $attempt->unique_id,
                'score_id'   => $score->id,
                'status'     => $passStatus,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Student score recorded and exam marked completed.',
                'data'    => $score,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing student score', [
                'error'   => $e->getMessage(),
                'request' => $request->all(),
            ]);
            return response()->json([
                'status' => false,
                'error'  => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created Student Answer.
     */
    public function storeAnswer(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id'            => 'required|integer|exists:exams,id',
                'question_id'        => 'required|integer|exists:exam_questions,id',
                'student_id'         => 'required|integer|exists:students,id',
                'selected_answer_id' => 'required|integer|exists:question_answers,id',
                'exam_attempt_id' => 'required|string|exists:exam_attempts,unique_id',
            ]);

            if ($validator->fails()) {
                Log::warning('Student answer validation failed', [
                    'errors' => $validator->errors(),
                    'request' => $request->all(),
                ]);
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'error'   => $validator->errors()->first(),
                ], 422);
            }

            $data = $validator->validated();

            // Resolve attempt id from unique_id
            $attempt = ExamAttempt::where('unique_id', $data['exam_attempt_id'])->first();

            if (!$attempt) {
                Log::warning('Invalid exam attempt unique_id in storeAnswer', $data);
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam attempt not found',
                ], 404);
            }

            // Save / update student answer
            $answer = StudentAnswer::updateOrCreate(
                [
                    'exam_id'        => $data['exam_id'],
                    'question_id'    => $data['question_id'],
                    'student_id'     => $data['student_id'],
                    'exam_attempt_id' => $attempt->unique_id,
                ],
                [
                    'selected_answer_id' => $data['selected_answer_id'],
                ]
            );

            Log::info('Student answer saved', [
                'student_id' => $data['student_id'],
                'exam_id'    => $data['exam_id'],
                'attempt_id' => $attempt->id,
                'answer_id'  => $answer->id,
            ]);

            return response()->json([
                'status'  => true,
                'message' => $answer->wasRecentlyCreated
                    ? 'Answer recorded successfully'
                    : 'Answer updated successfully',
                'data'    => $answer,
            ], $answer->wasRecentlyCreated ? 201 : 200);
        } catch (\Exception $e) {
            Log::error('Error in storeAnswer API', [
                'request' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Exam History for a student
     */
    /**
     * Get Exam History for a student
     */
    public function history(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'exam_id' => 'required|integer|exists:exams,id',
            'exam_attempt_id' => 'required|string|exists:exam_attempts,unique_id',
        ]);

        try {
            // Eager load exam, questions with answers, student answers
            $score = StudentScore::with([
                'student',
                'exam.questions.answers',
                'answers.selectedAnswer',
            ])
                ->where('student_id', $data['student_id'])
                ->where('exam_id', $data['exam_id'])
                ->where('exam_attempt_id', $data['exam_attempt_id'])
                ->firstOrFail();

            // Build questions with selected & correct flags
            $questions = $score->exam->questions->map(function ($q) use ($score) {
                $studentAnswer = $score->answers->firstWhere('question_id', $q->id);

                return [
                    'question_id' => $q->id,
                    'question_text' => $q->question,
                    'answers' => $q->answers->map(function ($a) use ($studentAnswer) {
                        return [
                            'id' => $a->id,
                            'answer_text' => $a->answer_text,
                            'is_correct' => (bool) $a->is_correct,
                            'is_selected' => $studentAnswer?->selected_answer_id === $a->id,
                        ];
                    }),
                ];
            });

            return response()->json([
                'status' => true,
                'data' => [
                    'student' => $score->student,
                    'exam' => ['id' => $score->exam->id, 'name' => $score->exam->name],
                    'score' => [
                        'total_marks' => $score->total_score,
                        'correct_answers' => $score->correct_answers,
                        'incorrect_answers' => $score->incorrect_answers,
                        'status' => $score->pass_status,
                        'joined_at' => $score->joined_at,
                        'completed_at' => $score->completed_at,
                        'total_time_taken' => $score->total_time_taken,
                    ],
                    'questions' => $questions
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'History not found'], 404);
        }
    }

    /**
     * Create student feedback after final exam passed
     */
    protected function createStudentFeedback(StudentScore $score): void
    {
        $student = $score->student;
        $subject = $score->exam->subject;

        // Get student's batch for this course
        $batch = $student->batches()
            ->where('course_id', $subject->course_id)
            ->first();

        StudentFeedback::create([
            'student_id'       => $student->id,
            'module_id'        => $subject->id,
            'batch'            => $batch?->name ?? 'N/A',
            'admission_number' => $student->admission_number ?? '',
            'location'         => $student->address ?? null,
            'contact_number'   => $student->phone ?? null,
            'status'           => 'pending',
        ]);
    }

    /**
     * Update StudentProgress after assessment completion
     */
    protected function updateStudentProgressAfterAssessment(StudentScore $score): void
    {
        $studentId = $score->student_id;
        $subject = $score->exam->subject; // eager loaded relation
        $courseId = $subject->course_id;

        // Load all student progress for this subject
        $progressList = StudentProgress::where('student_id', $studentId)
            ->where('subject_id', $subject->id)
            ->orderBy('module_id', 'asc')
            ->get();

        // Count completed assessments for this subject
        $assessmentIds = $subject->exams()->where('type', 'Assessment')->pluck('id');
        $completedCount = StudentScore::whereIn('exam_id', $assessmentIds)
            ->where('student_id', $studentId)
            ->count();

        // If all assessments completed
        if ($completedCount >= $assessmentIds->count()) {

            // Complete current module/session
            $currentProgress = $progressList->first();
            if ($currentProgress) {
                $currentProgress->update([
                    'status'       => 'completed',
                    'completed_at' => now()
                ]);
            }

            // Unlock next session/module
            $nextProgress = $progressList->where('status', 'locked')->first();
            if ($nextProgress) {
                $nextProgress->update([
                    'status'       => 'unlocked',
                    'unlocked_at'  => now()
                ]);
            } else {
                // No more modules in current subject → unlock next subject
                $nextSubject = Subject::where('course_id', $courseId)
                    ->where('id', '>', $subject->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($nextSubject) {
                    $firstSession = $nextSubject->sessions()->orderBy('id', 'asc')->first();

                    StudentProgress::firstOrCreate([
                        'student_id' => $studentId,
                        'subject_id' => $nextSubject->id,
                        'module_id'  => $firstSession->id,
                    ], [
                        'status'      => 'unlocked',
                        'unlocked_at' => now()
                    ]);
                }
            }
        }
    }

    /**
     * List all exam attempts for the authenticated student by subject
     */
    public function examAttempts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|integer|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();
        $studentId = auth()->user()->student_id;
        $subjectId = $data['subject_id'];

        try {
            $exams = Exam::where('subject_id', $subjectId)->get();
            $examAttempts = collect();

            foreach ($exams as $exam) {
                $attempts = ExamAttempt::where('exam_id', $exam->id)
                    ->where('student_id', $studentId)
                    ->orderBy('attempt_count', 'asc')
                    ->get();

                foreach ($attempts as $attempt) {
                    $score = StudentScore::where('exam_attempt_id', $attempt->unique_id)->first();

                    // ✅ filter only failed attempts
                    $passStatus = $score->pass_status ?? $attempt->status;
                    if (strtolower($passStatus) !== 'failed') {
                        continue;
                    }

                    $examAttempts->push([
                        'exam_id'          => $exam->id,
                        'exam_name'        => $exam->name,
                        'exam_type'        => $exam->type,
                        'attempt_id'       => $attempt->unique_id,
                        'status'           => $attempt->status,
                        'total_score'      => $score->total_score ?? null,
                        'correct_answers'  => $score->correct_answers ?? null,
                        'incorrect_answers' => $score->incorrect_answers ?? null,
                        'pass_status'      => $score->pass_status ?? null,
                        'joined_at'        => $attempt->created_at,
                        'completed_at'     => $attempt->completed_at,
                        'attempt_count'    => $attempt->attempt_count,
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'data'   => $examAttempts->values(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exam List API error', [
                'error'   => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}
