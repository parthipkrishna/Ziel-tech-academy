<?php

namespace App\Http\Controllers\dashboard\admin;
use App\Http\Controllers\Controller;
use App\Models\EventMedia;
use Illuminate\Http\Request;
use Storage;

class AdminEventMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'type' => 'required|in:image,video,youtube',
        'video' => 'file|mimes:mp4,mov,avi,wmv|max:10240|nullable',
        'image' => 'file|mimes:jpeg,png,jpg,gif,webp|max:5120|nullable',
        'youtube' => 'nullable',
    ]);

    $mediaUrl = null;

    if ($request->type === 'image' && $request->hasFile('image')) {
        $mediaUrl = $request->file('image')->store('event_media/images', 'public');
    } elseif ($request->type === 'video' && $request->hasFile('video')) {
        $mediaUrl = $request->file('video')->store('event_media/videos', 'public');
    } elseif ($request->type === 'youtube') {
        $mediaUrl = $request->youtube;
    }    

    EventMedia::create([
        'event_id' => $request->event_id,
        'type' => $request->type,
        'media_url' => $mediaUrl,
    ]);

    return redirect()->route('admin.events.index')->with(['status' => true, 'message' => 'Event has been added successfully']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $media = EventMedia::findOrFail($id);
    
    if ($media->type === 'image' || $media->type === 'video') {
        Storage::disk('public')->delete($media->media_url);
    }
    
    $media->delete();
    
    return redirect()->back()->with('success', 'Media deleted successfully.');
    }
}
