<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\RecordedVideo;
use App\Models\StudentProgress;
use App\Models\Subject;
use App\Models\SubjectSession;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LogicException;

class SubjectController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    /**
     * Display a listing of Subjects based on a course ID.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|integer|exists:courses,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()->first()
                ], 400);
            }

            // Retrieve validated course ID
            $courseId = $request->input('course_id');

            // Fetch Subjects based on course ID
            $subjects = Subject::where('course_id', $courseId)
                ->where('type', 'lms')
                ->get();

            if ($subjects->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No subjects found for this course.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $subjects
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch Subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Subject.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request using Validator
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'short_desc' => 'nullable|string',
                'desc' => 'nullable|string',
                'status' => 'required|boolean',
                'total_hours' => 'required|integer|min:1',
                'course_id' => 'required|integer|exists:courses,id'
            ]);

            // Return validation errors if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()->first()
                ], 422);
            }

            // Create the Subject
            $subject = Subject::create([
                'course_id' => $request->input('course_id'),
                'name' => $request->input('name'),
                'short_desc' => $request->input('short_desc'),
                'desc' => $request->input('desc'),
                'status' => $request->input('status'),
                'total_hours' => $request->input('total_hours'),
                'type' => 'lms'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Subject created successfully.',
            ], 201);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error' => 'Database error occurred.',
                'details' => $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific Subject by its ID.
     */
    /**
     * Show a specific Subject by its ID.
     */

    public function show(int $id): JsonResponse
    {
        $studentId = auth()->user()->student_id ?? null;

        try {
            if (!$studentId) {
                return response()->json([
                    'status' => false,
                    'error'  => 'Student not authenticated',
                ], 401);
            }

            // ✅ Load subject + sessions
            $subject = Subject::with(['sessions' => function ($query) {
                $query->orderBy('id', 'asc');
            }])->findOrFail($id);

            $sessions = $subject->sessions ?? collect();

            // ✅ Load recorded videos with relations (safe fallback if error)
            try {
                $videos = RecordedVideo::where('subject_id', $id)
                    ->with(['video', 'subjectSession'])
                    ->get()
                    ->groupBy('subject_session_id');
            } catch (Exception $e) {
                Log::warning('Failed to fetch recorded videos', [
                    'subject_id' => $id,
                    'student_id' => $studentId,
                    'error'      => $e->getMessage(),
                ]);
                $videos = collect();
            }

            // ✅ Load assessments
            $assessments = Exam::where('subject_id', $id)
                ->where('type', 'Assessment')
                ->get()
                ->groupBy('subject_session_id');

            // ✅ Build playlist safely
            $playlist = $sessions->map(function ($session) use ($videos, $assessments, $studentId) {
                $session->setStudentId($studentId); // dynamically attach student ID
                return [
                    'session_id'      => $session->id,
                    'title'           => $session->title ?? null,
                    'is_locked'       => (bool) $session->is_locked,
                    'recorded_videos' => $videos->get($session->id, collect())->values(),
                    'assessments'     => $assessments->get($session->id, collect())->values(),
                ];
            });

            // ✅ Final Exam (optional)
            $finalExam = Exam::where('subject_id', $id)
                ->where('type', 'Exam')
                ->first();

            if ($finalExam) {
                $completedSessionsCount = StudentProgress::where('student_id', $studentId)
                    ->where('subject_id', $id)
                    ->where('status', 'completed')
                    ->count();

                $finalExam->is_locked = $completedSessionsCount < $sessions->count();
            }

            return response()->json([
                'status' => true,
                'data'   => [
                    'subject'  => $subject,
                    'sessions' => $playlist,
                    'exam'     => $finalExam ?? null,
                ],
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::warning('Subject not found', [
                'subject_id' => $id,
                'student_id' => $studentId,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'error'  => 'Subject not found',
            ], 404);
        } catch (QueryException $e) {
            Log::error('Database query failed', [
                'subject_id' => $id,
                'student_id' => $studentId,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'error'  => 'Database error while retrieving subject details',
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected error in SubjectController@show', [
                'subject_id' => $id,
                'student_id' => $studentId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error'  => 'Something went wrong while retrieving subject',
            ], 500);
        }
    }


    /**
     * Update a specific Subject by its ID.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            // Define validation rules (only validate fields that are present)
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'short_desc' => 'sometimes|nullable|string',
                'desc' => 'sometimes|nullable|string',
                'status' => 'sometimes|boolean',
                'total_hours' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $subject = Subject::findOrFail($id);

            // Only update fields that are present in the request
            $subject->update($request->only(array_keys($validator->validated())));

            return response()->json([
                'status' => true,
                'message' => 'Subject updated successfully.',
                'data' => $subject
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Subject not found'
            ], 404);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error' => 'Database error occurred: ' . $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific Subject by its ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $subject = Subject::findOrFail($id);

            // Delete the Subject
            $subject->delete();

            return response()->json([
                'status' => true,
                'message' => 'Subject deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Subject not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
