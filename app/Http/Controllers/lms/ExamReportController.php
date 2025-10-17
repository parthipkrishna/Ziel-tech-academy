<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ExamReportController extends Controller
{
    /**
     * Show Exam Report page
     */
    public function viewExamReportPage()
    {
        $batches = Batch::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();

        return view('lms.sections.exam_report.index', compact('batches', 'subjects')); 
    }

    /**
     * Show Assessment Report page
     */
    public function viewAssessmentReportPage()
    {
        $batches = Batch::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        
        return view('lms.sections.assessment_report.index',compact('batches', 'subjects')); 
    }

    public function examReportAjax(Request $request)
    {
        $query = Exam::query()
            ->with('subject')
            ->where('type', 'Exam')
            ->withCount([
                'attempts as total_passed' => fn($q) => $q->where('status', 'Passed'),
                'attempts as total_failed' => fn($q) => $q->where('status', 'Failed'),
            ]);

        // Filter by batch
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        return DataTables::of($query)
            // 🔹 Default search support
            ->filter(function ($q) use ($request) {
                if ($request->has('search') && $search = $request->get('search')['value']) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('exam_name', fn($exam) => $exam->name)
            ->addColumn('subject_name', fn($exam) => $exam->subject->name ?? '-')
            ->addColumn('total_passed', fn($exam) => $exam->total_passed ?? 0)
            ->addColumn('total_failed', fn($exam) => $exam->total_failed ?? 0)
            ->editColumn('created_at', fn($exam) => $exam->created_at->format('d-M-Y'))
            ->make(true);
    }

    public function assessmentReportAjax(Request $request)
    {
        $query = Exam::query()
            ->with('subject', 'subjectSession') // eager load subject & session
            ->where('type', 'Assessment')
            ->withCount([
                'attempts as total_passed' => fn($q) => $q->where('status', 'Passed'),
                'attempts as total_failed' => fn($q) => $q->where('status', 'Failed'),
            ]);

        // 🔹 Filter by batch
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // 🔹 Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // 🔹 Filter by date range
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        return DataTables::of($query)
            // 🔹 Default search support
            ->filter(function ($q) use ($request) {
                if ($request->has('search') && $search = $request->get('search')['value']) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('subjectSession', fn($sq) => $sq->where('title', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('exam_name', fn($exam) => $exam->name)
            ->addColumn('subject_name', fn($exam) => $exam->subject->name ?? '-')
            ->addColumn('session_name', fn($exam) => $exam->subjectSession->title ?? '-')
            ->addColumn('total_passed', fn($exam) => $exam->total_passed ?? 0)
            ->addColumn('total_failed', fn($exam) => $exam->total_failed ?? 0)
            ->editColumn('created_at', fn($exam) => $exam->created_at->format('d-M-Y'))
            ->make(true);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['batch_id', 'subject_id', 'date_range']);
        $fileName = 'exam-report-' . now()->format('Ymd_His') . '.xlsx';

        try {
            return Excel::download(new \App\Exports\ExamReportExport($filters), $fileName);
        } catch (\Throwable $e) {
            Log::error('Failed to export exam report', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to export exam report',
            ], 500);
        }
    }

    public function exportAssessment(Request $request)
    {
        $filters = $request->only(['batch_id', 'subject_id', 'date_range']);
        $fileName = 'exam-report-' . now()->format('Ymd_His') . '.xlsx';

        try {
            return Excel::download(new \App\Exports\AssessmentReportExport($filters), $fileName);
        } catch (\Throwable $e) {
            Log::error('Failed to export assessment report', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to export assessment report',
            ], 500);
        }
    }   
}
