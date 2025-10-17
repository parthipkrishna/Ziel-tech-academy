<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SubjectSession;
use App\Models\VideoSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class VideoSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    // GET /video_sessions
    public function index(Request $request): JsonResponse
    {
        try {
            $sessions = VideoSession::all();

            if ($sessions->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No video sessions found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $sessions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch video sessions: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST /video_sessions
    public function store(Request $request): JsonResponse
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        // Create a new video session record
        $session = SubjectSession::create($request->all());

        return response()->json([
            'message' => 'Video session created successfully.',
            'data' => $session
        ], 201);
    }

    // GET /video_sessions/{id}
    public function show(int $id): JsonResponse
    {
        try {
            $session = SubjectSession::findOrFail($id);
            return response()->json(['status' => true, 'data' => $session], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video session not found'], 404);
        }
    }

    // PUT /video_sessions/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $session = SubjectSession::findOrFail($id);

            // Validate the incoming data
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first()], 422);
            }

            // Update the video session data
            $session->update($request->all());

            return response()->json(['message' => 'Video session updated successfully', 'data' => $session], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video session not found'], 404);
        }
    }

    // DELETE /video_sessions/{id}
    public function destroy(int $id): JsonResponse
    {
        try {
            $session = SubjectSession::findOrFail($id);
            $session->delete();

            return response()->json(['status' => true, 'message' => 'Video session deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video session not found'], 404);
        }
    }
}
