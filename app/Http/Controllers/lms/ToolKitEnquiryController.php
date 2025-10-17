<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\ToolKitEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ToolKitEnquiriesExport;
use App\Imports\ToolKitEnquiriesImport;
use App\Models\Batch;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class ToolKitEnquiryController extends Controller
{
    /**
     * List toolkit enquiries with pagination + filtering
     */
    public function index(Request $request)
    {
        try {
            
            $enquiries = ToolKitEnquiry::all();
            $batches = Batch::where('status', 1)->get();

            return view('lms.sections.toolkit_enquiries.index', compact('enquiries', 'batches'));
        } catch (\Exception $e) {
            \Log::error('Error fetching toolkit enquiries or batches: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Unable to load data. Please try again later.');
        }
    }
    /**
     * Update enquiry status or fields
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'       => 'nullable|in:request_placed,cancelled,delivered',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $enquiry = ToolkitEnquiry::findOrFail($id);

            $enquiry->update($request->only([
                'status',
                'total_amount',
                'student_name',
                'email',
                'phone',
                'state'
            ]));

            Log::channel('prologger')->info('Toolkit enquiry updated successfully', [
                'id'          => $enquiry->id,
                'status'      => $enquiry->status,
                'totalAmount' => $enquiry->total_amount,
                'updatedBy'   => auth()->id(), // track who did it
            ]);

            return redirect()
                ->route('lms.toolkit.enquiry.index')
                ->with('success', 'Enquiry updated successfully');
        } catch (\Throwable $e) {
            Log::channel('prologger')->error('Failed to update toolkit enquiry', [
                'id'    => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()
                ->back()
                ->with('error', 'Failed to update enquiry');
        }
    }

    /**
     * Delete enquiry
     */
    public function destroy($id)
    {
        try {
            $enquiry = ToolkitEnquiry::findOrFail($id);
            $enquiry->delete();

            Log::channel('prologger')->info('Toolkit enquiry deleted', [
                'id' => $id,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Enquiry deleted successfully',
            ]);
        } catch (\Throwable $e) {
            Log::channel('prologger')->error('Failed to delete toolkit enquiry', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete enquiry',
            ], 500);
        }
    }

    /**
     * Export enquiries (Excel/CSV)
     */
    public function export(Request $request)
    {
        $filters = $request->only(['status', 'month', 'year', 'search','batch_id','date_range']);
        $fileName = 'toolkit-enquiries-' . now()->format('Ymd_His') . '.xlsx';

        try {
            Log::channel('prologger')->info('Toolkit enquiries export triggered', [
                'filters' => $filters,
            ]);

            return Excel::download(new ToolkitEnquiriesExport($filters), $fileName);
        } catch (\Throwable $e) {
            Log::channel('prologger')->error('Failed to export toolkit enquiries', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to export enquiries',
            ], 500);
        }
    }

    /**
     * Import enquiries (bulk update)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new ToolkitEnquiriesImport, $request->file('file'));

            Log::channel('prologger')->info('Toolkit enquiries imported successfully');

            return redirect()->route('toolkit.index')->with('success', 'Enquiries imported successfully');

        } catch (\Throwable $e) {
            Log::channel('prologger')->error('Failed to import toolkit enquiries', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to import enquiries');
        }
    }

    public function ajaxList(Request $request)
    {
        $query = ToolKitEnquiry::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $start = $dates[0];
                $end = $dates[1];

                $start .= ' 00:00:00';
                $end .= ' 23:59:59';

                $query->whereBetween('created_at', [$start, $end]);
            }
        }

         if ($request->filled('batch_id')) {
        $batchId = $request->batch_id;
        $query->whereHas('student.batches', function($q) use ($batchId) {
            $q->where('batches.id', $batchId);
        });
    }

        return DataTables::of($query)
            ->editColumn('full_address', function ($row) {
                return !empty($row->address) ? $row->address : '<span class="small text-danger">Not Available</span>';
            })

            ->editColumn('total_amount', function($enquiry) {
                return $enquiry->total_amount ? number_format($enquiry->total_amount, 2) : '-';
            })
            ->editColumn('status', function ($enquiry) {
                return match ($enquiry->status) {
                    'request_placed' => '<button type="button" class="btn btn-soft-warning rounded-pill">Request Placed</button>',
                    'delivered'      => '<button type="button" class="btn btn-soft-success rounded-pill">Delivered</button>',
                    'cancelled'      => '<button type="button" class="btn btn-soft-danger rounded-pill">Cancelled</button>',
                    default          => '<span class="text-muted">'.ucfirst($enquiry->status).'</span>',
                };
            })

            ->editColumn('created_at', function($enquiry) {
                return $enquiry->created_at->format('d-M-Y'); 
            })
            ->addColumn('action', function($enquiry) {
                $actions = '';
                if (auth()->user()->hasPermission('toolkit-enquiries.update')) {
                     $actions .= '<a href="javascript:void(0);" class="action-icon editToolKitBtn" data-bs-toggle="modal" data-bs-target="#edit-tool-kit-modal' . $enquiry->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['action','status','full_address'])
            ->make(true);
    }

}
