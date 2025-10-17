<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\Request;
use App\Models\LiveClass;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LiveClassReportExport;
use App\Models\Batch;

class LiveClassReportController extends Controller
{
    /**
     * Report page view
     */
    public function index()
    {
        $batches = Batch::where('status', 1)->get();
        $tutors = Tutor::all();

        return view('lms.sections.live_class_report.index',compact('batches', 'tutors'));
    }

    /**
     * DataTable Ajax source
     */
    public function liveClassReportAjax(Request $request)
    {
        $query = LiveClass::query()
            ->with(['batch', 'tutor.user', 'participants']); // eager load

        // Filter by batch
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('start_time', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        return DataTables::of($query)
            ->addColumn('name', fn($class) => $class->name)
            ->addColumn('batch_name', fn($class) => $class->batch->name ?? '-')
            ->addColumn('total_students', fn($class) => $class->participants->count())
            ->addColumn('date_only', fn($class) =>
                $class->start_time ? $class->start_time->format('d-M-Y') : '-'
            )
            ->addColumn('time_range', fn($class) =>
                ($class->start_time ? $class->start_time->format('h:iA') : '-') . ' - ' .
                ($class->end_time ? $class->end_time->format('h:iA') : '-')
            )
            ->addColumn('faculty_name', fn($class) => $class->tutor->user->name ?? '-')

            // 👇 enable searching on joined columns
            ->filterColumn('batch_name', function ($query, $keyword) {
                $query->whereHas('batch', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('faculty_name', function ($query, $keyword) {
                $query->whereHas('tutor.user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('date_time', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_time, '%d-%b-%Y %H:%i') like ?", ["%{$keyword}%"]);
            })

            

            ->make(true);
    }
    /**
     * Export report
     */
    public function liveClassReportExport(Request $request)
    {
        $filters = $request->only(['batch_id', 'tutor_id', 'date_range']);
        $fileName = 'live-class-report-' . now()->format('Ymd_His') . '.xlsx';

        try {
            return Excel::download(
                new LiveClassReportExport($filters),
                $fileName
            );
        } catch (\Throwable $e) {
            \Log::error('Failed to export live class report', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to export live class report',
            ], 500);
        }
    }

}
