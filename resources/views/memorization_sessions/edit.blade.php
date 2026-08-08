@extends('layouts.app')
@section('title', 'تعديل جلسة تسميع')
@section('page-title', 'تعديل جلسة تسميع')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تعديل جلسة التسميع</h1>
            <p class="text-[var(--slate-blue)]">تحديث درجات وملاحظات الجلسة</p>
        </div>
        <a href="{{ route('memorization-sessions.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع للجلسات
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
        <form method="POST" action="{{ route('memorization-sessions.update', $session) }}" class="space-y-6">
            @csrf @method('PUT')

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-star text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">تحديث الدرجات</h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة الحفظ</label>
                        <input type="number" name="memorization_score" value="{{ old('memorization_score', $session->memorization_score) }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة التجويد</label>
                        <input type="number" name="tajweed_score" value="{{ old('tajweed_score', $session->tajweed_score) }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة الطلاقة</label>
                        <input type="number" name="fluency_score" value="{{ old('fluency_score', $session->fluency_score) }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-info-circle text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">حالة الجلسة والملاحظات</h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحالة</label>
                        <select name="status"
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="scheduled" @selected(old('status', $session->status) == 'scheduled')>مجدول</option>
                            <option value="in_progress" @selected(old('status', $session->status) == 'in_progress')>جاري</option>
                            <option value="completed" @selected(old('status', $session->status) == 'completed')>مكتمل</option>
                            <option value="cancelled" @selected(old('status', $session->status) == 'cancelled')>ملغي</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">ملاحظات المعلم</label>
                    <textarea name="teacher_notes" rows="4"
                              class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"
                              placeholder="ملاحظات...">{{ old('teacher_notes', $session->teacher_notes) }}</textarea>
                </div>
            </div>

            <div class="flex flex-wrap justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <a href="{{ route('memorization-sessions.show', $session) }}"
                   class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
