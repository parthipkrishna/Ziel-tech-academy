<?php

namespace App\Lms\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw("COALESCE(roles.role_name, 'No Role') as role"), // Use `role_name`
            'users.status',
            'users.type',
            'users.phone'
        )
        ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
        ->where('users.type', 'lms')
        ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Role', 'Status','Phone'];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role, // Already fetched using join
            $user->status,
            $user->phone ?? 'N/A',
            // $user->type
        ];
    }
}
