<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Campus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches=Branch::all();
        $campuses=Campus::all();
        $branch_main= [];
        foreach( $branches as $branch){
            $campus=Campus::where('id',$branch->campus_id )->first();
            $branch_main[] = [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'contact_number' => $branch->contact_number,
                'image' => $branch->image,
                'google_map_link' => $branch->google_map_link,
                'status' =>$branch->status,
            ];
        }
        return view('dashboard.branch.index')->with(compact('branch_main','campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campuses=Campus::all();
        return view('dashboard.branch.add')->with(compact('campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {     
        //dump( $request->all());
        $request->validate([
            'name' => 'required|min:3',
            'address' => 'required|min:5',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
        ]);
        try {
            $thumbnailImagePath = NULL;
            if ($request->hasFile('image')) {
                $thumbnailImagePath = $request->file('image')->store('uploads/images/Branch', 'public');
            }
            $branch = new Branch();
            $branch->name = $request->input('name');
            $branch->address = $request->input('address');
            $branch->contact_number = $request->input('contact_number') ?: NULL;
            $branch->image = $thumbnailImagePath;
            $branch->google_map_link = $request->input('google_map_link') ?: NULL;
            $branch->status = $request->has('status') ? 1 : 0;
            $branch->campus_id = $request->input('campus_id')?: NULL;
            $success = $branch->save();
            if ($success) {
                $message ='Campus added successfully ';
                return redirect()->route('admin.branches.index')->with('message', 'Successfully stored');
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save branch.'])->withInput($request->input());
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
        $branch = Branch::findOrFail($id);
        $existing_image = base_path($branch->image);
        if($request->file('image')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
           $thumbnailImagePath = $request->file('image')->store('uploads/images/Branch', 'public');
        }
        $updated = $branch->update([
            'name' => $request->input('name')?: $branch->name,
            'address' => $request->input('address')?: $branch->address,
            'contact_number' => $request->input('contact_number')?: $branch->contact_number,
            'campus_id' => $request->filled('campus_id') ? (int) $request->input('campus_id') : null,
            'status' =>  $status = $request->has('status') ? $request->input('status') : $branch->status,
            'image' => $request->file('image')?$thumbnailImagePath:$branch->image,
            'google_map_link' => $request->input('google_map_link')?: $branch->google_map_link,

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
        $success = Branch::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
