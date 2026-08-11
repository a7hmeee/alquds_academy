@extends('layouts.student')

@section('page-title', 'صفحة التسجيلات')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">

    {{-- رسائل الحالة --}}
    @if(session('success'))
        <div style="background: rgba(16,185,129,0.15); border: 1px solid #10B981; color: #10B981; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(239,68,68,0.15); border: 1px solid #EF4444; color: #EF4444; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div style="background: rgba(239,68,68,0.15); border: 1px solid #EF4444; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="color: #EF4444; font-weight: 600; margin-bottom: 10px;">حدثت أخطاء:</div>
            <ul style="color: #EF4444; margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom: 5px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- بطاقات الإحصائيات --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="stat-box">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">إجمالي التسجيلات</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #FBBF24;">{{ $stats['pending'] }}</div>
            <div class="stat-label">قيد المراجعة</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #10B981;">{{ $stats['reviewed'] }}</div>
            <div class="stat-label">تمت المراجعة</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #60a5fa;">{{ $stats['avg_score'] ? round($stats['avg_score']) : '—' }}</div>
            <div class="stat-label">متوسط الدرجة</div>
        </div>
    </div>

    {{-- قسم رفع تسجيل جديد --}}
    @if($circle)
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleUploadForm()">
            <div class="card-title" style="margin-bottom: 0;">
                <i class="fas fa-plus-circle" style="color: var(--gold);"></i>
                رفع تسجيل جديد
            </div>
            <i id="uploadToggleIcon" class="fas fa-chevron-down" style="color: var(--gold); transition: transform 0.3s;"></i>
        </div>

        <div id="uploadFormSection" style="display: none; margin-top: 20px;">
            <form id="uploadForm" method="POST" action="{{ route('circles.submissions.store', $circle->id) }}" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(220px, 100%), 1fr)); gap: 16px;">
                    {{-- اختيار السورة --}}
                    <div class="form-group">
                        <label class="form-label">السورة *</label>
                        <div style="position: relative;">
                            <input type="text" id="surahSearch" placeholder="ابحث عن السورة..." class="form-control" autocomplete="off">
                            <div id="surahDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: var(--dark-bg); border: 1px solid var(--gold); border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; display: none; z-index: 50;"></div>
                        </div>
                        <input type="hidden" id="surah_id" name="surah_id" required>
                    </div>

                    {{-- اختيار الجزء --}}
                    <div class="form-group">
                        <label class="form-label">الجزء *</label>
                        <select id="juz_id" name="juz_id" class="form-control" required disabled>
                            <option value="">اختر السورة أولاً</option>
                        </select>
                    </div>

                    {{-- من آية --}}
                    <div class="form-group">
                        <label class="form-label">من آية *</label>
                        <input type="number" id="ayah_from" name="ayah_from" min="1" placeholder="1" class="form-control" required disabled>
                    </div>

                    {{-- إلى آية --}}
                    <div class="form-group">
                        <label class="form-label">إلى آية</label>
                        <input type="number" id="ayah_to" name="ayah_to" min="1" placeholder="اختياري" class="form-control" disabled>
                    </div>
                </div>

                {{-- رفع ملف صوتي --}}
                <div class="form-group" style="margin-top: 8px;">
                    <label class="form-label">الملف الصوتي *</label>
                    <div id="dropZone" style="background: rgba(255,215,0,0.05); border: 2px dashed rgba(255,215,0,0.3); border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s;">
                        <div id="dropZoneContent">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 28px; color: var(--gold); margin-bottom: 8px; display: block;"></i>
                            <div style="color: var(--cream); margin-bottom: 4px;">اسحب الملف هنا أو اضغط للاختيار</div>
                            <div style="color: var(--slate-blue); font-size: 12px;">MP3, WAV, M4A, OGG — حتى 50 MB</div>
                        </div>
                        <div id="fileInfo" style="display: none;">
                            <i class="fas fa-check-circle" style="color: #10B981; font-size: 24px; margin-bottom: 8px; display: block;"></i>
                            <div id="fileName" style="color: var(--cream); font-weight: 500;"></div>
                            <div id="fileSize" style="color: var(--slate-blue); font-size: 12px; margin-top: 4px;"></div>
                            <button type="button" onclick="clearFile()" style="margin-top: 8px; background: none; border: 1px solid var(--slate-blue); color: var(--slate-blue); padding: 4px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">تغيير الملف</button>
                        </div>
                    </div>
                    <input type="file" id="audioFile" name="audio_file" accept="audio/*" style="display: none;" required>
                </div>

                {{-- ملاحظات --}}
                <div class="form-group">
                    <label class="form-label">ملاحظاتك (اختياري)</label>
                    <textarea name="notes" placeholder="أضف ملاحظات عن تسجيلك..." class="form-control" style="min-height: 70px; resize: vertical;"></textarea>
                </div>

                {{-- زر الإرسال --}}
                <button type="submit" id="submitBtn" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 16px;" disabled>
                    <i class="fas fa-paper-plane"></i>
                    رفع التسجيل
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="card" style="text-align: center; padding: 24px; margin-bottom: 24px;">
        <i class="fas fa-exclamation-triangle" style="color: #FBBF24; font-size: 24px; margin-bottom: 8px; display: block;"></i>
        <p style="color: var(--cream); margin-bottom: 8px;">لا يمكنك رفع تسجيل — أنت غير مسجل في أي حلقة</p>
        <a href="{{ route('student.circles') }}" class="btn btn-primary" style="display: inline-flex;">
            <i class="fas fa-users"></i> انضم لحلقة
        </a>
    </div>
    @endif

    {{-- قسم تقدم الحفظ لكل جزء --}}
    @if($juzProgress->count())
    @foreach($juzProgress as $jp)
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-title">
            <i class="fas fa-chart-line" style="color: var(--gold);"></i>
            تقدم الحفظ — {{ $jp['juz_name'] }}
            @if($jp['circle_name'])
                <span style="font-size: 12px; color: var(--slate-blue); font-weight: 400; margin-right: 8px;">({{ $jp['circle_name'] }})</span>
            @endif
        </div>

        {{-- شريط التقدم الكلي --}}
        <div style="background: rgba(255,215,0,0.08); border: 1px solid rgba(255,215,0,0.2); border-radius: 10px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: var(--cream); font-weight: 600; font-size: 16px;">النسبة الكلية للجزء</span>
                <span style="color: var(--gold); font-weight: bold; font-size: 24px;">{{ $jp['progress']['total_percent'] }}%</span>
            </div>
            <div class="progress-bar" style="height: 12px; border-radius: 6px;">
                <div class="progress-fill" style="width: {{ $jp['progress']['total_percent'] }}%; border-radius: 6px; background: {{ $jp['progress']['total_percent'] >= 100 ? '#10B981' : ($jp['progress']['total_percent'] >= 50 ? 'var(--gold)' : '#60a5fa') }};"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 6px; font-size: 12px; color: var(--slate-blue);">
                <span>{{ $jp['progress']['covered_ayahs'] }} آية من {{ $jp['progress']['total_ayahs'] }} آية</span>
                <span>{{ $jp['progress']['surahs']->where('approved_count', '>', 0)->count() }} / {{ $jp['progress']['surahs']->count() }} سورة</span>
            </div>
        </div>

        {{-- تفصيل كل سورة --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
            @foreach($jp['progress']['surahs'] as $surah)
            <div style="background: rgba(0,0,0,0.15); border: 1px solid {{ $surah['percent'] >= 100 ? 'rgba(16,185,129,0.3)' : ($surah['percent'] > 0 ? 'rgba(255,215,0,0.15)' : 'rgba(108,142,160,0.15)') }}; border-radius: 8px; padding: 14px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @if($surah['percent'] >= 100)
                            <i class="fas fa-check-circle" style="color: #10B981;"></i>
                        @elseif($surah['percent'] > 0)
                            <i class="fas fa-spinner" style="color: var(--gold);"></i>
                        @else
                            <i class="far fa-circle" style="color: var(--slate-blue);"></i>
                        @endif
                        <span style="color: var(--cream); font-weight: 600;">{{ $surah['surah_name'] }}</span>
                    </div>
                    <span style="font-weight: bold; font-size: 14px; color: {{ $surah['percent'] >= 100 ? '#10B981' : ($surah['percent'] > 0 ? 'var(--gold)' : 'var(--slate-blue)') }};">
                        {{ $surah['percent'] }}%
                    </span>
                </div>
                <div class="progress-bar" style="margin-bottom: 6px;">
                    <div class="progress-fill" style="width: {{ $surah['percent'] }}%; background: {{ $surah['percent'] >= 100 ? '#10B981' : 'var(--gold)' }};"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--slate-blue);">
                    <span>{{ $surah['covered_ayahs'] }} / {{ $surah['total_ayahs'] }} آية</span>
                    @if($surah['avg_score'])
                    <span>متوسط: <span style="color: {{ $surah['avg_score'] >= 90 ? '#4ade80' : ($surah['avg_score'] >= 70 ? '#60a5fa' : '#fbbf24') }}; font-weight: 600;">{{ $surah['avg_score'] }}</span>/100</span>
                    @else
                    <span>لا تسجيلات معتمدة</span>
                    @endif
                </div>
                {{-- تسجيلات السورة --}}
                @if($surah['submissions']->count())
                <div style="margin-top: 8px; border-top: 1px solid rgba(255,215,0,0.1); padding-top: 8px;">
                    @foreach($surah['submissions'] as $sub)
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; padding: 4px 0; font-size: 12px;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="color: var(--cream);">آية {{ $sub->ayah_from ?? '—' }}–{{ $sub->ayah_to ?? '—' }}</span>
                            @if($sub->file_path)
                            <audio controls preload="none" style="height: 24px; max-width: 120px;">
                                <source src="{{ asset('storage/' . $sub->file_path) }}" type="audio/mpeg">
                            </audio>
                            @endif
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if(!is_null($sub->score))
                            <span style="font-weight: bold; color: {{ $sub->score >= 70 ? '#4ade80' : '#f87171' }};">{{ $sub->score }}</span>
                            @endif
                            @if($sub->status === 'pending')
                                <span class="badge badge-warning" style="font-size: 10px; padding: 2px 8px;">قيد المراجعة</span>
                            @elseif($sub->status === 'reviewed' || $sub->status === 'accepted')
                                <span class="badge badge-success" style="font-size: 10px; padding: 2px 8px;">تم ✓</span>
                            @elseif($sub->status === 'needs_work')
                                <span class="badge" style="background: rgba(239,68,68,0.15); color: #fca5a5; font-size: 10px; padding: 2px 8px;">تحسين</span>
                            @endif
                        </div>
                    </div>
                    @if($sub->review_notes)
                    <div style="font-size: 11px; color: var(--slate-blue); padding: 2px 0 4px 0; border-right: 2px solid var(--gold); padding-right: 8px; margin: 2px 0;">
                        {{ $sub->review_notes }}
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif

    {{-- جدول التسجيلات --}}
    @if($submissions->count() > 0)
    <div class="card">
        <div class="card-title">
            <i class="fas fa-list-alt" style="color: var(--gold);"></i>
            تسجيلاتي ({{ $submissions->count() }})
        </div>

        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>السورة</th>
                        <th>الجزء</th>
                        <th>من آية</th>
                        <th>إلى آية</th>
                        <th>الملف الصوتي</th>
                        <th>الحالة</th>
                        <th>الدرجة</th>
                        <th>ملاحظات المعلم</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $index => $submission)
                    <tr>
                        <td style="color: var(--slate-blue);">{{ $index + 1 }}</td>
                        <td style="color: var(--cream); font-weight: 600;">{{ $submission->surah_display ?? 'لم تُحدد' }}</td>
                        <td style="color: var(--cream);">{{ $submission->juz_display ?? '—' }}</td>
                        <td style="color: var(--cream);">{{ $submission->ayah_from ?? $submission->ayah ?? '—' }}</td>
                        <td style="color: var(--cream);">{{ $submission->ayah_to ?? '—' }}</td>
                        <td>
                            @if($submission->file_path)
                                <audio controls preload="none" style="height: 32px; max-width: 160px;">
                                    <source src="{{ asset('storage/' . $submission->file_path) }}" type="audio/mpeg">
                                </audio>
                            @else
                                <span style="color: var(--slate-blue);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->status === 'pending')
                                <span class="badge badge-warning">قيد المراجعة</span>
                            @elseif($submission->status === 'reviewed')
                                <span class="badge badge-success">تم التقييم</span>
                            @elseif($submission->status === 'accepted')
                                <span class="badge badge-success">مقبول ✓</span>
                            @elseif($submission->status === 'needs_work')
                                <span class="badge" style="background: rgba(239,68,68,0.15); color: #fca5a5;">يحتاج تحسين</span>
                            @else
                                <span class="badge badge-info">{{ $submission->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if(!is_null($submission->score))
                                <span style="font-weight: bold; font-size: 18px; color: {{ $submission->score >= 90 ? '#4ade80' : ($submission->score >= 70 ? '#60a5fa' : ($submission->score >= 50 ? '#fbbf24' : '#f87171')) }};">
                                    {{ $submission->score }}
                                </span>
                                <span style="color: var(--slate-blue); font-size: 11px;">/ 100</span>
                            @else
                                <span style="color: var(--slate-blue);">—</span>
                            @endif
                        </td>
                        <td style="max-width: 200px;">
                            @if($submission->review_notes)
                                <div style="background: rgba(255,215,0,0.05); border-right: 3px solid var(--gold); padding: 8px; border-radius: 4px;">
                                    <div style="color: var(--cream); font-size: 13px;">{{ $submission->review_notes }}</div>
                                </div>
                            @else
                                <span style="color: var(--slate-blue);">—</span>
                            @endif
                        </td>
                        <td style="color: var(--slate-blue); white-space: nowrap;">{{ $submission->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card" style="text-align: center; padding: 40px 24px;">
        <i class="fas fa-inbox" style="font-size: 48px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
        <p style="color: var(--cream); font-size: 18px; margin-bottom: 8px;">لم تُرفع أي تسجيلات بعد</p>
        <p style="color: var(--slate-blue); margin-bottom: 16px;">ابدأ برفع تسجيلك الأول من الزر أعلاه</p>
    </div>
    @endif

</div>

<script>
// ========== TOGGLE UPLOAD FORM ==========
function toggleUploadForm() {
    const section = document.getElementById('uploadFormSection');
    const icon = document.getElementById('uploadToggleIcon');
    if (section.style.display === 'none') {
        section.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        section.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

// ========== FILE UPLOAD ==========
const dropZone = document.getElementById('dropZone');
const audioFileInput = document.getElementById('audioFile');

if (dropZone) {
    dropZone.addEventListener('click', () => audioFileInput.click());
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--gold)';
        dropZone.style.background = 'rgba(255,215,0,0.1)';
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = 'rgba(255,215,0,0.3)';
        dropZone.style.background = 'rgba(255,215,0,0.05)';
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(255,215,0,0.3)';
        dropZone.style.background = 'rgba(255,215,0,0.05)';
        if (e.dataTransfer.files.length > 0) {
            audioFileInput.files = e.dataTransfer.files;
            showFileInfo(e.dataTransfer.files[0]);
        }
    });
}

if (audioFileInput) {
    audioFileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            showFileInfo(this.files[0]);
        }
    });
}

function showFileInfo(file) {
    document.getElementById('dropZoneContent').style.display = 'none';
    document.getElementById('fileInfo').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
    checkFormValidity();
}

function clearFile() {
    audioFileInput.value = '';
    document.getElementById('dropZoneContent').style.display = 'block';
    document.getElementById('fileInfo').style.display = 'none';
    checkFormValidity();
}

// ========== SURAH SEARCH ==========
let allSurahs = [];

async function loadSurahs() {
    try {
        const response = await fetch('{{ route("api.recordings.surahs") }}');
        if (!response.ok) throw new Error('HTTP ' + response.status);
        allSurahs = await response.json();
        const searchEl = document.getElementById('surahSearch');
        if (searchEl) {
            searchEl.disabled = false;
            searchEl.placeholder = 'ابحث عن السورة...';
        }
    } catch (error) {
        console.error('خطأ تحميل السور:', error);
        const searchEl = document.getElementById('surahSearch');
        if (searchEl) searchEl.placeholder = 'خطأ في التحميل — أعد المحاولة';
    }
}

const surahSearchEl = document.getElementById('surahSearch');
if (surahSearchEl) {
    surahSearchEl.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        const dropdown = document.getElementById('surahDropdown');
        if (query.length === 0) { dropdown.style.display = 'none'; return; }

        const filtered = allSurahs.filter(s => {
            return (s.name_ar || '').toLowerCase().includes(query) ||
                   (s.name_en || '').toLowerCase().includes(query) ||
                   (s.number || '').toString() === query;
        });

        if (filtered.length === 0) {
            dropdown.innerHTML = '<div style="padding: 12px; color: var(--slate-blue); text-align: center;">لا توجد نتائج</div>';
            dropdown.style.display = 'block';
            return;
        }

        dropdown.innerHTML = filtered.map(s => `
            <div style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid rgba(255,215,0,0.1); transition: background 0.2s;"
                 onmouseover="this.style.background='rgba(255,215,0,0.1)'" onmouseout="this.style.background='transparent'"
                 data-id="${s.id}" data-name="${s.name_ar || s.name_en}" data-ayahs="${s.ayah_count || 0}">
                <span style="color: var(--gold); font-weight: 600;">${s.number} - ${s.name_ar || s.name_en}</span>
                <span style="color: var(--slate-blue); font-size: 12px; margin-right: 8px;">${s.ayah_count || 0} آية</span>
            </div>
        `).join('');

        dropdown.style.display = 'block';
        dropdown.querySelectorAll('div[data-id]').forEach(el => {
            el.addEventListener('click', function() {
                selectSurah(this.dataset.id, this.dataset.name, this.dataset.ayahs);
            });
        });
    });

    // إغلاق القائمة عند النقر خارجها
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#surahSearch') && !e.target.closest('#surahDropdown')) {
            document.getElementById('surahDropdown').style.display = 'none';
        }
    });
}

function selectSurah(id, name, ayahs) {
    document.getElementById('surah_id').value = id;
    document.getElementById('surahSearch').value = name;
    document.getElementById('surahDropdown').style.display = 'none';
    document.getElementById('juz_id').disabled = false;
    loadJuz(id, parseInt(ayahs));
}

async function loadJuz(surahId, maxAyahs) {
    try {
        const response = await fetch(`{{ route('api.recordings.surah.juz', ['surahId' => '__ID__']) }}`.replace('__ID__', surahId));
        if (!response.ok) throw new Error('HTTP ' + response.status);
        const juzzes = await response.json();

        const select = document.getElementById('juz_id');
        if (!juzzes || juzzes.length === 0) {
            select.innerHTML = '<option value="">لا توجد أجزاء</option>';
            return;
        }

        select.innerHTML = '<option value="">اختر الجزء</option>' +
            juzzes.map(j => `<option value="${j.id}">${j.name_ar || 'الجزء ' + (j.number || j.id)}</option>`).join('');

        select.addEventListener('change', function handler() {
            if (this.value) {
                loadAyahs(parseInt(surahId), parseInt(this.value), maxAyahs);
            }
        });
    } catch (error) {
        console.error('خطأ تحميل الأجزاء:', error);
    }
}

async function loadAyahs(surahId, juzId, maxAyahs) {
    try {
        const response = await fetch(`{{ route('api.recordings.surah.juz.ayahs', ['surahId' => '__ID__', 'juzId' => '__JID__']) }}`.replace('__ID__', surahId).replace('__JID__', juzId));
        if (!response.ok) throw new Error('HTTP ' + response.status);
        const data = await response.json();

        const fromInput = document.getElementById('ayah_from');
        const toInput = document.getElementById('ayah_to');
        const fromAyah = data.from || 1;
        const toAyah = data.to || maxAyahs || 286;

        fromInput.disabled = false;
        fromInput.min = fromAyah;
        fromInput.max = toAyah;
        fromInput.value = fromAyah;

        toInput.disabled = false;
        toInput.min = fromAyah;
        toInput.max = toAyah;
        toInput.value = toAyah;
        toInput.placeholder = `حتى ${toAyah}`;

        checkFormValidity();
    } catch (error) {
        console.error('خطأ تحميل الآيات:', error);
    }
}

// ========== FORM VALIDATION ==========
function checkFormValidity() {
    const btn = document.getElementById('submitBtn');
    if (!btn) return;
    const surahOk = document.getElementById('surah_id')?.value;
    const juzOk = document.getElementById('juz_id')?.value;
    const ayahOk = document.getElementById('ayah_from')?.value;
    const fileOk = document.getElementById('audioFile')?.files?.length > 0;
    btn.disabled = !(surahOk && juzOk && ayahOk && fileOk);
}

// مراقبة التغييرات على الحقول
['juz_id', 'ayah_from', 'ayah_to'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', checkFormValidity);
});

// ========== INIT ==========
document.addEventListener('DOMContentLoaded', loadSurahs);
</script>
@endsection
