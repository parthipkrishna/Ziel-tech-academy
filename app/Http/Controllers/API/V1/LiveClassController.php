<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use App\Models\LiveClassParticipant;
use App\Models\ClassSessionLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Validator;

class LiveClassController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // You can add authentication/authorization logic here.
            return $next($request);
        });
    }

    /**
     * List all live classes with optional filtering.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = LiveClass::with(['tutor.user', 'batch']); // ✅ fetch tutor.user

            if ($request->has('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            $classes = $query->get();

            if ($classes->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No live classes found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data'   => $classes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch live classes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created live class.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'meeting_link' => 'required|url',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'instructor_id' => 'required|exists:users,id',
            'thumbnail_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'short_summary' => 'nullable|string|max:500',
            'summary' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        // Handle image upload separately
        $thumbnailImagePath = null;
        if ($request->hasFile('thumbnail_image')) {
            // Store the image in the 'public/live/thumbnails' directory
            $thumbnailImagePath = $request->file('thumbnail_image')->store('live/thumbnails', 'public');
        }

        // Store the live class
        $liveClass = LiveClass::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'meeting_link' => $request->meeting_link,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'instructor_id' => $request->instructor_id,
            'thumbnail_image' => $thumbnailImagePath,  // Use the variable
            'short_summary' => $request->short_summary,
            'summary' => $request->summary,
        ]);

        return response()->json(['message' => 'Live class created successfully', 'data' => $liveClass], 201);
    }

    /**
     * Update the details of a live class.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            // Find the live class to update
            $liveClass = LiveClass::findOrFail($id);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'meeting_link' => 'sometimes|required|url',
                'start_time' => 'sometimes|required|date|after_or_equal:now',
                'end_time' => 'sometimes|required|date|after:start_time',
                'instructor_id' => 'sometimes|required|exists:users,id',
                'thumbnail_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'short_summary' => 'nullable|string|max:500',
                'summary' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first()], 422);
            }

            // Handle image upload separately if a new image is provided
            if ($request->hasFile('thumbnail_image')) {
                // Store the image in the 'public/live/thumbnails' directory
                $thumbnailImagePath = $request->file('thumbnail_image')->store('live/thumbnails', 'public');
            } else {
                // Keep the old thumbnail image if no new image is uploaded
                $thumbnailImagePath = $liveClass->thumbnail_image;
            }

            // Prepare data for update, only include fields that are present in the request
            $updateData = [];

            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->has('meeting_link')) {
                $updateData['meeting_link'] = $request->meeting_link;
            }
            if ($request->has('start_time')) {
                $updateData['start_time'] = $request->start_time;
            }
            if ($request->has('end_time')) {
                $updateData['end_time'] = $request->end_time;
            }
            if ($request->has('instructor_id')) {
                $updateData['instructor_id'] = $request->instructor_id;
            }
            $updateData['thumbnail_image'] = $thumbnailImagePath; // Always update thumbnail image if provided
            if ($request->has('short_summary')) {
                $updateData['short_summary'] = $request->short_summary;
            }
            if ($request->has('summary')) {
                $updateData['summary'] = $request->summary;
            }

            // Update the live class with the filtered data
            $liveClass->update($updateData);

            return response()->json(['message' => 'Live class updated successfully', 'data' => $liveClass], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to update live class: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete a live class by its ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Find the live class to delete
            $liveClass = LiveClass::findOrFail($id);

            // Delete the live class
            $liveClass->delete();

            return response()->json([
                'status' => true,
                'message' => 'Live class deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to delete live class: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show(int $id): JsonResponse
    {
        try {
            $liveClass = LiveClass::with(['tutor.user', 'batch'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data'   => $liveClass
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to retrieve live class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Join a live class.
     *
     * Request body should include:
     *   - user_id (required)
     */
    public function join(Request $request, int $id): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->student_id) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Authenticated user does not have a student profile.',
                ], 403);
            }

            $studentId = $user->student_id;
            $liveClass = LiveClass::findOrFail($id);

            $now = now();

            // ✅ Check if current time is within the class window
            if ($now->lt($liveClass->start_time)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This live class has not started yet.',
                ], 400);
            }

            if ($now->gt($liveClass->end_time)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This live class is already over.',
                ], 400);
            }

            // ✅ Find or create participant entry
            $participant = LiveClassParticipant::firstOrCreate(
                [
                    'live_class_id' => $liveClass->id,
                    'student_id'    => $studentId,
                ],
                [
                    'batch_id'  => $liveClass->batch_id ?? null,
                    'join_time' => $now,
                ]
            );

            // Update join_time if already exists
            if (!$participant->wasRecentlyCreated) {
                $participant->update(['join_time' => $now]);
            }

            // ✅ Record session log
            ClassSessionLog::create([
                'live_class_id' => $liveClass->id,
                'user_id'       => $studentId,
                'action'        => 'Joined',
                'created_at'    => $now,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Joined live class successfully.',
                'data'    => [
                    'meeting_link' => $liveClass->meeting_link,
                ],
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found.',
            ], 404);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error'  => 'Database error: ' . $qe->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to join live class: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Leave a live class.
     *
     * Request body should include:
     *   - user_id (required)
     */
    public function leave(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }
            $liveClass = LiveClass::findOrFail($id);

            // Find the participant record that hasn't been marked as left
            $participant = LiveClassParticipant::where('live_class_id', $liveClass->id)
                ->where('user_id', $request->input('user_id'))
                ->whereNull('leave_time')
                ->first();
            if (!$participant) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Participant record not found or already left.'
                ], 404);
            }
            // Update leave time
            $participant->update(['leave_time' => now()]);

            // Record session log for leave action
            ClassSessionLog::create([
                'live_class_id' => $liveClass->id,
                'user_id'       => $request->input('user_id'),
                'action'        => 'Left'
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Left live class successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error'  => 'Database error: ' . $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to leave live class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a live class.
     * This endpoint updates the live class status to 'Ongoing' and records a session log.
     *
     * Optionally, the request may include user_id (typically the instructor).
     */
    public function start(Request $request, int $id): JsonResponse
    {
        try {
            $liveClass = LiveClass::findOrFail($id);
            $liveClass->update(['status' => 'Ongoing']);
            $userId = $request->input('user_id') ?? $liveClass->instructor_id;
            ClassSessionLog::create([
                'live_class_id' => $liveClass->id,
                'user_id'       => $userId,
                'action'        => 'Started'
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'Live class started successfully.',
                'data'    => $liveClass
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to start live class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End a live class.
     * This endpoint updates the live class status to 'Completed' and records a session log.
     *
     * Optionally, the request may include user_id (typically the instructor).
     */
    public function end(Request $request, int $id): JsonResponse
    {
        try {
            $liveClass = LiveClass::findOrFail($id);
            $liveClass->update(['status' => 'Completed']);
            $userId = $request->input('user_id') ?? $liveClass->instructor_id;
            ClassSessionLog::create([
                'live_class_id' => $liveClass->id,
                'user_id'       => $userId,
                'action'        => 'Ended'
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'Live class ended successfully.',
                'data'    => $liveClass
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Live class not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to end live class: ' . $e->getMessage()
            ], 500);
        }
    }
}
