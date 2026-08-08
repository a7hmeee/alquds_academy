@extends('layouts.app')

@section('title', 'تقرير المعلم')
@section('page-title', '📋 تقرير المعلم: {{ $teacher->user?->name }}')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm">🖨️ طباعة</button>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-gray-400 text-sm">الاسم</p>
                    <p class="text-white font-bold">{{ $teacher->user?->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">البريد</p>
                    <p class="text-white">{{ $teacher->user?->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">التخصص</p>
                    <p class="text-white">{{ $teacher->specialization ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">الدرجة العلمية</p>
                    <p class="text-white">{{ $teacher->academic_degree ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalStudents }}</p>
                <p class="text-gray-400 text-sm">الطلاب</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-purple-400">{{ $totalSubmissions }}</p>
                <p class="text-gray-400 text-sm">إجمالي التسجيلات</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-yellow-400">{{ $pendingReviews }}</p>
                <p class="text-gray-400 text-sm">بانتظار المراجعة</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ number_format($avgScore ?? 0, 1) }}</p>
                <p class="text-gray-400 text-sm">متوسط الدرجات</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white">👨‍🎓 الطلاب</h3>
            </div>
            <div class="p-4">
                @if(count($students) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b border-gray-700">
                                    <th class="py-2 px-3">الاسم</th>
                                    <th class="py-2 px-3">البريد</th>
                                    <th class="py-2 px-3">مستوى التحفظ</th>
                                    <th class="py-2 px-3">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $s)
                                    <tr class="border-b border-gray-700/50 text-white">
                                        <td class="py-2 px-3">
                                            <a href="{{ route('reports.student', $s) }}" class="text-emerald-400 hover:text-emerald-300">{{ $s->full_name }}</a>
                                        </td>
                                        <td class="py-2 px-3 text-gray-400">{{ $s->user?->email ?? '—' }}</td>
                                        <td class="py-2 px-3">{{ $s->memorization_level ?? '—' }}</td>
                                        <td class="py-2 px-3">
                                            <span class="px-2 py-0.5 rounded-full text-xs {{ $s->status === 'active' ? 'bg-emerald-900/50 text-emerald-400' : 'bg-red-900/50 text-red-400' }}">
                                                {{ $s->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا يوجد طلاب</p>
                @endif
            </div>
        </div>
    </div>
@endsection