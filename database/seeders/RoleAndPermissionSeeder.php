<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =====================================================
        // USERS PERMISSIONS
        // =====================================================
        $userPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.change-role',
        ];

        // =====================================================
        // ROLES PERMISSIONS
        // =====================================================
        $rolePermissions = [
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.update',
        ];

        // =====================================================
        // ORGANIZATIONS PERMISSIONS
        // =====================================================
        $organizationPermissions = [
            'organizations.view',
            'organizations.create',
            'organizations.edit',
            'organizations.update',
            'organizations.delete',
        ];

        // =====================================================
        // TEACHERS PERMISSIONS
        // =====================================================
        $teacherPermissions = [
            'teachers.view',
            'teachers.create',
            'teachers.edit',
            'teachers.update',
            'teachers.delete',
        ];

        // =====================================================
        // STUDENTS PERMISSIONS
        // =====================================================
        $studentPermissions = [
            'students.view',
            'students.create',
            'students.edit',
            'students.update',
            'students.delete',
            'students.view-all-profiles',
        ];

        // =====================================================
        // CIRCLES PERMISSIONS
        // =====================================================
        $circlePermissions = [
            'circles.view',
            'circles.create',
            'circles.edit',
            'circles.update',
            'circles.delete',
        ];

        // =====================================================
        // CIRCLE TEACHERS PERMISSIONS
        // =====================================================
        $circleTeacherPermissions = [
            'circle-teachers.create',
            'circle-teachers.update',
            'circle-teachers.delete',
        ];

        // =====================================================
        // CIRCLE STUDENTS PERMISSIONS
        // =====================================================
        $circleStudentPermissions = [
            'circle-students.create',
            'circle-students.update',
            'circle-students.delete',
        ];

        // =====================================================
        // STUDENT PROGRESS PERMISSIONS
        // =====================================================
        $progressPermissions = [
            'progress.view',
            'progress.create',
            'progress.edit',
            'progress.update',
            'progress.delete',
            'progress.view-own',
        ];

        // =====================================================
        // STUDENT SUBMISSIONS PERMISSIONS
        // =====================================================
        $submissionPermissions = [
            'submissions.view',
            'submissions.create',
            'submissions.download',
            'submissions.review',
            'submissions.update-review',
        ];

        // =====================================================
        // PROFILE PERMISSIONS
        // =====================================================
        $profilePermissions = [
            'profile.view',
            'profile.edit',
            'profile.delete',
        ];

        // =====================================================
        // MEMORIZATION ASSIGNMENTS PERMISSIONS
        // =====================================================
        $memorizationAssignmentPermissions = [
            'memorization-assignments.view',
            'memorization-assignments.create',
            'memorization-assignments.update',
            'memorization-assignments.delete',
            'memorization-assignments.review',
        ];

        // =====================================================
        // MEMORIZATION SESSIONS PERMISSIONS
        // =====================================================
        $memorizationSessionPermissions = [
            'memorization-sessions.view',
            'memorization-sessions.create',
            'memorization-sessions.update',
        ];

        // =====================================================
        // DASHBOARD PERMISSIONS
        // =====================================================
        $dashboardPermissions = [
            'dashboard.view',
            'student-dashboard.view',
            'student-dashboard.recordings-upload',
            'student-dashboard.recordings-list',
            'student-dashboard.circles-view',
            'student-dashboard.progress-view',
            'student-dashboard.join-circle',
        ];

        // =====================================================
        // Create All Permissions
        // =====================================================
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $organizationPermissions,
            $teacherPermissions,
            $studentPermissions,
            $circlePermissions,
            $circleTeacherPermissions,
            $circleStudentPermissions,
            $progressPermissions,
            $submissionPermissions,
            $profilePermissions,
            $dashboardPermissions,
            $memorizationAssignmentPermissions,
            $memorizationSessionPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // =====================================================
        // Create Roles
        // =====================================================
        $superAdmin = Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);

        // =====================================================
        // SUPER ADMIN - كل الصلاحيات
        // =====================================================
        $superAdmin->syncPermissions(Permission::all());

        // =====================================================
        // ADMIN - إدارة دوريه ولكن بدون صلاحيات السوبر
        // =====================================================
        $adminPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $organizationPermissions,
            $teacherPermissions,
            $studentPermissions,
            $circlePermissions,
            $circleTeacherPermissions,
            $circleStudentPermissions,
            $progressPermissions,
            $submissionPermissions,
            $profilePermissions,
            $dashboardPermissions,
            $memorizationAssignmentPermissions,
            $memorizationSessionPermissions
        );
        $admin->syncPermissions($adminPermissions);

        // =====================================================
        // TEACHER - إدارة الحلقات والطلاب والطلبات
        // =====================================================
        $teacherPermissionsList = [
            // Dashboard
            'dashboard.view',
            'student-dashboard.view',

            // عرض الطلاب في الحلقة
            'students.view',
            'students.view-all-profiles',

            // الحلقات
            'circles.view',

            // الطلاب في الحلقة
            'circle-students.create',
            'circle-students.update',
            'circle-students.delete',

            // التقدم
            'progress.view',
            'progress.create',
            'progress.edit',
            'progress.update',
            'progress.delete',

            // الطلبات
            'submissions.view',
            'submissions.review',
            'submissions.update-review',

            // الملف الشخصي
            'profile.view',
            'profile.edit',

            // مهام الحفظ
            'memorization-assignments.view',
            'memorization-assignments.create',
            'memorization-assignments.update',
            'memorization-assignments.delete',
            'memorization-assignments.review',

            // جلسات الحفظ
            'memorization-sessions.view',
            'memorization-sessions.create',
            'memorization-sessions.update',
        ];
        $teacher->syncPermissions($teacherPermissionsList);

        // =====================================================
        // STUDENT - عرض التقدم والطلبات فقط
        // =====================================================
        $studentPermissionsList = [
            // Dashboard
            'dashboard.view',
            'student-dashboard.view',
            'student-dashboard.recordings-upload',
            'student-dashboard.recordings-list',
            'student-dashboard.circles-view',
            'student-dashboard.progress-view',
            'student-dashboard.join-circle',

            // الطلبات
            'submissions.create',
            'submissions.view',
            'submissions.download',

            // التقدم - عرض تقدمه فقط
            'progress.view-own',

            // الملف الشخصي
            'profile.view',
            'profile.edit',

            // عرض مهام وجلسات الحفظ الخاصة به
            'memorization-assignments.view',
            'memorization-sessions.view',
        ];
        $student->syncPermissions($studentPermissionsList);

        $this->command->info('✓ تم إنشاء الأدوار والصلاحيات بالتفصيل!');
        $this->command->info('✓ Total Permissions: ' . Permission::count());
        $this->command->info('✓ Super Admin: جميع الصلاحيات');
        $this->command->info('✓ Admin: ' . count($adminPermissions) . ' صلاحية');
        $this->command->info('✓ Teacher: ' . count($teacherPermissionsList) . ' صلاحية');
        $this->command->info('✓ Student: ' . count($studentPermissionsList) . ' صلاحية');
    }
}
