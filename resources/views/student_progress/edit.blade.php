@extends('layouts.app')

@section('title','تعديل سجل التقدّم')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">تعديل سجل التقدّم</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">تعديل بيانات سجل التقدّم</p>
        </div>
        <a href="{{ route('circles.progress.index', $studentProgress->circle) }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع</a>
    </div>

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

    <form method="POST" action="{{ route('student-progress.update', $studentProgress) }}" class="p-6 rounded-xl border bg-[var(--surface)]">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">الطالب</label>
                <div class="px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">{{ $studentProgress->student?->user?->name ?? $studentProgress->student?->full_name }}</div>
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">المعلّم المسؤول</label>
                <select name="teacher_id" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="">— بدون —</option>
                    @foreach($availableTeachers as $t)
                        <option value="{{ $t->id }}" @selected(old('teacher_id', $studentProgress->teacher_id) == $t->id)>{{ $t->user?->name ?? $t->full_name }} — ({{ $t->user?->email ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">الجزء</label>
                <input name="juz" value="{{ old('juz', $studentProgress->juz) }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">السورة</label>
                <input name="surah" value="{{ old('surah', $studentProgress->surah) }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div>
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">الآية</label>
                <input type="number" name="ayah" value="{{ old('ayah', $studentProgress->ayah) }}" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">ملاحظات</label>
                <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">{{ old('notes', $studentProgress->notes) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button class="px-6 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection
