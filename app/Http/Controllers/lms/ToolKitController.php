<?php


namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ToolKit;
use App\Models\ToolKitMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ToolKitController extends Controller
{
    /**
     * List toolkits for a given course.
     */
    public function index()
    {
        $tool_kits = ToolKit::where('is_enabled', 1)->get();
        $courses = Course::where('status', 1)
                     ->where('type', 'lms')
                     ->get();
        return view('lms.sections.toolkit.index', compact('tool_kits', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('lms.sections.toolkit.add', compact('courses'));
    }

    /**
     * Show details of a single toolkit.
     */
    public function show(int $id)
    {
        try {
            $toolKit = ToolKit::with(['media', 'course'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data'   => $toolKit,
            ]);
        } catch (\Throwable $e) {
            Log::error('ToolKit Show Error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Toolkit not found',
            ], 404);
        }
    }

    /**
     * Create a new toolkit.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'          => 'required|exists:courses,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable',
            'short_description'  => 'nullable|max:255',
            'is_enabled'         => 'boolean',
            'price'              => 'nullable|numeric|min:0',
            'offer_price'        => 'nullable|numeric|min:0|lt:price',
            'media'              => 'array',
            'media.*'            => 'file|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(), // send all errors
            ], 422);
        }

        try {
            $toolKit = DB::transaction(function () use ($request) {
                $toolKit = ToolKit::create($request->only([
                    'course_id',
                    'name',
                    'description',
                    'short_description',
                    'is_enabled',
                    'price',
                    'offer_price',
                ]));

                 if ($request->hasFile('media')) {
                    $mediaData = [];
                    foreach ($request->file('media') as $file) {
                        $path = $file->store('toolkits/media', 'public'); // or 'local' depending on your setup

                        $mediaData[] = [
                            'tool_kit_id' => $toolKit->id,
                            'file_path'   => $path,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }
                    ToolKitMedia::insert($mediaData);
                }

                return $toolKit->load('media');
            });

            return response()->json([
                'status'  => true,
                'message' => 'Toolkit created successfully',
                'data'    => $toolKit,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('ToolKit Store Error', ['input' => $request->all(), 'error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to create toolkit',
            ], 500);
        }
    }

    /**
     * Update an existing toolkit.
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'sometimes|required|string|max:255',
            'description'        => 'nullable',
            'short_description'  => 'nullable|max:255',
            'is_enabled'         => 'boolean',
            'price'              => 'nullable|numeric|min:0',
            'offer_price'        => 'nullable|numeric|min:0|lt:price',
            'media'              => 'array',
            'media.*'            => 'file|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error'  => $validator->errors()->first(),
            ], 422);
        }

        try {
            $toolKit = ToolKit::findOrFail($id);

            DB::transaction(function () use ($request, $toolKit) {
                $toolKit->update($request->only([
                    'name',
                    'description',
                    'short_description',
                    'is_enabled',
                    'price',
                    'offer_price',
                ]));

                if ($request->hasFile('media')) {
                $mediaData = [];
                foreach ($request->file('media') as $file) {
                    $path = $file->store('toolkits/media', 'public');
                    $mediaData[] = [
                        'tool_kit_id' => $toolKit->id,
                        'file_path'   => $path,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
                ToolKitMedia::insert($mediaData);
            }
            });

           return redirect()->route('lms.toolkits.index')->with([
                'status'  => true,
                'message' => 'Toolkit updated successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('ToolKit Update Error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update toolkit',
            ], 500);
        }
    }

    /**
     * Delete toolkit.
     */
    public function destroy(int $id)
    {
        try {
            $toolKit = ToolKit::findOrFail($id);
            $toolKit->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Toolkit deleted successfully',
            ]);
        } catch (\Throwable $e) {
            Log::error('ToolKit Delete Error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete toolkit',
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $toolkit = ToolKit::findOrFail($id);
        $toolkit ->update(['is_enabled' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function ajaxList(Request $request)
    {
        $tool_kits = ToolKit::select([
            'tool_kits.id',
            'tool_kits.name',
            'tool_kits.course_id',
            'tool_kits.price',
            'tool_kits.offer_price',
            'tool_kits.is_enabled',
            'courses.name as course_name'
        ])
        ->leftJoin('courses', 'tool_kits.course_id', '=', 'courses.id')
        ->latest('tool_kits.created_at');

        return DataTables::of($tool_kits)
            ->editColumn('name', function ($tool_kit) {
                return Str::limit($tool_kit->name, 50);
            })
            ->editColumn('price', function ($tool_kit) {
                return $tool_kit->price ? number_format($tool_kit->price, 2) : '-';
            })
            ->editColumn('offer_price', function ($tool_kit) {
                return $tool_kit->offer_price ? number_format($tool_kit->offer_price, 2) : '-';
            })
            ->addColumn('course_name', function ($tool_kit) {
                return $tool_kit->course_name ?? '-';
            })
            ->addColumn('is_enabled', function ($tool_kit) {
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" data-id="' . $tool_kit->id . '" id="switch' . $tool_kit->id . '" data-switch="success" ' . ($tool_kit->is_enabled ? 'checked' : '') . ' />
                        <label for="switch' . $tool_kit->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                    </div>
                ';
            })
            ->addColumn('action', function ($tool_kit) {
                $actions = '';

                if (auth()->user()->hasPermission('toolkits.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon editToolKitBtn" data-bs-toggle="modal" data-bs-target="#edit-tool-kit-modal' . $tool_kit->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('toolkits.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon deleteToolKitBtn" data-bs-toggle="modal" data-bs-target="#delete-tool-kit-modal' . $tool_kit->id . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $actions;
            })
            ->rawColumns(['is_enabled', 'action'])
            ->make(true);
    }
}
