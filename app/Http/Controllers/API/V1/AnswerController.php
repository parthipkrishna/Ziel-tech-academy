<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Add authentication logic here if required.
            return $next($request);
        });
    }

    /**
     * Display a listing of Question Answers.
     * Optionally filter by question_id.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_id' => 'sometimes|integer|exists:exam_questions,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $query = QuestionAnswer::query();
            if ($request->has('question_id')) {
                $query->where('question_id', $request->input('question_id'));
            }
            $answers = $query->get();
            if ($answers->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No answers found.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'data'   => $answers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch answers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Question Answer.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_id' => 'required|integer|exists:exam_questions,id',
                'answer_text' => 'required|string|max:255',
                'is_correct'  => 'required|boolean'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors()->first()
                ], 422);
            }
            $answer = QuestionAnswer::create($request->only([
                'question_id', 'answer_text', 'is_correct'
            ]));
            return response()->json([
                'status'  => true,
                'message' => 'Answer created successfully.',
                'data'    => $answer,
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
     * Show a specific Question Answer by its ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $answer = QuestionAnswer::findOrFail($id);
            return response()->json([
                'status' => true,
                'data'   => $answer,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Answer not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to retrieve answer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific Question Answer by its ID.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_id' => 'sometimes|integer|exists:exam_questions,id',
                'answer_text' => 'sometimes|string|max:255',
                'is_correct'  => 'sometimes|boolean'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $answer = QuestionAnswer::findOrFail($id);
            $answer->update($request->only(array_keys($validator->validated())));
            return response()->json([
                'status'  => true,
                'message' => 'Answer updated successfully.',
                'data'    => $answer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Answer not found'
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
     * Delete a specific Question Answer by its ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $answer = QuestionAnswer::findOrFail($id);
            $answer->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Answer deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Answer not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
