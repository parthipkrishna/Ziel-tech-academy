<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ToolKit;
use App\Models\ToolKitMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\ToolKitEnquiry;

class ToolKitController extends Controller
{
    /**
     * Show details of a single toolkit.
     */
   public function show(Request $request, int $id)
{
    try {
        // Check if user is authenticated
        $studentId = auth()->check() ? auth()->user()->student_id : null;

        // Find the toolkit
        $toolKit = ToolKit::with(['media', 'course'])->findOrFail($id);

        // Get enquiry for this student only (if logged in)
        $enquiry = null;
        if ($studentId) {
            $enquiry = $toolKit->enquiries()
                ->where('student_id', $studentId)
                ->first();
        }

        return response()->json([
            'status'  => true,
            'data'    => $toolKit,
            'enquiry' => $enquiry,
        ]);
    } catch (\Throwable $e) {
        Log::error('ToolKit Show Error', [
            'id'    => $id,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => 'Toolkit not found',
        ], 404);
    }
}

    /**
     * Create a new toolkit.
     */
    /**
     * Create a new toolkit.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'          => 'required|exists:courses,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'short_description'  => 'nullable|string|max:255',
            'is_enabled'         => 'boolean',
            'price'              => 'nullable|numeric|min:0',
            'offer_price'        => 'nullable|numeric|min:0|lt:price',
            'min_loyalty_points' => 'nullable|integer|min:0',
            'media'              => 'nullable|array',
            'media.*'            => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error'  => $validator->errors()->first(),
            ], 422);
        }

        try {
            $toolKit = DB::transaction(function () use ($request) {
                $toolKit = ToolKit::create($request->only([
                    'course_id',
                    'name',
                    'description',
                    'short_description',
                    'is_enabled',
                    'price',
                    'offer_price',
                    'min_loyalty_points',
                ]));

                if ($request->hasFile('media')) {
                    $mediaData = [];

                    foreach ($request->file('media') as $file) {
                        $uniqueName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

                        // Store under storage/app/public/toolkits/{course_id}/
                        $path = $file->storeAs(
                            "toolkits/{$toolKit->course_id}",
                            $uniqueName,
                            'public' // important: saves under storage/app/public
                        );

                        // $path returned will be: toolkits/{course_id}/filename.jpg
                        $mediaData[] = [
                            'tool_kit_id' => $toolKit->id,
                            'file_path'   => $path,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }

                    ToolKitMedia::insert($mediaData);
                }

                return $toolKit->load('media');
            });

            return response()->json([
                'status'  => true,
                'message' => 'Toolkit created successfully',
                'data'    => $toolKit,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('ToolKit Store Error', [
                'input' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to create toolkit',
            ], 500);
        }
    }


    /**
     * Submit a toolkit enquiry (student).
     */
    public function submitEnquiry(int $toolkitId)
    {
        $student = auth()->user()->studentProfile; // assuming relation user->student
        if (!$student) {
            return response()->json([
                'status'  => false,
                'message' => 'Student profile not found.'
            ], 404);
        }

        try {
            $toolkit = Toolkit::findOrFail($toolkitId);

            // Check for existing active enquiry
            $existing = ToolkitEnquiry::where('toolkit_id', $toolkit->id)
                ->where('student_id', $student->id)
                ->first();

            if ($existing) {
                return response()->json([
                    'status'  => false,
                    'message' => 'You already have an active enquiry for this toolkit.'
                ], 409);
            }
            $enquiry = ToolkitEnquiry::create([
                'toolkit_id'    => $toolkit->id,
                'student_id'    => $student->id,
                'student_name'  => $student->full_name,
                'state'         => $student->state ?? null,
                'phone'         => auth()->user()->phone ?? null,
                'email'         => auth()->user()->email ?? null,
                'address'       => $student->address ?? null, // assuming JSON or array
                'toolkit_name'  => $toolkit->name,
                'total_amount'  => $toolkit->price ?? null,
                'status'        => ToolkitEnquiry::STATUS_REQUEST_PLACED,
            ]);

            Log::info('Toolkit enquiry submitted successfully', [
                'enquiry_id'  => $enquiry->id,
                'toolkit_id'  => $toolkitId,
                'student_id'  => $student->id
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Enquiry submitted successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Toolkit enquiry failed', [
                'toolkit_id' => $toolkitId,
                'student_id' => $student->id ?? null,
                'error'      => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to submit enquiry. Please try again later.'
            ], 500);
        }
    }

    /**
     * Cancel a toolkit enquiry (student).
     */
    public function cancelEnquiry(int $enquiryId)
    {
        $student = auth()->user()->studentProfile; // assuming relation user->student

        try {
            $enquiry = ToolkitEnquiry::where('id', $enquiryId)
                ->where('student_id', $student->id)
                ->where('status', ToolkitEnquiry::STATUS_REQUEST_PLACED)
                ->firstOrFail();

            $enquiry->update([
                'status' => ToolkitEnquiry::STATUS_CANCELLED
            ]);

            Log::info('Toolkit enquiry cancelled', [
                'enquiry_id' => $enquiryId,
                'student_id' => $student->id
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Your enquiry has been cancelled.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'No active enquiry found to cancel.'
            ], 404);
        } catch (\Throwable $e) {
            Log::error('Failed to cancel enquiry', [
                'enquiry_id' => $enquiryId,
                'student_id' => $student->id ?? null,
                'error'      => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to cancel enquiry. Please try again later.'
            ], 500);
        }
    }
}
