<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;

class AdminCompanyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyinfo = CompanyInfo::first();
        return view('dashboard.companyinfo.index')->with(compact('companyinfo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.companyinfo.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mission' => 'nullable',
            'vision' => 'nullable',
            'why_choose_us' => 'nullable',
            'offerings' => 'nullable',
        ]);
        try {
            $info = new CompanyInfo();
            $info->mission = $request->input('mission')?: NULL;
            $info->vision = $request->input('vision')?: NULL;
            $info->why_choose_us = $request->input('why_choose_us') ?: NULL;
            $info->offerings = $request->input('offerings') ?: NULL;
            $success = $info->save();
            if ($success) {
                $message ='CompanyInfo added successfully ';
                return redirect()->back()->with(compact('message'));
            }
            else {
                return redirect()->back()->withErrors(['error' => 'Failed to save CompanyInfo.'])->withInput($request->input());
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
        $info = CompanyInfo::findOrFail($id);
        $updated = $info->update([
            'mission' => $request->input('mission')?: $info->mission,
            'vision' => $request->input('vision')?: $info->vision,
            'why_choose_us' => $request->input('why_choose_us')?: $info->why_choose_us,
            'offerings' => $request->input('offerings')?: $info->offerings,
        ]);
        if($updated){
            return redirect()->route('admin.company.infos.index')->with('message', 'Successfully updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = CompanyInfo::where('id',$id)->delete();
        if($success){
            return redirect()->back()->with(['message'=>'delete success']);
        }
    }
}
