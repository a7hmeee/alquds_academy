@extends('layouts.app')
@section('title', 'جلسة جديدة')
@section('page-title', 'جلسة جديدة: {{ $circle->name }}')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">جلسة جديدة</h1>
            <p class="text-[var(--slate-blue)]">إنشاء جلسة جديدة للحلقة: {{ $circle->name }}</p>
        </div>
        <a href="{{ route('circle-sessions.index', $circle) }}"
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
        <form method="POST" action="{{ route('circle-sessions.store', $circle) }}" class="space-y-6">
            @csrf

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">العنوان (اختياري)</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="مراجعة سورة...">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الجلسة <span class="text-red-400">*</span></label>
                        <input type="date" name="session_date" value="{{ old('session_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">وقت البداية</label>
                        <input type="time" name="starts_at" value="{{ old('starts_at') }}"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">وقت النهاية</label>
                        <input type="time" name="ends_at" value="{{ old('ends_at') }}"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">نوع الجلسة</label>
                        <select name="session_type"
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="regular">عادية</option>
                            <option value="exam">امتحان</option>
                            <option value="review">مراجعة</option>
                            <option value="event">فعالية</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">ملاحظات (اختياري)</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"
                              placeholder="ملاحظات الجلسة...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex flex-wrap justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <a href="{{ route('circle-sessions.index', $circle) }}"
                   class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> إنشاء الجلسة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
