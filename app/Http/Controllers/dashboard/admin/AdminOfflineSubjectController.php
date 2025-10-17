<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfflineSubject;
use App\Models\OfflineCourse;
class AdminOfflineSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects= OfflineSubject::all();
        $courses= OfflineCourse::all();
        $subject_main= [];
        foreach( $subjects as $subject){
            $course=OfflineCourse::where('id',$subject->course_id )->first();
            $subject_main[] = [
                'id' => $subject->id,
                'course_name' => $course->name,
                'name' => $subject->name,
                'short_desc' => $subject->short_desc,
                'desc' => $subject->desc,
                'status' =>$subject->status,
            ];
        }
        return view('dashboard.subject.offline.index')->with(compact('subject_main','courses'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses=OfflineCourse::all();
        return view('dashboard.subject.offline.add')->with(compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'short_desc' => 'required',
            'desc' => 'required',
            'course_id' => 'required',
            'status' => 'nullable',        
        ]);

        $subject= new OfflineSubject();
        $subject->name = $request->input('name');
        $subject->short_desc = $request->input('short_desc');
        $subject->desc = $request->input('desc');
        $subject->course_id = $request->input('course_id');
        $subject->status =  $request->has('status') ? 1 : 0;
        $success = $subject->save();
        if ($success) {
            $message = 'Subject added successfully';
            return redirect()->route('admin.offline.subjects.index')->with('message', 'Successfully stored');
        } else {
            $message = 'Failed to add Subject. Please try again.';
            return redirect()->back()->withErrors(compact('message'))->withInput($request->input());
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
        $subject = OfflineSubject::findOrFail($id);
        if ($request->ajax()) {
            $subject->status = $request->status;
            $subject->save();
            return response()->noContent();
        }
        
        $updated = $subject->update([
            'name' => $request->input('name')?: $subject->name,
            'short_desc' => $request->input('short_desc')?: $subject->short_desc,
            'desc' => $request->input('desc')?: $subject->desc,
            'course_id' => $request->input('course_id')?: $subject->course_id,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $subject->status,
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
        $success = OfflineSubject::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
