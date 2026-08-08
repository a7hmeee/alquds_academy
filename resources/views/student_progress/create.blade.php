@extends('layouts.app')

@section('title','إضافة سجل تقدّم — ' . $circle->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <a href="{{ route('circles.progress.index', $circle) }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع</a>
    </div>

    <form method="POST" action="{{ route('circles.progress.store', $circle) }}" class="p-6 rounded-xl border bg-[var(--surface)]">
        @csrf

        <input type="hidden" name="circle_id" value="{{ $circle->id }}">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-4 p-4 rounded bg-red-800/10 border border-red-700/10 text-red-200">
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">اختر الطالب</label>
                <select name="student_id" required class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="">— اختر —</option>
                    @foreach($availableStudents as $s)
                        <option value="{{ $s->id }}" @selected(old('student_id') == $s->id)>{{ $s->user?->name ?? $s->full_name }} — ({{ $s->user?->email ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">اختر المعلّم (اختياري)</label>
                <select name="teacher_id" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="">— بدون —</option>
                    @foreach($availableTeachers as $t)
                        <option value="{{ $t->id }}" @selected(old('teacher_id') == $t->id)>{{ $t->user?->name ?? $t->full_name }} — ({{ $t->user?->email ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">الجزء</label>
                <input name="juz" value="{{ old('juz') }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">السورة</label>
                <input name="surah" value="{{ old('surah') }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">الآية</label>
                <input type="number" name="ayah" value="{{ old('ayah') }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">ملاحظات</label>
                <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button class="px-6 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection
