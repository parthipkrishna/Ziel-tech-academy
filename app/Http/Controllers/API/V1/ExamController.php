<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // You can add authentication logic here
            return $next($request);
        });
    }

    /**
     * Display a listing of Exams.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $exams = Exam::all();
            if ($exams->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No exams found.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'data'   => $exams
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch exams: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Exam.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request data.
            $validator = Validator::make($request->all(), [
                'subject_id'      => 'required|integer|exists:subjects,id',
                'name'            => 'required|string|max:255',
                'short_description' => 'nullable|string',
                'description'     => 'nullable|string',
                'status'          => 'required|in:Scheduled,Ongoing,Completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors()->first()
                ], 422);
            }

            $exam = Exam::create($request->only([
                'subject_id',
                'name',
                'short_description',
                'description',
                'status'
            ]));

            return response()->json([
                'status'  => true,
                'message' => 'Exam created successfully.',
                'data'    => $exam
            ], 201);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error'  => 'Database error: ' . $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $studentId = $request->query('student_id'); // optional

            // Fetch exam with questions relation
            $exam = Exam::with('questions.answers')->findOrFail($id);

            // Determine question count based on exam type
            $questionCount = $exam->type === 'Exam' ? 25 : 10;

            if ($studentId) {
                // Check previous attempt for this student
                $lastScore = StudentScore::where('exam_id', $exam->id)
                    ->where('student_id', $studentId)
                    ->latest('created_at')
                    ->first();

                if ($lastScore && $lastScore->pass_status === 'Failed') {
                    // Student failed: return SAME questions as last attempt
                    $questions = $lastScore->answers->pluck('question')->unique('id')->take($questionCount);
                } else {
                    // New student or passed last time: random questions
                    $questions = $exam->questions()->inRandomOrder()->take($questionCount)->get();
                }
            } else {
                // No student_id: random questions
                $questions = $exam->questions()->inRandomOrder()->take($questionCount)->get();
            }

            // Format questions
            $formattedQuestions = $questions->map(function ($q) {
                return [
                    'question_id'   => $q->id,
                    'question_text' => $q->question,
                    'mark' => $q->mark,
                    'answers'       => $q->answers->map(fn($a) => [
                        'answer_id'   => $a->id,
                        'answer_text' => $a->answer_text,
                        'is_correct'  => (bool)$a->is_correct
                    ]),
                ];
            });

            return response()->json([
                'status' => true,
                'data' => [
                    'exam' => [
                        'id'        => $exam->id,
                        'name'      => $exam->name,
                        'type'      => $exam->type,
                        'questions' => $formattedQuestions,
                        'duration'  => $exam->duration,   
                        'total_marks' => $exam->total_marks,
                        'minimum_passing_marks' => $exam->minimum_passing_marks,
                        'short_description' => $exam->short_description,
                        'description' => $exam->description,
                    ],
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Exam not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to retrieve exam: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific Exam by its ID.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            // Validate only the fields that are present.
            $validator = Validator::make($request->all(), [
                'subject_id'      => 'sometimes|integer|exists:subjects,id',
                'name'            => 'sometimes|string|max:255',
                'short_description' => 'sometimes|nullable|string',
                'description'     => 'sometimes|nullable|string',
                'status'          => 'sometimes|in:Scheduled,Ongoing,Completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $exam = Exam::findOrFail($id);
            $exam->update($request->only(array_keys($validator->validated())));

            return response()->json([
                'status'  => true,
                'message' => 'Exam updated successfully.',
                'data'    => $exam
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Exam not found'
            ], 404);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error'  => 'Database error: ' . $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific Exam by its ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Exam deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Exam not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
