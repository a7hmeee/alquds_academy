<?php

namespace App\Helpers;

class NavigationHelper
{
    public static function getNavigation()
    {
        return [
            [
                'title' => 'الرئيسية',
                'items' => [
                    ['name' => 'لوحة التحكم', 'route' => 'admin.dashboard', 'icon' => 'fas fa-home'],
                ]
            ],
            [
                'title' => 'القرآن الكريم',
                'items' => [
                    ['name' => 'السور', 'route' => 'quran.index', 'icon' => 'fas fa-book-quran'],
                    ['name' => 'الأجزاء', 'route' => 'quran.juz.index', 'icon' => 'fas fa-layer-group'],
                    ['name' => 'البحث', 'route' => 'quran.search', 'icon' => 'fas fa-search'],
                    ['name' => 'إحصائيات', 'route' => 'quran.statistics', 'icon' => 'fas fa-chart-bar'],
                ]
            ],
            [
                'title' => 'الحلقات والتسجيلات',
                'items' => [
                    ['name' => 'الحلقات', 'route' => 'circles.index', 'icon' => 'fas fa-mosque'],
                    ['name' => 'التسجيلات', 'route' => 'student-submissions.index', 'icon' => 'fas fa-microphone'],
                    ['name' => 'جلسات الحلقات', 'route' => 'circles.index', 'icon' => 'fas fa-calendar-alt'],
                ]
            ],
            [
                'title' => 'المهام والمراجعة',
                'items' => [
                    ['name' => 'مهام الحفظ', 'route' => 'memorization-assignments.index', 'icon' => 'fas fa-tasks'],
                    ['name' => 'جلسات التسميع', 'route' => 'memorization-sessions.index', 'icon' => 'fas fa-chalkboard-teacher'],
                    ['name' => 'خطط المراجعة', 'route' => 'revision-plans.index', 'icon' => 'fas fa-calendar-check'],
                ]
            ],
            [
                'title' => 'الاختبارات',
                'items' => [
                    ['name' => 'الاختبارات', 'route' => 'quran-exams.index', 'icon' => 'fas fa-question-circle'],
                    ['name' => 'الإنجازات', 'route' => 'achievements.index', 'icon' => 'fas fa-trophy'],
                    ['name' => 'الشهادات', 'route' => 'certificates.index', 'icon' => 'fas fa-certificate'],
                ]
            ],
            [
                'title' => 'التقارير',
                'items' => [
                    ['name' => 'التقرير الشامل', 'route' => 'reports.system', 'icon' => 'fas fa-chart-pie'],
                    ['name' => 'جميع التقارير', 'route' => 'reports.index', 'icon' => 'fas fa-file-alt'],
                ]
            ],
            [
                'title' => 'إدارة المستخدمين',
                'items' => [
                    ['name' => 'المعلمين', 'route' => 'teachers.index', 'icon' => 'fas fa-chalkboard-teacher'],
                    ['name' => 'الطلاب', 'route' => 'students.index', 'icon' => 'fas fa-user-graduate'],
                    ['name' => 'المستخدمين', 'route' => 'users.index', 'icon' => 'fas fa-users'],
                    ['name' => 'الأدوار', 'route' => 'roles.index', 'icon' => 'fas fa-shield-alt'],
                    ['name' => 'المؤسسات', 'route' => 'organizations.index', 'icon' => 'fas fa-building'],
                ]
            ],
            [
                'title' => 'حسابي',
                'items' => [
                    ['name' => 'الملف الشخصي', 'route' => 'profile.edit', 'icon' => 'fas fa-user-cog'],
                ]
            ],
        ];
    }

    public static function canAccess($routeName)
    {
        try {
            if (!auth()->check() || !\Illuminate\Support\Facades\Route::has($routeName)) {
                return false;
            }

            $user = auth()->user();
            $isStudent = $user->hasRole('student');
            $isAdmin = $user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('teacher');

            // Student routes — only for students
            $studentRoutePrefixes = ['student.', 'recordings.', 'circles.my-progress', 'circles.submissions.', 'submissions.download'];
            foreach ($studentRoutePrefixes as $prefix) {
                if (str_starts_with($routeName, $prefix) && !$isStudent) {
                    return false;
                }
            }

            // Admin routes — only for admin/teacher
            $adminRoutePrefixes = ['admin.', 'circles.', 'teachers.', 'students.', 'users.', 'roles.', 'organizations.', 'reports.', 'student-progress.', 'student-submissions.'];
            foreach ($adminRoutePrefixes as $prefix) {
                if (str_starts_with($routeName, $prefix) && !$isAdmin) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
