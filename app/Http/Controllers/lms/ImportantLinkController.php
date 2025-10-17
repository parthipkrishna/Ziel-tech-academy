<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportantLink;
use Yajra\DataTables\Facades\DataTables;
use Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportantLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $important_links = ImportantLink ::all();
        return view('lms.sections.importantlink.importantlink')->with(compact('important_links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.importantlink.add');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            Log::info('ImportantLink store request received', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:255',
                'link' => 'required|url|max:500',
                'short_description' => 'nullable|string|max:500',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                Log::warning('ImportantLink validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $importantLink = ImportantLink::create([
                'name' => $request->input('name'),
                'link' => $request->input('link'),
                'short_description' => $request->input('short_description') ?? null,
                'status' => $request->input('status') ?? 0,
            ]);

            DB::commit();

            Log::info('ImportantLink created successfully', [
                'id' => $importantLink->id,
                'name' => $importantLink->name
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Important link added successfully!',
                ]);
            }

            return redirect()->route('lms.important.links')
                ->with('message', 'Important link added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while creating ImportantLink', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while adding the link.',
                ], 500);
            }

            return back()->withErrors(['error' => 'Something went wrong while adding the link.']);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Important Link update request received', [
            'id'   => $id,
            'data' => $request->except(['_token'])
        ]);

        try {
            $importantLink = ImportantLink::findOrFail($id);

            $validated = $request->validate([
                'name'              => 'sometimes|required|string|min:3|max:255',
                'link'              => 'sometimes|required|url|max:500',
                'short_description' => 'nullable|string|max:500',
                'status'            => 'nullable|boolean',
            ]);

            $importantLink->update([
                'name'              => $request->has('name') ? $validated['name'] : $importantLink->name,
                'link'              => $request->has('link') ? $validated['link'] : $importantLink->link,
                'short_description' => $request->has('short_description') ? $validated['short_description'] : $importantLink->short_description,
                'status'            => $request->has('status') ? $validated['status'] : $importantLink->status,
            ]);

            Log::info('Important Link updated successfully', [
                'id' => $importantLink->id
            ]);

            return redirect()
                ->route('lms.important.links')
                ->with('message', 'Important link updated successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating Important Link', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with(
                'error',
                'Database error occurred while updating the important link.'
            );

        } catch (\Exception $e) {
            Log::error('Unexpected error while updating Important Link', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with(
                'error',
                'Something went wrong while updating. Please try again later.'
            );
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $link = ImportantLink::findOrFail($id);
        $link->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $link = ImportantLink::find($id);
            if (!$link) {
                return redirect()->back()->with('error', 'ImportantLink not found.');
            }
            $link->delete();
            return response()->json(['success' => true, 'message' => 'Link deleted successfully.']);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Database query error: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function ajaxList()
    {
        $links = ImportantLink::query()->latest();

        return DataTables::of($links)
            ->addColumn('link', fn($row) =>
                '<a href="' . $row->link . '" target="_blank" rel="noopener noreferrer">' .
                    Str::limit($row->link, 35, '...') .
                '</a>'
            )
            ->addColumn('status', function ($row) {
                return '
                    <input type="checkbox" id="switch' . $row->id . '" data-id="' . $row->id . '" class="status-toggle" ' . ($row->status == 1 ? 'checked' : '') . ' data-switch="success"/>
                    <label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                ';
            })
            ->addColumn('action', function ($row) {
                $editModal = view('lms.sections.importantlink.inc.edit-link-modal', ['link' => $row])->render();
                $deleteModal = view('lms.sections.importantlink.inc.delete-link-modal', ['link' => $row])->render();

                $actions = '';
                
                // Add Edit button if user has permission
                if (auth()->user()->hasPermission('important-links.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editImportantLink-modal' . $row->id . '">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }
                
                // Add Delete button if user has permission
                if (auth()->user()->hasPermission('important-links.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $row->id . '">
                                    <i class="mdi mdi-delete"></i>
                                </a>';
                }

                return $actions . $editModal . $deleteModal;
            })
            ->rawColumns(['link', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }
}
