<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\StudentSubmission;
use App\Models\TeacherProfile;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية مع بيانات حقيقية + بيانات تجريبية منظمة.
     */
    public function index()
    {
        $stats = [
            'students'   => max(StudentProfile::count(), 500),
            'courses'    => max(Circle::count(), 40),
            'teachers'   => max(TeacherProfile::count(), 25),
            'recordings' => max(StudentSubmission::count(), 10000),
        ];

        $teachers = collect();
        foreach (TeacherProfile::with('user')->get() as $t) {
            $teachers->push([
                'name'            => $t->user->name ?? 'معلم',
                'photo'           => $t->photo ? asset('storage/'.$t->photo) : null,
                'specialization'  => $t->specialization ?: 'إجازة في القرآن الكريم',
                'experience'      => $t->years_of_experience ?: 10,
                'academic_degree' => $t->academic_degree,
                'rating'          => 4.9,
            ]);
        }

        if ($teachers->count() < 3) {
            $demo = [
                [
                    'name'            => 'الشيخ أحمد السوسي',
                    'photo'           => null,
                    'specialization'  => 'مقرئ معتمد — رواية حفص',
                    'experience'      => 12,
                    'academic_degree' => 'bachelor',
                    'rating'          => 4.8,
                ],
                [
                    'name'            => 'الشيخة أسماء النابلسي',
                    'photo'           => null,
                    'specialization'  => 'أحكام التجويد والوقف والابتداء',
                    'experience'      => 9,
                    'academic_degree' => 'master',
                    'rating'          => 4.9,
                ],
            ];
            $teachers = $teachers->merge($demo);
        }

        $courses = [
            [
                'name'      => 'أساسيات التلاوة',
                'level'     => 'مبتدئ',
                'description' => 'ابدأ رحلتك في تعلم القرآن الكريم من مخارج الحروف وحتى التلاوة الصحيحة.',
                'lessons'   => 12,
                'duration'  => '6 أسابيع',
                'students'  => 128,
                'rating'    => 4.8,
                'gradient'  => 'from-emerald-premium-400 to-emerald-premium-600',
            ],
            [
                'name'      => 'أحكام التجويد',
                'level'     => 'متوسط',
                'description' => 'تعمّق في أحكام التجويد النظري والتطبيقي مع تطبيق عملي على كامل السور.',
                'lessons'   => 24,
                'duration'  => '10 أسابيع',
                'students'  => 96,
                'rating'    => 4.9,
                'gradient'  => 'from-emerald-premium-500 to-deep-green-700',
            ],
            [
                'name'      => 'إتقان التلاوة',
                'level'     => 'متقدم',
                'description' => 'متابعة مباشرة ومراجعة دقيقة لتصل إلى إتقان التلاوة مع معلم متخصص.',
                'lessons'   => 30,
                'duration'  => 'متابعة مباشرة',
                'students'  => 64,
                'rating'    => 4.9,
                'gradient'  => 'from-deep-green-700 to-emerald-premium-800',
            ],
        ];

        $recentReviews = StudentSubmission::with(['student', 'surahModel', 'reviewer.user'])
            ->whereNotNull('score')
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact('stats', 'teachers', 'courses', 'recentReviews'));
    }
}
