<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Student;
use App\Models\UserRole;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedUsers();
        $this->seedUserRoles();
        $this->seedCourses();
        $this->seedCourseSections();
        $this->seedStudents();
        $this->seedSubjects();
    }

    private function seedRoles()
    {
        $roles = ['Super Admin', 'Admin', 'Tutor', 'QC', 'Student'];
        foreach ($roles as $index => $role) {
            Role::create([
                'role_name' => $role,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function seedUsers()
    {
        $users = [
            ['name' => 'Musavir P', 'email' => 'student@gmail.com', 'phone' => '1234567890'],
            ['name' => 'Admin User', 'email' => 'admin@gmail.com', 'phone' => '1234567891'],
            ['name' => 'Super Admin User', 'email' => 'superadmin@gmail.com', 'phone' => '1234567892'],
            ['name' => 'Tutor User', 'email' => 'tutor@gmail.com', 'phone' => '1234567893'],
            ['name' => 'QC User', 'email' => 'qc@gmail.com', 'phone' => '1234567894'],
        ];
        
        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'password' => Hash::make('12345678'),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function seedUserRoles()
    {
        $userRoles = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 3]
        ];

        foreach ($userRoles as $userRole) {
            UserRole::create([
                'user_id' => $userRole['user_id'],
                'role_id' => $userRole['role_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function seedCourses()
{
    $courses = [
        [
            'id' => 1,
            'name' => 'Smartphone Engineering Course',
            'short_description' => 'Learn the skills and techniques to repair smartphones.',
            'full_description' => 'In this course, you will learn the in-depth processes involved in repairing smartphones including hardware and software troubleshooting.',
            'target_audience' => '15 to 45 Years Old, LEVEL 1&2 Technicians',
            'languages' => json_encode(["Hindi", "Bengali", "Telugu", "Marathi", "Gujarati", "Kannada"]),
            'status' => 1,
            'course_fee' => 15000.00,
            'toolkit_fee' => 13500.00,
            'cover_image_web' => null,
            'cover_image_mobile' => null,
            'total_hours' => 100,
            'tags' => json_encode(["Smartphone Repair", "Technician Training"]),
            'created_at' => '2025-03-01 06:55:43',
            'updated_at' => '2025-03-01 06:55:43'
        ],
        [
            'id' => 2,
            'name' => 'iPhone Updation Class',
            'short_description' => 'Master the art of iPhone software and hardware updates.',
            'full_description' => 'A detailed course focusing on iPhone updation methods and troubleshooting techniques.',
            'target_audience' => '15 to 45 Years Old, LEVEL 1&2 Technicians',
            'languages' => json_encode(["Hindi", "Bengali", "Telugu", "Marathi", "Gujarati", "Kannada"]),
            'status' => 1,
            'course_fee' => 20000.00,
            'toolkit_fee' => 12500.00,
            'cover_image_web' => null,
            'cover_image_mobile' => null,
            'total_hours' => 50,
            'tags' => json_encode(["iPhone Repair", "Software Update", "Hardware Fix"]),
            'created_at' => '2025-03-01 06:55:43',
            'updated_at' => '2025-03-01 06:55:43'
        ]
    ];
    
    foreach ($courses as $course) {
        Course::create($course);
    }
}


    private function seedCourseSections()
    {
        $sections = ['Recorded Videos', 'Daily Assignments', 'Toolkit Practice', 'Live Sessions', 'Online Exam'];
        foreach ($sections as $section) {
            CourseSection::create([
                'name' => $section,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function seedStudents()
    {
        Student::create([
            'user_id' => 1, // Assuming user_id 1 is 'Musavir P'
            'first_name' => 'Musavir',
            'last_name' => 'P',
            'admission_number' => 'ADM0001',
            'date_of_birth' => Carbon::parse('1995-10-05'),
            'gender' => 'male',
            'address' => 'Parambil house, Kondoorkkara Po',
            'city' => 'Kochi',
            'state' => 'Kerala',
            'country' => 'India',
            'zip_code' => '682309',
            'profile_photo' => null,
            'admission_date' => Carbon::parse('2023-02-09'),
            'guardian_name' => 'John Doe',
            'guardian_contact' => '9876543210',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function seedSubjects()
    {
        $subjects = [
            [
                'name' => 'ELECTRONICS',
                'short_desc' => 'Basic Concepts',
                'desc' => 'Study of electronic circuits',
                'status' => 1,
                'course_id' => 1,
                'total_hours' => 40,
                'mobile_thumbnail' => 'thumbnails/EuKpHW6HMjXG3tLayGZio5wZUQpXxvsX4s34BQ6I.png',
                'web_thumbnail' => 'img1_web.jpg',
                'created_at' => '2025-03-03 09:56:46',
                'updated_at' => '2025-03-03 10:20:26'
            ],
            [
                'name' => 'REVERSE ENGINEERING',
                'short_desc' => 'System Breakdown',
                'desc' => 'Understanding reverse engineering techniques',
                'status' => 1,
                'course_id' => 1,
                'total_hours' => 35,
                'mobile_thumbnail' => 'thumbnails/R8qQVWvEELxkmjPg8xzv7Lh4ajp0RMLRdjuwD6yI.png',
                'web_thumbnail' => 'img2_web.jpg',
                'created_at' => '2025-03-03 09:56:46',
                'updated_at' => '2025-03-03 10:20:55'
            ],
            [
                'name' => 'TROUBLESHOOTING',
                'short_desc' => 'Fixing Issues',
                'desc' => 'Identifying and solving hardware/software problems',
                'status' => 1,
                'course_id' => 1,
                'total_hours' => 30,
                'mobile_thumbnail' => 'thumbnails/7tLSJQmuGkFa15PlDugqoSFQALW7UgbFkLmOY1BO.png',
                'web_thumbnail' => 'img3_web.jpg',
                'created_at' => '2025-03-03 09:56:46',
                'updated_at' => '2025-03-03 10:21:08'
            ],
            [
                'id' => 4,
                'name' => 'SOFTWARE',
                'short_desc' => 'Programming Basics',
                'desc' => 'Overview of programming and software development',
                'status' => 1,
                'course_id' => 1,
                'total_hours' => 50,
                'mobile_thumbnail' => 'thumbnails/XEUfa9hUFFM1vNKdXbdYIHZqND5wIGdm7ml3vIpH.png',
                'web_thumbnail' => 'img4_web.jpg',
                'created_at' => '2025-03-03 09:56:46',
                'updated_at' => '2025-03-03 10:21:20'
            ],
            [
                'name' => 'REWORK METHODS',
                'short_desc' => 'Circuit Repairs',
                'desc' => 'Methods for repairing and modifying circuits',
                'status' => 1,
                'course_id' => 1,
                'total_hours' => 20,
                'mobile_thumbnail' => 'thumbnails/gz8P9L2iehPtP45Dh1tWh13A2emN0FGTiZXlSXnO.png',
                'web_thumbnail' => 'img5_web.jpg',
                'created_at' => '2025-03-03 09:56:46',
                'updated_at' => '2025-03-03 10:21:33'
            ],
            [
                'name' => 'ELECTRONICS',
                'short_desc' => 'Basic Concepts',
                'desc' => 'Study of electronic circuits',
                'status' => 1,
                'course_id' => 2,
                'total_hours' => 40,
                'mobile_thumbnail' => 'thumbnails/EuKpHW6HMjXG3tLayGZio5wZUQpXxvsX4s34BQ6I.png',
                'web_thumbnail' => 'img1_web.jpg',
                'created_at' => '2025-03-03 09:58:12',
                'updated_at' => '2025-03-03 09:58:12'
            ],
            [
                'id' => 7,
                'name' => 'iPhone Sections',
                'short_desc' => 'Component Study',
                'desc' => 'Understanding iPhone hardware sections',
                'status' => 1,
                'course_id' => 2,
                'total_hours' => 35,
                'mobile_thumbnail' => 'img2.jpg',
                'web_thumbnail' => 'img2_web.jpg',
                'created_at' => '2025-03-03 09:58:12',
                'updated_at' => '2025-03-03 09:58:12'
            ],
            [
                'name' => 'TROUBLESHOOTING',
                'short_desc' => 'Fixing Issues',
                'desc' => 'Identifying and solving hardware/software problems',
                'status' => 1,
                'course_id' => 2,
                'total_hours' => 30,
                'mobile_thumbnail' => 'thumbnails/7tLSJQmuGkFa15PlDugqoSFQALW7UgbFkLmOY1BO.png',
                'web_thumbnail' => 'img3_web.jpg',
                'created_at' => '2025-03-03 09:58:12',
                'updated_at' => '2025-03-03 09:58:12'
            ],
            [
                'name' => 'REWORK METHODS',
                'short_desc' => 'Circuit Repairs',
                'desc' => 'Methods for repairing and modifying circuits',
                'status' => 1,
                'course_id' => 2,
                'total_hours' => 20,
                'mobile_thumbnail' => 'thumbnails/gz8P9L2iehPtP45Dh1tWh13A2emN0FGTiZXlSXnO.png',
                'web_thumbnail' => 'img4_web.jpg',
                'created_at' => '2025-03-03 09:58:12',
                'updated_at' => '2025-03-03 09:58:12'
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
