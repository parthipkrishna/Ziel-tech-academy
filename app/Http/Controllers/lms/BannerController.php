<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\ToolKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Models\Banner;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        $courses = Course::where('type','lms')->get();
        return view('lms.sections.banner.banner', ['types' => Banner::getBannerTypes(),'banners' => $banners,'courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('type','lms')->get();
        $toolkits = ToolKit::all();
        return view('lms.sections.banner.add-banner', ['types' => Banner::getBannerTypes(),'courses' => $courses, 'toolkits' => $toolkits]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Banner store request received', [
            'data' => $request->except(['_token'])
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'image'             => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'type'              => 'required|in:course,toolkit',
                'related_id'        => 'required',
                'short_description' => 'nullable|string|max:255',
                'status'            => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                Log::warning('Banner validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Explicit check: if file is missing (too large => PHP drops it)
            if (!$request->hasFile('image')) {
                Log::error('Image missing or too large for PHP limits');
                return response()->json([
                    'success' => false,
                    'errors'  => ['image' => ['File too large. Please upload under 2MB.']],
                ], 422);
            }

            $imagePath = $request->file('image')->store('uploads/images/banners', 'public');
            Log::info('Banner image uploaded successfully', ['path' => $imagePath]);

            $banner = null;
            DB::transaction(function () use ($request, $imagePath, &$banner) {
                $bannerData = [
                    'image'             => $imagePath,
                    'type'              => $request->input('type'),
                    'related_id'        => $request->input('related_id'),
                    'short_description' => $request->input('short_description'),
                    'status'            => $request->boolean('status') ? 1 : 0,
                ];

                $banner = Banner::create($bannerData);

                Log::info('Banner created successfully', ['id' => $banner->id]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Banner added successfully!',
                'banner_id' => $banner?->id
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while creating banner', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            Log::error('Unexpected error while creating banner', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Banner update request received', [
            'id'   => $id,
            'data' => $request->except(['_token'])
        ]);

        try {
            $validated = $request->validate([
                'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'type'              => 'nullable|in:course,toolkit',
                'related_id'        => 'nullable',
                'short_description' => 'nullable|string|max:255',
                'status'            => 'nullable|in:0,1',
            ]);

            DB::transaction(function () use ($request, $id, $validated) {
                $banner = Banner::findOrFail($id);

                if ($request->filled('type')) {
                    $banner->type = $validated['type'];
                }

                if ($request->filled('related_id')) {
                    $banner->related_id = $validated['related_id'];
                }

                if ($request->has('status')) {
                    $banner->status = $validated['status'] ? 1 : 0;
                }

                if ($request->filled('short_description')) {
                    $banner->short_description = $validated['short_description'];
                }

                // Handle image upload
                if ($request->hasFile('image')) {
                    // Delete old image if exists
                    if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                        Storage::disk('public')->delete($banner->image);
                        Log::info('Old banner image deleted', ['path' => $banner->image]);
                    }

                    $imagePath = $request->file('image')->store('uploads/images/banners', 'public');
                    $banner->image = $imagePath;

                    Log::info('New banner image uploaded', ['path' => $imagePath]);
                }

                $banner->save();

                Log::info('Banner updated successfully', ['id' => $banner->id]);
            });

            return redirect()->back()->with('success', 'Banner updated successfully!');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating banner', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Database error: ' . $e->getMessage()])
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Unexpected error while updating banner', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $banner = Banner::find($id);
            if (!$banner) {
                return redirect()->back()->with('error', 'Banner not found.');
            }
            $banner->delete();
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully.']);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Database query error: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function ajaxList(Request $request)
    {
        $banners = Banner::select(['id', 'image', 'type', 'status'])->latest();

        return DataTables::of($banners)
            ->editColumn('image', function ($banner) {
                if ($banner->image) {
                    $url = env('STORAGE_URL') . '/' . $banner->image;
                    return '<img src="' . $url . '" class="me-2 rounded-circle" width="40" height="40">';
                }
                return '<span class="small text-danger">No Image</span>';
            })
            ->addColumn('status', function ($banner) {
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" data-id="' . $banner->id . '" id="switch' . $banner->id . '" data-switch="success" ' . ($banner->status ? 'checked' : '') . ' />
                        <label for="switch' . $banner->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                    </div>
                ';
            })
            ->addColumn('action', function ($banner) {
                $actions = '';
                if (auth()->user()->hasPermission('banners.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editCourse-modal' . $banner->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }
                if (auth()->user()->hasPermission('banners.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $banner->id . '"><i class="mdi mdi-delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['image', 'status', 'action']) // render HTML
            ->make(true);
    }

}
