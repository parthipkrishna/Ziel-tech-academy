<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Placement;

class AdminPlacementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $placements = Placement::all();
        return view('dashboard.placement.index')->with(compact('placements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.placement.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'image' =>'nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
        ]);
        try {
            $thumbnailImagePath = null;
            if ($request->hasFile('image')) {
                $thumbnailImagePath = $request->file('image')->store('uploads/images/placement/images', 'public');
            }
            $placement = new Placement();
            $placement->company_name = $request->input('company_name') ?: NULL;
            $placement->description = $request->input('description') ?: NULL;
            $placement->image = $thumbnailImagePath;
            $placement->opportunities = $request->input('opportunities') ?: NULL;
            $placement->website = $request->input('website') ?: NULL;
            $success = $placement->save();
            if ($success) {
                return redirect()->route('admin.placement.index')->with(['status' => true, 'message' => 'Placement has been added successfully']);
            }
            else {
                return redirect()->back()->with(['error' => 'Failed to save Placement.'])->withInput($request->input());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput($request->input());
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
        $placement = Placement::findOrFail($id);

        $updated = $placement->update([
            'company_name' => $request->input('company_name')?: $placement->company_name,
            'description' => $request->input('description')?: $placement->description,
            'opportunities' => $request->input('opportunities')?: $placement->opportunities,
            'website' => $request->input('website')?: $placement->website,
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
        $success = Placement::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
