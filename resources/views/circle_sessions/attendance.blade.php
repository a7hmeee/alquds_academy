@extends('layouts.app')
@section('title', 'تسجيل الحضور')
@section('page-title', 'تسجيل الحضور - {{ $session->title ?? \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تسجيل الحضور</h1>
            <p class="text-[var(--slate-blue)]">{{ $circle->name }} — {{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('circle-sessions.show', [$circle, $session]) }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                <span class="font-medium">يوجد أخطاء في النموذج:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-content-section">
        <form method="POST" action="{{ route('circle-sessions.attendance.save', [$circle, $session]) }}">
            @csrf

            <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                        <tr>
                            <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                            <th class="py-4 px-6 text-center text-[var(--gold)] font-bold">حاضر</th>
                            <th class="py-4 px-6 text-center text-[var(--gold)] font-bold">غائب</th>
                            <th class="py-4 px-6 text-center text-[var(--gold)] font-bold">متأخر</th>
                            <th class="py-4 px-6 text-center text-[var(--gold)] font-bold">معذور</th>
                            <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">ملاحظة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @forelse($students as $student)
                            @php
                                $record = $records->get($student->id);
                                $currentStatus = $record->status ?? 'present';
                            @endphp
                            <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--deep-green)] to-teal-900 flex items-center justify-center text-[var(--gold)] font-bold">
                                            {{ mb_substr($student->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[var(--cream)]">{{ $student->user->name ?? 'طالب' }}</div>
                                        </div>
                                    </div>
                                </td>
                                @foreach(['present','absent','late','excused'] as $status)
                                    <td class="py-4 px-6 text-center">
                                        <input type="radio"
                                               name="attendance[{{ $student->id }}][status]"
                                               value="{{ $status }}"
                                               {{ $currentStatus === $status ? 'checked' : '' }}
                                               class="w-5 h-5 text-[var(--gold)] focus:ring-[var(--gold)] bg-[var(--surface)] border-[var(--border)]">
                                    </td>
                                @endforeach
                                <td class="py-4 px-6">
                                    <input type="text" name="attendance[{{ $student->id }}][note]"
                                           value="{{ $record->note ?? '' }}" placeholder="ملاحظة..."
                                           class="w-full px-3 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[var(--slate-blue)]">
                                    لا يوجد طلاب نشطون في هذه الحلقة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->count() > 0)
            <div class="flex flex-wrap justify-end gap-3 mt-6 pt-6 border-t border-[var(--border)]">
                <a href="{{ route('circle-sessions.show', [$circle, $session]) }}"
                   class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ الحضور
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const row = this.closest('tr');
        if (this.value === 'present') {
            row.style.opacity = '1';
        } else if (this.value === 'absent') {
            row.style.opacity = '0.7';
        } else {
            row.style.opacity = '0.85';
        }
    });
});
</script>
@endpush
@endsection
