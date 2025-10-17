<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentEnrollment;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
   
   /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function index(Request $request): JsonResponse
    {
        try {
            // Fetch only courses where type is 'lms'
            $courses = Course::where('type', 'lms')->get(); 
    
            return response()->json([
                'status' => true,
                'data' => $courses
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch courses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'short_desc' => 'nullable|string',
                'full_desc' => 'nullable|string',
                'cover_image_mobile' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Accept image files only
                'cover_image_web' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
                'total_hours' => 'nullable|integer',
                'tags' => 'nullable|array'
            ]);

            // Handle image uploads
            if ($request->hasFile('cover_image_mobile')) {
                $validatedData['cover_image_mobile'] = $request->file('cover_image_mobile')->store('courses/mobile', 'public');
            }
    
            if ($request->hasFile('cover_image_web')) {
                $validatedData['cover_image_web'] = $request->file('cover_image_web')->store('courses/web', 'public');
            }
    
            // Ensure the type is always 'lms'
            $validatedData['type'] = 'lms';
    
            // Create course with the validated data and authenticated user ID
            $course = Course::create(array_merge($validatedData, ['user_id' => Auth::id()]));
            return response()->json([
                'status' => true,
                'message' => 'Course created successfully',
                'data' => $course
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function show($id): JsonResponse
    // {
    //     try {
    //         $course = Course::with('subjects')->findOrFail($id);
    //         return response()->json([
    //             'status' => true,
    //             'course' => $course
    //         ], 200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'error' => 'Course not found'
    //         ], 404);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'error' => 'Failed to retrieve course: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function show($id): JsonResponse
    {
        try {
            $course = Course::with(['subjects' => fn($q) => $q->withCount('recordedVideos'), 'toolkits'])
                            ->findOrFail($id);
            // Static structure (can move to config/course_structure.php)
            $courseStructure = [
                ["title" => "Live Sessions", "description" => "Weekly 2 sessions"],
                ["title" => "Assignments", "description" => "Verification after each class"],
                ["title" => "Quality Checking", "description" => "Final online QC for theory and practical"],
                ["title" => "Certification", "description" => "Awarded to QC-passed students"],
                ["title" => "Technical Assistance", "description" => "Online support and forums"],
                ["title" => "Job Placement Guarantee", "description" => "Not included"],
            ];

            return response()->json([
                'status' => true,
                'course' => $course,
                'course_structure' => $courseStructure,
                'syllabus' => $course->subjects->map(fn($subject) => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'thumbnail' => $subject->thumbnail ?? null, // optional
                    'recorded_videos_count' => $subject->recorded_videos_count
                ]),
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'error' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);

            // Validate input (all optional)
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'short_desc' => 'sometimes|string|nullable',
                'full_desc' => 'sometimes|string|nullable',
                'cover_image_mobile' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
                'cover_image_web' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
                'total_hours' => 'sometimes|integer|nullable',
                'module_count' => 'sometimes|integer|nullable',
                'tags' => 'sometimes|array|nullable'
            ]);

            // Handle image updates with deletion of old ones
            if ($request->hasFile('cover_image_mobile')) {
                Storage::delete($course->cover_image_mobile); // Delete old file
                $validatedData['cover_image_mobile'] = $request->file('cover_image_mobile')->store('courses');
            }

            if ($request->hasFile('cover_image_web')) {
                Storage::delete($course->cover_image_web); // Delete old file
                $validatedData['cover_image_web'] = $request->file('cover_image_web')->store('courses');
            }

            // Update only provided fields
            $course->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Course updated successfully',
                'data' => $course
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);

            // if ($course->user_id !== Auth::id()) {
            //     return response()->json([
            //         'status' => false,
            //         'error' => 'Unauthorized deletion'
            //     ], 403);
            // }

            $course->delete();

            return response()->json([
                'status' => true,
                'message' => 'Course deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll a student in a course.
     */
    public function enroll(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|integer|exists:courses,id',
                'student_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()->first()
                ], 400);
            }

            // Check if the student is already enrolled
            $exists = StudentEnrollment::where('course_id', $request->input('course_id'))
                                       ->where('student_id', $request->input('student_id'))
                                       ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student is already enrolled in this course.'
                ], 409);
            }

            // Enroll the student
            $enrollment = StudentEnrollment::create([
                'course_id' => $request->input('course_id'),
                'student_id' => $request->input('student_id'),
                'status' => 'enrolled' // Default status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Student enrolled successfully.'
            ], 201);
        } catch (QueryException $qe) {
            return response()->json([
                'status' => false,
                'error' => 'Database error occurred.',
                'details' => $qe->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
