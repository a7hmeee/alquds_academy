@extends('layouts.app')
@section('title', 'مهمة حفظ جديدة')

@section('page-title', 'إنشاء مهمة حفظ جديدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">مهمة حفظ جديدة</h1>
            <p class="text-[var(--slate-blue)]">إنشاء مهمة حفظ أو مراجعة لطالب</p>
        </div>
        <a href="{{ route('memorization-assignments.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع للمهام
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
        <form method="POST" action="{{ route('memorization-assignments.store') }}" class="space-y-6" id="assignmentForm">
            @csrf

            <!-- Assignment Type -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-tasks text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">نوع المهمة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">اختر نوع المهمة المراد إسنادها</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach([
                        'new_memorization' => ['label' => 'حفظ جديد', 'icon' => 'fa-book-quran', 'desc' => 'حفظ آيات جديدة'],
                        'close_revision' => ['label' => 'مراجعة قريبة', 'icon' => 'fa-repeat', 'desc' => 'مراجعة المحفوظ القريب'],
                        'far_revision' => ['label' => 'مراجعة بعيدة', 'icon' => 'fa-history', 'desc' => 'مراجعة المحفوظ القديم'],
                        'consolidation' => ['label' => 'تثبيت', 'icon' => 'fa-anchor', 'desc' => 'تثبيت الحفظ'],
                        'test' => ['label' => 'اختبار', 'icon' => 'fa-question-circle', 'desc' => 'اختبار تقييمي'],
                    ] as $val => $info)
                    <label class="relative">
                        <input type="radio" name="assignment_type" value="{{ $val }}" class="sr-only peer" {{ old('assignment_type') === $val ? 'checked' : '' }} required>
                        <div class="p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] cursor-pointer transition-all duration-200 peer-checked:border-[var(--gold)] peer-checked:bg-[var(--gold)]/5 peer-checked:shadow-lg text-center hover:border-[var(--gold)]/50">
                            <i class="fas {{ $info['icon'] }} text-xl text-[var(--gold)] mb-2 block"></i>
                            <div class="font-bold text-[var(--cream)] text-sm">{{ $info['label'] }}</div>
                            <div class="text-xs text-[var(--slate-blue)] mt-1">{{ $info['desc'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Circle & Student -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-users text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">الحلقة والطالب</h3>
                        <p class="text-sm text-[var(--slate-blue)]">اختر الحلقة والطالب المستهدف</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحلقة <span class="text-red-400">*</span></label>
                        <select name="circle_id" id="circleSelect" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                            <option value="">اختر الحلقة</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" {{ old('circle_id') == $circle->id ? 'selected' : '' }}>{{ $circle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الطالب <span class="text-red-400">*</span></label>
                        <select name="student_id" id="studentSelect" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                            <option value="">اختر الطالب</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Quran Range -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-book-quran text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">نطاق الحفظ</h3>
                        <p class="text-sm text-[var(--slate-blue)]">حدد السورة والجزء والآيات</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">السورة <span class="text-red-400">*</span></label>
                        <select name="surah_id" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                            <option value="">اختر السورة</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الجزء <span class="text-red-400">*</span></label>
                        <select name="juz_id" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                            <option value="">اختر الجزء</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">من آية <span class="text-red-400">*</span></label>
                        <input type="number" name="ayah_from" value="{{ old('ayah_from') }}" min="1" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">إلى آية <span class="text-red-400">*</span></label>
                        <input type="number" name="ayah_to" value="{{ old('ayah_to') }}" min="1" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" required>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-cog text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">تفاصيل المهمة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">المواعيد والتعليمات</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الاستحقاق</label>
                        <input type="date" name="due_at" value="{{ old('due_at') }}"
                               class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الأولوية</label>
                        <select name="priority" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="0">عادية</option>
                            <option value="1">متوسطة</option>
                            <option value="2">عالية</option>
                            <option value="3">عاجلة</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحالة</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="assigned">إسناد مباشر</option>
                            <option value="draft">مسودة</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تعليمات المعلم</label>
                    <textarea name="instructions" rows="4" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]" placeholder="اكتب تعليمات للطالب حول المهمة...">{{ old('instructions') }}</textarea>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('memorization-assignments.index') }}" class="px-6 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200">إلغاء</a>
                <button type="submit" class="px-8 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ المهمة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const circleSelect = document.getElementById('circleSelect');
    const studentSelect = document.getElementById('studentSelect');

    // Load suras via API
    fetch('/api/quran/surahs')
        .then(r => r.json())
        .then(data => {
            const select = document.querySelector('select[name="surah_id"]');
            if (data.data) {
                data.data.forEach(s => {
                    select.innerHTML += `<option value="${s.id}">${s.name_ar}</option>`;
                });
            } else if (Array.isArray(data)) {
                data.forEach(s => {
                    select.innerHTML += `<option value="${s.id}">${s.name_ar}</option>`;
                });
            }
        });

    // Load juz via API
    fetch('/api/quran/juz')
        .then(r => r.json())
        .then(data => {
            const select = document.querySelector('select[name="juz_id"]');
            if (data.data) {
                data.data.forEach(j => {
                    select.innerHTML += `<option value="${j.id}">${j.name}</option>`;
                });
            } else if (Array.isArray(data)) {
                data.forEach(j => {
                    select.innerHTML += `<option value="${j.id}">${j.name}</option>`;
                });
            }
        });

    // Load students when circle changes
    if (circleSelect && studentSelect) {
        circleSelect.addEventListener('change', function() {
            const circleId = this.value;
            studentSelect.innerHTML = '<option value="">جاري التحميل...</option>';

            if (!circleId) {
                studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
                return;
            }

            fetch(`/admin/memorization-assignments/circles/${circleId}/students`)
                .then(r => r.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
                    data.forEach(s => {
                        studentSelect.innerHTML += `<option value="${s.id}">${s.full_name}</option>`;
                    });
                })
                .catch(() => {
                    studentSelect.innerHTML = '<option value="">حدث خطأ في التحميل</option>';
                });
        });
    }
});
</script>
@endpush
@endsection
