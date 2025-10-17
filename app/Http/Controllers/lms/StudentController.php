<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\UserVerification;
use App\Models\UserRole;
use App\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('lms.sections.student.students')->with(compact('students'));
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.student.add-student');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20|unique:users',
            'gender' => 'nullable|string|max:10|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'admission_date' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            // Log the validation errors for debugging purposes
            Log::info('Student registration validation failed.', ['errors' => $validator->errors()->toArray()]);

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // admission number
            $lastStudent = Student::latest('id')->first();
            $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
            $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 12, 4, '0', STR_PAD_LEFT);

            // user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone ?: null,
                'type' =>'lms',
                'password' => bcrypt($request->password),
            ]);
            
            // profile photo
            $profilePath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePath = $request->file('profile_photo')->store('uploads/images/Students', 'public');
            }

            // student
            Student::create([
                'user_id'           => $user->id,
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name ?: null,
                'admission_number'  => $newAdmissionNumber,
                'date_of_birth'     => $request->date_of_birth ?: null,
                'gender'            => $request->gender ?: null,
                'address'           => $request->address ?: null,
                'city'              => $request->city ?: null,
                'state'             => $request->state ?: null,
                'country'           => $request->country ?? 'India',
                'zip_code'          => $request->zip_code ?: null,
                'profile_photo'     => $profilePath ?: null,
                'admission_date'    => now(),
                'guardian_name'     => $request->guardian_name ?: null,
                'guardian_contact'  => $request->guardian_contact ?: null,
                'status'            => true,
            ]);

            // verification
            UserVerification::create([
                'user_id'           => $user->id,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'is_email_verified' => false,
                'is_phone_verified' => false,
            ]);

            // role
            $role = Role::whereRaw('UPPER(role_name) = ?', ['STUDENT'])->first();
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id ?? 5,
            ]);

            DB::commit();

            // Log the successful registration
            Log::info('Student registered successfully.', ['user_id' => $user->id, 'admission_number' => $newAdmissionNumber]);

            return response()->json([
                'message' => 'Student registered successfully.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the exception for detailed error detection
            Log::error('Error during student registration: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);

            return response()->json([
                'message' => 'Registration failed: ' . $e->getMessage()
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
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = User::where('id',$student->user_id)->first();
    
        // Update user fields
        $firstName = $request->first_name ?? $student->first_name;
        $lastName = $request->last_name ?? $student->last_name;
        $user->name = $firstName . ' ' . $lastName;
        $user->save();
    
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('uploads/images/Students', 'public');
            $student->profile_photo = $path;
        }
    
        // Update student fields
        $student->first_name = $request->filled('first_name') ? $request->first_name : $student->first_name;
        $student->last_name = $request->filled('last_name') ? $request->last_name : $student->last_name;
        $student->date_of_birth = $request->filled('date_of_birth') ? $request->date_of_birth : $student->date_of_birth;
        $student->gender = $request->filled('gender') ? $request->gender : $student->gender;
        $student->address = $request->filled('address') ? $request->address : $student->address;
        $student->city = $request->filled('city') ? $request->city : $student->city;
        $student->state = $request->filled('state') ? $request->state : $student->state;
        $student->country = $request->filled('country') ? $request->country : $student->country;
        $student->zip_code = $request->filled('zip_code') ? $request->zip_code : $student->zip_code;
        $student->admission_date = $request->filled('admission_date') ? $request->admission_date : $student->admission_date;
        $student->guardian_name = $request->filled('guardian_name') ? $request->guardian_name : $student->guardian_name;
        $student->guardian_contact = $request->filled('guardian_contact') ? $request->guardian_contact : $student->guardian_contact;
        $student->status = $request->has('status') ? $request->input('status') : $student->status;


    
        $student->save();
    
         return redirect()->back()->with('success', 'Student updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $student = Student::findOrFail($id); 
    $student->delete(); 

    return response()->json(['message' => 'User deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $atudent = Student::findOrFail($id);
        $atudent->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function ajaxStudentList(Request $request)
    {
        $students = Student::with('user')->latest();

        return DataTables::of($students)
            ->addColumn('profile', function ($student) {
                if ($student->profile_photo) {
                    $img = asset('storage/' . $student->profile_photo);
                    return "<img src='{$img}' class='me-2 rounded-circle' width='40'>";
                }
                return "<span class='text-danger small'>No Image</span>";
            })
            ->addColumn('email', function ($student) {
                return $student->user ? $student->user->email : '<span class="text-danger small">No Email</span>';
            })
            ->filterColumn('email', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('email', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('status', function ($student) {
                $checked = $student->status ? 'checked' : '';
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" id="switch-status' . $student->id . '" value="1" ' . $checked . ' data-id="' . $student->id . '" data-switch="success" />
                        <label for="switch-status' . $student->id . '" data-on-label="Yes" data-off-label="No"></label>
                    </div>';
            })
            ->addColumn('action', function ($student) {
                $editModal = view('lms.sections.student.inc.edit-student-modal', compact('student'))->render();
                $deleteModal = view('lms.sections.student.inc.delete-student-modal', compact('student'))->render();

                $editBtn = auth()->user()->hasPermission('students.update')
                    ? '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editStudent-modal' . $student->id . '"><i class="mdi mdi-square-edit-outline"></i></a>': '';

                $deleteBtn = auth()->user()->hasPermission('students.delete')
                    ? '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $student->id . '"><i class="mdi mdi-delete"></i></a>': '';

                return $editBtn . $deleteBtn . $editModal . $deleteModal;
            })
            ->rawColumns(['profile', 'status', 'action'])
            ->make(true);
    }
}
