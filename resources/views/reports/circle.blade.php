@extends('layouts.app')

@section('title', 'تقرير الحلقة')
@section('page-title', '📋 تقرير الحلقة: {{ $circle->name }}')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm">🖨️ طباعة</button>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-400 text-sm">اسم الحلقة</p>
                    <p class="text-white font-bold">{{ $circle->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">النوع</p>
                    <p class="text-white">{{ $circle->type }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">الحالة</p>
                    <p class="text-emerald-400">{{ $circle->status }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">الجزء المرتبط</p>
                    <p class="text-white">{{ $circle->juz?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">الجهة</p>
                    <p class="text-white">{{ $circle->organization?->name ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-white">{{ $circle->circleStudents->count() }}</p>
                <p class="text-gray-400 text-sm">الطلاب</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-purple-400">{{ $totalSubmissions }}</p>
                <p class="text-gray-400 text-sm">التسجيلات</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ $avgScore }}</p>
                <p class="text-gray-400 text-sm">متوسط الدرجات</p>
            </div>
        </div>

        @if($circle->juz_id)
            <div class="bg-gray-800 rounded-xl border border-gray-700">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">📈 تقدم الطلاب في الجزء {{ $circle->juz?->name }}</h3>
                </div>
                <div class="p-4">
                    @if(count($studentsProgress) > 0)
                        @foreach($studentsProgress as $studentId => $progress)
                            @php $student = $circle->circleStudents->firstWhere('student_id', $studentId)?->student; @endphp
                            @if($student)
                                <div class="mb-4 p-3 bg-gray-900/30 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-white font-medium">{{ $student->full_name }}</span>
                                        <span class="text-emerald-400">{{ $progress['total_percent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $progress['total_percent'] }}%"></div>
                                    </div>
                                    <p class="text-gray-400 text-xs mt-1">{{ $progress['covered_ayahs'] }} / {{ $progress['total_ayahs'] }} آية</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">لا توجد بيانات تقدم</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection