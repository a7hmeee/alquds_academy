@extends('layouts.app')
@section('title', 'تعديل المهمة')

@section('page-title', 'تعديل المهمة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تعديل المهمة</h1>
            <p class="text-[var(--slate-blue)]">تحديث بيانات المهمة</p>
        </div>
        <a href="{{ route('memorization-assignments.show', $assignment) }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-content-section">
        <form method="POST" action="{{ route('memorization-assignments.update', $assignment) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">نوع المهمة</label>
                    <select name="assignment_type" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="new_memorization" {{ $assignment->assignment_type === 'new_memorization' ? 'selected' : '' }}>حفظ جديد</option>
                        <option value="close_revision" {{ $assignment->assignment_type === 'close_revision' ? 'selected' : '' }}>مراجعة قريبة</option>
                        <option value="far_revision" {{ $assignment->assignment_type === 'far_revision' ? 'selected' : '' }}>مراجعة بعيدة</option>
                        <option value="consolidation" {{ $assignment->assignment_type === 'consolidation' ? 'selected' : '' }}>تثبيت</option>
                        <option value="test" {{ $assignment->assignment_type === 'test' ? 'selected' : '' }}>اختبار</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحالة</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="draft" {{ $assignment->status === 'draft' ? 'selected' : '' }}>مسودة</option>
                        <option value="assigned" {{ $assignment->status === 'assigned' ? 'selected' : '' }}>مُسندة</option>
                        <option value="in_progress" {{ $assignment->status === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                        <option value="cancelled" {{ $assignment->status === 'cancelled' ? 'selected' : '' }}>ملغية</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">من آية</label>
                    <input type="number" name="ayah_from" value="{{ old('ayah_from', $assignment->ayah_from) }}" min="1" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">إلى آية</label>
                    <input type="number" name="ayah_to" value="{{ old('ayah_to', $assignment->ayah_to) }}" min="1" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الاستحقاق</label>
                    <input type="date" name="due_at" value="{{ old('due_at', $assignment->due_at?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الأولوية</label>
                    <select name="priority" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="0" {{ $assignment->priority === 0 ? 'selected' : '' }}>عادية</option>
                        <option value="1" {{ $assignment->priority === 1 ? 'selected' : '' }}>متوسطة</option>
                        <option value="2" {{ $assignment->priority === 2 ? 'selected' : '' }}>عالية</option>
                        <option value="3" {{ $assignment->priority === 3 ? 'selected' : '' }}>عاجلة</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">نسبة الإنجاز</label>
                    <input type="number" name="completion_percent" value="{{ old('completion_percent', $assignment->completion_percent) }}" min="0" max="100" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تعليمات المعلم</label>
                <textarea name="instructions" rows="3" class="w-full px-4 py-2.5 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">{{ old('instructions', $assignment->instructions) }}</textarea>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('memorization-assignments.show', $assignment) }}" class="px-6 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">إلغاء</a>
                <button type="submit" class="px-8 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
