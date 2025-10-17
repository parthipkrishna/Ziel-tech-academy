<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralUse;
use Yajra\DataTables\Facades\DataTables;

class ReferralHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = ReferralUse::with(['referralCode.influencer','student'])->latest()->get();
        return view('lms.sections.history.history', compact('histories'));
    }
    public function ajaxList()
    {
        $histories = ReferralUse::with(['referralCode.influencer', 'student'])->latest();

        return DataTables::of($histories)
            ->addIndexColumn()
            ->addColumn('referral_code', fn($row) =>
                $row->referralCode?->code ?? '<span class="small text-danger">Not Available</span>'
            )
            ->addColumn('influencer', function ($row) {
                return $row->referralCode && $row->referralCode->influencer
                    ? $row->referralCode->influencer->name
                    : '<span class="small text-danger">Not Available</span>';
            })
            ->addColumn('student', fn($row) =>
                $row->student->full_name ?? '<span class="small text-danger">row</span>'
            )
            ->addColumn('used', fn($row) =>
                \Carbon\Carbon::parse($row->used_at)->format('d M Y')
            )
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'processing' => '<span class="badge bg-primary">Processing</span>',
                    'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                    default => '<span class="badge bg-success">Onboarded</span>',
                };
            })
            ->addColumn('action', function ($row) {
                $modal = view('lms.sections.history.inc.edit-history-modal', ['history' => $row])->render();

                return '
                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-viewHistory-modal' . $row->id . '">
                        <i class="mdi mdi-eye me-1"></i>
                    </a>' . $modal;
            })
            ->rawColumns(['referral_code', 'influencer', 'student', 'status', 'action'])
            ->make(true);
    }
}
