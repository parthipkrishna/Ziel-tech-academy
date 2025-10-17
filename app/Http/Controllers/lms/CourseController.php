<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Str;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = getLmsCourses();
        return view('lms.sections.courses.courses   ')->with(compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.courses.add-course');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'languages'         => 'nullable',
            'tags'              => 'nullable',
            'cover_image_web'   => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'cover_image_mobile'=> 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            // Log validation errors for debugging
            Log::info('Course creation validation failed.', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $webImage = null;
            $mobileImage = null;

            if ($request->hasFile('cover_image_web')) {
                $webImage = $request->file('cover_image_web')
                    ->store('uploads/images/Courses/lms/web', 'public');
            }

            if ($request->hasFile('cover_image_mobile')) {
                $mobileImage = $request->file('cover_image_mobile')
                    ->store('uploads/images/Courses/lms/mobile', 'public');
            }

            $course = new Course();
            $course->name               = $request->input('name');
            $course->short_description  = $request->input('short_description') ?: null;
            $course->full_description   = $request->input('full_description') ?: null;
            $course->target_audience    = $request->input('target_audience') ?: null;
            $course->course_fee         = $request->input('course_fee') ?: null;
            $course->course_end_date    = $request->input('course_end_months') ?: null;
            $course->toolkit_fee        = $request->input('toolkit_fee') ?: null;
            $course->total_hours        = $request->input('total_hours') ?: null;
            $course->cover_image_web    = $webImage;
            $course->cover_image_mobile = $mobileImage;
            $course->languages          = is_array($request->languages) ? implode(',', $request->languages) : $request->languages;
            $course->tags               = is_array($request->tags) ? implode(',', $request->tags) : $request->tags;
            $course->status             = $request->has('status') ? 1 : 0;
            $course->type               = 'lms';

            $course->save();

            // Log successful creation
            Log::info('Course created successfully.', ['course_id' => $course->id, 'course_name' => $course->name]);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully!',
            ]);

        } catch (Exception $e) {
            // Log the exception for detailed error detection
            Log::error('Error creating course: ' . $e->getMessage(), ['exception' => $e]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$e->getMessage(),
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
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        if ($request->ajax()) {
            $course->status = $request->status;
            $course->save();
            return response()->noContent();
        }

        $existing_image_web = base_path($course->cover_image_web);
        if($request->file('cover_image_web')){
            if(File::exists($existing_image_web)){
                File::delete($existing_image_web);
            }
           $webImage = $request->file('cover_image_web')->store('uploads/images/Courses/Web', 'public');
        }

        $existing_image_mobile = base_path($course->cover_image_mobile);
        if($request->file('cover_image_mobile')){
            if(File::exists($existing_image_mobile)){
                File::delete($existing_image_mobile);
            }
           $mobileImage = $request->file('cover_image_mobile')->store('uploads/images/Courses/Mobile', 'public');
        }
        
        $updated = $course->update([
            'name' => $request->input('name')?: $course->name,
            'short_description' => $request->input('short_description')?: $course->short_description,
            'full_description' => $request->input('full_description')?: $course->full_description,
            'target_audience' => $request->input('target_audience')?: $course->target_audience,
            'course_fee' => $request->input('course_fee')?: $course->course_fee,
            'course_end_date' => $request->input('course_end_date')?: $course->course_end_date,
            'toolkit_fee' => $request->input('toolkit_fee')?: $course->toolkit_fee,
            'total_hours' => $request->input('total_hours')?: $course->total_hours,
            'languages' => is_array($request->languages) ? implode(',', $request->languages) : $request->languages,
            'tags' =>is_array($request->tags) ? implode(',', $request->tags) : $request->tags,
            'cover_image_web' => $request->file('cover_image_web')?$webImage:$course->cover_image_web,
            'cover_image_mobile' => $request->file('cover_image_mobile')?$mobileImage:$course->cover_image_mobile,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $course->status,
        ]);
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
    {
    $course = Course::findOrFail($id); 
    $course->delete(); 

    return response()->json(['message' => 'User deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $course = Course::findOrFail($id);
        $course->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }
    public function ajaxCourseList()
    {
        $courses = Course::select('courses.*')->latest();

        return DataTables::of($courses)
            ->addColumn('cover', function ($course) {
                if ($course->cover_image_web) {
                    return '<img src="' . env('STORAGE_URL') . '/' . $course->cover_image_web . '" 
                                class="me-2" 
                                width="60 height="40" 
                                style="object-fit: cover;">';
                } else {
                    return '<span class="small text-danger">No Image</span>';
                }
            })
            ->addColumn('short_description', function ($course) {
                return $course->short_description ? Str::limit($course->short_description, 25, '...') : '<span class="small text-danger">No Short Description</span>';
            })
            ->addColumn('course_fee', function ($course) {
                return $course->course_fee ? Str::limit($course->course_fee, 25, '...') : '<span class="small text-danger">No Course Fee</span>';
            })
            ->addColumn('status', function ($course) {
                $checked = $course->status ? 'checked' : '';
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" id="switch-status' . $course->id . '" data-id="' . $course->id . '" value="1" ' . $checked . ' data-switch="success" />
                        <label for="switch-status' . $course->id . '" data-on-label="Yes" data-off-label="No"></label>
                    </div>';
            })
            ->addColumn('action', function ($course) {
                $editModal = view('lms.sections.courses.inc.edit-course-modal', compact('course'))->render();
                $deleteModal = view('lms.sections.courses.inc.delete-course-modal', compact('course'))->render();

                 $editBtn = auth()->user()->hasPermission('courses.update') 
                             ?'<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editCourse-modal' . $course->id . '"><i class="mdi mdi-square-edit-outline"></i></a>': '';
                
                 $deleteBtn = auth()->user()->hasPermission('courses.delete') 
                            ?'<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $course->id . '"><i class="mdi mdi-delete"></i></a>': '';
                

                return $editBtn . $deleteBtn . $editModal . $deleteModal;
    })
            ->rawColumns(['cover', 'short_description', 'course_fee', 'status', 'action'])
            ->make(true);
    }
}
