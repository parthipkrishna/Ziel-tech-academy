<?php

namespace App\Http\Controllers\dashboard\admin;
use App\Http\Controllers\Controller;
use App\Models\CampusFacility;
use Illuminate\Http\Request;
use Storage;

class AdminCampusfacilityController extends Controller
{

    public function index()
    {
        $campusFacilities = CampusFacility::all();
        return view('dashboard.campusfacilities.index',compact('campusFacilities'));
    }
    public function create()
    {
        return view('dashboard.campusfacilities.add');
    }
    public function store(Request $request)
    {
    $request->validate([
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'status' => 'sometimes|boolean',
    ]);

    
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads/images/campus_facility/images', 'public');
    }

    
    CampusFacility::create([
        'description' => $request->description,
        'image' => $imagePath,
        'status' => $request->status ?? 0, 
    ]);

    return redirect()->route('admin.campusfacilities.index')->with('success', 'Facility created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        $facility = CampusFacility::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($facility->image && Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('facility_images', 'public');
            $facility->image = $imagePath;
        }

        // Update facility data
        $facility->description = $request->description;
        $facility->status = $request->status ?? 0; // Default to 0 if not provided
        $facility->save();

        return redirect()->route('admin.campusfacilities.index')->with('success', 'Facility updated successfully.');
    }
    public function destroy($id)
    {
        $facility = CampusFacility::findOrFail($id);
        if ($facility->image && Storage::disk('public')->exists($facility->image)) {
            Storage::disk('public')->delete($facility->image);
        }
        $facility->delete();
        return redirect()->route('admin.campusfacilities.index')->with('success', 'Facility deleted successfully.');
    }

}