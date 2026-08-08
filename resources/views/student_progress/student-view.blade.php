@extends('layouts.student')

@section('title','تقدمي في ' . $circle->name)
@section('page-title', 'تقدمي')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">📖 تقدمي في الحلقة</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">{{ $circle->name }}</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع للوحة الطالب</a>
    </div>

    {{-- Current Progress Card (أبرز المعلومات) --}}
    @if($latestProgress)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Current Position --}}
        <div class="p-6 rounded-xl border border-[var(--gold)]/50 bg-gradient-to-br from-[var(--surface)] to-[var(--dark-bg)]/50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm text-[var(--slate-blue)] font-semibold">📍 موضعك الحالي</h3>
                    <div class="mt-3 text-lg font-bold text-[var(--gold)]">
                        جزء <span class="text-xl">{{ $latestProgress->juz ?? '-' }}</span>
                    </div>
                    <div class="text-sm text-[var(--cream)] mt-2">
                        {{ $latestProgress->surah ?? '-' }} • آية {{ $latestProgress->ayah ?? '-' }}
                    </div>
                    <div class="text-xs text-[var(--slate-blue)] mt-2">
                        📅 {{ $latestProgress->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <div class="text-5xl opacity-30">📖</div>
            </div>
        </div>

        {{-- Teacher Assignment --}}
        <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <h3 class="text-sm text-[var(--slate-blue)] font-semibold">👨‍🏫 معلمك</h3>
            @if($latestProgress->teacher)
                <div class="mt-3">
                    <div class="font-bold text-[var(--cream)]">{{ $latestProgress->teacher->user?->name ?? '-' }}</div>
                    <div class="text-xs text-[var(--slate-blue)] mt-1">{{ $latestProgress->teacher->user?->email ?? '-' }}</div>
                </div>
            @else
                <div class="mt-3 text-[var(--slate-blue)]">لم يتم تعيين معلم بعد</div>
            @endif
        </div>

        {{-- Next Assignment (ما بعده) --}}
        @if($progresses->count() > 1)
        <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
            <h3 class="text-sm text-[var(--slate-blue)] font-semibold">🎯 الآتي</h3>
            @php $nextProgress = $progresses[1] ?? null; @endphp
            @if($nextProgress)
                <div class="mt-3">
                    <div class="text-sm text-[var(--cream)]">
                        جزء {{ $nextProgress->juz ?? '-' }}<br>
                        {{ $nextProgress->surah ?? '-' }} آية {{ $nextProgress->ayah ?? '-' }}
                    </div>
                    <div class="text-xs text-[var(--slate-blue)] mt-2">
                        {{ $nextProgress->created_at->format('Y-m-d') }}
                    </div>
                </div>
            @else
                <div class="mt-3 text-[var(--slate-blue)]">-</div>
            @endif
        </div>
        @endif
    </div>

    {{-- Current Task Notes --}}
    @if($latestProgress->notes)
    <div class="p-6 rounded-xl border-2 border-[var(--gold)]/30 bg-gradient-to-r from-[var(--surface)] to-[var(--dark-bg)]/30">
        <h3 class="text-sm text-[var(--gold)] font-bold mb-3">✍️ ملاحظات المعلم</h3>
        <div class="text-[var(--cream)] leading-relaxed">
            {{ $latestProgress->notes }}
        </div>
    </div>
    @endif

    @endif

    {{-- All Progress Records --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4">📋 سجل التقدم الكامل</h3>

        @if($progresses->count())
            <div class="space-y-3">
                @foreach($progresses as $progress)
                    <div class="p-4 rounded-lg bg-[var(--dark-bg)]/50 border border-[var(--border)]/30 hover:border-[var(--gold)]/30 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex-1">
                                <div class="text-[var(--cream)] font-semibold">
                                    جزء {{ $progress->juz ?? '-' }} • {{ $progress->surah ?? '-' }} آية {{ $progress->ayah ?? '-' }}
                                </div>
                                @if($progress->notes)
                                    <div class="text-sm text-[var(--slate-blue)] mt-1">
                                        {{ Str::limit($progress->notes, 100) }}
                                    </div>
                                @endif
                                <div class="text-xs text-[var(--slate-blue)] mt-2 flex gap-3 flex-wrap">
                                    <span>📅 {{ $progress->created_at->format('Y-m-d H:i') }}</span>
                                    @if($progress->teacher)
                                        <span>👨‍🏫 {{ $progress->teacher->user?->name ?? '-' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="px-3 py-1 rounded-full bg-[var(--gold)]/20 text-[var(--gold)] text-xs font-semibold whitespace-nowrap">
                                #{{ $loop->iteration }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-[var(--slate-blue)]">
                <p class="text-lg">لم يتم تسجيل أي تقدم بعد</p>
                <p class="text-sm mt-2">سيقوم معلمك بتسجيل تقدمك قريباً إن شاء الله!</p>
            </div>
        @endif
    </div>

    {{-- My Submissions in this Circle --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4">🎙️ تسجيلاتي في هذه الحلقة</h3>

        @php
            $mySubmissions = App\Models\StudentSubmission::where('student_id', $student->id)
                ->where('circle_id', $circle->id)
                ->latest()
                ->limit(10)
                ->get();
        @endphp

        @if($mySubmissions->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-left text-[var(--slate-blue)]">
                            <th class="px-4 py-2">التاريخ</th>
                            <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2">تقييم المعلّم</th>
                            <th class="px-4 py-2 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mySubmissions as $submission)
                            <tr class="bg-[var(--dark-bg)]/50 rounded-lg overflow-hidden">
                                <td class="px-4 py-3 text-[var(--cream)]">{{ $submission->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3">
                                    @if($submission->status === 'pending')
                                        <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-200 text-xs">قيد المراجعة</span>
                                    @elseif($submission->status === 'accepted')
                                        <span class="px-2 py-1 rounded bg-green-600/30 text-green-200 text-xs">مقبول ✓</span>
                                    @elseif($submission->status === 'reviewed')
                                        <span class="px-2 py-1 rounded bg-blue-600/30 text-blue-200 text-xs">تم المراجعة</span>
                                    @elseif($submission->status === 'needs_work')
                                        <span class="px-2 py-1 rounded bg-red-600/30 text-red-200 text-xs">يحتاج تحسين</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-600/30 text-gray-200 text-xs">{{ ucfirst($submission->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($submission->rating)
                                        <div class="flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $submission->rating)
                                                    <span class="text-[var(--gold)]">⭐</span>
                                                @else
                                                    <span class="text-gray-600">⭐</span>
                                                @endif
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-[var(--slate-blue)]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('submissions.download', $submission) }}" class="px-3 py-1 rounded bg-[var(--gold)]/20 text-[var(--gold)] text-xs">تحميل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('student-submissions.index') }}" class="text-[var(--gold)] text-sm hover:underline">عرض جميع التسجيلات →</a>
            </div>
        @else
            <div class="text-center py-6 text-[var(--slate-blue)]">
                <p>لم تقم برفع أي تسجيلات بعد</p>
                <a href="{{ route('circles.submissions.create', $circle) }}" class="mt-3 inline-block px-4 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">رفع تسجيل الآن</a>
            </div>
        @endif
    </div>
</div>
@endsection
