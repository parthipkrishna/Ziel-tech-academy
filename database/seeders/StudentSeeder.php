<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\UserVerification;
use App\Models\UserRole;
use App\Models\Role;

class StudentSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // Student data (can later be 10, 100, or more)
            $studentsData = [
                ['first_name' => 'Rahul', 'last_name' => 'Menon', 'email' => 'rahul.menon@example.com', 'phone' => '9876500001', 'gender' => 'male', 'date_of_birth' => '2005-03-15', 'city' => 'Palakkad', 'state' => 'Kerala', 'country' => 'India', 'zip_code' => '678001', 'guardian_name' => 'Rajesh Menon', 'guardian_contact' => '9876543211'],
                ['first_name' => 'Anjali', 'last_name' => 'Nair', 'email' => 'anjali.nair@example.com', 'phone' => '9876500002', 'gender' => 'female', 'date_of_birth' => '2006-07-20', 'city' => 'Kozhikode', 'state' => 'Kerala', 'country' => 'India', 'zip_code' => '673001', 'guardian_name' => 'Suresh Nair', 'guardian_contact' => '9876543212'],
                ['first_name' => 'Vishnu', 'last_name' => 'Prasad', 'email' => 'vishnu.prasad@example.com', 'phone' => '9876500003', 'gender' => 'male', 'date_of_birth' => '2004-11-11', 'city' => 'Thrissur', 'state' => 'Kerala', 'country' => 'India', 'zip_code' => '680001', 'guardian_name' => 'Mohan Prasad', 'guardian_contact' => '9876543213'],
                // ... add more here later
            ];

            $roleId = Role::whereRaw('UPPER(role_name) = ?', ['STUDENT'])->value('id') ?? 5;

            // Find last admission number in DB
            $lastStudent = Student::latest('id')->first();
            $lastAdmissionNumber = $lastStudent
                ? (int) substr($lastStudent->admission_number, 3)
                : 0;

            // Start date for created_at & admission_date
            $currentDate = Carbon::today();

            foreach ($studentsData as $index => $data) {
                // Shift date every 3 students
                if ($index > 0 && $index % 3 === 0) {
                    $currentDate->addDay();
                }

                // Generate next admission number
                $lastAdmissionNumber++;
                $admissionNumber = 'ADM' . str_pad($lastAdmissionNumber, 4, '0', STR_PAD_LEFT);

                // Create User
                $user = User::create([
                    'name'       => "{$data['first_name']} {$data['last_name']}",
                    'email'      => $data['email'],
                    'phone'      => $data['phone'],
                    'type'       => 'lms',
                    'password'   => Hash::make('password123'),
                    'created_at' => $currentDate->copy(),
                    'updated_at' => $currentDate->copy(),
                ]);

                // Create Student
                Student::create([
                    'user_id'          => $user->id,
                    'first_name'       => $data['first_name'],
                    'last_name'        => $data['last_name'],
                    'admission_number' => $admissionNumber,
                    'date_of_birth'    => $data['date_of_birth'],
                    'gender'           => $data['gender'],
                    'address'          => $data['address'] ?? null,
                    'city'             => $data['city'],
                    'state'            => $data['state'],
                    'country'          => $data['country'],
                    'zip_code'         => $data['zip_code'],
                    'profile_photo'    => null,
                    'admission_date'   => $currentDate->copy(),
                    'guardian_name'    => $data['guardian_name'],
                    'guardian_contact' => $data['guardian_contact'],
                    'status'           => true,
                    'created_at'       => $currentDate->copy(),
                    'updated_at'       => $currentDate->copy(),
                ]);

                // Create UserVerification
                UserVerification::create([
                    'user_id'           => $user->id,
                    'email'             => $data['email'],
                    'phone'             => $data['phone'],
                    'is_email_verified' => false,
                    'is_phone_verified' => false,
                    'created_at'        => $currentDate->copy(),
                    'updated_at'        => $currentDate->copy(),
                ]);

                // Assign Role
                UserRole::create([
                    'user_id'    => $user->id,
                    'role_id'    => $roleId,
                    'created_at' => $currentDate->copy(),
                    'updated_at' => $currentDate->copy(),
                ]);
            }

            DB::commit();
            $this->command->info("✅ " . count($studentsData) . " students seeded successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Failed: " . $e->getMessage());
        }
    }
}
