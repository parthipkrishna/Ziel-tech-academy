<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Optional: global middleware for logging or auth checks
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    /**
     * Get all subscriptions for a given student ID.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // ✅ Step 1: Validate input
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|integer|exists:students,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'error'   => $validator->errors()->first(),
                ], 422);
            }

            $studentId = $request->input('student_id');

            // ✅ Step 2: Fetch subscriptions
            $subscriptions = Subscription::with(['course', 'student'])
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->get();

            if ($subscriptions->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No subscriptions found for this student.',
                ], 404);
            }

            // ✅ Step 3: Return successful response
            return response()->json([
                'status' => true,
                'data'   => $subscriptions,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch subscriptions: ' . $e->getMessage(),
            ], 500);
        }
    }
}
