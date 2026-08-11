@extends('layouts.student')

@section('page-title', 'رفع تسجيل جديد')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 30px;">
        <h1 style="color: var(--cream); font-size: 28px; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-upload" style="color: var(--gold);"></i> رفع تسجيل جديد
        </h1>
        <p style="color: var(--slate-blue);">اختر السورة والجزء والآيات، ثم رفع ملفك الصوتي</p>
    </div>

    <form id="uploadForm" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf

        {{-- Circle Selection (Hidden) --}}
        <input type="hidden" name="circle_id" value="{{ $circle->id }}">

        {{-- Step 1: Select Surah --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-book" style="color: var(--gold);"></i>
                الخطوة 1: اختر السورة
            </div>

            <div class="form-group">
                <label class="form-label">السورة *</label>
                <div style="position: relative;">
                    <input 
                        type="text" 
                        id="surahSearch" 
                        placeholder="ابحث عن السورة..." 
                        class="form-control"
                        style="background: var(--dark-bg); color: var(--cream); border: 1px solid var(--border); padding: 12px; border-radius: 6px; width: 100%; font-size: 14px;"
                    >
                    <input type="hidden" name="surah_id" id="surahIdInput">
                    
                    {{-- Dropdown List --}}
                    <div id="surahDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--dark-bg); border: 1px solid var(--border); border-top: none; max-height: 300px; overflow-y: auto; z-index: 10; border-radius: 0 0 6px 6px;">
                        <div id="surahList"></div>
                    </div>
                </div>
                <div id="surahError" style="color: #EF4444; font-size: 12px; margin-top: 4px; display: none;"></div>
            </div>

            {{-- Selected Surah Info --}}
            <div id="surahInfo" style="display: none; background: var(--gold)/10; padding: 12px; border-radius: 6px; margin-top: 12px;">
                <div style="color: var(--cream); font-weight: 600;">
                    <i class="fas fa-check-circle" style="color: var(--gold);"></i>
                    <span id="surahInfoText"></span>
                </div>
            </div>
        </div>

        {{-- Step 2: Select Juz --}}
        <div class="card" id="juzStep" style="display: none; opacity: 0.5;">
            <div class="card-title">
                <i class="fas fa-layer-group" style="color: var(--gold);"></i>
                الخطوة 2: اختر الجزء
            </div>

            <div class="form-group">
                <label class="form-label">الجزء *</label>
                <select name="juz_id" id="juzSelect" class="form-control" disabled>
                    <option value="">اختر الجزء...</option>
                </select>
                <div id="juzError" style="color: #EF4444; font-size: 12px; margin-top: 4px; display: none;"></div>
            </div>
        </div>

        {{-- Step 3: Select Ayahs --}}
        <div class="card" id="ayahStep" style="display: none; opacity: 0.5;">
            <div class="card-title">
                <i class="fas fa-align-left" style="color: var(--gold);"></i>
                الخطوة 3: اختر الآيات
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="form-group">
                    <label class="form-label">من الآية *</label>
                    <input type="number" name="ayah_from" id="ayahFrom" class="form-control" min="1" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">إلى الآية (اختياري)</label>
                    <input type="number" name="ayah_to" id="ayahTo" class="form-control" min="1" disabled>
                </div>
            </div>
            <div id="ayahError" style="color: #EF4444; font-size: 12px; margin-top: 4px; display: none;"></div>
        </div>

        {{-- Step 4: File Upload --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-microphone" style="color: var(--gold);"></i>
                الخطوة 4: رفع الملف الصوتي
            </div>

            <div class="form-group">
                <label class="form-label">الملف الصوتي (mp3, wav, m4a, ogg) - بحد أقصى 50 ميجابايت *</label>
                <div 
                    id="dropZone" 
                    style="border: 2px dashed var(--border); border-radius: 8px; padding: 32px; text-align: center; background: var(--dark-bg)/30; transition: all 0.3s ease; cursor: pointer;"
                >
                    <div style="color: var(--slate-blue); margin-bottom: 12px;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 40px;"></i>
                    </div>
                    <input type="file" name="audio" id="audioInput" accept="audio/*" style="display: none;">
                    <label for="audioInput" style="cursor: pointer; color: var(--gold); font-weight: 500; display: block;">
                        اختر الملف أو اسحبه هنا
                    </label>
                    <div style="color: var(--slate-blue); font-size: 12px; margin-top: 8px;" id="fileName">
                        حجم الملف الأقصى: 50 ميجابايت
                    </div>
                    <div style="color: #10B981; font-size: 12px; margin-top: 4px; display: none;" id="recordingDuration">
                        المدة: <span id="recordingDurationValue">0:00</span>
                    </div>
                </div>
                <div id="audioError" style="color: #EF4444; font-size: 12px; margin-top: 4px; display: none;"></div>
            </div>

            {{-- Progress Bar --}}
            <div id="progressWrap" style="display: none; margin-top: 16px;">
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 8px;">جارٍ التحميل...</div>
                <div style="width: 100%; height: 8px; background: var(--dark-bg)/30; border-radius: 4px; overflow: hidden;">
                    <div id="progressBar" style="height: 100%; background: linear-gradient(90deg, var(--gold), #10B981); width: 0%; transition: width 0.1s ease;"></div>
                </div>
                <div style="color: var(--slate-blue); font-size: 12px; margin-top: 4px;">
                    <span id="progressPct">0</span>% | <span id="uploadSpeed">0 KB/s</span>
                </div>
            </div>
        </div>

        {{-- Step 5: Additional Info --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-sticky-note" style="color: var(--gold);"></i>
                الخطوة 5: ملاحظات إضافية (اختيارية)
            </div>

            <div class="form-group">
                <label class="form-label">ملاحظاتك على التسجيل</label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="4"
                    class="form-control" 
                    placeholder="مثال: أشعر بصعوبة في التجويد..."
                    style="background: var(--dark-bg); color: var(--cream); border: 1px solid var(--border); padding: 12px; border-radius: 6px; font-family: inherit;"
                ></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">صورة توضيحية (اختيارية)</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
        </div>

        {{-- Submit Button --}}
        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('recordings.dashboard') }}" class="btn btn-secondary" style="padding: 12px 24px;">
                إلغاء
            </a>
            <button type="submit" id="submitBtn" class="btn btn-primary" style="padding: 12px 24px; opacity: 0.5; cursor: not-allowed;" disabled>
                <i class="fas fa-upload"></i> رفع التسجيل
            </button>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script>
    const surahSearch = document.getElementById('surahSearch');
    const surahDropdown = document.getElementById('surahDropdown');
    const surahList = document.getElementById('surahList');
    const surahIdInput = document.getElementById('surahIdInput');
    const surahInfo = document.getElementById('surahInfo');
    const surahInfoText = document.getElementById('surahInfoText');
    const juzStep = document.getElementById('juzStep');
    const juzSelect = document.getElementById('juzSelect');
    const ayahStep = document.getElementById('ayahStep');
    const ayahFrom = document.getElementById('ayahFrom');
    const ayahTo = document.getElementById('ayahTo');
    const dropZone = document.getElementById('dropZone');
    const audioInput = document.getElementById('audioInput');
    const fileName = document.getElementById('fileName');
    const recordingDuration = document.getElementById('recordingDuration');
    const recordingDurationValue = document.getElementById('recordingDurationValue');
    const progressWrap = document.getElementById('progressWrap');
    const progressBar = document.getElementById('progressBar');
    const progressPct = document.getElementById('progressPct');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const uploadForm = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');

    let allSurahs = [];
    let selectedSurah = null;
    let selectedJuz = null;
    let lastTime = 0;
    let lastLoaded = 0;

    // دالة لتحويل الوقت إلى mm:ss
    function formatDuration(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    }

    // التحقق من اكتمال الخطوات
    function validateSteps() {
        const hasAudio = audioInput.files.length > 0;
        const hasAyah = ayahFrom.value && surahIdInput.value && juzSelect.value;
        
        if (hasAudio && hasAyah) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    }

    // تحميل السور في البداية
    async function loadSurahs() {
        try {
            const response = await fetch('{{ route("api.recordings.surahs") }}');
            allSurahs = await response.json();
            console.log('Surahs loaded:', allSurahs.length);
        } catch (error) {
            console.error('Error loading surahs:', error);
        }
    }

    // البحث عن السور
    surahSearch.addEventListener('input', async (e) => {
        const query = e.target.value.trim();
        
        if (query.length < 1) {
            surahDropdown.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`{{ route("api.recordings.surahs.search") }}?q=${encodeURIComponent(query)}`);
            const results = await response.json();

            surahList.innerHTML = results.slice(0, 15).map(surah => `
                <div data-surah-id="${surah.id}" style="padding: 10px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--gold)/10'" onmouseout="this.style.background='transparent'">
                    <div style="color: var(--cream); font-weight: 500;">${surah.name_ar}</div>
                    <div style="color: var(--slate-blue); font-size: 12px;">${surah.name_en} | السورة ${surah.number}</div>
                </div>
            `).join('');

            surahDropdown.style.display = 'block';

            // Event listeners للخيارات
            document.querySelectorAll('#surahList > div').forEach(item => {
                item.addEventListener('click', selectSurah);
            });

        } catch (error) {
            console.error('Error searching:', error);
        }
    });

    // اختيار السورة
    async function selectSurah(e) {
        const surahId = e.currentTarget.dataset.surahId;
        selectedSurah = allSurahs.find(s => s.id == surahId);

        if (!selectedSurah) return;

        surahIdInput.value = surahId;
        surahSearch.value = selectedSurah.name_ar;
        surahDropdown.style.display = 'none';

        // عرض معلومات السورة
        surahInfo.style.display = 'block';
        surahInfoText.innerHTML = `${selectedSurah.name_ar} - ${selectedSurah.ayah_count} آية | ${selectedSurah.juz_count} أجزاء`;

        // تحميل الأجزاء
        await loadJuz(surahId);

        // إظهار خطوة الجزء
        juzStep.style.display = 'block';
        juzSelect.disabled = false;
        juzStep.style.opacity = '1';

        validateSteps();
    }

    // تحميل الأجزاء
    async function loadJuz(surahId) {
        try {
            const response = await fetch(`{{ route('api.recordings.surah.juz', ['surahId' => '__ID__']) }}`.replace('__ID__', surahId));
            if (!response.ok) {
                console.error('Juz API error:', response.status, await response.text());
                return;
            }
            const juzData = await response.json();

            if (!Array.isArray(juzData) || juzData.length === 0) {
                console.warn('No juz data for surah', surahId);
                return;
            }

            juzSelect.innerHTML = '<option value="">اختر الجزء...</option>' + juzData.map(juz => `
                <option value="${juz.id}">${juz.name_ar}</option>
            `).join('');

            juzSelect.addEventListener('change', selectJuz);

        } catch (error) {
            console.error('Error loading juz:', error);
        }
    }

    // اختيار الجزء
    async function selectJuz(e) {
        const juzId = e.target.value;
        
        if (!juzId) {
            ayahStep.style.display = 'none';
            ayahFrom.value = '';
            ayahTo.value = '';
            validateSteps();
            return;
        }

        // إظهار خطوة الآيات
        ayahStep.style.display = 'block';
        ayahFrom.disabled = false;
        ayahTo.disabled = false;
        ayahStep.style.opacity = '1';

        // تحديد الحد الأقصى للآيات
        const surahId = surahIdInput.value;
        if (selectedSurah) {
            ayahFrom.max = selectedSurah.ayah_count;
            ayahTo.max = selectedSurah.ayah_count;
        }

        ayahFrom.addEventListener('change', validateSteps);
        ayahTo.addEventListener('change', validateSteps);

        validateSteps();
    }

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.background = 'var(--gold)/10';
        dropZone.style.borderColor = 'var(--gold)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.background = 'var(--dark-bg)/30';
        dropZone.style.borderColor = 'var(--border)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            audioInput.files = files;
            handleAudioFile(files[0]);
        }
        dropZone.style.background = 'var(--dark-bg)/30';
        dropZone.style.borderColor = 'var(--border)';
    });

    audioInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleAudioFile(e.target.files[0]);
        }
    });

    function handleAudioFile(file) {
        fileName.textContent = file.name;
        
        // قراءة مدة الصوتية
        const audio = new Audio();
        audio.addEventListener('loadedmetadata', () => {
            recordingDurationValue.textContent = formatDuration(audio.duration);
            recordingDuration.style.display = 'block';
        });
        audio.src = URL.createObjectURL(file);

        validateSteps();
    }

    // معالجة الـ form submission
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // التحقق من الحقول المطلوبة
        if (!surahIdInput.value || !juzSelect.value || !ayahFrom.value || !audioInput.files.length) {
            alert('يرجى ملء جميع الحقول المطلوبة');
            return;
        }

        const formData = new FormData(uploadForm);
        const xhr = new XMLHttpRequest();

        progressWrap.style.display = 'block';

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressPct.textContent = percent;

                const currentTime = Date.now();
                const timeDiff = (currentTime - lastTime) / 1000;
                const loadedDiff = (e.loaded - lastLoaded) / (1024 * 1024);

                if (timeDiff > 0.5) {
                    const speed = loadedDiff / timeDiff;
                    uploadSpeed.textContent = speed.toFixed(2) + ' MB/s';
                    lastTime = currentTime;
                    lastLoaded = e.loaded;
                }
            }
        });

        xhr.addEventListener('load', () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                window.location.href = '{{ route("recordings.dashboard") }}?success=1';
            } else {
                const response = JSON.parse(xhr.responseText);
                alert(response.error || 'فشل الرفع');
                progressWrap.style.display = 'none';
            }
        });

        xhr.addEventListener('error', () => {
            alert('حدث خطأ أثناء الرفع');
            progressWrap.style.display = 'none';
        });

        xhr.open('POST', '{{ route("recordings.store") }}');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });

    // تحميل السور عند تحميل الصفحة
    loadSurahs();
</script>

<style>
    .form-control {
        background: var(--dark-bg);
        color: var(--cream);
        border: 1px solid var(--border);
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 2px var(--gold)/20;
    }

    .form-control:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .form-label {
        display: block;
        color: var(--cream);
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 16px;
    }
</style>
@endsection
