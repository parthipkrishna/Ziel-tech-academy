<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventMedia;
class AdminEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events=Event::all();
        return view('dashboard.event.index')->with(compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.event.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
        ]);
        try {

            $event = new Event();
            $event->name = $request->input('name');
            $event->date = $request->input('date');
            $event->location = $request->input('location') ?: NULL;
            $event->description = $request->input('description') ?: NULL;
            $success = $event->save();
            if ($success) {
                return redirect()->route('admin.events.index')->with(['status' => true, 'message' => 'Event has been added successfully']);
            }
            else {
                return redirect()->back()->with(['status' => false, 'message' => 'Failed to add Event: ' . $e->getMessage()])->withInput($request->input());;

            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Failed to add Event: ' . $e->getMessage()])->withInput($request->input());;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $event_media = EventMedia::where('event_id',$id)->get();
        return view('dashboard.event.view')->with(compact('event','event_media'));
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
        $event = Event::findOrFail($id);

        $updated = $event->update([
            'name' => $request->input('name')?: $event->name,
            'description' => $request->input('description')?: $event->description,
            'date' => $request->input('date')?: $event->date,
            'location' => $request->input('location')?: $event->location,
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
        $success = Event::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
