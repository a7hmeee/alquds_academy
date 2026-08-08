@extends('layouts.app')
@section('title', 'إنشاء خطة مراجعة')
@section('page-title', 'إنشاء خطة مراجعة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إنشاء خطة مراجعة</h1>
            <p class="text-[var(--slate-blue)]">ضع خطة منظمة لمراجعة الحفظ للطالب</p>
        </div>
        <a href="{{ route('revision-plans.index') }}"
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
        <form method="POST" action="{{ route('revision-plans.store') }}" class="space-y-6">
            @csrf

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-info-circle text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">معلومات الخطة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">البيانات الأساسية للخطة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">اسم الخطة <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"
                               placeholder="خطة مراجعة الجزء...">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحلقة <span class="text-red-400">*</span></label>
                        <select name="circle_id" required
                                class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">اختر الحلقة</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @selected(old('circle_id') == $circle->id)>{{ $circle->name }}</option>
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
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ البداية <span class="text-red-400">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ النهاية <span class="text-red-400">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-list text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">بنود المراجعة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">أضف بنود المراجعة (سورة، آيات، نوع المراجعة)</p>
                    </div>
                </div>

                <div id="items-container">
                    <div class="item-row p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)] mb-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">النوع</label>
                                <select name="items[0][assignment_type]" required
                                        class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                                    <option value="new_memorization">حفظ جديد</option>
                                    <option value="close_revision">مراجعة قريبة</option>
                                    <option value="far_revision">مراجعة بعيدة</option>
                                    <option value="consolidation">تثبيت</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">السورة</label>
                                <select name="items[0][surah_id]" required
                                        class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                                    <option value="">اختر</option>
                                    @foreach(\App\Models\Surah::orderBy('id')->get() as $surah)
                                        <option value="{{ $surah->id }}">{{ $surah->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">الجزء</label>
                                <select name="items[0][juz_id]" required
                                        class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                                    <option value="">اختر</option>
                                    @foreach(\App\Models\Juz::orderBy('id')->get() as $juz)
                                        <option value="{{ $juz->id }}">{{ $juz->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">من آية</label>
                                <input type="number" name="items[0][ayah_from]" min="1" required
                                       class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">إلى آية</label>
                                <input type="number" name="items[0][ayah_to]" min="1" required
                                       class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-[var(--cream)]">عدد التكرارات</label>
                                <input type="number" name="items[0][repetition_target]" value="1" min="1" max="100"
                                       class="w-full px-3 py-2 rounded-lg bg-[var(--green-900)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            </div>
                        </div>
                        <button type="button" onclick="this.closest('.item-row').remove()"
                                class="mt-2 text-sm text-red-400 hover:text-red-300">إزالة البند</button>
                    </div>
                </div>

                <button type="button" onclick="addItem()"
                        class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-sm flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    إضافة بند
                </button>
            </div>

            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-sticky-note text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">ملاحظات (اختياري)</h3>
                    </div>
                </div>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] resize-none"
                          placeholder="ملاحظات...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex flex-wrap justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <a href="{{ route('revision-plans.index') }}"
                   class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> إنشاء الخطة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('items-container');
    const template = container.querySelector('.item-row').cloneNode(true);
    template.innerHTML = template.innerHTML.replace(/items\[0\]/g, `items[${itemIndex}]`);
    template.querySelectorAll('input, select').forEach(el => el.value = '');
    itemIndex++;
    container.appendChild(template);
}

document.querySelector('select[name="circle_id"]').addEventListener('change', function() {
    const circleId = this.value;
    const studentSelect = document.querySelector('select[name="student_id"]');
    studentSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    fetch(`/admin/memorization-assignments/circles/${circleId}/students`)
        .then(r => r.json())
        .then(data => {
            studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
            (data.students || []).forEach(s => {
                studentSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
        })
        .catch(() => studentSelect.innerHTML = '<option value="">خطأ في التحميل</option>');
});
</script>
@endpush
@endsection
