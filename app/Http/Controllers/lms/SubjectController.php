<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = getLmsSubjects();
        $courses= getLmsCourses();
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
        return view('lms.sections.subjects.subjects')->with(compact('subject_main','courses'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('type', 'lms')->get();
        return view('lms.sections.subjects.add-subject')->with(compact('courses'));    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the start of the request
        Log::info('Attempting to create a new subject.', ['request' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'short_desc'        => 'required|string',
            'desc'              => 'required|string',
            'course_id'         => 'required|exists:courses,id',
            'total_hours'       => 'required|integer',
            'mobile_thumbnail'  => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'web_thumbnail'     => 'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
            'status'            => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            // Log validation failures for debugging
            Log::info('Subject creation validation failed.', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $webImage = null;
            $mobileImage = null;

            if ($request->hasFile('web_thumbnail')) {
                $webImage = $request->file('web_thumbnail')
                    ->store('uploads/images/Subjects/lms/Web', 'public');
            }

            if ($request->hasFile('mobile_thumbnail')) {
                $mobileImage = $request->file('mobile_thumbnail')
                    ->store('uploads/images/Subjects/lms/Mobile', 'public');
            }

            $subject = new Subject();
            $subject->name              = $request->input('name');
            $subject->short_desc        = $request->input('short_desc')?: null;
            $subject->desc              = $request->input('desc')?: null;
            $subject->course_id         = $request->input('course_id')?: null;
            $subject->total_hours       = $request->input('total_hours')?: null;
            $subject->web_thumbnail     = $webImage;
            $subject->mobile_thumbnail  = $mobileImage;
            $subject->status            = $request->has('status') ? 1 : 0;
            $subject->type              = 'lms';
            $subject->save();
            
            // Log successful subject creation
            Log::info('Subject created successfully.', ['subject_id' => $subject->id, 'subject_name' => $subject->name]);

            return response()->json([
                'success' => true,
                'message' => 'Subject added successfully!',
            ]);

        } catch (Exception $e) {
            // Log the exception for detailed error detection
            Log::error('Error adding new subject: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
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
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
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
           $webImage = $request->file('web_thumbnail')->store('uploads/images/Subjects/lms/Web', 'public');
        }

        $existing_image_mobile = base_path($subject->mobile_thumbnail);
        if($request->file('mobile_thumbnail')){
            if(File::exists($existing_image_mobile)){
                File::delete($existing_image_mobile);
            }
           $mobileImage = $request->file('mobile_thumbnail')->store('uploads/images/Subjects/lms/Mobile', 'public');
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
            'type' => 'lms',
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
            return response()->json(['message' => 'User deleted successfully.']);
        }
    }
    public function ajaxSubjectList()
    {
        $subjects = Subject::with('course') // Assuming Subject has a `course()` relationship
            ->select('id', 'name', 'web_thumbnail', 'course_id', 'total_hours', 'status','desc','short_desc','mobile_thumbnail')
            ->get()
            ->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'web_thumbnail' => $subject->web_thumbnail,
                'mobile_thumbnail' => $subject->mobile_thumbnail,
                'course_name' => optional($subject->course)->name,
                'course_id' => $subject->course_id, // for dropdown pre-selection
                'total_hours' => $subject->total_hours,
                'short_desc' => $subject->short_desc,
                'desc' => $subject->desc,
                'status' => $subject->status,
            ];
        });


        return DataTables::of($subjects)
            ->addColumn('subject', function ($subject) {
                return $subject['web_thumbnail']
                    ? '<img src="' . env('STORAGE_URL') . '/' . $subject['web_thumbnail'] . '" class="me-2" 
                                width="60 height="40" 
                                style="object-fit: cover;">'
                    : '<span class="small text-danger">No Image</span>';
            })
            ->addColumn('total_hours', fn($subject) => $subject['total_hours'] . ' Hours')
            ->addColumn('status', function ($subject) {
                $checked = $subject['status'] ? 'checked' : '';
                return '
                    <div>
                        <input type="checkbox" id="switch' . $subject['id'] . '" data-id="' . $subject['id'] . '" class="status-toggle" ' . $checked . ' data-switch="success"/>
                        <label for="switch' . $subject['id'] . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                    </div>';
            })
            ->addColumn('action', function ($subject) {
                $editModal = view('lms.sections.subjects.inc.edit-subject-modal', ['subject' => $subject, 'courses' => Course::select('id', 'name')->get()])->render();
                $deleteModal = view('lms.sections.subjects.inc.delete-subject-modal', ['subject' => $subject])->render();

                $actions = '';
                if (auth()->user()->hasPermission('subjects.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editsubject-modal' . $subject['id'] . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('subjects.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $subject['id'] . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $actions . $editModal . $deleteModal;
            })
            ->rawColumns(['subject', 'status', 'action'])
            ->make(true);
    }
}
