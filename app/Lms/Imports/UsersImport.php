<?php

namespace App\Lms\Imports;

use App\Models\User;
use App\Models\UserRole;
use App\Models\QC;
use App\Models\Tutor;
use App\Models\Role; // Assuming Role model exists
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Validate required fields
            if (!isset($row['name'], $row['email'], $row['password'], $row['role'], $row['type'])) {
                return null;
            }

            // Find role ID from name
            $role = Role::where('role_name', $row['role'])->first();
            if (!$role) {
                return null; // Skip if role not found
            }

            // Create User
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'status' => $row['status'],
                'type' => $row['type'], // Set default status if needed
                'phone' => $row['phone'] ?? null,
            ]);

            // Assign Role
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);

            // Handle QC/Tutor based on role
            switch ($role->name) {
                case 'QC':
                    QC::create(['user_id' => $user->id]);
                    break;
                case 'Tutor':
                    Tutor::create(['user_id' => $user->id]);
                    break;
            }

            return $user;
        });
    }
}

