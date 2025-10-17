<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\ReferralCode;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\StudentEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentEnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StudentEnrollment::with(['student.user', 'course']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $enrollments = $query->get();
        $statuses = StudentEnrollment::getStatusOptions();
        $enrollment_main = [];

        foreach ($enrollments as $enroll) {
            $student = $enroll->student;
            $user = $student?->user;
            $course = $enroll->course;

            $enrollment_main[] = [
                'id' => $enroll->id,
                'first_name' => $student->first_name ?? null,
                'profile_photo' => $student->profile_photo ?? null,
                'email' => $user->email ?? null,
                'phone' => $user->phone ?? null,
                'course_name' => $course->name ?? null,
                'status' => $enroll->status,
                'created_at' => $enroll->created_at,
            ];
        }

        return view('lms.sections.enrollment.enrollment', compact('enrollment_main', 'statuses'));
    }


    public function ajaxEnrollmentList(Request $request)
    {
        $query = StudentEnrollment::with(['student.user', 'course']);

        // Apply date filter if provided
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        return DataTables::of($query)
            ->addColumn('student', function ($enroll) {
                $photo = $enroll->student?->profile_photo;
                return $photo
                    ? '<img src="' . env('STORAGE_URL') . '/' . $photo . '" class="me-2 rounded-circle" width="40">'
                    : '<span class="small text-danger">No Image</span>';
            })
            ->addColumn('first_name', fn($enroll) => $enroll->student?->first_name)
            ->addColumn('course_name', fn($enroll) => $enroll->course?->name)
            ->addColumn('email', fn($enroll) => $enroll->student?->user?->email)
            ->addColumn('phone', fn($enroll) => $enroll->student?->user?->phone)
            ->addColumn('created_at', fn($enroll) => \Carbon\Carbon::parse($enroll->created_at)->format('Y-m-d'))
            ->addColumn('status', function ($enroll) {
                return match ($enroll->status) {
                    'active' => '<button type="button" class="btn btn-soft-success rounded-pill">Active</button>',
                    'completed' => '<button type="button" class="btn btn-soft-primary rounded-pill">Completed</button>',
                    'cancelled' => '<button type="button" class="btn btn-soft-danger rounded-pill">Cancelled</button>',
                    'enrolled' => '<button type="button" class="btn btn-soft-warning rounded-pill">Enrolled</button>',
                    'free' => '<button type="button" class="btn btn-soft-info rounded-pill">Free</button>',
                    default => '<span class="text-muted">N/A</span>',
                };
            })
            ->addColumn('action', function ($enroll) {
                $id = $enroll->id;
                $action = '';

                if (auth()->user()->hasPermission('enrollments.update')) {
                    $action .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editOnlineEnroll-modal' . $id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('enrollments.delete')) {
                    $action .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $id . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $action;
            })
            ->rawColumns(['student', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('type', 'lms')->get();
        $statuses = StudentEnrollment::getStatusOptions();
        return view('lms.sections.enrollment.add-enrollment')->with(compact('courses','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:active,cancelled,enrolled,completed,free',
            'student_id' => 'required_without:is_new_student|nullable|exists:students,id',
            'is_new_student' => 'boolean',
            'first_name' => 'required_if:is_new_student,true',
            'last_name' => 'required_if:is_new_student,true',
            'phone' => 'required_if:is_new_student,true',
            'email' => 'required_if:is_new_student,true|email',
        ]);

        DB::beginTransaction();

        try {
            $studentId = $request->student_id;

            if ($request->input('is_new_student')) {
                // New Student → create User + Student
                $lastStudent = Student::latest('id')->first();
                $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
                $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 1, 4, '0', STR_PAD_LEFT);

                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'type' => 'lms',
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'admission_number' => $newAdmissionNumber,
                    'admission_date' => now(),
                    'guardian_name' => $request->guardian_name ?? null,
                    'guardian_contact' => $request->guardian_contact ?? null,
                ]);

                $studentId = $student->id;
            }

            $existingEnrollment = StudentEnrollment::where('student_id', $studentId)
                                                   ->where('course_id', $request->course_id)
                                                   ->first();
            if ($existingEnrollment) {
                DB::rollBack();
                // We'll return a more specific message if the enrollment already exists
                return response()->json(['message' => 'Error: The student is already enrolled in this course.'], 409);
            }

            // Enrollment, Payment, and Status Logic
            $course = Course::findOrFail($request->course_id);
            $isFree = $course->course_fee == 0;
            $enrollmentStatus = $request->status ?? 'pending';

            $paymentStatus = 'initiated';
            if ($isFree) {
                $paymentStatus = 'success';
            } elseif ($enrollmentStatus === 'enrolled') {
                $paymentStatus = 'success';
            }

            $enrollment = StudentEnrollment::create([
                'student_id' => $studentId,
                'course_id' => $request->course_id,
                'status' => $enrollmentStatus,
            ]);

            $gateway = $request->payment_method;

            if (!in_array($gateway, ['razorpay', 'manual', 'gpay', 'bank_transfer', 'cash'])) {
                $gateway = 'manual';
            }

            if (!$isFree) {
                Payment::create([
                    'student_id' => $studentId,
                    'course_id' => $course->id,
                    'amount' => $course->course_fee,
                    'currency' => 'INR',
                    'paid_at' => now(),
                    'status' => $paymentStatus,
                    'payment_gateway' => $gateway,
                    'transaction_id' => $request->transaction_id ?: 'trans_' . substr(md5(uniqid('', true)), 0, 14),
                ]);
            }

            // If payment is successful (free course or paid and manually enrolled) → mark enrollment as completed
            if ($paymentStatus === 'success') {
                $enrollment->update(['status' => 'completed']);
                $endDate = null;
                if (!is_null($course->course_end_date)) {
                    $months = floor($course->course_end_date); // integer part
                    $fraction = $course->course_end_date - $months; // decimal part

                    $endDate = Carbon::now()->addMonths($months);

                    // If fractional month exists (e.g., 0.5 → ~15 days)
                    if ($fraction > 0) {
                        $days = round($fraction * 30); // approx days
                        $endDate->addDays($days);
                    }
                }
                // Create subscription record
                Subscription::create([
                    'student_id' => $studentId,
                    'course_id'  => $course->id,
                    'start_date' => now(),
                    'end_date'   => $endDate,
                    'status'     => 'active',
                ]);
            }

            // Find an available batch first
            $batch = Batch::where('course_id', $course->id)
                ->where('is_full', false)
                ->where('status', true)
                ->first();

            if (!$batch) {
                // No available batch → create a new one
                $latestBatch = Batch::where('course_id', $course->id)
                    ->orderByDesc(DB::raw('CAST(SUBSTRING(batch_number, 2) AS UNSIGNED)'))
                    ->first();

                $lastNumber = $latestBatch ? (int) substr($latestBatch->batch_number, 1) : 0;
                $nextNumber = 'B' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

                $batch = Batch::create([
                    'name' => $course->name . ' - ' . $nextNumber,
                    'course_id' => $course->id,
                    'student_limit' => 50,
                    'status' => true,
                    'is_full' => false,
                    'batch_number' => $nextNumber,
                ]);
            }

            BatchStudent::create([
                'batch_id' => $batch->id,
                'student_id' => $studentId,
            ]);

            if ($batch->students()->count() >= $batch->student_limit) {
                $batch->update(['is_full' => true]);
            }

            DB::commit();

            return response()->json(['message' => 'Enrollment created successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function studentSearch(Request $request)
    {
       $query = Student::with('user')
        ->whereDoesntHave('subscriptions', function ($q) {
            $q->where('status', 'active');
        }); 

        if ($request->filled('search_term')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search_term}%")
                ->orWhere('last_name', 'like', "%{$request->search_term}%")
                ->orWhere('admission_number', 'like', "%{$request->search_term}%");
            });
        } elseif ($request->filled('id')) {
            $query->where('id', $request->id);
        } else {
            return response()->json([]);
        }

        $students = $query
            ->select('id', 'first_name', 'last_name', 'admission_number', 'user_id')
            ->limit(10)
            ->get();

        $results = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'text' => $student->first_name . ' ' . $student->last_name . ' - ' . $student->admission_number,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->user?->email,   // from users table
                'phone' => $student->user?->phone,   // from users table
            ];
        });

        return response()->json($results);
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
            return redirect()->route('lms.students.enroll')->with(['message' => 'Successfully updated']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $enroll = StudentEnrollment::find($id);

        if (!$enroll) {
            return response()->json(['message' => 'Enrollment not found.'], 404);
        }

        $student = Student::find($enroll->student_id);

        if ($student) {
            $user = User::find($student->user_id);

            if ($user) {
                // Delete dependent referral codes manually
                ReferralCode::where('generated_by', $user->id)->delete();

                // Now delete the user
                $user->delete();
            }

            $student->delete();
        }

        $enroll->delete();

        return response()->json(['status' => 'success', 'message' => 'User deleted successfully.']);
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
    
        return view('lms.sections.enrollment.enrollment', compact('enrollment_main'));
    }
}
