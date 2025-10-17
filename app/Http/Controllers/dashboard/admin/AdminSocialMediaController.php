<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialMediaLink;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = SocialMediaLink::all();
        return view('dashboard.socialmedia.index')->with(compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.socialmedia.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required',
            'url' => 'required',
        ]);
       
        try {
            $link = new SocialMediaLink();
            $link->platform = $request->input('platform');
            $link->url = $request->input('url');
            $success = $link->save();
            if ($success) {
                $message ='SocialMedia link added successfully ';
                return redirect()->route('admin.socialmedia.index')->with('message', 'Successfully stored');
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
        $info = SocialMediaLink::findOrFail($id);
        $updated = $info->update([
            'platform' => $request->input('platform')?: $info->platform,
            'url' => $request->input('url')?: $info->url,
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
        $success = SocialMediaLink::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
