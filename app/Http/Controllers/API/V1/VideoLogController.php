<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoLog;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoLogController extends Controller
{
    /**
     * Store video log (start, pause, leave, complete)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'subject_session_id' => 'required|exists:subject_sessions,id',
            'video_id' => 'required|exists:videos,id',
            'start_time' => 'required|integer|min:0',
            'end_time' => 'nullable|integer|min:0',
            'status' => 'required|in:watching,left,completed',
        ]);

        // Return first validation error if any
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first() ?? 'Validation error',
            ], 422);
        }

        try {
            $studentId = $request->student_id;
            $duration = 0;

            if ($request->end_time !== null) {
                $duration = max(0, $request->end_time - $request->start_time);
            }

            // 1. Save play log
            $log = VideoLog::create([
                'student_id' => $studentId,
                'subject_id' => $request->subject_id,
                'subject_session_id' => $request->subject_session_id,
                'video_id' => $request->video_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $duration,
                'status' => $request->status,
            ]);

            // 2. Update aggregated progress
            $progress = VideoProgress::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'video_id'   => $request->video_id,
                ],
                [
                    'total_watch_time' => 0,
                    'is_completed' => false,
                ]
            );

            $progress->total_watch_time += $duration;

            // 3. Mark completed if fully watched
            $video = Video::find($request->video_id);
            if ($video && $progress->total_watch_time >= $video->duration) {
                $progress->is_completed = true;
            }

            $progress->save();

            return response()->json([
                'status' => true,
                'message' => 'Video log saved successfully',
            ]);

        } catch (\Exception $e) {
            // Return only the first error message
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
