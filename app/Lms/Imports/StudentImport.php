<?php

namespace App\Lms\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\UserVerification;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\StudentEnrollment;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\Subscription;

class StudentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            return DB::transaction(function () use ($row) {

                // --- Find or Create User & Student ---
                $user = User::firstOrCreate(
                    ['email' => $row['email']],
                    [
                        'name'     => $row['first_name'] . ' ' . $row['last_name'],
                        'phone'    => $row['phone'] ?? null,
                        'type'     => 'lms',
                        'password' => $row['password'] ? bcrypt($row['password']) : null,
                    ]
                );
                
                // Get the admission number for a new student. This is more robust.
                $newAdmissionNumber = null;
                if ($user->wasRecentlyCreated) {
                    $lastStudent = Student::latest('id')->first();
                    $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
                    $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 1, 4, '0', STR_PAD_LEFT);

                    // Assign student role for new users
                    $role = Role::whereRaw('UPPER(role_name) = ?', ['STUDENT'])->first();
                    UserRole::firstOrCreate(
                        ['user_id' => $user->id, 'role_id' => $role->id ?? 5]
                    );

                    // Create user verification entry for new users
                    UserVerification::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'email'             => $row['email'],
                            'phone'             => $row['phone'] ?? null,
                            'is_email_verified' => false,
                            'is_phone_verified' => false,
                        ]
                    );
                }

                $student = Student::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name'       => $row['first_name'],
                        'last_name'        => $row['last_name'],
                        'date_of_birth'    => $row['date_of_birth'] ?? null,
                        'gender'           => $row['gender'] ?? null,
                        'address'          => $row['address'] ?? null,
                        'city'             => $row['city'] ?? null,
                        'state'            => $row['state'] ?? null,
                        'country'          => $row['country'] ?? null,
                        'zip_code'         => $row['zip_code'] ?? null,
                        'admission_number' => $newAdmissionNumber ?? ($user->studentProfile->admission_number ?? null), // Use existing or new number
                        'admission_date'   => now(),
                        'guardian_name'    => $row['guardian_name'] ?? null,
                        'guardian_contact' => $row['guardian_contact'] ?? null,
                        'status'           => true,
                    ]
                );

                $studentId = $student->id;

                // --- Get Course ---
                $course = DB::table('courses')->where('name', $row['course_name'])->first();
                if (!$course) {
                    throw new \Exception("Course '{$row['course_name']}' not found in the database.");
                }

                // Find or create enrollment
                $enrollment = StudentEnrollment::firstOrCreate(
                    ['student_id' => $studentId, 'course_id' => $course->id],
                    ['status' => 'enrolled']
                );
                
                // --- Payment & Subscription Logic ---
                $paymentStatus = 'initiated';
                $isFree = $course->course_fee == 0;

                // Mark payment as completed if the course is free, the payment status is explicit, or the enrollment status is 'enrolled'
                if ($isFree || (isset($row['payment_status']) && strtolower($row['payment_status']) === 'success') || (isset($row['status']) && strtolower($row['status']) === 'enrolled')) {
                    $paymentStatus = 'success';
                }

                if (!$isFree) {
                    // Check if a payment record already exists for this enrollment
                    $existingPayment = Payment::where('student_id', $studentId)
                                                ->where('course_id', $course->id)
                                                ->first();
                    
                    if (!$existingPayment) {
                        DB::table('payments')->insert([
                            'student_id'      => $studentId,
                            'course_id'       => $course->id,
                            'amount'          => $course->course_fee,
                            'currency'        => 'INR',
                            'paid_at'         => $paymentStatus === 'success' ? now() : null,
                            'status'          => $paymentStatus,
                            'payment_gateway' => $row['payment_method'] ?? 'manual',
                            'transaction_id'  => $row['transaction_id'] ?: 'trans_' . substr(md5(uniqid('', true)), 0, 14),
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                    }
                }

                if ($paymentStatus === 'success') {
                    // Update enrollment status to completed
                    $enrollment->update(['status' => 'completed']);

                    $endDate = null;
                    if (!is_null($course->course_end_date)) {
                        $months = floor($course->course_end_date);
                        $fraction = $course->course_end_date - $months;

                        $endDate = Carbon::now()->addMonths($months);

                        if ($fraction > 0) {
                            $days = round($fraction * 30);
                            $endDate->addDays($days);
                        }
                    }

                    Subscription::updateOrCreate(
                        ['student_id' => $studentId, 'course_id' => $course->id],
                        [
                            'start_date' => now(),
                            'end_date'   => $endDate,
                            'status'     => 'active',
                        ]
                    );
                }

                // --- Batch Handling ---
                if (!empty($row['batch_name'])) {
                    $batch = Batch::where('course_id', $course->id)
                        ->where('name', $row['batch_name'])
                        ->first();

                    if (!$batch) {
                        $latestBatch = Batch::where('course_id', $course->id)
                            ->orderByDesc(DB::raw('CAST(SUBSTRING(batch_number, 2) AS UNSIGNED)'))
                            ->first();

                        $lastNumber = $latestBatch ? (int) substr($latestBatch->batch_number, 1) : 0;
                        $nextNumber = 'B' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

                        $batch = Batch::create([
                            'name'          => $row['batch_name'], // keep exact name from Excel
                            'course_id'     => $course->id,
                            'student_limit' => 50,
                            'status'        => true,
                            'is_full'       => false,
                            'batch_number'  => $nextNumber,
                        ]);
                    }

                    BatchStudent::firstOrCreate([
                        'batch_id'   => $batch->id,
                        'student_id' => $studentId,
                    ]);

                    if ($batch->students()->count() >= $batch->student_limit) {
                        $batch->update(['is_full' => true]);
                    }
                }

                return null;
            });
            } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during import', [
                'row'   => $row,
                'error' => $e->getMessage()
            ]);

            if (str_contains($e->getMessage(), 'users_email_unique')) {
                throw new \Exception("The email '{$row['email']}' is already registered.");
            }
            if (str_contains($e->getMessage(), 'users_phone_unique')) {
                throw new \Exception("The phone number '{$row['phone']}' is already registered.");
            }

            throw new \Exception("Import failed due to a database constraint. Please check the data.");
        } catch (\Exception $e) {
            Log::error('Unexpected error during import', [
                'row'   => $row,
                'error' => $e->getMessage()
            ]);

            throw new \Exception(
                "Import failed for student: " . ($row['first_name'] ?? 'N/A') . " " . ($row['last_name'] ?? 'N/A') .
                ". Reason: " . $e->getMessage()
            );
        }

    }
}