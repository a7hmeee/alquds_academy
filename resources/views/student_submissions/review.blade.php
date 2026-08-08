@extends('layouts.app')

@section('title','مراجعة تسجيل الطالب')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">مراجعة تسجيل — {{ $submission->student?->user?->name ?? $submission->student?->full_name }}</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">استمع وقيّم القراءة</p>
        </div>
        <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">رجوع</a>
    </div>

    {{-- معلومات التسجيل --}}
    <div class="p-6 rounded-xl border bg-[var(--surface)] space-y-3">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-[var(--slate-blue)]">السورة:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $submission->surah_display ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">الجزء:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $submission->juz_display ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">من آية:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $submission->ayah_from ?? $submission->ayah ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[var(--slate-blue)]">إلى آية:</span>
                <span class="text-[var(--cream)] font-bold mr-1">{{ $submission->ayah_to ?? '—' }}</span>
            </div>
        </div>

        <div class="text-sm text-[var(--slate-blue)]">التاريخ: {{ $submission->created_at->format('Y-m-d H:i') }}</div>
    </div>

    {{-- مشغل الصوت --}}
    <div class="p-6 rounded-xl border bg-[var(--surface)] space-y-4">
        <h3 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-headphones text-[var(--gold)] ml-2"></i>الاستماع للتسجيل</h3>
        <audio controls class="w-full">
            <source src="{{ asset('storage/' . $submission->file_path) }}" type="audio/mpeg">
            المتصفح لا يدعم تشغيل الصوت.
        </audio>

        @if($submission->image_path)
            <img src="{{ asset('storage/' . $submission->image_path) }}" alt="صفحة" class="max-w-xs rounded-lg border border-[var(--border)]">
        @endif

        @if($submission->notes)
            <div class="text-sm text-[var(--slate-blue)]">ملاحظات الطالب: {{ $submission->notes }}</div>
        @endif
    </div>

    {{-- نموذج التقييم --}}
    <div class="p-6 rounded-xl border bg-[var(--surface)] space-y-4">
        <h3 class="text-lg font-bold text-[var(--cream)]"><i class="fas fa-star text-[var(--gold)] ml-2"></i>تقييم المعلم</h3>

        @if($errors->any())
            <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/30">
                @foreach($errors->all() as $error)
                    <div class="text-red-300 text-sm">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('submissions.review.update', $submission) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                {{-- التقييم من 100 --}}
                <div>
                    <label class="block text-sm text-[var(--cream)] font-bold mb-2">
                        الدرجة (من 0 إلى 100) <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input name="score" type="number" min="0" max="100" required
                               value="{{ old('score', $submission->score) }}"
                               class="w-32 px-4 py-3 rounded-lg bg-[var(--dark-bg)] border-2 border-[var(--gold)] text-[var(--cream)] text-center text-xl font-bold"
                               placeholder="0">
                        <span class="text-[var(--slate-blue)] text-lg">/ 100</span>
                        <div id="scoreIndicator" class="px-3 py-1 rounded-full text-sm font-bold"></div>
                    </div>
                    @error('score')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الحالة --}}
                <div>
                    <label class="block text-sm text-[var(--cream)] font-bold mb-2">حالة المراجعة</label>
                    <select name="status" class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                        <option value="reviewed" @selected($submission->status=='reviewed')>تمت المراجعة</option>
                        <option value="accepted" @selected($submission->status=='accepted')>مقبول</option>
                        <option value="needs_work" @selected($submission->status=='needs_work')>يحتاج لتحسين</option>
                    </select>
                </div>

                {{-- تقييم النجوم (اختياري) --}}
                <div>
                    <label class="block text-sm text-[var(--cream)] font-bold mb-2">تقييم بالنجوم (اختياري 1-5)</label>
                    <input name="rating" type="number" min="1" max="5"
                           value="{{ old('rating', $submission->rating) }}"
                           class="w-32 px-4 py-3 rounded-lg bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]"
                           placeholder="1-5">
                </div>

                {{-- الملاحظات (إجبارية) --}}
                <div>
                    <label class="block text-sm text-[var(--cream)] font-bold mb-2">
                        ملاحظات المعلم <span class="text-red-400">*</span>
                    </label>
                    <textarea name="review_notes" rows="4" required minlength="3"
                              placeholder="اكتب ملاحظاتك على القراءة..."
                              class="w-full px-4 py-3 rounded-lg bg-[var(--dark-bg)] border-2 border-[var(--gold)] text-[var(--cream)]">{{ old('review_notes', $submission->review_notes) }}</textarea>
                    @error('review_notes')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-[var(--slate-blue)] mt-1">الملاحظات إجبارية — لا يمكن حفظ التقييم بدونها</p>
                </div>

                <div class="flex justify-end">
                    <button class="px-6 py-3 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold text-lg hover:opacity-90">
                        <i class="fas fa-save ml-1"></i> حفظ التقييم
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('input[name="score"]').addEventListener('input', function() {
    const val = parseInt(this.value) || 0;
    const el = document.getElementById('scoreIndicator');
    if (val >= 90) { el.textContent = 'ممتاز'; el.className = 'px-3 py-1 rounded-full text-sm font-bold bg-green-500/20 text-green-300'; }
    else if (val >= 75) { el.textContent = 'جيد جداً'; el.className = 'px-3 py-1 rounded-full text-sm font-bold bg-blue-500/20 text-blue-300'; }
    else if (val >= 60) { el.textContent = 'جيد'; el.className = 'px-3 py-1 rounded-full text-sm font-bold bg-yellow-500/20 text-yellow-300'; }
    else if (val >= 50) { el.textContent = 'مقبول'; el.className = 'px-3 py-1 rounded-full text-sm font-bold bg-orange-500/20 text-orange-300'; }
    else { el.textContent = 'ضعيف'; el.className = 'px-3 py-1 rounded-full text-sm font-bold bg-red-500/20 text-red-300'; }
});
// Trigger on load
document.querySelector('input[name="score"]').dispatchEvent(new Event('input'));
</script>
@endsection