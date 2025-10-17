<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use App\Models\Course;
use App\Models\OfflineCourse;
use App\Models\QuickLink;
use App\Models\SocialMediaLink;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentTestimonial;
use App\Models\User;
use App\Models\WebBanner;
use App\Models\CampusFacility;
use App\Models\Event;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomepageController extends Controller
{
    public function index()
    {
        $data = [
            'courses' => Course::where('type', 'web')
            ->where('status', 1)
            ->with(['subjects' => function ($query) {
                $query->where('type', 'web')->where('status', 1);
            }])
            ->get(),
            'quickLinks' => QuickLink::all(),
            'testimonials' => StudentTestimonial::with('student')->get(),
        ];
        return view('web.index', $data);
    }

    public function showSocialMediaLinks()
    {
        $socialLinks = SocialMediaLink::all(); // Fetch all social media links
        return view('web.layouts.layout', compact('socialLinks'));
    }
    public function footercontact()
    {
        $contact = ContactInfo::first();
        return view('web.layouts.layout', compact('contact'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'course_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $lastStudent = Student::latest('id')->first();
        $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
        $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 12, 4, '0', STR_PAD_LEFT);

        $student_exist = User::where('email', $data['email'])->first();

        if ($student_exist) {
            return response()->json(['message' => 'Email already exists. Please try with a different email.'], 409);
        }
        $student_exist_phone = User::where('phone', $data['phone'])->first();
        if ($student_exist_phone) {
            return response()->json(['message' => 'Phone number already exists. Please try with a different phone.'], 409);
        }else{
            $userId = DB::table('users')->insertGetId([
                'name' => $data['first_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'type' => 'web',
            ]);
    
            $studentId = DB::table('students')->insertGetId([
                'user_id' => $userId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'admission_number' => $newAdmissionNumber,
                'admission_date' => now(),
                'guardian_name' => $data['guardian_name'] ?? null,
                'guardian_contact' => $data['guardian_contact'] ?? null,
                'created_at' => now(),
            ]);
    
            $enroll = new StudentEnrollment();
            $enroll->student_id = $studentId;
            $enroll->course_id = $data['course_id'];
            $enroll->status = $request->input('status', 'enrolled');
    
            if ($enroll->save()) {
                return response()->json(['message' => 'Student enrolled successfully.'], 200);
            } else {
                return response()->json(['message' => 'Failed to enroll student.'], 500);
            }

        }
    
    }

    public function terms() {
        return view('web.terms-and-conditions');
    }

    public function policy() {
        return view('web.privacy-and-policy');
    }
}
