<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectSession;
use App\Models\VideoLog;
use Illuminate\Http\Request;
use App\Models\Video;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VideoReportExport;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;

class VideoReportController extends Controller
{
    /**
     * Video report page
     */
    public function index()
    {   
        $batches = Batch::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();

        return view('lms.sections.video_report.index', compact('batches', 'subjects'));
    }

    /**
     * DataTable Ajax source with pagination
     */
    public function videoReportAjax(Request $request)
    {
       $query = VideoLog::query()
            ->with(['video', 'subject', 'session'])
            ->select('video_id', 'subject_id', 'subject_session_id')
            ->selectRaw('SUM(duration) as total_seconds')
            ->selectRaw('COUNT(DISTINCT student_id) as total_students')
            ->groupBy('video_id', 'subject_id', 'subject_session_id');

        // Filter by batch (if needed) through students relation
        if ($request->filled('batch_id')) {
            $query->whereHas('student', fn($q) => $q->where('batch_id', $request->batch_id));
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by session
        if ($request->filled('session_id')) {
            $query->where('subject_session_id', $request->session_id);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

       return DataTables::of($query)
            ->addColumn('video_name', fn($log) => $log->video->title ?? '-')
            ->addColumn('subject_name', fn($log) => $log->subject->name ?? '-')
            ->addColumn('session_name', fn($log) => $log->session->title ?? '-')
            ->addColumn('total_hours', fn($log) => gmdate("H:i:s", $log->total_seconds ?? 0))
            ->addColumn('total_students', fn($log) => $log->total_students ?? 0)
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->whereHas('video', fn($q) => $q->where('title', 'like', "%{$search}%"))
                        ->orWhereHas('subject', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('session', fn($q) => $q->where('title', 'like', "%{$search}%"));
                }
            })
            ->make(true);
    }
    /**
     * Export report
     */

    public function videoReportExport(Request $request)
    {
        $filters = $request->only(['batch_id', 'subject_id', 'session_id', 'date_range']);
        $fileName = 'video-report-' . now()->format('Ymd_His') . '.xlsx';

        try {
            Log::channel('prologger')->info('Video report export triggered', [
                'filters' => $filters,
            ]);

            return Excel::download(new VideoReportExport($filters), $fileName);

        } catch (\Throwable $e) {
            Log::channel('prologger')->error('Failed to export video report', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to export video report',
            ], 500);
        }
    }
}
