<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\OfflineCourse;
use App\Models\OfflineCourseType;

class AdminOfflineCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offline_courses = OfflineCourse::all();
        $list_main = [];
        foreach( $offline_courses as $course){
            $type = OfflineCourseType::where('offline_course_id',$course->id )->first();
            $list_main[] = [
                'id' => $course->id,
                'name' => $course->name,
                'total_fee' => $course->total_fee,
                'advance_fee' => $course->advance_fee,
                'monthly_fee' => $course->monthly_fee,
                'monthly_fee_duration' => $course->monthly_fee_duration,
                'base_name' => $type->base_name,
                'short_description' =>$type->short_description,
                'full_description' => $type->full_description,
                'cover_image' => $type->cover_image,
                'duration' =>$type->duration,
                'status' => $type->status,
            ];
        }
        return view('dashboard.course.offline.index')->with(compact('list_main'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.course.offline.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'total_fee'=>'required',
            'advance_fee'=>'required',
            'monthly_fee'=>'required',
            'base_name'=>'required',
            'duration'=>'required',
            'monthly_fee_duration'=>'required',
            'cover_image' => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
        ]);
        $data = $request->all();
        $webImage = NULL;
        if ($request->hasFile('cover_image')) {
            $webImage = $request->file('cover_image')->store('uploads/images/Courses/Offline', 'public');
        }
        $offlineCourseId = DB::table('offline_courses')->insertGetId([
            'name' => $data['name'],
            'total_fee' => $data['total_fee'],
            'advance_fee' => $data['advance_fee'],
            'monthly_fee' => $data['monthly_fee'],
            'monthly_fee_duration' => $data['monthly_fee_duration'],
            
        ]);

        $enroll= new OfflineCourseType();
        $enroll->offline_course_id = $offlineCourseId;
        $enroll->base_name = $request->input('base_name');
        $enroll->short_description = $request->input('short_description')? : NULL;
        $enroll->full_description = $request->input('full_description')? : NULL;
        $enroll->cover_image = $webImage;
        $enroll->duration = $request->input('duration');
        $enroll->status =  $request->has('status') ? 1 : 0;
        $success = $enroll->save();
        if ($success) {
            $message = 'Offline Course added successfully';
            return redirect()->route('admin.offline.courses.index')->with('message', 'Successfully stored');
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
        $offline_course = OfflineCourse::findOrFail($id);
        $offline_course_type = OfflineCourseType::where('offline_course_id',$id)->first();
        if ($request->ajax()) {
            $offline_course_type->status = $request->status;
            $offline_course_type->save();
            return response()->noContent();
        }

        $existing_image_web = base_path($offline_course_type->cover_image);
        if($request->file('cover_image')){
            if(File::exists($existing_image_web)){
                File::delete($existing_image_web);
            }
           $webImage = $request->file('cover_image')->store('uploads/images/Courses/Offline', 'public');
        }

        $updated = $offline_course->update([
            'name' => $request->input('name')?: $offline_course->name,
            'total_fee' => $request->input('total_fee')?: $offline_course->total_fee,
            'advance_fee' => $request->input('advance_fee')?: $offline_course->advance_fee,
            'monthly_fee' => $request->input('monthly_fee')?: $offline_course->monthly_fee,
            'monthly_fee_duration' => $request->input('monthly_fee_duration')?: $offline_course->monthly_fee_duration,
        ]);   
        $updated = $offline_course_type->update([
            'base_name' => $request->input('base_name')?: $offline_course_type->base_name,
            'short_description' => $request->input('short_description')?: $offline_course_type->short_description,
            'full_description' => $request->input('full_description')?: $offline_course_type->full_description,
            'cover_image' => $request->file('cover_image')?$webImage:$offline_course_type->cover_image,
            'duration' => $request->input('duration')?: $offline_course_type->duration,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $offline_course_type->status,
            
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
        $offline_course = OfflineCourse::find($id);
        if ($offline_course) {
            OfflineCourseType::where('offline_course_id', $id)->delete();
            $offline_course->delete();
            return redirect()->back()->with(['message' => 'Delete success']);
        }
        return redirect()->back()->with(['error' => 'Course not found']);
    }
}
