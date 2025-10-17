<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralPayment;
use App\Models\Influencer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ReferralPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $influencer_payments = ReferralPayment::with('influencer')->get();
        $influencers = Influencer::all();
        $payment_status = ReferralPayment::paymentStatus();
        return view('lms.sections.payment.payment', ['payment_methods' => ReferralPayment::paymentMethods(), 'payment_status' => $payment_status, 'influencers' => $influencers, 'influencer_payments' =>$influencer_payments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $influencers = Influencer::all();
        return view('lms.sections.payment.add', ['payment_methods' => ReferralPayment::paymentMethods(),'influencers' => $influencers,]);
    }

    public function getInfluencerTotal($id)
    {
        try {
            $influencer = Influencer::findOrFail($id);
            $totalCommission = $influencer->getTotalCommission();
            $totalWithdrawal = $influencer->getTotalWithdrawal();
            $balance = $influencer->getBalance();
            return response()->json([
                'total_amount' => round($balance, 2),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('ReferralPayment store request received', $request->all());

            $validator = Validator::make($request->all(), [
                'influencer_id'      => 'required|exists:influencers,id',
                'current_withdrawal' => 'required|numeric|min:0',
                'payment_date'       => 'required|date',
                'gst_number'         => 'nullable|string|max:50',
                'method'             => 'required|in:cash,upi,bank_transfer,cheque,other',
                'transaction_id'     => 'nullable|string|max:255',
                'attachment_path'    => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:2048',
                'notes'              => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                Log::warning('ReferralPayment validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors'  => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $attachmentPath = null;
            if ($request->hasFile('attachment_path')) {
                $attachmentPath = $request->file('attachment_path')->store('uploads/images/referral_attachments', 'public');
            }

            $payment = ReferralPayment::create([
                'influencer_id'      => $request->input('influencer_id'),
                'current_withdrawal' => $request->input('current_withdrawal'),
                'payment_date'       => $request->input('payment_date'),
                'gst_number'         => $request->input('gst_number'),
                'method'             => $request->input('method'),
                'transaction_id'     => $request->input('transaction_id'),
                'attachment_path'    => $attachmentPath,
                'status'             => 'initiated',
                'notes'              => $request->input('notes'),
            ]);

            DB::commit();

            Log::info('ReferralPayment created successfully', [
                'id' => $payment->id,
                'influencer_id' => $payment->influencer_id,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Referral payment created successfully!',
                ]);
            }

            return redirect()->route('lms.referral.payment')
                ->with('message', 'Referral payment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while creating ReferralPayment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while creating the referral payment.',
                ], 500);
            }

            return back()->withErrors(['error' => 'Something went wrong while creating the referral payment.']);
        }
    }


    public function getInfluencer($id)
    {
        $influencer = Influencer::findOrFail($id);
        return response()->json([
            'total_amount' => $influencer->getTotalCommission(),
            'total_withdrawal' => $influencer->getTotalWithdrawal(),
            'balance' => $influencer->getBalance(),
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('ReferralPayment update request received', [
                'payment_id' => $id,
                'data' => $request->all()
            ]);

            $payment = ReferralPayment::findOrFail($id);

            $validated = $request->validate([
                'influencer_id'       => 'nullable|exists:influencers,id',
                'method'              => 'nullable|string',
                'status'              => 'nullable|string',
                'transaction_id'      => 'nullable|string',
                'current_withdrawal'  => 'nullable|numeric|min:0',
                'notes'               => 'nullable|string',
            ]);

            $payment->influencer_id      = $request->filled('influencer_id') ? $request->influencer_id : $payment->influencer_id;
            $payment->method             = $request->filled('method') ? $request->method : $payment->method;
            $payment->status             = $request->filled('status') ? $request->status : $payment->status;
            $payment->current_withdrawal = $request->filled('current_withdrawal') ? $request->current_withdrawal : $payment->current_withdrawal;
            $payment->notes              = $request->filled('notes') ? $request->notes : $payment->notes;
            $payment->transaction_id     = $request->filled('transaction_id') ? $request->transaction_id : $payment->transaction_id;

            $payment->save();

            Log::info('ReferralPayment updated successfully', ['payment_id' => $payment->id]);

            return redirect()->back()->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating ReferralPayment', [
                'payment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to update payment. Please try again.');
        }
}

    public function ajaxList(Request $request)
    {
        $payments = ReferralPayment::with('influencer:id,name')->select(['id', 'influencer_id', 'current_withdrawal', 'payment_date', 'status'])->latest();

        return DataTables::of($payments)
            ->editColumn('influencer', function ($payment) {
                return optional($payment->influencer)->name ?? 'N/A';
            })
            ->editColumn('payment_date', function ($payment) {
                return Carbon::parse($payment->payment_date)->format('d M Y');
            })
            ->editColumn('status', function ($payment) {
                $statusBadges = [
                    'on_hold'     => ['label' => 'On Hold',     'class' => 'bg-warning'],
                    'initiated'   => ['label' => 'Initiated',   'class' => 'bg-primary'],
                    'processing'  => ['label' => 'Processing',  'class' => 'bg-info'],
                    'completed'   => ['label' => 'Completed',   'class' => 'bg-secondary'],
                    'failed'      => ['label' => 'Failed',      'class' => 'bg-success'],
                    'check_issues'=> ['label' => 'Check Issues','class' => 'bg-success'],
                    'rejected'    => ['label' => 'Rejected',    'class' => 'bg-danger'],
                ];
                $badge = $statusBadges[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'bg-dark'];
                return '<span class="badge ' . $badge['class'] . '">' . $badge['label'] . '</span>';
            })
            ->addColumn('action', function ($payment) {
                $actions = '';
                if (auth()->user()->hasPermission('referral-payments.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editPayment-modal' . $payment->id . '">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }
                return $actions;
            })
            ->rawColumns(['status', 'action']) // HTML safe columns
            ->make(true);
    }
}
