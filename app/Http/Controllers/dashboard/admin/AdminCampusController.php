<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campus;
class AdminCampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campuses=Campus::all();
        return view('dashboard.campus.index')->with(compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.campus.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'home_tour' => 'nullable|string',
            'desc' => 'nullable|string',
            'short' => 'nullable|string',
            'home_tour_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $campus = new Campus();
            $campus->status = 1;
            $campus->home_tour = $request->input('home_tour') ?? null;
            $campus->desc = $request->input('desc') ?? null;
            $campus->short = $request->input('short') ?? null;

            // Handle image upload
            if ($request->hasFile('home_tour_image')) {
                $imagePath = $request->file('home_tour_image')->store('campus_images', 'public');
                $campus->home_tour_image = $imagePath;
            }

            if ($campus->save()) {
                return redirect()->route('admin.campuses.index')->with('message', 'Campus added successfully.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong. Please try again.'])
                ->withInput($request->input());
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
    public function update(Request $request, $id)
    {
        $campus = Campus::findOrFail($id);
    
        // Validate input
        $request->validate([
            'home_tour' => 'nullable|string',
            'home_tour_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'desc' => 'nullable|string',
            'short' => 'nullable|string|max:255',
        ]);
    
        // Update fields
        $campus->home_tour = $request->home_tour;
        $campus->desc = $request->desc;
        $campus->short = $request->short;
    
        // Handle file upload
        if ($request->hasFile('home_tour_image')) {
            $imagePath = $request->file('home_tour_image')->store('campus_images', 'public');
            $campus->home_tour_image = $imagePath;
        }
    
        $campus->save();
    
        return redirect()->back()->with('success', 'Campus updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $success = Campus::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
