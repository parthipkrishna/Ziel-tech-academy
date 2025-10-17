<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuickLink;
use Storage;

class AdminQuicklinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quicklinks = QuickLink::all();
        return view('dashboard.quicklink.index')->with(compact('quicklinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.quicklink.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'nullable',
            'order' => 'nullable|integer',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);
        try {
            $link = new QuickLink();
            $link->title = $request->input('title');
            $link->url = $request->input('url');
            $link->order = $request->input('order') ?? 0;
            $link->type = $request->input('type');
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filePath = $file->store('attachments', 'public'); 
                $link->attachment = $filePath;
            }
            $success = $link->save();
            if ($success) {
                $message ='QuickLink added successfully ';
                return redirect()->route('admin.quicklinks.index')->with('message', 'Successfully updated');
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save QuickLink.'])->withInput($request->input());
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput($request->input());
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
        $info = QuickLink::findOrFail($id);
        $updated = $info->update([
            'title' => $request->input('title')?: $info->title,
            'url' => $request->input('url')?: $info->url,
            'order' => $request->input('order')?: $info->order,
            'type' => $request->input('type') ?: $info->type,
            'attachment' => $info->attachment,
        ]);
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($info->attachment) {
                Storage::delete('public/' . $info->attachment);
            }
    
            // Upload new file
            $file = $request->file('attachment');
            $filePath = $file->store('attachments', 'public');
    
            // Update the attachment field
            $info->update(['attachment' => $filePath]);
        }
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = QuickLink::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
