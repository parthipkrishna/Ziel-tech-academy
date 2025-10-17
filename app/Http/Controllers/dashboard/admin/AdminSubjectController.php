<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Support\Facades\File;

class AdminSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::where('type', 'web')->get();
        $courses= Course::all();
        $subject_main= [];
        foreach( $subjects as $subject){
            $course=Course::where('id',$subject->course_id )->first();
            $subject_main[] = [
                'id' => $subject->id,
                'course_name' => $course->name,
                'name' => $subject->name,
                'short_desc' => $subject->short_desc,
                'desc' => $subject->desc,
                'total_hours' => $subject->total_hours,
                'mobile_thumbnail' => $subject->mobile_thumbnail,
                'web_thumbnail' => $subject->web_thumbnail,
                'status' =>$subject->status,
            ];
        }
        return view('dashboard.subject.online.index')->with(compact('subject_main','courses'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('type', 'web')->get();
        return view('dashboard.subject.online.add')->with(compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'short_desc' => 'required',
            'desc' => 'required',
            'course_id' => 'required',
            'total_hours' => 'required|integer',
            'mobile_thumbnail' => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'web_thumbnail' => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'status' => 'required',        ]);
        $data = $request->all();
        $webImage = NULL;
        $mobileImage = NULL;
        if ($request->hasFile('web_thumbnail')) {
            $webImage = $request->file('web_thumbnail')->store('uploads/images/Subjects/Web', 'public');
        }

        if ($request->hasFile('mobile_thumbnail')) {
            $mobileImage = $request->file('mobile_thumbnail')->store('uploads/images/Subjects/Mobile', 'public');
        }

        $subject= new Subject();
        $subject->name = $request->input('name');
        $subject->short_desc = $request->input('short_desc')? : NULL;
        $subject->desc = $request->input('desc')? : NULL;
        $subject->course_id = $request->input('course_id')? : NULL;
        $subject->total_hours = $request->input('total_hours')? : NULL;
        $subject->web_thumbnail = $webImage;
        $subject->mobile_thumbnail = $mobileImage  ;
        $subject->status =  $request->has('status') ? 1 : 0;
        $subject->type = 'web';
        $success = $subject->save();
        if ($success) {
            $message = 'Subject added successfully';
            return redirect()->route('admin.subjects.index')->with('message', 'Successfully updated');
        } else {
            $message = 'Failed to add Subject. Please try again.';
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
        $subject = Subject::findOrFail($id);
        if ($request->ajax()) {
            $subject->status = $request->status;
            $subject->save();
            return response()->noContent();
        }
        
        $existing_image_web = base_path($subject->web_thumbnail);
        if($request->file('web_thumbnail')){
            if(File::exists($existing_image_web)){
                File::delete($existing_image_web);
            }
           $webImage = $request->file('web_thumbnail')->store('uploads/images/Subjects/Web', 'public');
        }

        $existing_image_mobile = base_path($subject->mobile_thumbnail);
        if($request->file('mobile_thumbnail')){
            if(File::exists($existing_image_mobile)){
                File::delete($existing_image_mobile);
            }
           $mobileImage = $request->file('mobile_thumbnail')->store('uploads/images/Subjects/Mobile', 'public');
        }
        
        $updated = $subject->update([
            'name' => $request->input('name')?: $subject->name,
            'short_desc' => $request->input('short_desc')?: $subject->short_desc,
            'desc' => $request->input('desc')?: $subject->desc,
            'course_id' => $request->input('course_id')?: $subject->course_id,
            'total_hours' => $request->input('total_hours')?: $subject->total_hours,
            'web_thumbnail' => $request->file('web_thumbnail')?$webImage:$subject->web_thumbnail,
            'mobile_thumbnail' => $request->file('mobile_thumbnail')?$mobileImage:$subject->mobile_thumbnail,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $subject->status,
            'type' => 'web',
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
        $success = Subject::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
