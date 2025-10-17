<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\CampusFacility;
use App\Models\Event;
use App\Models\OfflineCourse;
use App\Models\OfflineCourseEnrollment;
use App\Models\OfflineCourseType;
use App\Models\QuickLink;
use App\Models\User;
use App\Models\WebBanner;
use DB;
use App\Models\Course;
use App\Models\StudentTestimonial;
use Illuminate\Http\Request;
use Validator;

class CampusController extends Controller
{
    public function index()
    {
        $data = [
            'banner' => WebBanner::where('type', 'campus')->get(),
            'courses' => OfflineCourse::whereHas('offlineSubjects', function ($query) {
                    $query->where('status', 1);
                })
                ->orWhereHas('offlineCourseTypes', function ($query) {
                    $query->where('status', 1);
                })
                ->with([
                    'offlineSubjects' => function ($query) {
                        $query->where('status', 1);
                    },
                    'offlineCourseTypes' => function ($query) {
                        $query->where('status', 1);
                    }
                ])
                ->get(),
            'quickLinks' => QuickLink::all(),
            'campus' => CampusFacility::all(),
            'events' => Event::with('media')->get(),
        ];
        return view('web.offline-courses', $data); 
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'course_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $student_exist = User::where('email', $data['email'])->first();

        if ($student_exist) {
            return response()->json(['message' => 'Email already exists. Please try with a different email.'], 409);
        }
        $student_exist_phone = User::where('phone', $data['phone'])->first();
        if ($student_exist_phone) {
            return response()->json(['message' => 'Phone number already exists. Please try with a different phone.'], 409);
        }
        else{
            $userId = DB::table('users')->insertGetId([
                'name' => $data['first_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'type' => 'web',
            ]);
    
            $studentId = DB::table('offline_students')->insertGetId([
                'user_id' => $userId,
                'first_name' => $data['first_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $offline_course_type = OfflineCourseType::where('offline_course_id', $request->input('course_id'))->first();
    
            $enroll = new OfflineCourseEnrollment();
            $enroll->student_id = $studentId;
            $enroll->offline_course_id = $request->input('course_id');
            $enroll->offline_course_type_id = $offline_course_type->id;
            $enroll->status = $request->input('status') ?: 'enrolled';
    
            if ($enroll->save()) {
                return response()->json(['message' => 'Student offline enrollment added successfully.'], 200);
            } else {
                return response()->json(['message' => 'Failed to register. Please try again.'], 500);
            }
        }
  
    }
}
