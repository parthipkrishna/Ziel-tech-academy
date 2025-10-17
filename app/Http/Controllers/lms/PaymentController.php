<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; 

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['student', 'course'])->latest()->get();
        $students = Student::select('id', 'first_name')->get();
        $courses = Course::select('id', 'name')->get();

        return view('lms.sections.payment_records.index', compact('payments', 'students', 'courses'));
    }

    public function ajaxList(Request $request)
    {
        $query = Payment::with(['student', 'course'])->select('payments.*')->latest();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('paid_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->month) {
            $query->whereMonth('paid_at', $request->month);
        }

        if ($request->year) {
            $query->whereYear('paid_at', $request->year);
        }

        return DataTables::of($query)
            ->addColumn('student', fn($p) => $p->student->first_name ?? 'N/A')
            ->addColumn('course', fn($p) => $p->course->name ?? 'N/A')
            ->addColumn('amount', fn($p) => $p->currency . ' ' . number_format($p->amount, 2))
            ->addColumn('payment_gateway', fn($p) => $p->payment_gateway ?? 'N/A')
            ->addColumn('transaction_id', fn($p) => $p->transaction_id ?? 'N/A')
            ->addColumn('paid_at', fn($p) => $p->paid_at ? $p->paid_at->format('d M Y') : 'Not Paid')
            ->addColumn('status', function ($p) {
                $badge = match($p->status) {
                    'success' => 'success',
                    'failed', 'cancelled' => 'danger',
                    'pending' => 'warning',
                    default => 'secondary',
                };
                return '<span class="badge bg-' . $badge . '">' . ucfirst($p->status) . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

}
