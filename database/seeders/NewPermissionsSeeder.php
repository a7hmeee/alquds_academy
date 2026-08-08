<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NewPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByGroup = [
            'memorization-assignments' => ['view', 'create', 'update', 'delete', 'review'],
            'memorization-sessions' => ['view', 'create', 'update', 'delete'],
            'memorization-mistakes' => ['view', 'create', 'update', 'delete'],
            'revision-plans' => ['view', 'create', 'update', 'delete'],
            'attendance' => ['view', 'create', 'update', 'delete'],
            'quran-exams' => ['view', 'create', 'update', 'delete'],
            'certificates' => ['view', 'create'],
            'achievements' => ['view', 'create'],
            'parent-portal' => ['view'],
        ];

        $allNewPermissions = [];
        foreach ($permissionsByGroup as $group => $actions) {
            foreach ($actions as $action) {
                $permName = "{$group}.{$action}";
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                $allNewPermissions[] = $permName;
            }
        }

        // Assign to roles
        $superAdmin = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $parent = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // Super Admin gets all
        $superAdmin->givePermissionTo($allNewPermissions);

        // Admin gets all
        $admin->givePermissionTo($allNewPermissions);

        // Teacher permissions
        $teacherPermissions = [
            'memorization-assignments.view', 'memorization-assignments.create', 'memorization-assignments.update', 'memorization-assignments.review',
            'memorization-sessions.view', 'memorization-sessions.create', 'memorization-sessions.update',
            'memorization-mistakes.view', 'memorization-mistakes.create', 'memorization-mistakes.update',
            'revision-plans.view', 'revision-plans.create', 'revision-plans.update',
            'attendance.view', 'attendance.create', 'attendance.update',
            'quran-exams.view', 'quran-exams.create', 'quran-exams.update',
            'achievements.view', 'achievements.create',
            'certificates.view',
        ];
        $teacher->givePermissionTo($teacherPermissions);

        // Student permissions
        $studentPermissions = [
            'memorization-assignments.view',
            'memorization-sessions.view',
            'memorization-mistakes.view',
            'revision-plans.view',
            'attendance.view',
            'quran-exams.view',
            'achievements.view',
            'certificates.view',
        ];
        $student->givePermissionTo($studentPermissions);

        // Parent permissions
        $parentPermissions = [
            'parent-portal.view',
            'attendance.view',
            'achievements.view',
            'certificates.view',
        ];
        $parent->givePermissionTo($parentPermissions);

        $this->command->info('New permissions seeded successfully!');
    }
}
