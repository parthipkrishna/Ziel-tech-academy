<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use App\Imports\OfflineStudentsImport;
use Illuminate\Http\Request;
use App\Models\OfflineCourseEnrollment;
use App\Models\OfflineStudent;
use App\Models\OfflineCourse;
use App\Models\OfflineCourseType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminOfflineEnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = OfflineCourseEnrollment::all();
        $statuses = OfflineCourseEnrollment::getStatusOptions();
        $enrollment_main = [];
    
        foreach ($enrollments as $enroll) {
            $student = OfflineStudent::where('id',$enroll->student_id)->first();
            $course = OfflineCourse::where('id',$enroll->offline_course_id)->first();
            $user = User::where('id',$student->user_id)->first();
    
            $enrollment_main[] = [
                'id' => $enroll->id,
                'first_name' => $student->first_name ?? NULL,
                'course_name' => $course->name ?? NULL,
                'profile_image' => $user->profile_image ?? NULL,
                'email' => $user->email ?? NULL,
                'phone' => $user->phone ?? NULL,
                'status' => $enroll->status ?? NULL,
                'created_at' => $student->created_at,
            ];
        }
        return view('dashboard.student.offline.index')->with(compact('enrollment_main'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = OfflineCourse::all();
        return view('dashboard.student.offline.add')->with(compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'course_id' => 'required',
        ]);
        $data = $request->all();
        $existingUser = DB::table('users')->where('email', $data['email'])->first();
        if ($existingUser) {
            return response()->json(['message' => 'Email already exists. Please try with a different email.'], 409);
        }
        $student_exist_phone = User::where('phone', $data['phone'])->first();
        if ($student_exist_phone) {
            return response()->json(['message' => 'Phone number already exists. Please try with a different phone.'], 409);
        }
        else{
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'type' => 'web',
            ]);
    
            $studentId = DB::table('offline_students')->insertGetId([
                'user_id' => $userId,
                'first_name' => $data['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $offline_course_type = OfflineCourseType::where('offline_course_id',$request->input('course_id'))->first();
            
            $enroll= new OfflineCourseEnrollment();
            $enroll->student_id = $studentId;
            $enroll->offline_course_id = $request->input('course_id');
            $enroll->offline_course_type_id = $offline_course_type->id;
            $enroll->status = $request->input('status')? : 'enrolled';
            $success = $enroll->save();
            if ($success) {
                return response()->json([
                    'message' => 'Student Online enroll added successfully',
                    'redirect' => route('admin.offline.student.enroll.index'),
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to add . Please try again.'
                ], 500);
            }
        }  
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new OfflineStudentsImport, $request->file('file'));

        return redirect()->route('admin.offline.student.enroll.index')->with('message', 'Excel data imported successfully.');
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
        $student = OfflineCourseEnrollment::findOrFail($id);
        $updated = $student->update([
            'status' => $request->input('status') ? $request->input('status') : $branch->status,
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
        $enroll = OfflineCourseEnrollment::find($id);
        if ($enroll) {
            $student = OfflineStudent::where('id', $enroll->student_id)->first();
            $user = User::where('id', $student->user_id)->first();
            $user->delete();
            $student->delete();
            $enroll->delete();
            return redirect()->back()->with(['message' => 'Delete success']);
        }
        return redirect()->back()->with(['error' => 'Enroll not found']);

    }
}
