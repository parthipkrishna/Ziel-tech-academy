<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $enrollments = StudentEnrollment::all();
        $exportData = [];

        foreach ($enrollments as $enroll) {
            $student = Student::where('id', $enroll->student_id)->first();
            $course = Course::where('id', $enroll->course_id)->first();
            $user = User::where('id', $student->user_id)->first();

            $exportData[] = [
                'Name' => $student->first_name ?? 'N/A',
                'Course' => $course->name ?? 'N/A',
                'Email' => $user->email ?? 'N/A',
                'Contact Number' => $user->phone ?? 'N/A',
                'Date of Entrolled' => $enroll->created_at->format('Y-m-d'),
                'Status' => $enroll->status ?? 'N/A',
                'Type' => $user->type,
            ];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return ['Name', 'Course', 'Email', 'Contact Number', 'Date of Entrolled', 'Status','Type'];
    }
}
