@extends('layouts.app')

@section('title','رفع صوتية — ' . $circle->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">رفع صوتية للحلقة — {{ $circle->name }}</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">أرسل تسجيلك ليتم مراجعته من قبل المعلّم</p>
        </div>
        <a href="{{ route('student-submissions.index') }}" class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)]">صوتياتي</a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded bg-green-900/10 border border-green-700/10 text-green-200">{{ session('success') }}</div>
    @endif

    <form id="uploadForm" method="POST" action="{{ route('circles.submissions.store', $circle) }}" enctype="multipart/form-data" class="p-6 rounded-xl border bg-[var(--surface)]">
        @csrf

        {{-- allow admin/teacher to pick a student when provided --}}
        @if(isset($availableStudents) && $availableStudents && auth()->user()->hasRole('super admin') || auth()->user()->teacherProfile)
            <div class="mb-4">
                <label class="block mb-2 text-sm text-[var(--slate-blue)]">اختر الطالب (رفع نيابةً عنه)</label>
                <select name="student_id" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="">— اختر طالب —</option>
                    @foreach($availableStudents as $s)
                        <option value="{{ $s->id }}">{{ $s->user?->name ?? $s->full_name }} — ({{ $s->user?->email ?? '-' }})</option>
                    @endforeach
                </select>
                @error('student_id')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
            </div>
        @endif

        <div class="space-y-4">
            <label class="block text-sm text-[var(--slate-blue)]">صوت (mp3, wav, m4a, ogg)</label>
            <div class="border-dashed border-2 border-[var(--border)] rounded-lg p-6 text-center bg-[var(--dark-bg)]/10">
                <input id="audioInput" type="file" name="audio_file" accept="audio/*" required class="mx-auto" />
                <div class="text-xs text-[var(--slate-blue)] mt-2">اسحب الملف هنا أو اضغط للاختيار</div>
            </div>
            @error('audio_file')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror

            <label class="block text-sm text-[var(--slate-blue)]">صورة (اختياري)</label>
            <input type="file" name="image" accept="image/*" class="w-full" />
            @error('image')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Surah dropdown --}}
                <div>
                    <label class="block text-sm text-[var(--slate-blue)] mb-1">السورة</label>
                    <select name="surah_id" id="surah_id" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]"
                        onchange="loadJuzForSurah(this.value); document.getElementById('surah_name').value = this.options[this.selectedIndex].text;">
                        <option value="">-- اختر السورة --</option>
                    </select>
                    <input type="hidden" name="surah" id="surah_name" value="{{ old('surah') }}">
                </div>
                {{-- Juz dropdown --}}
                <div>
                    <label class="block text-sm text-[var(--slate-blue)] mb-1">الجزء</label>
                    <select name="juz_id" id="juz_id" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]"
                        onchange="loadAyahsForJuz(); document.getElementById('juz_name').value = this.options[this.selectedIndex].text;">
                        <option value="">-- اختر الجزء --</option>
                    </select>
                    <input type="hidden" name="juz" id="juz_name" value="{{ old('juz') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Ayah from --}}
                <div>
                    <label class="block text-sm text-[var(--slate-blue)] mb-1">من آية</label>
                    <select name="ayah_from" id="ayah_from" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]"
                        onchange="document.getElementById('ayah_num').value = this.value;">
                        <option value="">-- اختر --</option>
                    </select>
                    <input type="hidden" name="ayah" id="ayah_num" value="{{ old('ayah') }}">
                </div>
                {{-- Ayah to --}}
                <div>
                    <label class="block text-sm text-[var(--slate-blue)] mb-1">إلى آية</label>
                    <select name="ayah_to" id="ayah_to" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">
                        <option value="">-- اختر --</option>
                    </select>
                </div>
            </div>

            <label class="block text-sm text-[var(--slate-blue)]">ملاحظات</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded bg-[var(--dark-bg)] border border-[var(--border)] text-[var(--cream)]">{{ old('notes') }}</textarea>

            <div id="progressWrap" class="hidden">
                <div class="w-full bg-[var(--dark-bg)]/30 rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="h-3 bg-[var(--gold)]" style="width:0%"></div>
                </div>
                <div class="text-xs text-[var(--slate-blue)] mt-2">جارٍ التحميل: <span id="progressPct">0%</span></div>
            </div>

            <div class="flex justify-end">
                <button id="uploadBtn" class="px-6 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">رفع</button>
            </div>
        </div>
    </form>
</div>

<script>
// Load surahs on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("api.recordings.surahs") }}')
        .then(r => r.json())
        .then(surahs => {
            const sel = document.getElementById('surah_id');
            surahs.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name_ar + ' (' + s.name_en + ')';
                sel.appendChild(opt);
            });
        });
});

function loadJuzForSurah(surahId) {
    const juzSel = document.getElementById('juz_id');
    juzSel.innerHTML = '<option value="">-- اختر الجزء --</option>';
    document.getElementById('ayah_from').innerHTML = '<option value="">-- اختر --</option>';
    document.getElementById('ayah_to').innerHTML = '<option value="">-- اختر --</option>';
    if (!surahId) return;

    fetch('/api/recordings/surah/' + surahId + '/juz')
        .then(r => r.json())
        .then(juzzes => {
            if (Array.isArray(juzzes)) {
                juzzes.forEach(j => {
                    const opt = document.createElement('option');
                    opt.value = j.id;
                    opt.textContent = j.name_ar;
                    juzSel.appendChild(opt);
                });
            }
        });
}

function loadAyahsForJuz() {
    const surahId = document.getElementById('surah_id').value;
    const juzId = document.getElementById('juz_id').value;
    const fromSel = document.getElementById('ayah_from');
    const toSel = document.getElementById('ayah_to');
    fromSel.innerHTML = '<option value="">-- اختر --</option>';
    toSel.innerHTML = '<option value="">-- اختر --</option>';
    if (!surahId || !juzId) return;

    fetch('/api/recordings/surah/' + surahId + '/juz/' + juzId + '/ayahs')
        .then(r => r.json())
        .then(data => {
            const ayahs = data.ayahs || [];
            ayahs.forEach(a => {
                const opt1 = document.createElement('option');
                opt1.value = a.ayah_number;
                opt1.textContent = 'آية ' + a.ayah_number;
                fromSel.appendChild(opt1);

                const opt2 = document.createElement('option');
                opt2.value = a.ayah_number;
                opt2.textContent = 'آية ' + a.ayah_number;
                toSel.appendChild(opt2);
            });
        });
}

document.getElementById('uploadForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.upload.onprogress = function(e){
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            document.getElementById('progressWrap').classList.remove('hidden');
            document.getElementById('progressBar').style.width = pct + '%';
            document.getElementById('progressPct').innerText = pct + '%';
        }
    };
    xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300) {
            window.location.href = '{{ route('student-submissions.index') }}';
        } else {
            alert('فشل الرفع. تأكد من حجم الملف ونوعه.');
        }
    };
    xhr.send(data);
});
</script>
@endsection