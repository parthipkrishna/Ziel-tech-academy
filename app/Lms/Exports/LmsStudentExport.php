<?php

namespace App\Lms\Exports;

use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Course;
use App\Models\User;
use App\Models\BatchStudent;
use App\Models\Batch;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LmsStudentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        Log::info('Starting export: fetching all student enrollments');
        $enrollments = StudentEnrollment::all();
        $exportData = [];

        foreach ($enrollments as $enroll) {
            $student = Student::where('id', $enroll->student_id)->first();
            $course = Course::where('id', $enroll->course_id)->first();
            $user = User::where('id', $student->user_id)->first();

            // Fetch batch (via batch_students pivot)
            $batchStudent = BatchStudent::where('student_id', $student->id)
                ->where('batch_id', '!=', null)
                ->first();
            $batchName = null;
            if ($batchStudent) {
                $batch = Batch::find($batchStudent->batch_id);
                $batchName = $batch ? $batch->name : null;
            }

            // Fetch payment info
            $payment = Payment::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->latest()
                ->first();

            $exportData[] = [
                'First Name'       => $student->first_name ?? 'N/A',
                'Last Name'        => $student->last_name ?? 'N/A',
                'Course'           => $course->name ?? 'N/A',
                'Email'            => $user->email ?? 'N/A',
                'Contact Number'   => $user->phone ?? 'N/A',
                'Admission Number' => $student->admission_number ?? 'N/A',
                'Admission Date'   => $student->admission_date ? Carbon::parse($student->admission_date)->format('Y-m-d') : 'N/A',
                'Date of Birth'    => $student->date_of_birth ?? 'N/A',
                'Gender'           => $student->gender ?? 'N/A',
                'Address'          => $student->address ?? 'N/A',
                'City'             => $student->city ?? 'N/A',
                'State'            => $student->state ?? 'N/A',
                'Country'          => $student->country ?? 'N/A',
                'Zip Code'         => $student->zip_code ?? 'N/A',
                'Guardian Name'    => $student->guardian_name ?? 'N/A',
                'Guardian Contact' => $student->guardian_contact ?? 'N/A',
                'Date of Enrolled' => Carbon::parse($enroll->created_at)->format('Y-m-d'),
                'Enrollment Status'=> $enroll->status ?? 'N/A',
                'Batch Name'       => $batchName ?? 'N/A',
                // 'Payment Method'   => $payment->payment_gateway ?? 'N/A',
                // 'Transaction ID'   => $payment->transaction_id ?? 'N/A',
                // 'Payment Status'   => $payment->status ?? 'N/A',
                // 'Type'             => $user->type ?? 'N/A',
            ];
        }
        Log::info('Export data collection complete');
        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Course',
            'Email',
            'Contact Number',
            'Admission Number',
            'Admission Date',
            'Date of Birth',
            'Gender',
            'Address',
            'City',
            'State',
            'Country',
            'Zip Code',
            'Guardian Name',
            'Guardian Contact',
            'Date of Enrolled',
            'Enrollment Status',
            'Batch Name',
            // 'Payment Method',
            // 'Transaction ID',
            // 'Payment Status',
            // 'Type'
        ];
    }
}

