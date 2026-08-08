@extends('layouts.app')
@section('title', 'لوحة ولي الأمر')
@section('page-title', 'لوحة ولي الأمر')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">لوحة ولي الأمر</h1>
    <p class="text-[var(--slate-blue)] mb-6">متابعة تقدم أبنائك في الحفظ</p>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @forelse($students as $student)
        <div class="main-content-section">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                <h2 class="text-xl font-bold text-[var(--cream)] flex items-center gap-2">
                    <i class="fas fa-user-graduate text-[var(--gold)]"></i>
                    {{ $student->user?->name ?? 'طالب' }}
                </h2>
                <a href="{{ route('parent.student.progress', $student) }}"
                   class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-sm flex items-center gap-2">
                    <i class="fas fa-chart-line"></i>
                    عرض التقدم
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">الحلقة</div>
                    <div class="text-[var(--cream)] font-bold">{{ $student->circle?->name ?? 'غير مسجل' }}</div>
                </div>
                <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">آخر تقييم</div>
                    <div class="text-[var(--cream)] font-bold">
                        @if($student->latestProgress)
                            {{ $student->latestProgress->rating ?? '—' }} / 5
                        @else
                            لا يوجد
                        @endif
                    </div>
                </div>
                <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">التسجيلات</div>
                    <div class="text-[var(--cream)] font-bold">{{ $student->submissions->count() }}</div>
                </div>
            </div>

            @if($student->submissions->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                            <tr>
                                <th class="py-3 px-4 text-right text-sm text-[var(--gold)] font-bold">السورة</th>
                                <th class="py-3 px-4 text-center text-sm text-[var(--gold)] font-bold">الدرجة</th>
                                <th class="py-3 px-4 text-center text-sm text-[var(--gold)] font-bold">الحالة</th>
                                <th class="py-3 px-4 text-left text-sm text-[var(--gold)] font-bold">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach($student->submissions as $submission)
                                <tr>
                                    <td class="py-3 px-4 text-[var(--cream)]">{{ $submission->surah_display ?? '—' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($submission->score)
                                            <span class="font-bold {{ $submission->score >= 70 ? 'text-green-400' : 'text-[var(--gold)]' }}">{{ $submission->score }}</span>
                                        @else
                                            <span class="text-[var(--slate-blue)]">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $statuses = ['pending'=>'text-yellow-400','reviewed'=>'text-green-400','accepted'=>'text-green-400','needs_work'=>'text-red-400'];
                                        @endphp
                                        <span class="{{ $statuses[$submission->status] ?? 'text-[var(--slate-blue)]' }}">{{ $submission->status }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-left text-[var(--slate-blue)] whitespace-nowrap">{{ $submission->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @empty
        <div class="main-content-section text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                <i class="fas fa-users text-3xl text-[var(--slate-blue)]"></i>
            </div>
            <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا يوجد أبناء مسجلين</h3>
            <p class="text-[var(--slate-blue)]">لم يتم ربط أي طالب بحسابك كولي أمر</p>
        </div>
    @endforelse
</div>
@endsection
