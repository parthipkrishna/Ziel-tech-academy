<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\RecordedVideo;
use App\Models\Subject;
use App\Models\SubjectSession;
use App\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RecordVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recordedVideos = RecordedVideo::with(['subject', 'subjectSession', 'video'])->get();
        $subjects =  $subjects = Subject::with('course')
            ->where('type', 'lms')
            ->where('status', 1)
            ->get();
        $videos = Video::all();

        return view('lms.sections.recorded_videos.index', compact('recordedVideos', 'subjects', 'videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::with('course')
            ->where('type', 'lms')
            ->where('status', 1)
            ->get();
        $sessions = SubjectSession::all();
        $videos = Video::where('is_enabled', 1)->get();


        return view('lms.sections.recorded_videos.add', compact('subjects', 'sessions', 'videos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subject_id' => 'required|exists:subjects,id',
                'session_id' => 'required|exists:subject_sessions,id',
                'video_id'   => 'required|exists:videos,id',
                'is_enabled' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();

            $validated['subject_session_id'] = $validated['session_id'];
            unset($validated['session_id']);

            RecordedVideo::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Recorded video session created successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Error while storing recorded video', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the recorded video.',
            ], 500);
        }
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function ajaxList(Request $request)
    {
        $videos = RecordedVideo::with(['video', 'subject', 'subjectSession'])->select('recorded_videos.*')->latest();

        return DataTables::of($videos)
            ->addColumn('title', function ($video) {
                return $video->video?->title ?? '<em>No title</em>';
            })
            ->addColumn('subject', function ($video) {
                return $video->subject?->name ?? '<em>Not Assigned</em>';
            })
            ->addColumn('session', function ($video) {
                return $video->subjectSession?->title ?? '<em>Not Assigned</em>';
            })
            ->addColumn('duration', function ($video) {
                return $video->video?->duration ??'<span class="small text-danger">Not Available</span>';
            })
            ->addColumn('is_enabled', function ($video) {
                return $video->is_enabled
                    ? '<button class="btn btn-soft-success rounded-pill">Enabled</button>'
                    : '<button class="btn btn-soft-danger rounded-pill">Disabled</button>';
            })
            ->addColumn('action', function ($video) {
                $actions = '';

                if (auth()->user()->hasPermission('recorded-videos.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon editVideoBtn" data-bs-toggle="modal" data-bs-target="#edit-video-modal' . $video->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('recorded-videos.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon deleteVideoBtn" data-bs-toggle="modal" data-bs-target="#delete-video-modal' . $video->id . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $actions;
            })
            ->rawColumns(['is_enabled', 'action', 'duration'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Attempting to update recorded video.', ['recorded_video_id' => $id, 'request_data' => $request->except(['_token', '_method'])]);

        try {
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'subject_session_id' => 'required|exists:subject_sessions,id',
                'video_id' => 'required|exists:videos,id',
                'video_order' => 'required|numeric',
                'is_enabled' => 'nullable|boolean',
            ]);

            $recordedVideo = RecordedVideo::findOrFail($id);

            Log::info('Recorded video found for update.', ['recorded_video_id' => $recordedVideo->id]);

            $recordedVideo->update([
                'subject_id' => $request->subject_id,
                'subject_session_id' => $request->subject_session_id,
                'video_id' => $request->video_id,
                'video_order' => $request->video_order,
                'is_enabled' => $request->is_enabled ?? 0,
            ]);

            Log::info('Recorded video updated successfully.', ['recorded_video_id' => $recordedVideo->id]);

            return redirect()->back()->with('success', 'Recorded video updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating recorded video: ' . $e->getMessage(), ['exception' => $e, 'recorded_video_id' => $id]);

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recordedVideo = RecordedVideo::findOrFail($id);
        $recordedVideo->delete();

        return response()->json(['message' => 'Recorded video deleted successfully.']);
    }
}
