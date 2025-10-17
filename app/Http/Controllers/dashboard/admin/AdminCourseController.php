<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\File;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::where('type', 'web')->get();
        return view('dashboard.course.index')->with(compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.course.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'languages' => 'nullable',
            'tags' => 'nullable',
            'cover_image_web' => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'cover_image_mobile' => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
        ]);
        $data = $request->all();
        $webImage = NULL;
        $mobileImage = NULL;
        if ($request->hasFile('cover_image_web')) {
            $webImage = $request->file('cover_image_web')->store('uploads/images/Courses/Web', 'public');
        }

        if ($request->hasFile('cover_image_mobile')) {
            $mobileImage = $request->file('cover_image_mobile')->store('uploads/images/Courses/Mobile', 'public');
        }

        $course= new Course();
        $course->name = $request->input('name');
        $course->short_description = $request->input('short_description')? : NULL;
        $course->full_description = $request->input('full_description')? : NULL;
        $course->target_audience = $request->input('target_audience')? : NULL;
        $course->course_fee = $request->input('course_fee')? : NULL;
        $course->toolkit_fee = $request->input('toolkit_fee')? : NULL;
        $course->total_hours = $request->input('total_hours') ? : NULL;
        $course->cover_image_web = $webImage;
        $course->cover_image_mobile = $mobileImage  ;
        $course->languages = is_array($request->languages) ? implode(',', $request->languages) : $request->languages;
        $course->tags = is_array($request->tags) ? implode(',', $request->tags) : $request->tags;
        $course->status =  $request->has('status') ? 1 : 0;
        $course->type = 'web';
        $success = $course->save();
        if ($success) {
            $message = 'Course added successfully';
            return redirect()->route('admin.courses.index')->with('message', 'Successfully updated');
        } else {
            $message = 'Failed to add course. Please try again.';
            return redirect()->back()->withErrors(compact('message'))->withInput($request->input());
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
        $success = Course::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
