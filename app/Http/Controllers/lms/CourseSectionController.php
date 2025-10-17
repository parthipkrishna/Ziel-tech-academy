<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseSection;
use Yajra\DataTables\Facades\DataTables;

class CourseSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseSections = CourseSection::all();
        return view('lms.sections.coursesection.section')->with(compact('courseSections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);
            CourseSection::create([
                'name' => $validatedData['name'],
                'status' => $validatedData['status'],
            ]);
    
            return redirect()->route('lms.course.section')->with('success', 'Course section created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Course section creation failed: ' . $e->getMessage());
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
    public function update(Request $request, string $id)
    {
        try {
            $courseSection = CourseSection::findOrFail($id);
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'status' => 'nullable|boolean',
            ]);
            $courseSection->update($validatedData);
            return redirect()->route('lms.course.section')->with('success', 'Course section updated successfully.');
        
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['error' => 'Course section not found.'])->withInput();
        
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $courseSection = CourseSection::findOrFail($id);
            $courseSection->delete();

            return response()->json(['message' => 'Course section deleted successfully.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course section not found.'], 404);

        } catch (Exception $e) {
            return response()->json(['message' => 'Deletion failed: ' . $e->getMessage()], 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $section = CourseSection::findOrFail($id);
        $section->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function ajaxList(Request $request)
    {
        $sections = CourseSection::select(['id', 'name', 'status'])->latest();

        return DataTables::of($sections)
            ->addColumn('status', function ($course) {
                return '
                    <input type="checkbox" id="switch' . $course->id . '" 
                        data-id="' . $course->id . '" 
                        class="status-toggle" 
                        ' . ($course->status == 1 ? 'checked' : '') . ' 
                        data-switch="success"/>
                    <label for="switch' . $course->id . '" 
                        data-on-label="Yes" 
                        data-off-label="No" 
                        class="mb-0 d-block"></label>
                ';
            })
            ->addColumn('action', function ($course) {
                $actions = '';

                if (auth()->user()->hasPermission('course-sections.update')) {
                    $actions .= '<a href="javascript:void(0);" 
                                    class="action-icon" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#bs-editCourse-modal' . $course->id . '">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }

                if (auth()->user()->hasPermission('course-sections.delete')) {
                    $actions .= '<a href="javascript:void(0);" 
                                    class="action-icon" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#delete-alert-modal' . $course->id . '">
                                    <i class="mdi mdi-delete"></i>
                                </a>';
                }

                // Include modals in the response
                $editModal = view('lms.sections.coursesection.inc.edit-coursesection-modal', compact('course'))->render();
                $deleteModal = view('lms.sections.coursesection.inc.delete-coursesection-modal', compact('course'))->render();

                return $actions . $editModal . $deleteModal;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
