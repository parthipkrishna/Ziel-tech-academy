<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OnlineStudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $lastStudent = Student::latest('id')->first();
        $lastAdmissionNumber = $lastStudent ? (int) substr($lastStudent->admission_number, 3) : 0;
        $newAdmissionNumber = 'ADM' . str_pad($lastAdmissionNumber + 12, 4, '0', STR_PAD_LEFT);

        $course = DB::table('courses')->where('name', $row['course_name'])->first();
        if (!$course) {
            throw new \Exception("Course '{$row['course_name']}' not found in the database.");
        }
        $userId = DB::table('users')->insertGetId([
            'name'  => $row['first_name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'type'  => 'web',
        ]);

        $studentId = DB::table('students')->insertGetId([
            'user_id'          => $userId,
            'first_name'       => $row['first_name'],
            'last_name'        => $row['last_name'],
            'admission_number' => $newAdmissionNumber,
            'admission_date'   => now(),
            'guardian_name'    => $row['guardian_name'] ?? null,
            'guardian_contact' => $row['guardian_contact'] ?? null,
        ]);

        return new StudentEnrollment([
            'student_id' => $studentId,
            'course_id'  => $course->id,
            'status'     => $row['status'] ?? 'enrolled',
        ]);
    }
}

