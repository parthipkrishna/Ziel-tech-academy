<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuickLink;
use App\Models\ContactInfos;
use Illuminate\Support\Facades\File;

class AdminHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quicklinks = QuickLink::all();
        return view('dashboard.home.index')->with(compact('quicklinks'));
    }

    public function contactIndex() {
        $contact = ContactInfos::all();
        return view('dashboard.contact.index')->with(compact('contact'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.home.add-quicklink');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'subtitle' => 'required',
            'link'=>'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpg,webpe,jpeg,png,svg,gif|max:2048',
        ]);
        try {

            $file = $request->file('image');   
            if($file){   
                $file_name = $file->getClientOriginalName();
                $image_path = $file->storeAs('public/uploads/images/Quicklinks', $file_name , 'public_folder');   
            }
            $quicklink =  new QuickLink();
            $quicklink -> title = $request->input('title');
            $quicklink -> subtitle = $request->input('subtitle');
            $quicklink -> link = $request->input('link');
            $quicklink -> status =  $request->has('status') ? 1 : 0;
            $quicklink -> image = $image_path;
            $success = $quicklink -> save();
            if($success){
                $message ='Quick link added successfully ';
                return redirect()->back()->with(compact('message'));
            } 
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quicklink = QuickLink::where('quick_link_id', $id)->firstOrFail(); 
        $existing_image = base_path($quicklink->image);
        if($request->file('image')){
            if(File::exists($existing_image)){
                File::delete($existing_image);
            }
            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            $image_path = $file->storeAs('public/uploads/images/Quicklinks', $file_name , 'public_folder');
        }   
        $updated = $quicklink->update([
            'title' => $request->input('title')?:$quicklink->title,
            'subtitle' => $request->input('subtitle')?:$quicklink->subtitle,
            'link' => $request->input('link')?:$quicklink->link,
            'status' => $request->has('status') ? $request->input('status') : $quicklink->status,
            'image' => $request->file('image')?$image_path:$quicklink->image,
        ]);
        if($updated){
            return redirect()->back()->with(['message' => 'Successfully updated']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = QuickLink::where('quick_link_id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
