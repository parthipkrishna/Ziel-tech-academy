<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterSection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminFooterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $footer = FooterSection::first(); // Get the first footer record
        return view('dashboard.footer.index', compact('footer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.footer.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'footer_logo' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
        ]);
        try {
            $thumbnailImagePath = NULL;
            if ($request->hasFile('footer_logo')) {
                $thumbnailImagePath = $request->file('footer_logo')->store('uploads/images/Footer', 'public');
            }

            $footer = new FooterSection();
            $footer->title = $request->input('title') ?: NULL;
            $footer->playstore = $request->input('playstore') ?: NULL;
            $footer->appstore = $request->input('appstore') ?: NULL;
            $footer->copy_right = $request->input('copy_right') ?: NULL;
            $footer->short_desc = $request->input('short_desc') ?: NULL;
            $footer->slug = $request->input('title') ? \Illuminate\Support\Str::slug($request->input('title')) : NULL;
            $footer->footer_logo = $thumbnailImagePath;
            $success = $footer->save();
            if ($success) {
                $message ='FooterSection added successfully ';
                return redirect()->back()->with(compact('message'));
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save FooterSection.'])->withInput($request->input());
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
        $footer = FooterSection::findOrFail($id);
        $existing_image = base_path($footer->footer_logo);
        if($request->file('footer_logo')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
           $imagePath = $request->file('footer_logo')->store('uploads/images/Footer', 'public');
        }

        $updated = $footer->update([
            'title' => $request->input('title')?: $footer->title,
            'playstore' => $request->input('playstore')?: $footer->playstore,
            'appstore' => $request->input('appstore')?: $footer->appstore,
            'copy_right' => $request->input('copy_right')?: $footer->copy_right,
            'short_desc' => $request->input('short_desc')?: $footer->short_desc,
            'slug' => $request->input('title') ? \Illuminate\Support\Str::slug($request->input('title')) : $footer->slug,
            'footer_logo' => $request->file('footer_logo')?$imagePath:$footer->footer_logo,
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
        $success = FooterSection::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
