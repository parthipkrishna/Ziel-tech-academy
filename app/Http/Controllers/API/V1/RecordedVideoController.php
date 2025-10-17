<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\RecordedVideo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class RecordedVideoController extends Controller
{
    // Get a list of recorded videos (with optional filters)
    public function index(Request $request): JsonResponse
    {
        try {
            $query = RecordedVideo::query();

            if ($request->has('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            $recordedVideos = $query->with(['videoSession', 'video'])->get();

            if ($recordedVideos->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No recorded videos found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $recordedVideos
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch recorded videos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Create a new recorded video
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'video_session_id' => 'required|exists:video_sessions,id',
            'video_id' => 'required|exists:videos,id',
            'is_enabled' => 'nullable|boolean',
            'video_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        try {
            $recordedVideo = RecordedVideo::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Recorded video created successfully.',
                'data' => $recordedVideo
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to create recorded video: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get a specific recorded video by ID
    public function show($id): JsonResponse
    {
        try {
            $recordedVideo = RecordedVideo::with(['subject', 'videoSession', 'video'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $recordedVideo
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Recorded video not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch recorded video: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update a recorded video
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'nullable|exists:subjects,id',
            'video_session_id' => 'nullable|exists:video_sessions,id',
            'video_id' => 'nullable|exists:videos,id',
            'is_enabled' => 'nullable|boolean',
            'video_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $recordedVideo = RecordedVideo::findOrFail($id);
            $recordedVideo->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Recorded video updated successfully.',
                'data' => $recordedVideo
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Recorded video not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to update recorded video: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete a recorded video
    public function destroy($id): JsonResponse
    {
        try {
            $recordedVideo = RecordedVideo::findOrFail($id);
            $recordedVideo->delete();

            return response()->json([
                'status' => true,
                'message' => 'Recorded video deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Recorded video not found.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to delete recorded video: ' . $e->getMessage()
            ], 500);
        }
    }
}
