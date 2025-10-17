<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\TopAchiever;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TopAchieverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {      
        $top_achievers = TopAchiever::with('student')->get();
        return view('lms.sections.topachiever.topachiever')->with(compact('top_achievers'));
    }

    public function studentSearch(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search_term')) {
            $query->where('first_name', 'like', "%{$request->search_term}%")
                ->orWhere('last_name', 'like', "%{$request->search_term}%")
                ->orWhere('admission_number', 'like', "%{$request->search_term}%");
        } elseif ($request->filled('id')) {
            $query->where('id', $request->id);
        } else {
            return response()->json([]);
        }

        $students = $query->select('id', 'first_name', 'last_name', 'admission_number')->limit(10)->get();

        $results = $students->map(function ($student) {
            
            $subscriptions = Subscription::where('student_id', $student->id)
                ->with('course')
                ->get();
            
            $courses = [];
            foreach ($subscriptions as $subscription) {
                if ($subscription->course) {
                    $courses[$subscription->course_id] = $subscription->course->name;
                }
            }

            return [
                'id' => $student->id,
                'text' => $student->first_name . ' ' . $student->last_name . ' - ' . $student->admission_number,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'courses' => $courses
            ];
        });

        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        return view('lms.sections.topachiever.add');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        Log::info('Top Achiever store request received', [
            'data' => $request->except(['_token'])
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'name'       => 'required|string|max:255',
                'status'     => 'required|boolean',
                'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'course_id'  => 'required|exists:courses,id',
            ]);

            if ($validator->fails()) {
                Log::warning('Top Achiever validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            if (!$request->hasFile('image')) {
                Log::error('Image missing or too large for PHP limits');
                return response()->json([
                    'success' => false,
                    'errors'  => ['image' => ['File too large. Please upload under 2MB.']],
                ], 422);
            }

            $imagePath = $request->file('image')->store('uploads/images/top_achievers', 'public');
            Log::info('Top Achiever image uploaded successfully', ['path' => $imagePath]);

            $topAchiever = TopAchiever::create([
                'student_id' => $request->input('student_id'),
                'name'       => $request->input('name'),
                'status'     => $request->boolean('status'),
                'image'      => $imagePath,
                'course_id'  => $request->input('course_id'),
            ]);

            Log::info('Top Achiever created successfully', [
                'id'   => $topAchiever->id,
                'name' => $topAchiever->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Top Achiever added successfully!',
                'id'      => $topAchiever->id
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while creating Top Achiever', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            Log::error('Unexpected error while creating Top Achiever', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        Log::info('Top Achiever update request received', [
            'id'   => $id,
            'data' => $request->except(['_token'])
        ]);

        $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'name'       => 'nullable|string|max:255',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'status'     => 'nullable|boolean',
            'course_id'  => 'required|exists:courses,id',
        ]);

        try {
            $achiever = TopAchiever::findOrFail($id);

            $achiever->student_id = $request->filled('student_id') ? $request->student_id : $achiever->student_id;
            $achiever->name       = $request->filled('name') ? $request->name : $achiever->name;
            $achiever->status     = $request->has('status') ? $request->boolean('status') : $achiever->status;
            $achiever->course_id  = $request->course_id;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/images/top_achievers', 'public');
                $achiever->image = $imagePath;

                Log::info('Top Achiever image uploaded', [
                    'id'   => $achiever->id,
                    'path' => $imagePath
                ]);
            } else {
                Log::info('No new image uploaded, keeping existing image', [
                    'id' => $achiever->id
                ]);
            }

            $achiever->save();

            Log::info('Top Achiever updated successfully', [
                'id' => $achiever->id
            ]);

            return redirect()->back()->with('success', 'Top Achiever updated successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating Top Achiever', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Database error while updating.');

        } catch (\Exception $e) {
            Log::error('Unexpected error while updating Top Achiever', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Something went wrong while updating.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $top_achiever = TopAchiever::find($id);
            if (!$top_achiever) {
                return redirect()->back()->with('error', 'TopAchiever not found.');
            }
            $top_achiever->delete();
            return response()->json(['success' => 'Top Achiever deleted successfully.'], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Database query error: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $achiever = TopAchiever::findOrFail($id);
        $achiever->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function ajaxList()
    {
        $achievers = TopAchiever::with(['student', 'course'])->latest();


        return DataTables::of($achievers)
            ->addColumn('image', function ($achiever) {
                return $achiever->image
                    ? '<img src="' . env('STORAGE_URL') . '/' . $achiever->image . '" class="me-2 rounded-circle" width="40">'
                    : '<span class="small text-danger">No Image</span>';
            })
            ->addColumn('status', function ($achiever) {
                return '
                    <input type="checkbox" id="switch' . $achiever->id . '" data-id="' . $achiever->id . '" class="status-toggle" ' . ($achiever->status ? 'checked' : '') . ' data-switch="success"/>
                    <label for="switch' . $achiever->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                ';
            })
            ->addColumn('course', function ($achiever) {
                return $achiever->course ? $achiever->course->name : '<span class="text-danger">No Course</span>';
            })

            ->addColumn('action', function ($achiever) {
                $actions = '';

                if (auth()->user()->hasPermission('top-achievers.update')) {
                    $actions .= '
                        <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-TopAchiever-modal' . $achiever->id . '">
                            <i class="mdi mdi-square-edit-outline"></i>
                        </a>';
                }

                if (auth()->user()->hasPermission('top-achievers.delete')) {
                    $actions .= '
                        <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $achiever->id . '">
                            <i class="mdi mdi-delete"></i>
                        </a>';
                }

                return $actions;
            })
            ->rawColumns(['image', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

}
