<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Add authentication logic here if needed.
            return $next($request);
        });
    }

    /**
     * Display a listing of Exam Questions.
     * Optionally filter by exam_id.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id' => 'sometimes|integer|exists:exams,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $query = ExamQuestion::query();
            if ($request->has('exam_id')) {
                $query->where('exam_id', $request->input('exam_id'));
            }
            $questions = $query->get();
            if ($questions->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No questions found.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'data'   => $questions,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Exam Question.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id'  => 'required|integer|exists:exams,id',
                'question' => 'required|string',
                'mark'     => 'required|integer|min:1'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors()->first()
                ], 422);
            }
            $question = ExamQuestion::create($request->only([
                'exam_id', 'question', 'mark'
            ]));
            return response()->json([
                'status'  => true,
                'message' => 'Question created successfully.',
                'data'    => $question,
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

    /**
     * Show a specific Exam Question by its ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $question = ExamQuestion::findOrFail($id);
            return response()->json([
                'status' => true,
                'data'   => $question,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Question not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to retrieve question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific Exam Question by its ID.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'exam_id'  => 'sometimes|integer|exists:exams,id',
                'question' => 'sometimes|string',
                'mark'     => 'sometimes|integer|min:1'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $question = ExamQuestion::findOrFail($id);
            $question->update($request->only(array_keys($validator->validated())));
            return response()->json([
                'status'  => true,
                'message' => 'Question updated successfully.',
                'data'    => $question
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Question not found'
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
     * Delete a specific Exam Question by its ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $question = ExamQuestion::findOrFail($id);
            $question->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Question deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Question not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
