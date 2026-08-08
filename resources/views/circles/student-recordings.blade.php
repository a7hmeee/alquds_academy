@extends('layouts.app')

@section('title', 'تسجيلات الطالب — ' . ($student->full_name ?? $student->user?->name))

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">
                <i class="fas fa-microphone text-[var(--gold)] ml-2"></i>
                تسجيلات الطالب: {{ $student->full_name ?? $student->user?->name ?? 'بدون اسم' }}
            </h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">
                الحلقة: {{ $circle->name }} — عدد التسجيلات: {{ $submissions->count() }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('circles.show', $circle) }}"
               class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                ← رجوع للحلقة
            </a>
        </div>
    </div>

    {{-- Student Progress Section — Juz-based --}}
    @if($juzProgress && $juzProgress['total_ayahs'] > 0)
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                <i class="fas fa-chart-line text-green-400"></i>
            </div>
            <h2 class="text-lg font-bold text-[var(--cream)]">
                تقدم الحفظ — {{ $circle->juz?->name ?? 'الجزء ' . $circle->juz_id }}
            </h2>
        </div>

        {{-- شريط التقدم الكلي --}}
        <div class="bg-[var(--dark-bg)]/30 rounded-lg p-4 border border-[var(--border)]">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[var(--cream)] font-semibold">النسبة الكلية</span>
                <span class="text-[var(--gold)] font-bold text-2xl">{{ $juzProgress['total_percent'] }}%</span>
            </div>
            <div class="w-full h-3 bg-[var(--dark-bg)] rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $juzProgress['total_percent'] }}%; background: {{ $juzProgress['total_percent'] >= 100 ? '#10B981' : ($juzProgress['total_percent'] >= 50 ? '#FFD700' : '#60a5fa') }};"></div>
            </div>
            <div class="flex justify-between text-xs text-[var(--slate-blue)] mt-2">
                <span>{{ $juzProgress['covered_ayahs'] }} آية من {{ $juzProgress['total_ayahs'] }}</span>
                <span>{{ $juzProgress['surahs']->where('approved_count', '>', 0)->count() }} / {{ $juzProgress['surahs']->count() }} سورة</span>
            </div>
        </div>

        {{-- تفصيل كل سورة --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($juzProgress['surahs'] as $surah)
            <div class="bg-[var(--dark-bg)]/30 rounded-lg p-4 border {{ $surah['percent'] >= 100 ? 'border-green-500/30' : ($surah['percent'] > 0 ? 'border-[var(--gold)]/20' : 'border-[var(--border)]') }}">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2">
                        @if($surah['percent'] >= 100)
                            <i class="fas fa-check-circle text-green-400"></i>
                        @elseif($surah['percent'] > 0)
                            <i class="fas fa-spinner text-[var(--gold)]"></i>
                        @else
                            <i class="far fa-circle text-[var(--slate-blue)]"></i>
                        @endif
                        <span class="text-[var(--cream)] font-bold">{{ $surah['surah_name'] }}</span>
                    </div>
                    <span class="font-bold text-sm {{ $surah['percent'] >= 100 ? 'text-green-400' : ($surah['percent'] > 0 ? 'text-[var(--gold)]' : 'text-[var(--slate-blue)]') }}">{{ $surah['percent'] }}%</span>
                </div>
                <div class="w-full h-2 bg-[var(--dark-bg)] rounded-full overflow-hidden mb-2">
                    <div class="h-full rounded-full" style="width: {{ $surah['percent'] }}%; background: {{ $surah['percent'] >= 100 ? '#10B981' : '#FFD700' }};"></div>
                </div>
                <div class="flex justify-between text-xs text-[var(--slate-blue)]">
                    <span>{{ $surah['covered_ayahs'] }} / {{ $surah['total_ayahs'] }} آية</span>
                    @if($surah['avg_score'])
                    <span>متوسط: <span class="{{ $surah['avg_score'] >= 90 ? 'text-green-400' : ($surah['avg_score'] >= 70 ? 'text-blue-400' : 'text-yellow-400') }} font-semibold">{{ $surah['avg_score'] }}</span>/100</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recordings List --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-[var(--gold)]/20 flex items-center justify-center">
                <i class="fas fa-list text-[var(--gold)]"></i>
            </div>
            <h2 class="text-lg font-bold text-[var(--cream)]">قائمة التسجيلات</h2>
        </div>

        @if($submissions->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 6px;">
                <thead>
                    <tr class="text-right text-[var(--slate-blue)]">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">السورة</th>
                        <th class="px-4 py-2">الجزء</th>
                        <th class="px-4 py-2">من آية</th>
                        <th class="px-4 py-2">إلى آية</th>
                        <th class="px-4 py-2">استماع</th>
                        <th class="px-4 py-2">الدرجة</th>
                        <th class="px-4 py-2">ملاحظات المعلم</th>
                        <th class="px-4 py-2">الحالة</th>
                        <th class="px-4 py-2">التاريخ</th>
                        <th class="px-4 py-2">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $i => $sub)
                    <tr class="bg-[var(--dark-bg)]/20 rounded-lg">
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 text-[var(--cream)] font-bold">{{ $sub->surah_display ?? '—' }}</td>
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $sub->juz_display ?? '—' }}</td>
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $sub->ayah_from ?? $sub->ayah ?? '—' }}</td>
                        <td class="px-4 py-3 text-[var(--cream)]">{{ $sub->ayah_to ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($sub->file_path)
                                <audio controls preload="none" style="height: 32px; max-width: 200px;">
                                    <source src="{{ asset('storage/' . $sub->file_path) }}" type="audio/mpeg">
                                </audio>
                            @else
                                <span class="text-[var(--slate-blue)]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(!is_null($sub->score))
                                <span class="font-bold text-lg {{ $sub->score >= 90 ? 'text-green-400' : ($sub->score >= 75 ? 'text-blue-400' : ($sub->score >= 60 ? 'text-yellow-400' : ($sub->score >= 50 ? 'text-orange-400' : 'text-red-400'))) }}">
                                    {{ $sub->score }}
                                </span>
                                <span class="text-[var(--slate-blue)] text-xs">/ 100</span>
                            @else
                                <span class="text-[var(--slate-blue)]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[var(--slate-blue)] text-xs max-w-[200px]">
                            {{ Str::limit($sub->review_notes, 50) ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($sub->status === 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-300">بانتظار</span>
                            @elseif($sub->status === 'accepted')
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-300">مقبول</span>
                            @elseif($sub->status === 'needs_work')
                                <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-300">يحتاج تحسين</span>
                            @elseif($sub->status === 'reviewed')
                                <span class="px-2 py-1 text-xs rounded bg-blue-500/20 text-blue-300">تمت المراجعة</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $sub->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            @if(auth()->user()?->teacherProfile || auth()->user()?->hasRole('super admin'))
                                <a href="{{ route('submissions.review', $sub) }}"
                                   class="px-3 py-1 rounded bg-[var(--gold)] text-[var(--dark-bg)] font-bold text-xs whitespace-nowrap">
                                    <i class="fas fa-star ml-1"></i> تقييم
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- متوسط الدرجات --}}
        @php $avgScore = $submissions->whereNotNull('score')->avg('score'); @endphp
        @if($avgScore)
        <div class="mt-4 p-4 rounded-lg bg-[var(--dark-bg)]/30 border border-[var(--border)] flex flex-wrap items-center gap-4">
            <i class="fas fa-chart-bar text-[var(--gold)] text-xl"></i>
            <div>
                <span class="text-[var(--cream)] font-bold">متوسط الدرجات:</span>
                <span class="text-2xl font-bold mr-2 {{ $avgScore >= 90 ? 'text-green-400' : ($avgScore >= 75 ? 'text-blue-400' : ($avgScore >= 60 ? 'text-yellow-400' : 'text-red-400')) }}">
                    {{ number_format($avgScore, 1) }}
                </span>
                <span class="text-[var(--slate-blue)]">/ 100</span>
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-8">
            <i class="fas fa-microphone-slash text-4xl text-[var(--slate-blue)] mb-4"></i>
            <p class="text-[var(--slate-blue)]">لا توجد تسجيلات لهذا الطالب في هذه الحلقة</p>
        </div>
        @endif
    </div>

    {{-- Teacher Notes (if any reviewed submissions) --}}
    @php $reviewed = $submissions->whereNotNull('review_notes'); @endphp
    @if($reviewed->count())
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                <i class="fas fa-comment-dots text-blue-400"></i>
            </div>
            <h2 class="text-lg font-bold text-[var(--cream)]">ملاحظات المعلم</h2>
        </div>
        @foreach($reviewed as $sub)
        <div class="bg-[var(--dark-bg)]/20 rounded-lg p-4 border border-[var(--border)]">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[var(--cream)] font-bold text-sm">{{ $sub->surah_display ?? '' }} - آية {{ $sub->ayah_from ?? $sub->ayah ?? '' }}</span>
                <span class="text-xs text-[var(--slate-blue)]">{{ $sub->created_at->format('Y-m-d') }}</span>
            </div>
            <p class="text-[var(--slate-blue)] text-sm">{{ $sub->review_notes }}</p>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
