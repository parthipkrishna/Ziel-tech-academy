<?php

namespace App\Imports;

use App\Models\OfflineCourseEnrollment;
use App\Models\OfflineCourseType;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfflineStudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Insert user
        $userId = DB::table('users')->insertGetId([
            'name'  => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'type'  => 'web',
        ]);

        // Insert student
        $studentId = DB::table('offline_students')->insertGetId([
            'user_id'    => $userId,
            'first_name' => $row['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get offline course type
        $offlineCourseType = OfflineCourseType::where('offline_course_id', $row['course_id'])->first();

        // Store enrollment
        return new OfflineCourseEnrollment([
            'student_id'            => $studentId,
            'offline_course_id'     => $row['course_id'],
            'offline_course_type_id' => $offlineCourseType->id ?? null,
            'status'                => $row['status'] ?? 'enrolled',
        ]);
    }
}
