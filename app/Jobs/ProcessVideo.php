<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;
    protected $videoPath;

    public function __construct($videoId, $videoPath)
    {
        $this->videoId = $videoId;
        $this->videoPath = $videoPath;
    }

    public function handle()
    {
        $video = Video::find($this->videoId);

        if (!$video) {
            Log::error("Video processing failed: Video ID {$this->videoId} not found.");
            return;
        }

        // Update video status to processing
        $video->update(['status' => 'processing']);

        try {
            // Ensure the file exists before processing
            if (!Storage::disk('public')->exists($this->videoPath)) {
                Log::error("File not found: {$this->videoPath}");
                $video->update([
                    'status' => 'failed',
                    'error_message' => 'File not found'
                ]);
                return;
            }

            // Define the new path for the video
            $newPath = 'videos/' . basename($this->videoPath);

            // Move file safely
            if (!Storage::disk('public')->move($this->videoPath, $newPath)) {
                throw new \Exception("Failed to move video to {$newPath}");
            }

            // Update the database with the final video location
            $video->update([
                'video' => $newPath,
                'status' => 'completed'
            ]);

            Log::info("Video processing completed successfully for Video ID {$video->id}");

        } catch (\Exception $e) {
            Log::error("Video processing failed for Video ID {$video->id}: " . $e->getMessage());

            $video->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            throw $e; // Ensure the job is marked as failed for tracking
        }
    }
}
