@extends('layouts.app')

@section('title','صوتياتي')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">صوتياتي</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">قائمة التسجيلات التي رفعتها</p>
        </div>
        <a href="{{ url()->previous() ?: route('admin.dashboard') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع</a>
    </div>

    <div class="p-6 rounded-xl border bg-[var(--surface)]">
        @if($submissions->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-right text-[var(--slate-blue)]">
                            <th class="px-4 py-2">السورة</th>
                            <th class="px-4 py-2">من آية</th>
                            <th class="px-4 py-2">إلى آية</th>
                            <th class="px-4 py-2">الحلقة</th>
                            <th class="px-4 py-2">التاريخ</th>
                            <th class="px-4 py-2">الدرجة</th>
                            <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2">ملاحظات المعلم</th>
                            <th class="px-4 py-2 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $s)
                            <tr class="bg-[var(--dark-bg)]/50 rounded-lg overflow-hidden">
                                <td class="px-4 py-3 text-[var(--cream)] font-bold">{{ $s->surah_display ?? '—' }}</td>
                                <td class="px-4 py-3 text-[var(--cream)]">{{ $s->ayah_from ?? $s->ayah ?? '—' }}</td>
                                <td class="px-4 py-3 text-[var(--cream)]">{{ $s->ayah_to ?? '—' }}</td>
                                <td class="px-4 py-3 text-[var(--cream)]">
                                    <div class="font-semibold">{{ $s->circle->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $s->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    @if(!is_null($s->score))
                                        <span class="font-bold text-lg {{ $s->score >= 90 ? 'text-green-400' : ($s->score >= 70 ? 'text-blue-400' : ($s->score >= 50 ? 'text-yellow-400' : 'text-red-400')) }}">
                                            {{ $s->score }}
                                        </span>
                                        <span class="text-[var(--slate-blue)] text-xs">/ 100</span>
                                    @else
                                        <span class="text-[var(--slate-blue)]">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($s->status === 'pending')
                                        <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-200 text-xs">قيد المراجعة</span>
                                    @elseif($s->status === 'accepted')
                                        <span class="px-2 py-1 rounded bg-green-600/30 text-green-200 text-xs">مقبول ✓</span>
                                    @elseif($s->status === 'reviewed')
                                        <span class="px-2 py-1 rounded bg-blue-600/30 text-blue-200 text-xs">تم المراجعة</span>
                                    @elseif($s->status === 'needs_work')
                                        <span class="px-2 py-1 rounded bg-red-600/30 text-red-200 text-xs">يحتاج تحسين</span>
                                    @else
                                        <span class="text-[var(--slate-blue)]">{{ ucfirst($s->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[var(--slate-blue)] text-xs max-w-[200px]">
                                    @if($s->review_notes)
                                        {{ Str::limit($s->review_notes, 60) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('submissions.download', $s) }}" class="px-3 py-1 rounded bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] text-xs">تحميل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-[var(--slate-blue)]">
                <p class="text-lg">لم ترفع أي تسجيلات بعد</p>
                <p class="text-sm mt-2">اضغط على "رفع صوتية" في لوحة التحكم لبدء أول تسجيل</p>
            </div>
        @endif
    </div>
</div>
@endsection