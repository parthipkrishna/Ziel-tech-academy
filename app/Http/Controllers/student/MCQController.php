<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\StudentProgress;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\ExamAttempt;
use App\Models\ExamParticipant;
use App\Models\StudentScore;
use App\Models\StudentAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MCQController extends Controller
{
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

            // Prevent retake if already passed
            if ($data['status'] === 'Join') {
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
            }

            // Find existing active attempt
            $attempt = ExamAttempt::where('exam_id', $data['exam_id'])
                ->where('student_id', $data['student_id'])
                ->where('status', 'In Progress')
                ->latest('attempt_count')
                ->first();

            if ($data['status'] === 'Join') {
                // Create new attempt if none exists
                if (!$attempt) {
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

                    StudentScore::create([
                        'exam_id'           => $data['exam_id'],
                        'student_id'        => $data['student_id'],
                        'exam_attempt_id'   => $attempt->unique_id,
                        'total_score'       => 0,
                        'correct_answers'   => 0,
                        'incorrect_answers' => 0,
                    ]);
                }

                ExamParticipant::create([
                    'exam_id'        => $data['exam_id'],
                    'student_id'     => $data['student_id'],
                    'exam_attempt_id'=> $attempt->unique_id,
                    'status'         => 'Join',
                    'joined_at'      => now(),
                ]);

                return response()->json([
                    'status'     => true,
                    'message'    => 'Exam joined successfully.',
                    'attempt_id' => $attempt->unique_id,
                ], 200);
            }

            // For Left / Completed, update existing participant
            if (!$attempt) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No active attempt found to update.',
                ], 404);
            }

            $participant = ExamParticipant::where('exam_attempt_id', $attempt->unique_id)
                ->where('student_id', $data['student_id'])
                ->latest()
                ->first();

            if ($participant) {
                $updateData = ['status' => $data['status']];
                if ($data['status'] === 'Left') $updateData['left_at'] = now();
                if ($data['status'] === 'Completed') $updateData['completed_at'] = now();

                $participant->update($updateData);

                return response()->json([
                    'status'     => true,
                    'message'    => "ExamParticipant status updated to {$data['status']}.",
                    'attempt_id' => $attempt->unique_id,
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'ExamParticipant not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
                'data'   => $request->all()
            ], 500);
        }
    }

    public function submitScore(Request $request): JsonResponse
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

            $existingScore = StudentScore::where('exam_id', $data['exam_id'])
                    ->where('student_id', $data['student_id'])
                    ->where('exam_attempt_id', $attempt->unique_id)
                    ->first();

                if ($existingScore) {
                    // instead of blocking, update it
                    $existingScore->update([
                        'total_score'       => $data['total_score'],
                        'correct_answers'   => $data['correct_answers'],
                        'incorrect_answers' => $data['incorrect_answers'],
                    ]);
                    $score = $existingScore;
                } else {

                    // 1️⃣ Create Student Score
                    $score = StudentScore::create([
                        'exam_id'           => $data['exam_id'],
                        'student_id'        => $data['student_id'],
                        'exam_attempt_id'   => $attempt->unique_id,
                        'total_score'       => $data['total_score'],
                        'correct_answers'   => $data['correct_answers'],
                        'incorrect_answers' => $data['incorrect_answers'],
                    ]);
                }

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

                // If the exam type is 'Assessment', update the student's progress
                if ($attempt->exam->type === 'Assessment') {
                    $this->storeAssessmentProgress($score);
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
                'message' => 'Your score recorded and exam marked completed.',
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

    protected function storeAssessmentProgress(StudentScore $score): void
    {
        try {
            $exam = $score->exam;
            $subjectId = $exam->subject_id;
            $subjectSessionId = $exam->subject_session_id;
            $moduleId = $subjectSessionId;

            $progress = StudentProgress::firstOrCreate(
                [
                    'student_id' => $score->student_id,
                    'module_id' => $moduleId,
                ],
                [
                    'subject_id' => $subjectId,
                    'status' => 'locked',
                ]
            );

            if ($score->pass_status === 'Passed') {
                $progress->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
                Log::info('Student progress completed', [
                    'student_id' => $score->student_id,
                    'subject_id' => $subjectId,
                    'module_id' => $moduleId,
                ]);
            } else {
                Log::info('Student progress not completed due to failing the assessment', [
                    'student_id' => $score->student_id,
                    'subject_id' => $subjectId,
                    'module_id' => $moduleId,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error updating student progress', [
                'student_id' => $score->student_id,
                'module_id' => $moduleId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

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
                    'exam_attempt_id'=> $data['exam_attempt_id'],
                ],
                [
                    'selected_answer_id' => $data['selected_answer_id'],
                ]
            );

            Log::info('Student answer saved', [
                'student_id' => $data['student_id'],
                'exam_id'    => $data['exam_id'],
                'exam_attempt_id' => $attempt->unique_id,
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
}
