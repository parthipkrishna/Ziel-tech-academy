<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        $subscriptions = Subscription::with('student', 'course')->get();
        return view('lms.sections.subscriptions.index', compact('subscriptions', 'courses'));
    }

    public function ajaxList(Request $request)
    {
        $subscriptions = Subscription::with(['student.batches', 'course'])
            ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
            ->when($request->start_date, fn($q) => $q->whereDate('start_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('end_date', '<=', $request->end_date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->select('subscriptions.*')->latest();

        return DataTables::of($subscriptions)
            ->addColumn('student', fn($s) => $s->student->first_name ?? 'N/A')
            ->addColumn('course', fn($s) => $s->course->name ?? 'N/A')
            ->addColumn('batch', function ($s) {
                return $s->student->batches->pluck('name')->join(', ') ?? '-';
            })
            ->addColumn('start_date', fn($s) => \Carbon\Carbon::parse($s->start_date)->format('d-m-Y'))
            ->addColumn('end_date', fn($s) => $s->end_date ? \Carbon\Carbon::parse($s->end_date)->format('d-m-Y') : 'N/A')
            ->addColumn('status', function ($s) {
                $color = match($s->status) {
                    'active' => 'success',
                    'expired' => 'secondary',
                    'cancelled' => 'danger',
                    default => 'dark'
                };
                return '<span class="badge bg-' . $color . '">' . ucfirst($s->status) . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
