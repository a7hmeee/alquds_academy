@extends('layouts.app')
@section('title', 'تسجيل جلسة تسميع')
@section('page-title', 'تسجيل جلسة تسميع')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تسجيل جلسة تسميع</h1>
            <p class="text-[var(--slate-blue)]">تسجيل جلسة استماع وتقييم للطالب</p>
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
        <form method="POST" action="{{ route('memorization-sessions.store') }}" class="space-y-6">
            @csrf

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-user text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">معلومات الطالب والجلسة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">اختر الطالب والحلقة والبيانات الأساسية</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحلقة <span class="text-red-400">*</span></label>
                        <select name="circle_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر الحلقة</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @selected(old('circle_id') == $circle->id)>
                                    {{ $circle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الطالب <span class="text-red-400">*</span></label>
                        <select name="student_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر الطالب</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">المهمة (اختياري)</label>
                        <select name="memorization_assignment_id"
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">— بدون مهمة —</option>
                            @foreach($assignments as $assignment)
                                <option value="{{ $assignment->id }}" @selected(old('memorization_assignment_id') == $assignment->id)>
                                    {{ $assignment->surah?->name_ar ?? 'مهمة' }} - {{ $assignment->student?->user?->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الجلسة <span class="text-red-400">*</span></label>
                        <input type="date" name="session_date" value="{{ old('session_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">السورة <span class="text-red-400">*</span></label>
                        <select name="surah_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر السورة</option>
                            @foreach(\App\Models\Surah::orderBy('id')->get() as $surah)
                                <option value="{{ $surah->id }}" @selected(old('surah_id') == $surah->id)>{{ $surah->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الجزء <span class="text-red-400">*</span></label>
                        <select name="juz_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر الجزء</option>
                            @foreach(\App\Models\Juz::orderBy('id')->get() as $juz)
                                <option value="{{ $juz->id }}" @selected(old('juz_id') == $juz->id)>{{ $juz->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">من آية</label>
                        <input type="number" name="ayah_from" value="{{ old('ayah_from') }}" min="1"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="رقم الآية">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">إلى آية</label>
                        <input type="number" name="ayah_to" value="{{ old('ayah_to') }}" min="1"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="رقم الآية">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-star text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">التقييم والنتيجة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">يمكن ترك scores فارغة إذا لم تقيم بعد</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة الحفظ</label>
                        <input type="number" name="memorization_score" value="{{ old('memorization_score') }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="0-100">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة التجويد</label>
                        <input type="number" name="tajweed_score" value="{{ old('tajweed_score') }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="0-100">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">درجة الطلاقة</label>
                        <input type="number" name="fluency_score" value="{{ old('fluency_score') }}" min="0" max="100"
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="0-100">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-notes-medical text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">ملاحظات الجلسة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">أضف ملاحظات عن أداء الطالب (اختياري)</p>
                    </div>
                </div>

                <div>
                    <textarea name="teacher_notes" rows="4"
                              class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"
                              placeholder="ملاحظات...">{{ old('teacher_notes') }}</textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-info-circle ml-1"></i>
                    الحقول المميزة بـ <span class="text-red-400">*</span> إلزامية
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('memorization-sessions.index') }}"
                       class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        حفظ الجلسة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const circleSelect = document.querySelector('select[name="circle_id"]');
    const studentSelect = document.querySelector('select[name="student_id"]');

    circleSelect.addEventListener('change', function() {
        const circleId = this.value;
        studentSelect.innerHTML = '<option value="">جاري التحميل...</option>';

        fetch(`/admin/memorization-assignments/circles/${circleId}/students`)
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
                if (data.students) {
                    data.students.forEach(s => {
                        studentSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                    });
                }
            })
            .catch(() => {
                studentSelect.innerHTML = '<option value="">حدث خطأ في التحميل</option>';
            });
    });
});
</script>
@endpush
@endsection
