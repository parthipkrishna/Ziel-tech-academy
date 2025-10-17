<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\SubjectSession;
use Illuminate\Http\Request;
use App\Models\LiveClass;
use App\Models\User;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Exception; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class LiveClassController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $subjects = Subject::with('course')
            ->where('type', 'lms')
            ->where('status', 1) // Only active subjects
            ->get();
        $user = auth()->user();
        $batches = collect();
        $tutors = collect();

        // If the user is a Tutor
        if ($user->hasRole('Tutor')) {
            $tutorRelation = $user->tutorBatches;
            $batches = $tutorRelation ? $tutorRelation->tutorBatchesRelation : collect();
            $tutors = Tutor::where('user_id', $user->id)->with('user')->get();
        } 

        // If the user is Admin or Super Admin
        elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            $batches = Batch::all();
            $tutors = Tutor::whereHas('user', function ($query) {
                $query->where('type', 'lms')
                    ->whereHas('roles', function ($q) {
                        $q->where('role_name', 'Tutor');
                    });
            })->with('user')->get();
        }

        return view('lms.sections.liveclasses.liveclass', compact('subjects', 'batches', 'tutors'));
    }

    /**
     * Get a listing of the resource.
    */
    public function getLiveClasses(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Non-AJAX request'], 400);
        }

        $liveClasses = LiveClass::with(['subject', 'tutor.user', 'batch'])->select('live_classes.*')->latest();

        return DataTables::of($liveClasses)

        ->editColumn('start_time', function($row) {
            return $row->start_time ? Carbon::parse($row->start_time)->format('M d, Y h:i A') : '-';
        })

        ->editColumn('end_time', function($row) {
            return $row->end_time  ? Carbon::parse($row->end_time)->format('M d, Y h:i A') : '-';
        })

        ->addColumn('actions', function ($row) {
            $actions = '';

            if (auth()->user()->hasPermission('live-classes.update')) {
                $actions .= '
                    <a href="javascript:void(0);" class="action-icon editLiveClass"
                        data-id="' . $row->id . '"
                        data-name="' . e($row->name) . '"
                        data-meeting_link="' . e($row->meeting_link) . '"
                        data-start_time="' . Carbon::parse($row->start_time)->format('M d, Y h:i A') . '"
                        data-end_time="' . Carbon::parse($row->end_time)->format('M d, Y h:i A') . '"
                        data-subject_id="' . $row->subject_id . '"
                        data-tutor_id="' . $row->tutor_id . '"
                        data-batch_id="' . $row->batch_id . '"
                        data-short_summary="' . e($row->short_summary) . '"
                        data-summary="' . e($row->summary) . '"
                        data-thumbnail_image="' . $row->thumbnail_image . '"
                        data-subject_session_id="'.$row->subject_session_id.'"
                        data-status="'.$row->status.'">
                        <i class="mdi mdi-square-edit-outline"></i>
                    </a>
                ';
            }

            if (auth()->user()->hasPermission('live-classes.delete')) {
                $actions .= '
                    <a href="javascript:void(0);" class="action-icon deleteLiveClass ms-2"
                        data-id="' . $row->id . '"
                        data-name="' . e($row->name) . '"
                        title="Delete">
                        <i class="mdi mdi-delete"></i>
                    </a>
                ';
            }

            return $actions;
        })
        ->rawColumns(['actions'])
        ->make(true);
    }


    /**
     * Show the form for creating a new resource.
    */
    public function create()
    {
        $subjects = Subject::with('course')
            ->where('type', 'lms')
            ->where('status', 1) // Only active subjects
            ->get();
        $user = auth()->user();
        $batches = null;
        $tutors = null;

        if ($user->hasRole('Tutor')) {
            $tutorRelation = $user->tutorBatches;
            $batches = $tutorRelation ? $tutorRelation->tutorBatchesRelation : collect();

        } elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            $batches = Batch::all();

            $tutors = Tutor::whereHas('user', function ($query) {
                $query->where('type', 'lms')
                    ->whereHas('roles', function ($q) {
                        $q->where('role_name', 'Tutor');
                    });
            })->with('user')->get();
        }
        return view('lms.sections.liveclasses.add-liveclass', compact('batches', 'tutors','subjects'));
    }

    public function getSessions(Subject $subject)
    {
        $sessions = $subject->sessions; // Assuming Subject has a 'sessions' relationship
        return response()->json($sessions);
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        try {
            Log::info('LiveClass store request started', [
                'subject_id' => $request->subject_id,
                'tutor_id' => $request->tutor_id,
                'batch_id' => $request->batch_id,
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status,
            ]);

            $validator = Validator::make($request->all(), [
                'subject_id' => 'required|exists:subjects,id',
                'tutor_id' => 'required|exists:tutors,id',
                'batch_id' => 'required|exists:batches,id',
                'name' => 'required|string|max:255',
                'meeting_link' => 'required|url',
                'start_time' => 'required|date|after_or_equal:now',
                'end_time' => 'required|date|after:start_time',
                'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'short_summary' => 'nullable|string|max:500',
                'summary' => 'nullable|string',
                'subject_session_id' => 'required|exists:subject_sessions,id',
                'status' => 'required|in:Pending,Ongoing,Completed',
            ]);

            if ($validator->fails()) {
                Log::warning('LiveClass validation failed', [
                    'errors' => $validator->errors()->toArray(),
                ]);

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $thumbnailImagePath = null;
            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImagePath = $request->file('thumbnail_image')
                    ->store('uploads/images/liveclasses', 'public');

                Log::info('LiveClass thumbnail uploaded', [
                    'path' => $thumbnailImagePath,
                ]);
            }

            $liveClass = LiveClass::create([
                'subject_id' => $request->subject_id,
                'tutor_id' => $request->tutor_id,
                'batch_id' => $request->batch_id,
                'name' => $request->name,
                'meeting_link' => $request->meeting_link,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'thumbnail_image' => $thumbnailImagePath,
                'short_summary' => $request->short_summary,
                'summary' => $request->summary,
                'subject_session_id' => $request->subject_session_id,
                'status' => $request->status,
            ]);

            Log::info('LiveClass created successfully', [
                'live_class_id' => $liveClass->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Live class created successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Error while storing LiveClass', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the live class.',
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        Log::info('Attempting to update live class.', ['live_class_id' => $id, 'request_data' => $request->except(['_token', '_method'])]);

        $liveClass = LiveClass::find($id);

        if (!$liveClass) {

            Log::warning('Live class not found for update.', ['live_class_id' => $id]);
            return redirect()->back()->withErrors('Live class not found.');
        }

        try {
            $validated = $request->validate([
                'name'               => 'sometimes|required|string|max:255',
                'meeting_link'       => 'sometimes|required|url',
                'start_time'         => 'sometimes|required|date',
                'end_time'           => 'sometimes|required|date|after:start_time',
                'subject_id'         => 'sometimes|required|exists:subjects,id',
                'tutor_id'           => 'sometimes|nullable|exists:tutors,id',
                'batch_id'           => 'sometimes|required|exists:batches,id',
                'short_summary'      => 'sometimes|nullable|string|max:500',
                'summary'            => 'sometimes|nullable|string',
                'thumbnail_image'    => 'sometimes|nullable|image|max:2048',
                'subject_session_id' => 'sometimes|required|exists:subject_sessions,id',
                'status'             => 'sometimes|required|in:Pending,Ongoing,Completed',
            ]);
            
            Log::info('Live class validation passed.', ['live_class_id' => $id]);

            $existing_image = base_path($liveClass->thumbnail_image);
            $thumbnailImagePath = $liveClass->thumbnail_image;
            if ($request->file('thumbnail_image')) {
                if (File::exists($existing_image)) {
                    File::delete($existing_image);
                }
                $thumbnailImagePath = $request->file('thumbnail_image')->store('uploads/images/liveclasses', 'public');
            }

            $liveClass->name             = $request->has('name') ? $request->name : $liveClass->name;
            $liveClass->meeting_link     = $request->has('meeting_link') ? $request->meeting_link : $liveClass->meeting_link;
            $liveClass->start_time       = $request->has('start_time') ? Carbon::parse($request->start_time) : $liveClass->start_time;
            $liveClass->end_time         = $request->has('end_time') ? Carbon::parse($request->end_time) : $liveClass->end_time;
            $liveClass->subject_id       = $request->has('subject_id') ? $request->subject_id : $liveClass->subject_id;
            $liveClass->tutor_id         = $request->has('tutor_id') ? $request->tutor_id : $liveClass->tutor_id;
            $liveClass->batch_id         = $request->has('batch_id') ? $request->batch_id : $liveClass->batch_id;
            $liveClass->short_summary    = $request->has('short_summary') ? $request->short_summary : $liveClass->short_summary;
            $liveClass->summary          = $request->has('summary') ? $request->summary : $liveClass->summary;
            $liveClass->thumbnail_image  = $thumbnailImagePath;
            $liveClass->subject_session_id = $request->has('subject_session_id') ? $request->subject_session_id : $liveClass->subject_session_id;
            $liveClass->status           = $request->has('status') ? $request->status : $liveClass->status;

            $liveClass->save();
            
            Log::info('Live class updated successfully.', ['live_class_id' => $liveClass->id, 'name' => $liveClass->name]);
            
            return redirect()->back()->with('success', 'Live class updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating live class: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            
            return redirect()->back()->withErrors('An error occurred while updating the live class.');
        }
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        $success = LiveClass::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
