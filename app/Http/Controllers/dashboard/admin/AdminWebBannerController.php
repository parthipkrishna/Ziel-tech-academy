<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebBanner;
use Illuminate\Support\Facades\File;

class AdminWebBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = WebBanner::getTypes();
        $banners=WebBanner::all();
        return view('dashboard.webbanner.index')->with(compact('types','banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = WebBanner::getTypes();
        return view('dashboard.webbanner.add')->with(compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'image_url' => 'required|mimes:jpg,jpeg,png,svg,gif,webp|max:2048',
        ]);
        try {
            $webImagePath = NULL;
            if ($request->hasFile('image_url')) {
                $webImagePath = $request->file('image_url')->store('uploads/images/WebBanner', 'public');
            }
            $link = new WebBanner();
            $link->title = $request->input('title');
            $link->type = $request->input('type');
            $link->image_url = $webImagePath;
            $link->short_desc = $request->input('short_desc') ?? NULL;
            $link->description = $request->input('description') ?? NULL;
            $success = $link->save();
            if ($success) {
                $message ='WebBanner link added successfully ';
                return redirect()->route('admin.web.banners.index')->with('message', 'Successfully updated');
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save WebBanner.'])->withInput($request->input());
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput($request->input());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = WebBanner::findOrFail($id);

        $existing_image = base_path($banner->image_url);
        if($request->file('image_url')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
           $imagePath = $request->file('image_url')->store('uploads/images/WebBanner', 'public');
        }
        
        $updated = $banner->update([
            'title' => $request->input('title')?: $banner->title,
            'type' => $request->input('type')?: $banner->type,
            'short_desc' => $request->input('short_desc')?: $banner->short_desc,
            'description' => $request->input('description')?: $banner->description,
            'image_url' => $request->file('image_url')?$imagePath:$banner->image_url,
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
        $success = WebBanner::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
