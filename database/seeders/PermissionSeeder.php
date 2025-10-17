<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['section_name' => 'User Management', 'permission_name' => 'user-management.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'users.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'users.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'users.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'users.delete', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'roles-permissions.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'roles-permissions.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'roles-permissions.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'User Management', 'permission_name' => 'roles-permissions.delete', 'status' => 1, 'type' => 'web'],

            // Campus Management
            ['section_name' => 'Campus Management', 'permission_name' => 'campus-management.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'social-media-link.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'social-media-link.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'social-media-link.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'social-media-link.delete', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'contact-info.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'contact-info.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'branches.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'branches.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'branches.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'branches.delete', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'campus-facilities.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'campus-facilities.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'campus-facilities.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Campus Management', 'permission_name' => 'campus-facilities.delete', 'status' => 1, 'type' => 'web'],

            // Academy Management
            ['section_name' => 'Academy Management', 'permission_name' => 'academy-management.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'quick-links.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'quick-links.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'quick-links.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'quick-links.delete', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'web-banners.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'web-banners.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'web-banners.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'web-banners.delete', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'company-info.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Academy Management', 'permission_name' => 'company-info.edit', 'status' => 1, 'type' => 'web'],

            // Online and Offline Courses, Events, Testimonials, etc.
            ['section_name' => 'Online Courses', 'permission_name' => 'online-courses.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-courses.view', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Online Courses', 'permission_name' => 'online-courses.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-courses.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-courses.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Online Courses', 'permission_name' => 'online-subjects.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-subjects.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-subjects.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-subjects.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Online Courses', 'permission_name' => 'online-students.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-students.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-students.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Online Courses', 'permission_name' => 'online-students.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-courses.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-courses.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-courses.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-subjects.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-subjects.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-subjects.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-subjects.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-students.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-students.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-students.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Offline Courses', 'permission_name' => 'offline-students.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Event Management', 'permission_name' => 'event-management.view', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Event Management', 'permission_name' => 'event.create', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Event Management', 'permission_name' => 'event.edit', 'status' => 1, 'type' => 'web'],
            ['section_name' => 'Event Management', 'permission_name' => 'event.delete', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Course Management', 'permission_name' => 'course-management.view', 'status' => 1, 'type' => 'web'],

            ['section_name' => 'Student Achievements', 'permission_name' => 'student-achievements.view', 'status' => 0, 'type' => 'web'],

            ['section_name' => 'Student Testimonials', 'permission_name' => 'student-testimonials.view', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Student Testimonials', 'permission_name' => 'student-testimonials.create', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Student Testimonials', 'permission_name' => 'student-testimonials.edit', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Student Testimonials', 'permission_name' => 'student-testimonials.delete', 'status' => 0, 'type' => 'web'],

            ['section_name' => 'Placements', 'permission_name' => 'placements.view', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Placements', 'permission_name' => 'placements.create', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Placements', 'permission_name' => 'placements.edit', 'status' => 0, 'type' => 'web'],
            ['section_name' => 'Placements', 'permission_name' => 'placements.delete', 'status' => 0, 'type' => 'web'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
