@extends('layouts.app')

@section('title','سجل التقدّم — ' . $circle->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">سجل التقدّم — {{ $circle->name }}</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">قائمة سجلات التقدّم داخل الحلقة</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-0 sm:flex-none">
                <input id="progressSearch" type="search" placeholder="ابحث عن طالب، معلم أو ملاحظة..."
                       class="pr-10 pl-4 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-lg text-[var(--cream)] w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[var(--slate-blue)]">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <a href="{{ route('circles.progress.create', $circle) }}"
               class="px-4 py-2 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i> إضافة سجل جديد
            </a>

            <a href="{{ route('circles.show', $circle) }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع للحلقة</a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 animate-slide-in">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200 animate-slide-in">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Auto-calculated Juz Progress --}}
    @if($circle->juz_id && $circle->circleStudents->count())
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                <i class="fas fa-chart-line text-green-400"></i>
            </div>
            <h2 class="text-lg font-bold text-[var(--cream)]">
                التقدم التلقائي من التسجيلات — {{ $circle->juz?->name ?? 'الجزء ' . $circle->juz_id }}
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 6px;">
                <thead>
                    <tr class="text-right text-[var(--slate-blue)]">
                        <th class="px-4 py-2">الطالب</th>
                        <th class="px-4 py-2">نسبة الحفظ</th>
                        <th class="px-4 py-2">الآيات المحفوظة</th>
                        <th class="px-4 py-2">السور المكتملة</th>
                        <th class="px-4 py-2">التفاصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($circle->circleStudents as $cs)
                        @php
                            $prog = $autoProgress[$cs->student?->id] ?? null;
                            $pct = $prog['total_percent'] ?? 0;
                            $completedSurahs = $prog ? $prog['surahs']->where('percent', '>=', 100)->count() : 0;
                            $totalSurahs = $prog ? $prog['surahs']->count() : 0;
                        @endphp
                        <tr class="bg-[var(--dark-bg)]/30 rounded-lg">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[var(--cream)]">{{ $cs->student?->user?->name ?? $cs->student?->full_name ?? 'بدون اسم' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-2.5 bg-[var(--dark-bg)] rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background: {{ $pct >= 100 ? '#10B981' : ($pct >= 50 ? '#FFD700' : '#60a5fa') }};"></div>
                                    </div>
                                    <span class="font-bold text-sm {{ $pct >= 100 ? 'text-green-400' : ($pct >= 50 ? 'text-[var(--gold)]' : 'text-blue-400') }}">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-[var(--cream)]">{{ $prog['covered_ayahs'] ?? 0 }} / {{ $prog['total_ayahs'] ?? 0 }}</td>
                            <td class="px-4 py-3 text-[var(--cream)]">{{ $completedSurahs }} / {{ $totalSurahs }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('circles.students.recordings', [$circle, $cs->student]) }}"
                                   class="px-3 py-1 rounded bg-[var(--gold)] text-[var(--dark-bg)] font-bold text-xs">
                                    <i class="fas fa-eye ml-1"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Manual Progress Records --}}

    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)]">
        @if($progresses->count())
            <div class="overflow-x-auto">
                <table id="progressTable" class="w-full text-sm table-auto border-separate" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-left text-[var(--slate-blue)]">
                            <th class="px-4 py-2">الطالب</th>
                            <th class="px-4 py-2">المعلّم</th>
                            <th class="px-4 py-2">التقدّم (جزء • سورة • آية)</th>
                            <th class="px-4 py-2">ملاحظات</th>
                            <th class="px-4 py-2">تاريخ</th>
                            <th class="px-4 py-2 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progresses as $p)
                            <tr class="group bg-[var(--dark-bg)]/50 hover:bg-[var(--dark-bg)]/70 rounded-lg overflow-hidden transition-colors" data-search="{{ strtolower($p->student?->user?->name . ' ' . ($p->teacher?->user?->name ?? '') . ' ' . $p->notes) }}">
                                <td class="px-4 py-3 font-bold text-[var(--cream)]">
                                    <div>{{ $p->student?->user?->name ?? $p->student?->full_name ?? 'بدون اسم' }}</div>
                                    <div class="text-xs text-[var(--slate-blue)]">{{ $p->student?->user?->email ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-[var(--slate-blue)]">
                                    @if($p->teacher)
                                        <div>{{ $p->teacher->user?->name ?? $p->teacher->full_name }}</div>
                                        <div class="text-xs">{{ $p->teacher->user?->email ?? '-' }}</div>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $p->juz ?? '-' }} • {{ $p->surah ?? '-' }} • {{ $p->ayah ?? '-' }}</td>
                                <td class="px-4 py-3 text-[var(--slate-blue)]">{{ Str::limit($p->notes, 100) }}</td>
                                <td class="px-4 py-3">{{ $p->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('student-progress.edit', $p) }}" class="px-3 py-1 rounded bg-[var(--deep-green)] text-white">تعديل</a>
                                    <form method="POST" action="{{ route('student-progress.destroy', $p) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-600 text-white" onclick="return confirm('حذف السجل؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script>
                (function(){
                    const input = document.getElementById('progressSearch');
                    const rows = Array.from(document.querySelectorAll('#progressTable tbody tr'));
                    input?.addEventListener('input', function(e){
                        const q = e.target.value.trim().toLowerCase();
                        rows.forEach(r => {
                            const txt = r.getAttribute('data-search') || r.innerText.toLowerCase();
                            r.style.display = txt.includes(q) ? '' : 'none';
                        });
                    });
                })();
            </script>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[var(--dark-bg)]/40 mx-auto mb-4">
                    <i class="fas fa-book-open text-2xl text-[var(--gold)]"></i>
                </div>
                <div class="text-lg font-semibold text-[var(--cream)]">لا يوجد سجلات تقدّم لهذه الحلقة حتى الآن</div>
                <div class="text-sm text-[var(--slate-blue)] mt-2">اضغط على "إضافة سجل جديد" لإدخال أول سجل تقدّم</div>
            </div>
        @endif
    </div>
</div>
@endsection
