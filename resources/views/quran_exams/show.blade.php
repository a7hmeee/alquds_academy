@extends('layouts.app')
@section('title', 'نتائج الاختبار')
@section('page-title', 'نتائج الاختبار: {{ $exam->title }}')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">{{ $exam->title }}</h1>
            <p class="text-[var(--slate-blue)]">{{ $exam->circle?->name ?? '' }} — {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('quran-exams.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">النوع</div>
            <div class="text-[var(--cream)] font-bold">{{ $exam->exam_type }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">الدرجة القصوى</div>
            <div class="text-[var(--cream)] font-bold">{{ $exam->total_score }}</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">درجة النجاح</div>
            <div class="text-[var(--cream)] font-bold">{{ $exam->passing_score }}%</div>
        </div>
        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <div class="text-sm text-[var(--slate-blue)] mb-1">عدد المختبرين</div>
            <div class="text-[var(--cream)] font-bold">{{ $exam->results->count() }}</div>
        </div>
    </div>

    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-star text-[var(--gold)]"></i>
            النتائج
        </h3>

        {{-- Add Result Form --}}
        <div class="mb-6 p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
            <h4 class="text-sm font-bold text-[var(--cream)] mb-3">إضافة نتيجة</h4>
            <form method="POST" action="{{ route('quran-exams.results.store', $exam) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                @csrf
                <div>
                    <select name="student_id" required
                            class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="">الطالب</option>
                        @foreach($exam->circle?->students ?? [] as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" name="score" placeholder="الدرجة" max="{{ $exam->total_score }}"
                           class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <select name="status" required
                            class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="completed">مكتمل</option>
                        <option value="absent">غائب</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="teacher_notes" placeholder="ملاحظات"
                           class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 text-sm">
                    <i class="fas fa-plus"></i> إضافة
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[var(--border)]">
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">الطالب</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">الدرجة</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">النسبة</th>
                        <th class="py-3 px-4 text-center text-sm text-[var(--slate-blue)]">النتيجة</th>
                        <th class="py-3 px-4 text-right text-sm text-[var(--slate-blue)]">ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exam->results as $result)
                        <tr class="border-b border-[var(--border)] hover:bg-[var(--deep-green)]/5">
                            <td class="py-3 px-4 font-bold text-[var(--cream)]">{{ $result->student?->user?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-center text-[var(--cream)]">{{ $result->score ?? '—' }} / {{ $exam->total_score }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="{{ ($result->percentage ?? 0) >= $exam->passing_score ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $result->percentage ?? 0 }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($result->status === 'absent')
                                    <span class="px-3 py-1 rounded-full text-sm from-gray-900/20 to-gray-900/10 text-gray-300 border border-white/10 bg-gradient-to-r">غائب</span>
                                @elseif($result->passed)
                                    <span class="px-3 py-1 rounded-full text-sm from-green-900/20 to-emerald-900/10 text-green-300 border border-white/10 bg-gradient-to-r">ناجح</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm from-red-900/20 to-red-900/10 text-red-300 border border-white/10 bg-gradient-to-r">راسب</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-[var(--slate-blue)]">{{ $result->teacher_notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($exam->instructions)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-[var(--gold)]"></i>
            تعليمات
        </h3>
        <p class="text-[var(--cream)]">{{ $exam->instructions }}</p>
    </div>
    @endif
</div>
@endsection
