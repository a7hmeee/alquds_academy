@extends('layouts.app')

@section('title', 'تقرير الطالب')
@section('page-title', '📋 تقرير الطالب: {{ $student->full_name }}')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm">🖨️ طباعة</button>
        </div>

        {{-- Student Info --}}
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-400 text-sm">الاسم</p>
                    <p class="text-white font-bold">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">البريد الإلكتروني</p>
                    <p class="text-white">{{ $student->user?->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">المعلم</p>
                    <p class="text-white">{{ $student->teacher?->user?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">مستوى التحفظ</p>
                    <p class="text-white">{{ $student->memorization_level ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">مستوى التجويد</p>
                    <p class="text-white">{{ $student->tajweed_level ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">الحالة</p>
                    <p class="text-emerald-400">{{ $student->status }}</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                <p class="text-gray-400 text-sm">إجمالي التسجيلات</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                <p class="text-gray-400 text-sm">قيد المراجعة</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ $stats['accepted'] }}</p>
                <p class="text-gray-400 text-sm">مقبول</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-purple-400">{{ $stats['avg_score'] }}</p>
                <p class="text-gray-400 text-sm">متوسط الدرجات</p>
            </div>
        </div>

        {{-- Progress Table --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 mb-6">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white">📈 تقدم الحفظ</h3>
            </div>
            <div class="p-4">
                @if(count($progress) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b border-gray-700">
                                    <th class="py-2 px-3">السورة</th>
                                    <th class="py-2 px-3">من آية</th>
                                    <th class="py-2 px-3">إلى آية</th>
                                    <th class="py-2 px-3">عدد التسجيلات</th>
                                    <th class="py-2 px-3">متوسط الدرجة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progress as $p)
                                    <tr class="border-b border-gray-700/50 text-white">
                                        <td class="py-2 px-3">{{ $p['surah'] }}</td>
                                        <td class="py-2 px-3">{{ $p['min_ayah'] }}</td>
                                        <td class="py-2 px-3">{{ $p['max_ayah'] }}</td>
                                        <td class="py-2 px-3">{{ $p['count'] }}</td>
                                        <td class="py-2 px-3 text-emerald-400">{{ $p['avg_score'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا توجد تسجيلات معتمدة بعد</p>
                @endif
            </div>
        </div>

        {{-- All Submissions --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white">🎤 جميع التسجيلات</h3>
            </div>
            <div class="p-4">
                @if(count($submissions) > 0)
                    <div class="space-y-2">
                        @foreach($submissions as $submission)
                            <div class="flex flex-wrap items-center justify-between gap-2 p-3 bg-gray-900/30 rounded-lg text-sm">
                                <div>
                                    <span class="text-white">{{ $submission->surah_display }}</span>
                                    <span class="text-gray-500 mx-1">|</span>
                                    <span class="text-gray-400">{{ $submission->juz_display }}</span>
                                    <span class="text-gray-500 mx-1">|</span>
                                    <span class="text-gray-400">آية {{ $submission->ayah_from }}{{ $submission->ayah_to ? ' → ' . $submission->ayah_to : '' }}</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    @if($submission->score)
                                        <span class="text-emerald-400">{{ $submission->score }}/100</span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded-full text-xs
                                        @if($submission->status === 'pending') bg-yellow-900/50 text-yellow-400
                                        @elseif($submission->status === 'accepted') bg-emerald-900/50 text-emerald-400
                                        @elseif($submission->status === 'needs_work') bg-red-900/50 text-red-400
                                        @else bg-blue-900/50 text-blue-400 @endif">
                                        {{ $submission->status }}
                                    </span>
                                    <span class="text-gray-500">{{ $submission->created_at?->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا توجد تسجيلات</p>
                @endif
            </div>
        </div>
    </div>
@endsection