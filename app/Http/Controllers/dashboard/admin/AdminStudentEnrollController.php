<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use App\Imports\OnlineStudentsImport;
use Illuminate\Http\Request;
use App\Models\StudentEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminStudentEnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $query = StudentEnrollment::query();

        // Apply date filter if provided
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $enrollments = $query->get();
        $statuses = StudentEnrollment::getStatusOptions();
        $enrollment_main = [];

        foreach ($enrollments as $enroll) {
            $student = Student::where('id', $enroll->student_id)->first();
            $course = Course::where('id', $enroll->course_id)->first();
            $user = User::where('id', $student->user_id)->first();

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
        return view('dashboard.student.online.index', compact('enrollment_main', 'statuses'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('type', 'web')->get();
        $statuses = StudentEnrollment::getStatusOptions();
        return view('dashboard.student.online.add')->with(compact('courses','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
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
            $lastStudent = Student::latest('id')->first();
        $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
        $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 12, 4, '0', STR_PAD_LEFT);

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
        
        $enroll= new StudentEnrollment();
        $enroll->student_id = $studentId;
        $enroll->course_id = $request->input('course_id');
        $enroll->status = $request->input('status')? : 'enrolled';
        $success = $enroll->save();
        if ($success) {
            return response()->json([
                'message' => 'Student Online enroll added successfully',
                'redirect' => route('admin.student.enroll.index'),
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to add. Please try again.'
            ], 500);
        }
        }               
    }
    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    Excel::import(new OnlineStudentsImport, $request->file('file'));

    return redirect()->route('admin.student.enroll.index')->with('message', 'Excel data imported successfully.');
    }
    public function filter(Request $request)
    {
        $query = StudentEnrollment::query(); // Assuming Enrollment is the model for enrollments
    
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
    
            // Filter enrollments within the date range
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }
    
        $enrollments = $query->get();
        $enrollment_main = [];
    
        foreach ($enrollments as $enroll) {
            $student = Student::where('id', $enroll->student_id)->first();
            $course = Course::where('id', $enroll->course_id)->first();
            $user = User::where('id', $student->user_id ?? null)->first();
    
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
    
        return view('dashboard.student.online.index', compact('enrollment_main'));
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
        $student = StudentEnrollment::findOrFail($id);
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
        $enroll = StudentEnrollment::find($id);
        if ($enroll) {
            $student = Student::where('id', $enroll->student_id)->first();
            $user = User::where('id', $student->user_id)->first();
            $user->delete();
            $student->delete();
            $enroll->delete();
            return redirect()->back()->with(['message' => 'Delete success']);
        }
        return redirect()->back()->with(['error' => 'Enroll not found']);

    }   
}
