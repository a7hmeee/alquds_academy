@extends('layouts.app')

@section('title', 'التقارير')
@section('page-title', '📊 التقارير')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('reports.system') }}" class="rounded-xl p-6 border transition text-center" style="background: linear-gradient(135deg, #0B1F14, #1A3828); border-color: #C9A84C;">
                <span class="text-4xl block mb-3">📋</span>
                <h3 class="text-lg font-bold" style="color: #C9A84C;">التقرير الشامل</h3>
                <p class="text-sm mt-2" style="color: #8A9A8E;">تقرير تحليلي شامل لكل النظام</p>
            </a>
            <a href="{{ route('students.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 transition text-center">
                <span class="text-4xl block mb-3">👨‍🎓</span>
                <h3 class="text-lg font-bold text-white">تقرير طالب</h3>
                <p class="text-gray-400 text-sm mt-2">عرض تقرير شامل لتقدم طالب</p>
            </a>
            <a href="{{ route('teachers.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-emerald-500 transition text-center">
                <span class="text-4xl block mb-3">👨‍🏫</span>
                <h3 class="text-lg font-bold text-white">تقرير معلم</h3>
                <p class="text-gray-400 text-sm mt-2">عرض أداء وتقدم المعلم</p>
            </a>
            <a href="{{ route('circles.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-amber-500 transition text-center">
                <span class="text-4xl block mb-3">🕌</span>
                <h3 class="text-lg font-bold text-white">تقرير حلقة</h3>
                <p class="text-gray-400 text-sm mt-2">عرض إحصائيات الحلقة</p>
            </a>
            <a href="{{ route('organizations.index') }}" class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-purple-500 transition text-center">
                <span class="text-4xl block mb-3">🏢</span>
                <h3 class="text-lg font-bold text-white">تقرير مؤسسة</h3>
                <p class="text-gray-400 text-sm mt-2">عرض تقارير المؤسسة</p>
            </a>
        </div>
    </div>
@endsection