<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessVideo;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

use Exception;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = Video::query();

            if ($request->has('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            $videos = $query->get();

            if ($videos->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No videos found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $videos
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch videos: ' . $e->getMessage()
            ], 500);
        }
    }

    
   public function store(Request $request): JsonResponse
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi,mkv|max:2048000', // Max 2GB
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }
    
        try {
            return DB::transaction(function () use ($request) {
                // Store the video temporarily
                $videoFile = $request->file('video');
                $videoPath = $videoFile->store('temp_videos', 'public'); 
    
                // Create video record in database
                $video = Video::create([
                    'subject_id' => $request->subject_id,
                    'title' => $request->title,
                    'video' => '', // Placeholder, updated later
                    'thumbnail' => $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('thumbnails', 'public') : null,
                    'description' => $request->description,
                    'order' => $request->order ?? 0,
                    'status' => 'uploading',
                ]);
    
                // Dispatch video processing job
                ProcessVideo::dispatch($video->id, $videoPath)->onQueue('video-processing');
    
                return response()->json([
                    'message' => 'Video upload is in progress.',
                    'status' => 'uploading'
                ], 202);
            });
    
        } catch (\Exception $e) {
            Log::error("Video upload failed: " . $e->getMessage());
    
            return response()->json([
                'message' => 'Video upload failed, please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $video = Video::findOrFail($id);
            return response()->json(['status' => true, 'data' => $video], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video not found'], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $video = Video::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'video' => 'sometimes|file|mimes:mp4,mov,avi,mkv|max:51200',
                'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'description' => 'nullable|string',
                'order' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first()], 422);
            }

            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('videos', 'public');
                $video->video = $videoPath;
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $video->thumbnail = $thumbnailPath;
            }

            $video->update($request->except(['video', 'thumbnail']));

            return response()->json(['message' => 'Video updated successfully', 'data' => $video], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video not found'], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();

            return response()->json(['status' => true, 'message' => 'Video deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Video not found'], 404);
        }
    }
}
