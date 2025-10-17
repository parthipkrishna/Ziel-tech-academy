<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Subscription;
use App\Models\ToolKit;
use App\Models\ToolKitEnquiry;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        try {
            
            $user = Auth::user();

            $student = Student::where('user_id', $user->id)->firstOrFail();

            $courseIds = Subscription::where('student_id', $student->id)
                        ->where('status', 'active')
                        ->pluck('course_id');

            $subscriptions = Subscription::with(['course.subjects' => function ($query) {
                                $query->withCount('videos'); // Adds videos_count to each subject
                            }])
                            ->where('student_id', $student->id)
                            ->get();
            $banner = Banner::where('status', 1)
                    ->with(['toolkit', 'course'])
                    ->where(function ($query) use ($courseIds) {
                        $query->where('type', '!=', 'course')
                            ->orWhereNotIn('related_id', $courseIds);
                    })->get();
                    
            $data = [
                'banner' => $banner,
            ];

            return view('student.courses.course', compact('subscriptions', 'data'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load courses: ' . $e->getMessage());
        }
    }

    public function storeEnquiry(Request $request)
    {
        $request->validate([
            'toolkit_id' => 'required|integer|exists:tool_kits,id',
        ]);

        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $toolkit = ToolKit::findOrFail($request->toolkit_id);

            $existingEnquiry = ToolKitEnquiry::where('student_id', $student->id)
                                            ->where('toolkit_id', $toolkit->id)
                                            ->first();

            if ($existingEnquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already placed an enquiry for this toolkit.'
                ], 409);
            }

            ToolKitEnquiry::create([
                'toolkit_id'   => $toolkit->id,
                'student_id'   => $student->id,
                'student_name' => $student->first_name,
                'state'        => $student->state,
                'phone'        => $user->phone,
                'email'        => $user->email,
                'address'      => $student->address,
                'toolkit_name' => $toolkit->name,
                'total_amount' => $toolkit->price ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your enquiry has been placed successfully!',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The requested toolkit or student could not be found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function show(Course $course)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();

            $subscriptions = Subscription::with(['course.subjects' => function ($query) {
                                    $query->withCount('videos');
                                }])
                                ->where('student_id', $student->id)
                                ->get();

            $banner = Banner::where('status', 1)
                            ->where('type', 'course')
                            ->where('related_id', $course->id) 
                            ->with(['course'])
                            ->get();
            $data = ['banner' => $banner];

            $course->load([
                'subjects' => fn($q) => $q->withCount('videos'),
                'toolkits'
            ]);

            return view('student.courses.course', compact('subscriptions', 'data', 'course'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load course details: ' . $e->getMessage());
        }
    }
}
