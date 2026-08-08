@extends('layouts.student')

@section('page-title', 'رفع ملف صوتي')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">

    {{-- Success Message --}}
    @if(session('success'))
        <div style="background: #10B981; color: white; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div style="background: #EF4444; color: white; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
            @foreach($errors->all() as $error)
                <div>❌ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @php
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
    @endphp

    @if($circle)
        <div style="background: #2A2A3E; padding: 24px; border-radius: 8px;">
            <h2 style="color: #FFD700; margin-bottom: 20px;">{{ $circle->name }}</h2>

            <form method="POST" action="{{ route('circles.submissions.store', $circle) }}" enctype="multipart/form-data" style="display: grid; gap: 16px;">
                @csrf

                {{-- Audio File (Required) --}}
                <div>
                    <label style="display: block; color: #FFD700; margin-bottom: 8px; font-weight: 500;">الملف الصوتي * (mp3, wav, m4a)</label>
                    <input type="file" name="audio_file" accept=".mp3,.wav,.m4a" required 
                        style="display: block; width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 2px solid #FFD700; border-radius: 6px;">
                </div>

                {{-- Surah Selection --}}
                <div>
                    <label style="display: block; color: #FFD700; margin-bottom: 6px; font-size: 14px;">السورة *</label>
                    <select name="surah_id" id="surah_id" required
                        style="width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 4px;"
                        onchange="loadJuzForSurah(this.value); document.getElementById('surah_hidden').value = this.options[this.selectedIndex].text;">
                        <option value="">-- اختر السورة --</option>
                    </select>
                    <input type="hidden" name="surah" id="surah_hidden" value="{{ old('surah') }}">
                </div>

                {{-- Juz Selection --}}
                <div>
                    <label style="display: block; color: #FFD700; margin-bottom: 6px; font-size: 14px;">الجزء *</label>
                    <select name="juz_id" id="juz_id" required
                        style="width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 4px;"
                        onchange="loadAyahsForJuz(); document.getElementById('juz_hidden').value = this.options[this.selectedIndex].text;">
                        <option value="">-- اختر الجزء --</option>
                    </select>
                    <input type="hidden" name="juz" id="juz_hidden" value="{{ old('juz') }}">
                </div>

                {{-- Ayah From/To --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(160px, 100%), 1fr)); gap: 12px;">
                    <div>
                        <label style="display: block; color: #FFD700; margin-bottom: 6px; font-size: 14px;">من آية *</label>
                        <select name="ayah_from" id="ayah_from" required
                            style="width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 4px;">
                            <option value="">-- اختر --</option>
                        </select>
                        <input type="hidden" name="ayah" id="ayah_hidden" value="{{ old('ayah') }}">
                    </div>
                    <div>
                        <label style="display: block; color: #FFD700; margin-bottom: 6px; font-size: 14px;">إلى آية *</label>
                        <select name="ayah_to" id="ayah_to" required
                            style="width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 4px;">
                            <option value="">-- اختر --</option>
                        </select>
                    </div>
                </div>

                {{-- Optional: Notes --}}
                <div>
                    <label style="display: block; color: #FFD700; margin-bottom: 8px; font-size: 14px;">ملاحظات (اختياري)</label>
                    <textarea name="notes" rows="3" placeholder="أي ملاحظات للمعلم..." 
                        style="width: 100%; padding: 8px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 4px;"
                        >{{ old('notes') }}</textarea>
                </div>

                {{-- Optional: Image --}}
                <div>
                    <label style="display: block; color: #FFD700; margin-bottom: 8px; font-size: 14px;">صورة (اختياري)</label>
                    <input type="file" name="image" accept="image/*" 
                        style="display: block; width: 100%; padding: 10px; background: #1A1A2E; color: #E5E5E5; border: 1px solid #444; border-radius: 6px;">
                </div>

                {{-- Submit Button --}}
                <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px;">
                    <button type="submit" style="flex: 1; padding: 12px; background: #FFD700; color: #1A1A2E; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px;">
                        📤 رفع الملف
                    </button>
                    <a href="{{ route('student.dashboard') }}" style="flex: 1; padding: 12px; background: #444; color: #E5E5E5; border: none; border-radius: 6px; text-align: center; text-decoration: none; cursor: pointer;">
                        ❌ إلغاء
                    </a>
                </div>
            </form>
        </div>

    @else
        <div style="background: #2A2A3E; padding: 40px; border-radius: 8px; text-align: center;">
            <p style="color: #E5E5E5; font-size: 18px; margin-bottom: 16px;">⚠️ لم تُسجل في أي حلقة</p>
            <a href="{{ route('student.circles') }}" style="display: inline-block; padding: 12px 24px; background: #FFD700; color: #1A1A2E; border-radius: 6px; text-decoration: none; font-weight: bold;">
                عرض الحلقات المتاحة
            </a>
        </div>
    @endif

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
            // Set ayah hidden field on change
            fromSel.onchange = function() {
                document.getElementById('ayah_hidden').value = this.value;
            };
        });
}
</script>
@endsection
