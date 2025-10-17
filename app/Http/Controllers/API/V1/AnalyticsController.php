<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    Student,
    Attendance,
    Course,
    Exam,
    Subject,
    VideoProgress,
    StudentScore,
    LiveClassParticipant
};

class AnalyticsController extends Controller
{
    /**
     * Main Analytics API
     * Provides student details, attendance, syllabus progress and quality checks
     */
    public function index(Request $request)
    {
        try {
            // 🔹 Step 1: Validate input
            $validator = Validator::make($request->all(), [
                'course_id'  => 'required|exists:courses,id',
                'subject_id' => 'nullable|exists:subjects,id',
                'attendance' => 'nullable|in:week,month,3months',
            ]);

            if ($validator->fails()) {
                Log::warning("Analytics validation failed", [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $studentId = auth()->user()->student_id;
            $course    = Course::findOrFail($request->course_id);

            // 🔹 Step 2: Collect all required modules
            $student    = $this->getStudentDetails($course);
            $attendance = $this->getAttendance($studentId, $request->attendance);
            $syllabus   = $this->getSyllabusTracker($studentId, $course->id, $request->subject_id);

            if ($request->subject_id && empty($syllabus['subjects_progress'])) {
                Log::info("Analytics subject filter returned no data", [
                    'student_id' => $studentId,
                    'subject_id' => $request->subject_id
                ]);
                return $this->errorResponse('No data available for this subject or assessment', 404);
            }

            $qualityCheck = $this->getQualityCheck($studentId, $syllabus, $attendance['days']);

            // 🔹 Step 3: Success Response
            Log::info("Analytics data fetched successfully", [
                'student_id' => $studentId,
                'course_id'  => $course->id
            ]);

            return response()->json([
                'status'           => true,
                'student'          => $student,
                'attendance'       => $attendance,
                'syllabus_tracker' => $syllabus,
                'quality_check'    => $qualityCheck,
            ], 200);
        } catch (\Throwable $e) {
            // 🔹 Error logging with full trace
            Log::error("Analytics API Exception", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Something went wrong!', 500);
        }
    }

    /**
     * Get basic student details
     */
    private function getStudentDetails(Course $course): array
    {
        $student = auth()->user()->studentProfile;

        return [
            'name'          => $student->full_name ?? 'N/A',
            'course'        => $course->name ?? "No Course Available",
            'profile_image' => auth()->user()->profile_image,
        ];
    }

    /**
     * Get attendance details with filter (week/month/3months)
     */
    private function getAttendance(int $studentId, ?string $filter = 'week'): array
    {
        $range = match ($filter) {
            'month'    => 30,
            '3months'  => 90,
            default    => 7,
        };

        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $attendanceLogs = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $days = [];
        $presentCount = 0;

        for ($i = 0; $i < $range; $i++) {
            $day = Carbon::now()->subDays($range - 1 - $i)->format('Y-m-d');
            $isPresent = in_array($day, $attendanceLogs);
            if ($isPresent) $presentCount++;

            $days[] = [
                'day'        => $i + 1,
                'date'       => $day,
                'is_present' => $isPresent,
            ];
        }

        $percentage = $range > 0 ? round(($presentCount / $range) * 100) : 0;

        $status = match (true) {
            $percentage >= 50 => "🎉 Great! You are going well at the moment",
            $percentage >= 20 => "⚠️ Needs Improvement! Try to attend more classes",
            default           => "❌ Critical! Your attendance is too low",
        };

        return compact('days', 'percentage', 'status');
    }

    /**
     * Get syllabus tracker details for all subjects under a course
     */
    /**
     * Get syllabus tracker details for all subjects under a course
     */
    private function getSyllabusTracker(int $studentId, int $courseId, ?int $subjectId = null): array
    {
        $course = Course::with(['subjects.sessions.videos', 'subjects.liveClasses'])
            ->find($courseId);

        if (!$course) {
            Log::warning("Course not found in syllabus tracker", ['course_id' => $courseId]);
            return ['status' => false, 'error' => 'Course not found'];
        }

        // ✅ Fetch all active subjects
        $allSubjects = $course->subjects()->where('status', true)->get();

        if ($subjectId) {
            $allSubjects = $allSubjects->where('id', $subjectId);
        }

        $syllabus = [];
        $totalItems = $totalCompleted = 0;

        foreach ($allSubjects as $subject) {
            [$subjectTotal, $subjectCompleted, $counts] = $this->calculateSubjectProgress($studentId, $subject);

            // Even if total_items > 0, completed_items may be 0
            $completionPercent = $subjectTotal > 0
                ? round(($subjectCompleted / $subjectTotal) * 100, 0)
                : 0;

            $syllabus[] = [
                'subject_id'         => $subject->id,
                'subject_name'       => $subject->name,
                'total_items'        => $subjectTotal,
                'completed_items'    => $subjectCompleted,
                'completion_percent' => $completionPercent,
                'breakdown'          => $counts,
            ];

            $totalItems     += $subjectTotal;
            $totalCompleted += $subjectCompleted;
        }

        $courseProgress = $totalItems > 0 ? round(($totalCompleted / $totalItems) * 100, 0) : 0;

        return [
            'status'            => true,
            'course_id'         => $course->id,
            'course_title'      => $course->name,
            'total_progress'    => $courseProgress,
            'subjects_progress' => $syllabus,
        ];
    }

    /**
     * Calculate progress for a specific subject
     * Always returns breakdown (even if student progress = 0)
     */
    private function calculateSubjectProgress(int $studentId, Subject $subject): array
    {
        $subjectTotal = 0;
        $subjectCompleted = 0;

        // Default breakdown structure
        $counts = [
            'videos'       => 0,
            'assessments'  => 0,
            'exams'        => 0,
            'live_classes' => 0,
        ];

        // 🔹 Videos
        foreach ($subject->sessions as $session) {
            $videoIds = $session->videos->pluck('id');
            $videoCount = $videoIds->count();

            $counts['videos'] += $videoCount;
            $subjectTotal     += $videoCount;

            if ($videoCount > 0) {
                $completedVideos = VideoProgress::where('student_id', $studentId)
                    ->whereIn('video_id', $videoIds)
                    ->where('is_completed', 1)
                    ->count();
                $subjectCompleted += $completedVideos;
            }
        }

        // 🔹 Assessments & Exams
        foreach (['Assessment', 'Exam'] as $type) {
            $exams = Exam::where('subject_id', $subject->id)
                ->where('type', $type)
                ->get();

            foreach ($exams as $exam) {
                $subjectTotal++;
                $counts[strtolower($type) . 's']++;

                $score = StudentScore::where('student_id', $studentId)
                    ->where('exam_id', $exam->id)
                    ->first();

                if ($score?->total_score >= $exam->minimum_passing_marks) {
                    $subjectCompleted++;
                }
            }
        }

        // 🔹 Live Classes
        foreach ($subject->liveClasses as $live) {
            $subjectTotal++;
            $counts['live_classes']++;

            if (LiveClassParticipant::where('student_id', $studentId)
                ->where('live_class_id', $live->id)
                ->exists()
            ) {
                $subjectCompleted++;
            }
        }

        return [$subjectTotal, $subjectCompleted, $counts];
    }


    /**
     * Perform quality check based on attendance & content availability
     */
    private function getQualityCheck(int $studentId, array $syllabus, array $attendanceDays): array
    {
        $totals = collect($syllabus['subjects_progress'] ?? [])
            ->pluck('breakdown')
            ->reduce(function ($carry, $item) {
                foreach ($item as $key => $val) {
                    $carry[$key] = ($carry[$key] ?? 0) + $val;
                }
                return $carry;
            }, []);

        $totalDays = count($attendanceDays);
        $presentDays = collect($attendanceDays)->where('is_present', true)->count();
        $attendancePercent = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;

        $checks = [
            'live_classes'    => ($totals['live_classes'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'assessments'     => ($totals['assessments'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'recorded_videos' => ($totals['videos'] ?? 0) > 0 ? 'Pass' : 'Fail',
            'attendance'      => $attendancePercent >= 50 ? 'Pass' : 'Fail',
        ];

        // Overall result
        $checks['title'] = collect($checks)->contains('Fail') ? 'Fail' : 'Pass';

        return $checks;
    }

    /**
     * Standard error response
     */
    private function errorResponse(string $message, int $code = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $code);
    }
}
