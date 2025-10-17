<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use getID3;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest()->get();
        return view('lms.sections.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('lms.sections.videos.add', compact('subjects'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $video = Video::findOrFail($id);
        $video->update(['is_enabled' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function ajaxList(Request $request)
    {
        $videos = Video::query()->latest();

        return DataTables::of($videos)
            ->addColumn('thumbnail', function ($video) {
                if ($video->thumbnail) {
                    return '<img src="' . asset('storage/' . $video->thumbnail) . '" alt="Thumbnail" width="60">';
                }
                return '<img src="' . asset('lms/assets/images/gallery/video_thumbnail.webp') . '" alt="No Thumbnail" width="60">';
            })
            ->addColumn('duration', function ($video) {
                return $video->duration ??'<span class="small text-danger">Not Available</span>';
            })
            ->addColumn('status', function ($video) {
                $checked = $video->is_enabled ? 'checked' : '';
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" data-id="' . $video->id . '" id="switch' . $video->id . '" ' . $checked . ' data-switch="success">
                        <label for="switch' . $video->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                    </div>';
            })
            ->addColumn('action', function ($video) {
                $actions = '';

                if (auth()->user()->hasPermission('videos.update')) {
                    $editUrl = route('lms.videos.edit', $video->id);
                    $actions .= '<a href="' . $editUrl . '" class="action-icon">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }

                if (auth()->user()->hasPermission('videos.delete')) {
                    $deleteModalId = 'deleteVideoModal' . $video->id;
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#' . $deleteModalId . '">
                                    <i class="mdi mdi-delete"></i>
                                </a>';
                }

                return $actions;
            })


            ->rawColumns(['thumbnail', 'status', 'action', 'duration'])
            ->make(true);
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'chunk' => 'required|file',
            'upload_id' => 'required|string',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'original_name' => 'required|string',
        ]);

        $tempDir = storage_path("app/temp_chunks/{$request->upload_id}");
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }

        // Save chunk file
        $chunkPath = "{$tempDir}/chunk_{$request->chunk_index}";
        File::put($chunkPath, file_get_contents($request->file('chunk')));

        // Check if all chunks are uploaded
        $uploadedChunks = File::files($tempDir);
        if (count($uploadedChunks) == $request->total_chunks) {
            // Generate unique filename (keeps original extension)
            $extension = pathinfo($request->original_name, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('video_', true) . '.' . $extension;
            $relativePath = "videos/{$uniqueFileName}";
            $finalPath = storage_path("app/public/" . $relativePath);

            $output = fopen($finalPath, 'ab');

            for ($i = 0; $i < $request->total_chunks; $i++) {
                $chunk = "{$tempDir}/chunk_{$i}";
                $in = fopen($chunk, 'rb');
                stream_copy_to_stream($in, $output);
                fclose($in);
            }

            fclose($output);

            File::deleteDirectory($tempDir);

            return response()->json([
                'message' => 'Video uploaded and merged successfully',
                'file_name' => $relativePath
            ]);
        }

        return response()->json(['message' => "Chunk {$request->chunk_index} uploaded"], 200);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Video store request started', [
                'title' => $request->title,
                'video_path' => $request->video_path,
                'has_thumbnail' => $request->hasFile('thumbnail'),
            ]);

            $validator = Validator::make($request->all(), [
                'title'       => 'required|string|max:255',
                'video_path'  => 'required|string',
                'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description' => 'nullable|string',
                'order'       => 'nullable|integer',
                'is_enable'   => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('uploads/thumbnails', 'public');
                Log::info('Thumbnail uploaded', ['path' => $thumbnailPath]);
            }

            $videoPath = storage_path('app/public/' . $request->video_path);
            $duration = null;
            if (file_exists($videoPath)) {
                $getID3 = new getID3;
                $fileInfo = $getID3->analyze($videoPath);

                if (isset($fileInfo['playtime_seconds'])) {
                    $duration = round($fileInfo['playtime_seconds'] / 60, 2); 
                }
            }
            
            $video = Video::create([
                'title'           => $request->title,
                'video'           => $request->video_path, // already uploaded via chunk uploader
                'thumbnail'       => $thumbnailPath,
                'description'     => $request->description,
                'order'           => $request->order ?? 0,
                'is_enabled'      => $request->boolean('is_enable'),
                'is_bulk_uploaded' => 0,
                'status'          => 'completed',
                'duration'        => $duration
            ]);

            Log::info('Video created successfully', ['video_id' => $video->id]);

            return response()->json([
                'success' => true,
                'message' => 'Video created successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Error while storing video', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the video.',
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video = Video::findOrFail($id);
        $subjects = Subject::all();
        return view('lms.sections.videos.edit', compact('subjects', 'video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info('Attempting to update video.', ['video_id' => $id, 'request_data' => $request->except(['_token', '_method'])]);

        try {
            $video = Video::findOrFail($id);

            Log::info('Video found for update.', ['video_id' => $video->id]);

            $thumbnailPath = $video->thumbnail;
            $videoPath = $request->video_path ? basename($request->video_path) : $video->video;

            if ($request->hasFile('thumbnail')) {
                if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                    Storage::disk('public')->delete($video->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('uploads/thumbnails', 'public');
            }

            if ($videoPath !== $video->video) {
                if ($video->video && Storage::disk('public')->exists("videos/{$video->video}")) {
                    Storage::disk('public')->delete("videos/{$video->video}");
                }
            }

            $video->update([
                'title'            => $request->title,
                'video'            => $videoPath,
                'thumbnail'        => $thumbnailPath,
                'description'      => $request->description,
                'order'            => $request->order ?? 0,
                'is_enabled'       => $request->input('is_enable', 0),
                'is_bulk_uploaded' => 0,
                'status'           => 'completed',
            ]);

            Log::info('Video updated successfully.', ['video_id' => $video->id]);

            return redirect()->route('lms.videos.index')->with('success', 'Video updated successfully!');
        } catch (Exception $e) {
            Log::error('Error updating video: ' . $e->getMessage(), ['exception' => $e, 'video_id' => $id]);

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        if ($video->video && Storage::disk('public')->exists('videos/' . $video->video)) {
            Storage::disk('public')->delete('videos/' . $video->video);
        }

        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully.'
        ]);
    }
}
