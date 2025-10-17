<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Subject;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $exams = Exam::where('type','Exam')->get();
        $subjects = Subject::where('type', 'lms')->get();
        $batches = Batch::where('status', '1')->get();
        return view('lms.sections.exam.index',compact('exams','subjects','batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $subjects = Subject::where('type', 'lms')->get();
        $batches = Batch::where('status', '1')->get(); 

        return view('lms.sections.exam.add', compact('subjects', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_id'            => 'required|exists:subjects,id',
                'name'                  => 'required|string|min:3',
                'short_description'     => 'nullable|string|max:255',
                'description'           => 'nullable|string',
                'status'                => 'required|in:Scheduled,Ongoing,Completed',
                'batch_id'              => 'required|exists:batches,id',
                'subject_session_id'    => 'nullable|exists:subject_sessions,id',
                'duration'              => 'nullable|integer|min:1',
                'total_marks'           => 'nullable|integer|min:1',
                'minimum_passing_marks' => 'nullable|integer|min:0|lte:total_marks',
            ]);

          DB::transaction(function () use ($validated) {
            $exam = new Exam();
            $exam->subject_id            = $validated['subject_id'];
            $exam->name                  = $validated['name'];
            $exam->short_description     = $validated['short_description'] ?? null;
            $exam->description           = $validated['description'] ?? null;
            $exam->status                = $validated['status'];
            $exam->batch_id              = $validated['batch_id'];
            $exam->subject_session_id    = $validated['subject_session_id'] ?? null;

            $exam->duration              = !empty($validated['duration']) ? $validated['duration'] : null;
            $exam->total_marks           = !empty($validated['total_marks']) ? $validated['total_marks'] : null;
            $exam->minimum_passing_marks = !empty($validated['minimum_passing_marks']) ? $validated['minimum_passing_marks'] : null;

            $exam->type                  = 'Exam';
            $exam->save();
        });


            return response()->json([
                'success' => true,
                'message' => 'Exam created successfully!',
                'redirect' => route('lms.exams.index')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Exam store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
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
    public function ajaxList(Request $request)
    {
        $exams = Exam::with(['subject', 'batch', 'subjectSession'])
            ->where('type', 'Exam')
            ->select('exams.*')->latest();

        return DataTables::of($exams)
            ->editColumn('name', function ($exam) {
                return   $exam->name; 
            })

            ->addColumn('subject', function ($exam) {
                return $exam->subject->name ?? '<span class="small text-danger">Not Available</span>';
            })
            
            ->addColumn('subjectSession', function ($exam) {
                return $exam->subjectSession->title ?? '<span class="small text-danger">Not Available</span>';
            })

            ->editColumn('duration', function ($exam) {
                return   $exam->duration ?? '<span class="small text-danger">Not Available</span>';
            })

            ->editColumn('total_marks', function ($exam) {
                return   $exam->total_marks  ??'<span class="small text-danger">Not Available</span>';
            })
            ->editColumn('status', function ($exam) {
                return '<span class="badge bg-info">' . $exam->status . '</span>';
            })
            ->addColumn('action', function ($exam) {
                $actions = '';
                if (auth()->user()->hasPermission('exams.update')) {
                    $actions .=  '<a href="javascript:void(0);" class="action-icon editVideoBtn" data-bs-toggle="modal" data-bs-target="#edit-exam-modal'. $exam->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }
                if (auth()->user()->hasPermission('exams.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon deleteExamBtn" data-bs-toggle="modal" data-bs-target="#delete-exam-modal' . $exam->id . '"><i class="mdi mdi-delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['status', 'action', 'name', 'subjectSession', 'duration', 'total_marks'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'name' => 'sometimes|required|string|min:3',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Scheduled,Ongoing,Completed',
            'batch_id' => 'sometimes|required|exists:batches,id',
            'subject_session_id' => 'nullable|exists:subject_sessions,id',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'minimum_passing_marks' => 'nullable|integer|min:0|lte:total_marks',
        ]);

        $validated['type'] = 'Exam';
        
        $exam->update($validated);

        return redirect()->route('lms.exams.index')->with('message', 'Exam updated successfully.');
    }

    public function showQuestionsPage(Exam $exam)
    {
        // Eager load the questions and their answers to prevent N+1 query issues
        $exam->load('questions.answers');

        return view('lms.sections.exam.question_add', compact('exam'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:65535',
            'mark' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'answers' => 'required|array|min:2', // Must have at least 2 answers
            'answers.*' => 'required|string|max:255', // Each answer must be a string
            'is_correct' => 'required|integer', // The index of the correct answer
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($request, $exam) {
            // 2. Handle Image Upload if present
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('question_images', 'public');
            }

            $question = $exam->questions()->create([
                'question' => $request->input('question'),
                'mark' => $request->input('mark'),
                'image' => $imagePath,
            ]);

            foreach ($request->input('answers') as $index => $answerText) {
                $question->answers()->create([
                    'answer_text' => $answerText,
                    'is_correct' => ($index == $request->input('is_correct')),
                ]);
            }
        });

        return redirect()->route('lms.exams.questions', $exam->id)
            ->with('success', 'Question added successfully!');
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'mark' => 'required|numeric|min:1',
            'answers' => 'required|array|min:2',
            'is_correct' => 'required',
        ]);

        $question = ExamQuestion::findOrFail($id);
        $question->question = $request->question;
        $question->mark = $request->mark;

       if ($request->hasFile('image')) {
            $question->image = $request->file('image')->store('question_images', 'public');
        }

        $question->save();

        // Delete old answers
        $question->answers()->delete();

        // Add new answers
        foreach ($request->answers as $index => $answerText) {
            $question->answers()->create([
                'answer_text' => $answerText,
                'is_correct' => $request->is_correct == $index
            ]);
        }

        return redirect()->back()->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion($id)
    {
        $question = ExamQuestion::findOrFail($id);
        $question->answers()->delete();
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully.']);
    }
}
