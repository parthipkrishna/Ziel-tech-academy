<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInfo;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact = ContactInfo::first(); 
        return view('dashboard.contactinfo.index')->with(compact('contact'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.contactinfo.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'address' => 'required|min:5',
        ]);
        try {
            $info = new ContactInfo();
            $info->email = $request->input('email');
            $info->address = $request->input('address');
            $info->phone = $request->input('phone') ?: NULL;
            $info->google_map_link = $request->input('google_map_link') ?: NULL;
            $success = $info->save();
            if ($success) {
                $message ='ContactInfo added successfully ';
                return redirect()->route('admin.contacts.index')->with('message', 'Successfully stored');
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save ContactInfo.'])->withInput($request->input());
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
        $info = ContactInfo::findOrFail($id);
        $updated = $info->update([
            'google_map_link' => $request->input('google_map_link')?: $info->google_map_link,
            'email' => $request->input('email')?: $info->email,
            'phone' => $request->input('phone')?: $info->phone,
            'address' => $request->input('address')?: $info->address,
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
        $success = ContactInfo::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
