<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectSession;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class SubjectSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $subjectSessions = SubjectSession::all();
       $subjects = Subject::with('course') 
            ->where('type', 'lms')
            ->where('status', 1)
            ->get();
        return view('lms.sections.subject_session.index', compact('subjects','subjectSessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects =  $subjects = Subject::with('course') 
            ->where('status', 1)
            ->get();
        return view('lms.sections.subject_session.add',compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the start of the request
        Log::info('Attempting to create a new subject session.', ['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id'  => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            // Log validation failures for debugging
            Log::info('Subject session validation failed.', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $session = SubjectSession::create([
                'title'       => $request->title,
                'description' => $request->description,
                'subject_id'  => $request->subject_id,
            ]);

            // Log successful session creation
            Log::info('Subject session created successfully.', ['session_id' => $session->id, 'title' => $session->title]);

            return response()->json([
                'success' => true,
                'message' => 'Subject session created successfully!',
            ]);

        } catch (Exception $e) {
            // Log the exception for detailed error detection
            Log::error('Error creating subject session: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
   public function ajaxList(Request $request)
    {
        $sessions = SubjectSession::with('subject')->latest();

        return DataTables::of($sessions)
            ->addColumn('subject', function ($session) {
                    return $session->subject ? $session->subject->name : 'No data'; // subject name instead of just ID
                })
            ->editColumn('description', function ($session) {
                    return Str::limit($session->description, 50, '...');
                })
            ->addColumn('action', function ($session) {
            $actions = '';

            if (auth()->user()->hasPermission('subject-sessions.update')) {
                $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#editSessionModal' . $session->id . '">
                                <i class="mdi mdi-square-edit-outline"></i>
                             </a>';
            }

            if (auth()->user()->hasPermission('subject-sessions.delete')) {
                $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#deleteSessionModal' . $session->id . '">
                                <i class="mdi mdi-delete"></i>
                             </a>';
            }

            return $actions;
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $session = SubjectSession::findOrFail($id);
        $session->update($validated);

        return redirect()->route('subject-sessions.index')->with('success', 'Subject session updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $session = SubjectSession::findOrFail($id);
        $session->delete();

        return response()->json(['message' => 'Subject session deleted successfully.']);
    }
}
