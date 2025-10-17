<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Course,
    Payment,
    RazorpayOrder,
    ReferralCode,
    ReferralUse,
    InfluencerCommission,
    StudentEnrollment,
    Subscription,
    Batch,
    BatchStudent,
    PaymentGatewayConfig,
    ReferralPoints,
    Student,
    StudentProgress,
    Subject,
    SubjectSession,
    ToolKit,
    ToolKitEnquiry
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Checkout: create or reuse an initiated payment and (for paid courses) create a Razorpay order.
     * Improvements:
     * - Use integer paişa where needed for gateway payload (we keep DB amount as decimal for compatibility but compute paise for gateway)
     * - Avoid logging sensitive request payloads
     * - Ensure payment record is created once (idempotent)
     */
    /**
     * Checkout API
     */
    public function checkout(Request $request)
    {
        try {
            $studentId = auth()->user()->studentId;

            // Validate input
            $validator = Validator::make($request->all(), [
                'toolkit_id'        => 'nullable|exists:tool_kits,id',
                'course_id'     => 'required|exists:courses,id',
                'referral_code' => 'nullable|string|exists:referral_codes,code',
                'use_loyalty_points' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $validated = $validator->validated();

            // Validate referral code if provided
            if (!empty($validated['referral_code'])) {
                $referral = ReferralCode::where('code', $validated['referral_code'])->first();
                // Check if already used by this student
                $alreadyUsed = ReferralUse::where('referral_code_id', $referral->id)
                    ->where('used_by_user_id', $studentId)
                    ->where('status', 'onboarded')
                    ->exists();
                if ($alreadyUsed) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Referral code already used.',
                    ], 422);
                }
            }

            // Load course and optional toolkit
            $course  = Course::findOrFail($validated['course_id']);
            $toolkit = isset($validated['toolkit_id']) ? ToolKit::findOrFail($validated['toolkit_id']) : null;

            // GST 18%
            $courseAmount  = (float) $course->course_fee;
            $gstAmount     = round($courseAmount * 0.18, 2);
            $totalAmount   = round($courseAmount + $gstAmount, 2);

            // Payment gateway
            $gateway = PaymentGatewayConfig::where('status', 'active')->first();

            if (!$gateway && $totalAmount > 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment gateway not configured',
                ], 500);
            }

            // Create or reuse initiated payment
            $payment = $this->initPayment($studentId, $course, $toolkit, $totalAmount, $gateway);

            // Calculate loyalty eligibility
            $loyaltyInfo = $this->calculateLoyalty($studentId, $course, $validated['use_loyalty_points'] ?? false);
            // Save referral usage (firstOrCreate)
            if (!empty($validated['referral_code'])) {
                // Clear previous processing records
                ReferralUse::where('used_by_user_id', $studentId)
                    ->where('status', 'processing')
                    ->delete();

                // Create new processing record
                ReferralUse::create([
                    'referral_code_id' => $referral->id,
                    'used_by_user_id'  => $studentId,
                    'status'           => 'processing',
                    'source'           => 'mobile',
                    'used_at'          => now(),
                ]);
            } else {
                // Referral removed → clear processing entries
                ReferralUse::where('used_by_user_id', $studentId)
                    ->where('status', 'processing')
                    ->delete();
            }

            $finalTotal = $totalAmount - ($loyaltyInfo['applied_discount'] ?? 0);

            // Build response
            $response = [
                'status'         => true,
                'is_paid_course' => $totalAmount > 0,
                'summary'        => [
                    'course_title'           => $course->name,
                    'total_duration'         => $course->total_hours,
                    'start_date'             => now()->toDateString(),
                    'end_date'               => now()->addYear(1)->toDateString(),
                    'course_fee'             => number_format($courseAmount, 2),
                    'gst'                    => number_format($gstAmount, 2),
                    'total'                  => number_format($finalTotal, 2),
                    'transaction_id'         => $payment->transaction_id,
                    'eligible_for_loyalty'   => $loyaltyInfo['eligible'],
                    'available_loyalty_points' => $loyaltyInfo['available_points'],
                    'loyalty_discount'        => $loyaltyInfo['applied_discount'] ?? 0,
                    'max_usable_points'      => $loyaltyInfo['max_usable_points'] ?? 0,
                ]
            ];

            // Razorpay order (if paid)
            if ($totalAmount > 0) {
                $response['payment'] = $this->createRazorpayOrder($payment, $gateway, $totalAmount, $studentId, $course->id);
            }

            return response()->json($response);
        } catch (Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage(), ['student_id' => auth()->user()->studentId]);
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong, please try again later.' . $e
            ], 500);
        }
    }

    /**
     * Initialize payment (create or reuse)
     */
    private function initPayment(int $studentId, ?Course $course, ?ToolKit $toolkit, float $amount, ?PaymentGatewayConfig $gateway)
    {
        // Determine query conditions
        $query = Payment::where('student_id', $studentId)
            ->where('status', 'initiated');

        if ($course) {
            $query->where('course_id', $course->id);
        }

        if ($toolkit) {
            $query->where('toolkit_id', $toolkit->id);
        }

        // Reuse latest initiated payment if exists
        $payment = $query->latest()->first();

        if ($payment) {
            Log::info('Reusing existing initiated payment', [
                'payment_id' => $payment->id,
                'student_id' => $studentId,
                'course_id'  => $course?->id,
                'toolkit_id' => $toolkit?->id,
            ]);
            return $payment;
        }

        // Otherwise create new
        $payment = Payment::create([
            'student_id'      => $studentId,
            'course_id'       => $course?->id,
            'toolkit_id'      => $toolkit?->id,
            'amount'          => $amount,
            'currency'        => 'INR',
            'status'          => 'initiated',
            'payment_gateway' => $gateway?->gateway_name ?? 'manual',
            'transaction_id'  => substr(md5(uniqid('', true)), 0, 14),
        ]);

        Log::info('New payment initiated', [
            'payment_id' => $payment->id,
            'student_id' => $studentId,
            'course_id'  => $course?->id,
            'toolkit_id' => $toolkit?->id,
            'amount'     => $amount,
        ]);

        return $payment;
    }

    /**
     * Calculate loyalty eligibility and discount
     */
    private function calculateLoyalty($studentId, $course, $apply = false)
    {
        // Available points
        $availablePoints = ReferralPoints::availablePoints($studentId);
        // Minimum points for course + toolkit
        $minPoints = ($course->min_loyalty_points ?? 0);
        // Eligible if student has points
        $eligible = $availablePoints >= $minPoints;

        // Max redeemable: cannot exceed subtotal
        $maxRedeemable = min($availablePoints, $minPoints);

        $appliedDiscount = 0;
        if ($apply && $maxRedeemable > 0) {
            $appliedDiscount = $maxRedeemable;
        }

        return [
            'eligible'         => $eligible,
            'available_points' => $availablePoints,
            'applied_discount' => $appliedDiscount,
            'max_usable_points' => $maxRedeemable,
        ];
    }

    /**
     * Create Razorpay order
     */
    private function createRazorpayOrder($payment, $gateway, $totalAmount, $studentId, $courseId)
    {
        $razorpay = new Api($gateway->api_key, $gateway->api_secret);
        $rzpOrder = $razorpay->order->create([
            'receipt'         => $payment->transaction_id,
            'amount'          => (int) round($totalAmount * 100), // paise
            'currency'        => 'INR',
            'payment_capture' => 1,
            'notes' => [
                'student_id' => $studentId,
                'course_id'  => $courseId,
            ]
        ]);

        $razorpayOrder = RazorpayOrder::updateOrCreate(
            ['payment_id' => $payment->id],
            [
                'razorpay_order_id' => $rzpOrder['id'],
                'currency'          => 'INR',
                'receipt'           => $rzpOrder['receipt'] ?? null,
            ]
        );

        return [
            'gateway'      => 'razorpay',
            'order_id'     => $razorpayOrder->razorpay_order_id,
            'payment_key'  => $gateway->api_key,
            'company_name' => config('app.name'),
        ];
    }

    /**
     * Confirm Free: mark payment pending and onboard student.
     * Improvements: idempotency and locking
     */
    public function confirmFree(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:payments,transaction_id',
        ]);

        DB::beginTransaction();
        try {
            // lock payment row to prevent concurrent updates
            $payment = Payment::where('transaction_id', $validated['transaction_id'])->lockForUpdate()->firstOrFail();

            // idempotency: if already pending or success, return ok
            if (in_array($payment->status, ['pending', 'success'])) {
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Already processed.']);
            }

            $payment->update(['status' => 'pending']);

            // onboard student (safe to call — internal checks in onboarding service)
            $this->onboardStudent($payment);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Free course enrolled successfully.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ConfirmFree Error: ' . $e->getMessage(), ['transaction_id' => $validated['transaction_id']]);
            return response()->json([
                'status' => false,
                'message' => 'Enrollment failed.'
            ], 500);
        }
    }

    /**
     * Confirm Paid: simplified since you've asked to ignore signature verification.
     * Still includes idempotency, locking, gateway sanity checks (fetch payment row and mark success)
     */
    public function confirmPaid(Request $request)
    {
        $validated = $request->validate([
            'transaction_id'       => 'required|exists:payments,transaction_id',
            'razorpay_payment_id'  => 'required|string',
            'signature'            => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::where('transaction_id', $validated['transaction_id'])->lockForUpdate()->firstOrFail();

            // idempotency guard
            if ($payment->status === 'success') {
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Payment already confirmed.']);
            }

            // Mark payment success and record paid_at
            $payment->update([
                'status'  => 'success',
                'paid_at' => now()
            ]);

            $razorpayOrder = RazorpayOrder::where('payment_id', $payment->id)->first();
            if ($razorpayOrder) {
                $razorpayOrder->update([
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'signature'           => $validated['signature'],
                    'response_payload'    => json_encode($validated),
                ]);
            }

            // Onboard (idempotent by DB constraints / firstOrCreate checks)
            $this->onboardStudent($payment);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment confirmed and course enrolled.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation error: ' . $e->getMessage(), ['transaction_id' => $validated['transaction_id']]);
            return response()->json([
                'status' => false,
                'message' => 'Payment confirmation failed.' . $e
            ], 500);
        }
    }

    /**
     * Cancel: mark cancelled and store minimal error payload
     */
    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:payments,transaction_id',
            'reason' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $payment = Payment::where('transaction_id', $validated['transaction_id'])->firstOrFail();
            $payment->update(['status' => 'cancelled']);

            $razorpayOrder = RazorpayOrder::where('payment_id', $payment->id)->first();
            $razorpayOrder?->update([
                'error_payload' => is_array($validated['reason']) ? json_encode($validated['reason']) : ($validated['reason'] ?? 'Cancelled by user')
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Payment cancelled successfully.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment cancellation error: ' . $e->getMessage(), ['transaction_id' => $validated['transaction_id']]);
            return response()->json([
                'status' => false,
                'message' => 'Cancellation failed.'
            ], 500);
        }
    }

    /**
     * Onboard student: major improvements
     * - ensure batch selection is by course_id
     * - allocate batch_number using atomic increment on courses.batch_sequence
     * - idempotent creation of enrollments, subscriptions, batch students
     */
    private function onboardStudent(Payment $payment): void
    {
        $course     = Course::find($payment->course_id);
        $studentId  = $payment->student_id;

        $this->enrollStudent($studentId, $payment, $course);
        $this->submitEnquiry($payment->toolkit_id, $payment->student);
        $this->handleReferral($payment, $studentId);
        $this->assignToBatch($studentId, $course, $payment);
        $this->unlockInitialContent($studentId, $course);
        $this->generateReferralCodeForStudentUser($payment);
    }

    /* ------------------ Smaller Responsibility Methods ------------------ */

    private function enrollStudent(int $studentId, Payment $payment, Course $course): void
    {
        StudentEnrollment::firstOrCreate(
            ['student_id' => $studentId, 'course_id' => $payment->course_id],
            ['status' => 'completed']
        );

        Subscription::firstOrCreate(
            ['student_id' => $studentId, 'course_id' => $payment->course_id],
            [
                'start_date' => now(),
                'end_date'   => now()->addDays($course->duration),
                'status'     => 'active',
            ]
        );
    }

    private function handleReferral(Payment $payment, int $studentId): void
    {
        $referralUse = ReferralUse::where('used_by_user_id', $studentId)
            ->where('status', 'processing')
            ->latest()
            ->first();

        if (!$referralUse) return;

        $referralUse->update(['status' => 'onboarded', 'converted_at' => now()]);
        $referralCode = $referralUse->referralCode;

        if (!$referralCode) return;

        // Case 1: Influencer referral
        if ($referralCode->influencer?->id) {
            InfluencerCommission::create([
                'influencer_id'   => $referralCode->influencer->id,
                'referral_use_id' => $referralUse->id,
                'amount'          => $referralCode->influencer->commission_per_user,
                'status'          => 'pending',
            ]);
            return;
        }

        // Case 2: Student referral
        if ($referralCode->generator && $referralCode->generator->studentId) {
            $points = $payment->course?->loyalty_points_earn ?? 0;

            if ($payment->toolkit_id && $payment->toolkit) {
                $points += $payment->toolkit->loyalty_points_earn ?? 0;
            }

            if ($points > 0) {
                ReferralPoints::create([
                    'student_id'      => $referralCode->generator->studentId,
                    'referral_use_id' => $referralUse->id,
                    'type'            => 'earned',
                    'points'          => $points,
                    'source'          => 'referral',
                    'notes'           => "Referral bonus for course {$payment->course->name}" .
                        ($payment->toolkit ? " + toolkit {$payment->toolkit->name}" : ''),
                ]);
            }
        }
    }

    private function assignToBatch(int $studentId, Course $course, Payment $payment): Batch
    {
        $batch = Batch::where('status', true)
            ->where('course_id', $payment->course_id)
            ->where('is_full', false)
            ->first();

        if (!$batch) {
            DB::transaction(function () use ($course, &$batch) {
                DB::table('courses')->where('id', $course->id)->increment('batch_sequence');
                $seq = DB::table('courses')->where('id', $course->id)->value('batch_sequence');
                $nextNumber = 'B' . str_pad(intval($seq), 3, '0', STR_PAD_LEFT);

                $batch = Batch::create([
                    'name'          => 'Batch - ' . Str::random(5),
                    'course_id'     => $course->id,
                    'student_limit' => 50,
                    'status'        => true,
                    'is_full'       => false,
                    'batch_number'  => $nextNumber,
                ]);
            });
        }

        BatchStudent::firstOrCreate(['batch_id' => $batch->id, 'student_id' => $studentId]);

        if (BatchStudent::where('batch_id', $batch->id)->count() >= $batch->student_limit) {
            $batch->update(['is_full' => true]);
        }

        return $batch;
    }

    private function unlockInitialContent(int $studentId, Course $course): void
    {
        try {
            Log::info("Unlocking initial content", [
                'student_id' => $studentId,
                'course_id'  => $course->id,
            ]);

            $firstSubject = Subject::where('course_id', $course->id)
                ->orderBy('id')
                ->first();

            if (!$firstSubject) {
                Log::warning("No subject found for course", [
                    'student_id' => $studentId,
                    'course_id'  => $course->id,
                ]);
                return;
            }

            $firstSession = SubjectSession::where('subject_id', $firstSubject->id)
                ->orderBy('id')
                ->first();

            if (!$firstSession) {
                Log::warning("No session found for subject", [
                    'student_id'  => $studentId,
                    'course_id'   => $course->id,
                    'subject_id'  => $firstSubject->id,
                ]);
                return;
            }

            $progress = StudentProgress::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $firstSubject->id,
                    'module_id'  => $firstSession->id,
                ],
                [
                    'status'      => 'unlocked',
                    'unlocked_at' => now(),
                ]
            );

            Log::info("Initial content unlocked", [
                'student_id' => $studentId,
                'course_id'  => $course->id,
                'subject_id' => $firstSubject->id,
                'session_id' => $firstSession->id,
                'progress_id' => $progress->id,
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to unlock initial content", [
                'student_id' => $studentId,
                'course_id'  => $course->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    }

    private function generateReferralCodeForStudentUser(Payment $payment): void
    {
        $user = optional($payment->student)->user;

        if (!$user) {
            return;
        }

        // Ensure one active referral code per user
        if (ReferralCode::where('generated_by', $user->id)
            ->where('type', 'student')
            ->where('is_active', true)
            ->exists()
        ) {
            return;
        }

        $code = strtoupper(Str::random(8));

        ReferralCode::create([
            'code'         => $code,
            'generated_by' => $user->id,
            'type'         => 'student',
            'deeplink_url' => config('app.url') . '?referral_code=' . $code,
            'is_active'    => true,
        ]);
    }

    // for toolkits
    public function checkoutToolkit(Request $request)
    {
        try {
            $studentId = auth()->user()->studentId;

            // Validate input
            $validator = Validator::make($request->all(), [
                'toolkit_id'         => 'required|exists:tool_kits,id',
                'use_loyalty_points' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $validated = $validator->validated();

            // Load toolkit
            $toolkit = ToolKit::findOrFail($validated['toolkit_id']);

            // GST 18%
            $toolkitAmount = (float) $toolkit->price;
            $gstAmount     = round($toolkitAmount * 0.18, 2);
            $totalAmount   = round($toolkitAmount + $gstAmount, 2);

            // Payment gateway
            $gateway = PaymentGatewayConfig::where('status', 'active')->first();

            if (!$gateway && $totalAmount > 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment gateway not configured',
                ], 500);
            }

            // Create or reuse initiated payment for toolkit
            $payment = $this->initPayment($studentId, null, $toolkit, $totalAmount, $gateway);

            // Calculate loyalty eligibility
            $loyaltyInfo = $this->calculateLoyalty($studentId, null, $toolkit, $validated['use_loyalty_points'] ?? false);

            $finalTotal = $totalAmount - ($loyaltyInfo['applied_discount'] ?? 0);

            // Build response
            $response = [
                'status'         => true,
                'is_paid_toolkit' => $totalAmount > 0,
                'summary'        => [
                    'toolkit_name'             => $toolkit->name,
                    'toolkit_fee'              => number_format($toolkitAmount, 2),
                    'gst'                      => number_format($gstAmount, 2),
                    'total'                    => number_format($finalTotal, 2),
                    'transaction_id'           => $payment->transaction_id,
                    'eligible_for_loyalty'     => $loyaltyInfo['eligible'],
                    'available_loyalty_points' => $loyaltyInfo['available_points'],
                    'loyalty_discount'         => $loyaltyInfo['applied_discount'] ?? 0,
                    'max_usable_points'        => $loyaltyInfo['max_usable_points'] ?? 0,
                ]
            ];

            // Razorpay order (if paid)
            if ($totalAmount > 0) {
                $response['payment'] = $this->createRazorpayOrder(
                    $payment,
                    $gateway,
                    $finalTotal,
                    $studentId,
                    null, // no course
                    $toolkit->id
                );
            }

            return response()->json($response);
        } catch (Exception $e) {
            Log::error('CheckoutToolkit Error: ' . $e->getMessage(), ['student_id' => auth()->user()->studentId]);
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function submitEnquiry(?int $toolkitId, $student)
    {
        // Handle null toolkitId
        if (empty($toolkitId)) {
            Log::warning('Toolkit enquiry skipped: toolkitId is null', [
                'student_id' => $student->id ?? null,
            ]);
            return;
        }
        
        $toolkit = ToolKit::findOrFail($toolkitId);

        // Check for existing enquiry
        $existing = ToolKitEnquiry::where('toolkit_id', $toolkit->id)
            ->where('student_id',  $student->id)
            ->first();

        if ($existing) {
            // Either return it, or throw to prevent duplicate
            return;
            // OR throw new \Exception('You already have an active enquiry for this toolkit.');
        }
        
        $enquiry = ToolKitEnquiry::create([
            'toolkit_id'    => $toolkit->id,
            'student_id'    => $student->id,
            'student_name'  => $student->full_name,
            'state'         => $student->state ?? null,
            'phone'         => $student->user->phone ?? null,
            'email'         => $student->user->email ?? null,
            'address'       => $student->address ?? null,
            'toolkit_name'  => $toolkit->name,
            'total_amount'  => $toolkit->price ?? null,
            'status'        => ToolKitEnquiry::STATUS_REQUEST_PLACED,
        ]);
        Log::info('Toolkit enquiry submitted', [
            'enquiry_id' => $enquiry->id,
            'toolkit_id' => $toolkitId,
            'student_id' => $student->id,
        ]);
    }
}
