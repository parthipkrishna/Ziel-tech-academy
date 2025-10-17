<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Subject;
use App\Models\LiveClass;
use App\Models\Video;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\TopAchiever;
use App\Models\BatchStudent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->userId = auth()->id();
            }
            return $next($request);
        });
    }

    /**
     * Authenticated home API
     */
    public function home(Request $request): JsonResponse
    {
        try {
     
            // Validate course_id (nullable, must exist if provided)
            $validator = Validator::make($request->all(), [
                'course_id' => 'nullable|string|exists:courses,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Common data
            $banners = Banner::where('status', 1)->get();
            $courses = Course::where('status', 1)->get();

            $courseId = $request->input('course_id');
            $subjects = Subject::where('status', 1)
                ->when($courseId, fn($q) => $q->where('course_id', $courseId))
                ->get();

            $tQuery = TopAchiever::where('status', 1);
            $topAchievers = $tQuery->get();

            // Guest vs Auth path
            if (!auth()->check()) {
                return $this->guestHome($banners, $courses, $subjects, $topAchievers);
            }

            return $this->authHome($banners, $courses, $subjects, $topAchievers, $courseId);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error fetching home data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Guest User Home Data
     */
    private function guestHome($banners, $courses, $subjects, $topAchievers): JsonResponse
    {
        // First 3 videos (not batch specific)
        $videos = Video::where('status', 'completed')
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'status'       => true,
            'user_type'    => 'guest',
            'banners'      => $banners,
            'courses'      => $courses,
            'videos'       => $videos,
            'subjects'     => $subjects,
            'topAchievers' => $topAchievers,
        ], 200);
    }

    /**
     * Handle Authenticated User Home Data
     */
    private function authHome($banners, $courses, $subjects, $topAchievers, $courseId): JsonResponse
    {
        $studentId = auth()->user()->studentProfile->id;
        //creating attendance for student
        Attendance::firstOrCreate(
            [
                'student_id' => $studentId,
                'date'       => Carbon::today()->toDateString(),
            ],
            [
                'time'       => Carbon::now()->format('H:i:s'), // ✅ store time once
            ]
        );

        // Find first batch for student
        $batchStudent = BatchStudent::where('student_id', $studentId)->first();

        // If course_id is null, get from batch
        if (!$courseId && $batchStudent && $batchStudent->batch) {
            $courseId = $batchStudent->batch->course_id;
            $subjects = Subject::where('status', 1)
                ->when($courseId, fn($q) => $q->where('course_id', $courseId))
                ->get();
        }

        // Live classes
        $liveClasses = [];
        if ($batchStudent && $batchStudent->batch_id) {
            $liveClasses = LiveClass::with(['tutor.user', 'batch'])->where('status', 1)
                ->where('batch_id', $batchStudent->batch_id)
                ->get();
        }

        // First 3 videos (not batch specific)
        $videos = Video::where('status', 'completed')
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'status'       => true,
            'user_type'    => 'auth',
            'student_id'   => $studentId,
            'banners'      => $banners,
            'courses'      => $courses,
            'subjects'     => $subjects,
            'liveClasses'  => $liveClasses,
            'videos'       => $videos,
            'topAchievers' => $topAchievers,
        ], 200);
    }

    /**
     * Delete User Account (Auth Required)
     */
    public function deleteUserAccount(): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $user_role = $user->roles->first(); // Retrieve the first associated role (if any)

            if (!$user_role || strcasecmp($user_role->role_name, "STUDENT") !== 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Delete user and cascade remove related data
            $user->delete();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'User account deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Error deleting account: ' . $e->getMessage(),
            ], 500);
        }
    }
}
