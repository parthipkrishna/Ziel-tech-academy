<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permission;

class LMSPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'User & Access Management' => [
                ['section' => 'Users', 'slug' => 'users'],
                ['section' => 'Roles and Permissions', 'slug' => 'roles'],
            ],
            'Student Management' => [
                ['section' => 'Student Records', 'slug' => 'students'],
                ['section' => 'Enrollments', 'slug' => 'enrollments'],
                ['section' => 'Student Feedback', 'slug' => 'feedback', 'only' => ['update','view']],
                ['section' => 'Student Batches', 'slug' => 'batches'],
            ],
            'Course & Learning Management' => [
                ['section' => 'Courses', 'slug' => 'courses'],
                ['section' => 'Sections', 'slug' => 'course-sections'],
                ['section' => 'Subjects', 'slug' => 'subjects'],
            ],
             'Subjects Management' => [
                ['section' => 'Subject Sessions', 'slug' => 'subject-sessions'],
                ['section' => 'Recorded Session', 'slug' => 'recorded-session', 'only' => ['view']],
                ['section' => 'Manage Live class', 'slug' => 'manage-live-classes', 'only' => ['view']],
                ['section' => 'Manage Assessment', 'slug' => 'manage-assessment', 'only' => ['view']],
                ['section' => 'Manage Exams', 'slug' => 'manage-exams', 'only' => ['view']],
                ['section' => 'Videos', 'slug' => 'videos'],
                ['section' => 'Recorded Videos', 'slug' => 'recorded-videos'],
                ['section' => 'Live Classes', 'slug' => 'live-classes'],
                ['section' => 'Live Class Reports', 'slug' => 'live-class-reports', 'only' => ['view']],
                ['section' => 'Assessments', 'slug' => 'assessments'],
                ['section' => 'Assessment Reports', 'slug' => 'assessment-reports', 'only' => ['view']],
                ['section' => 'Exams', 'slug' => 'exams'],
                ['section' => 'Exam Reports', 'slug' => 'exam-reports', 'only' => ['view']],
            ],
             'Payments & Subscriptions' => [
                ['section' => 'Manage Payments', 'slug' => 'manage-payments', 'only' => ['view']],
                ['section' => 'Payments Records', 'slug' => 'payments','only' => ['view']],
                ['section' => 'Payment Config', 'slug' => 'payment-config'],
                ['section' => 'Payment Links', 'slug' => 'payment-links','only' => ['view']],
                ['section' => 'Subscriptions', 'slug' => 'subscriptions','only' => ['view']],
            ],
            'Marketing Management' => [
                ['section' => 'Notifications', 'slug' => 'notifications', 'only' => ['create','view']],
                ['section' => 'Promotions', 'slug' => 'promotions'],
                ['section' => 'Discounts', 'slug' => 'discounts'],
                ['section' => 'FAQs', 'slug' => 'faqs'],
            ],
            'Mobile & App Content Management' => [
                ['section' => 'Banners', 'slug' => 'banners'],
                ['section' => 'Top Achievers', 'slug' => 'top-achievers'],
                ['section' => 'Important Links', 'slug' => 'important-links'],
            ],
            'Referral & Affiliate System' => [
                ['section' => 'Influencers', 'slug' => 'influencers'],
                ['section' => 'Referral Payments', 'slug' => 'referral-payments', 'only' => ['create', 'update']],
            ],
            'Toolkit management' => [
                ['section' => 'Toolkits', 'slug' => 'toolkits'],
                ['section' => 'Toolkit Enquiries', 'slug' => 'toolkit-enquiries', 'only' => ['view', 'update']],
            ],
        ];

        $defaultActions = ['view', 'create', 'update', 'delete'];

        foreach ($groups as $groupName => $items) {
            // Create section-level permission
            $sectionPermission = Str::slug($groupName) . '-view';

            Permission::updateOrCreate(
                ['permission_name' => $sectionPermission, 'type' => 'lms'],
                ['section_name' => $groupName, 'status' => true]
            );

            // Create item-level permissions
            foreach ($items as $item) {
                $actions = $item['only'] ?? $defaultActions;

                foreach ($actions as $action) {
                    Permission::updateOrCreate(
                        ['permission_name' => "{$item['slug']}.$action", 'type' => 'lms'],
                        ['section_name' => $item['section'], 'status' => true]
                    );
                }
            }
        }

        echo "LMS Permissions seeded with customized actions.\n";
        Role::updateOrCreate(
        ['role_name' => 'Super Admin', 'type' => 'lms'],
        ['system_reserved' => true, 'status' => true]
    );

    echo "Super Admin role seeded with system_reserved = 1.\n";
    }
}