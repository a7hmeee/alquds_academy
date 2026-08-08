@extends('layouts.student')

@section('page-title', 'تسجيل صوتي جديد')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    {{-- Status Messages --}}
    @if(session('success'))
        <div style="background: #10B981/20; border: 1px solid #10B981; color: #10B981; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #EF4444/20; border: 1px solid #EF4444; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="color: #EF4444; font-weight: 600; margin-bottom: 10px;">❌ حدثت أخطاء:</div>
            <ul style="color: #EF4444; margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom: 5px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div style="background: linear-gradient(135deg, var(--gold) 0%, var(--slate-blue) 100%); padding: 30px; border-radius: 12px; margin-bottom: 30px; color: white; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">
            🎙️ تسجيل صوتي جديد
        </div>
        <div style="font-size: 14px; opacity: 0.9;">
            سجّل آياتك القرآنية أو حمّل الملف الصوتي
        </div>
    </div>

    <form id="recordingForm" method="POST" action="{{ route('circles.submissions.store', $circle ?? 1) }}" enctype="multipart/form-data" onsubmit="handleFormSubmit(event)">
        @csrf

        <div style="display: grid; gap: 30px;">

            {{-- Step 1: Choose Surah --}}
            <div style="background: var(--dark-bg); padding: 24px; border-radius: 12px; border: 1px solid var(--gold)/30;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span style="background: var(--gold); color: var(--dark-bg); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700;">1</span>
                    <h2 style="margin: 0; color: var(--gold); font-size: 18px;">اختر السورة</h2>
                </div>

                <div style="position: relative;">
                    <input 
                        type="text" 
                        id="surahSearch" 
                        placeholder="جاري التحميل..." 
                        style="width: 100%; padding: 12px; border: 1px solid var(--gold)/30; background: var(--dark-bg); color: var(--cream); border-radius: 8px; font-size: 14px;"
                    >
                    <div id="surahDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: var(--dark-bg); border: 1px solid var(--gold); border-top: none; border-radius: 0 0 8px 8px; max-height: 250px; overflow-y: auto; display: none; z-index: 10;">
                    </div>
                </div>

                <input type="hidden" id="surah_id" name="surah_id" required>
                <div id="surahInfo" style="margin-top: 12px; padding: 12px; background: var(--gold)/10; border-radius: 6px; display: none;">
                    <div style="color: var(--cream); font-size: 14px;">
                        <strong id="surahName"></strong> - 
                        <span id="surahAyahs"></span> آية
                    </div>
                </div>
            </div>

            {{-- Step 2: Choose Juz --}}
            <div style="background: var(--dark-bg); padding: 24px; border-radius: 12px; border: 1px solid var(--gold)/30;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span style="background: var(--gold); color: var(--dark-bg); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700;">2</span>
                    <h2 style="margin: 0; color: var(--gold); font-size: 18px;">اختر الجزء</h2>
                </div>

                <select id="juz_id" name="juz_id" style="width: 100%; padding: 12px; border: 1px solid var(--gold)/30; background: var(--dark-bg); color: var(--cream); border-radius: 8px; font-size: 14px;" required disabled>
                    <option value="">اختر السورة أولاً</option>
                </select>
            </div>

            {{-- Step 3: Choose Ayahs --}}
            <div style="background: var(--dark-bg); padding: 24px; border-radius: 12px; border: 1px solid var(--gold)/30;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span style="background: var(--gold); color: var(--dark-bg); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700;">3</span>
                    <h2 style="margin: 0; color: var(--gold); font-size: 18px;">اختر الآيات</h2>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(160px, 100%), 1fr)); gap: 12px;">
                    <div>
                        <label style="color: var(--cream); font-size: 12px; display: block; margin-bottom: 6px;">من الآية *</label>
                        <input 
                            type="number" 
                            id="ayah_from" 
                            name="ayah_from" 
                            placeholder="1" 
                            min="1" 
                            style="width: 100%; padding: 10px; border: 1px solid var(--gold)/30; background: var(--dark-bg); color: var(--cream); border-radius: 6px; font-size: 14px;"
                            required disabled
                        >
                    </div>
                    <div>
                        <label style="color: var(--cream); font-size: 12px; display: block; margin-bottom: 6px;">إلى الآية (اختياري)</label>
                        <input 
                            type="number" 
                            id="ayah_to" 
                            name="ayah_to" 
                            placeholder="اتركه فارغاً لآية واحدة" 
                            min="1" 
                            style="width: 100%; padding: 10px; border: 1px solid var(--gold)/30; background: var(--dark-bg); color: var(--cream); border-radius: 6px; font-size: 14px;"
                            disabled
                        >
                    </div>
                </div>
            </div>

            {{-- Step 4: Record or Upload --}}
            <div style="background: var(--dark-bg); padding: 24px; border-radius: 12px; border: 1px solid var(--gold)/30;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <span style="background: var(--gold); color: var(--dark-bg); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700;">4</span>
                    <h2 style="margin: 0; color: var(--gold); font-size: 18px;">التسجيل أو الرفع</h2>
                </div>

                {{-- Tab Toggle --}}
                <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--gold)/30; padding-bottom: 0;">
                    <button type="button" id="recordTab" style="padding: 12px 20px; background: var(--gold); color: var(--dark-bg); border: none; border-radius: 6px 6px 0 0; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                        🎙️ تسجيل مباشر
                    </button>
                    <button type="button" id="uploadTab" style="padding: 12px 20px; background: transparent; color: var(--cream); border: none; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                        📁 رفع ملف
                    </button>
                </div>

                {{-- Record Panel --}}
                <div id="recordPanel" style="display: block;">
                    <div style="background: var(--gold)/10; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                        
                        {{-- Recording Status --}}
                        <div id="recordingStatus" style="text-align: center; margin-bottom: 20px; display: none;">
                            <div style="font-size: 24px; color: #EF4444; font-weight: 700; margin-bottom: 8px;">
                                🔴 جاري التسجيل
                            </div>
                            <div id="recordingTime" style="color: var(--cream); font-size: 32px; font-family: 'Courier New'; letter-spacing: 2px; font-weight: 700;">
                                00:00
                            </div>
                        </div>

                        {{-- Waveform Display --}}
                        <canvas id="waveform" style="width: 100%; height: 100px; background: var(--dark-bg); border-radius: 6px; display: none; margin-bottom: 16px;"></canvas>

                        {{-- Buttons --}}
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(150px, 100%), 1fr)); gap: 10px; margin-bottom: 16px;">
                            <button type="button" id="startRecordBtn" style="padding: 12px; background: #10B981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                                ▶️ ابدأ التسجيل
                            </button>
                            <button type="button" id="stopRecordBtn" style="padding: 12px; background: #EF4444/50; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; display: none;">
                                ⏹️ إيقاف
                            </button>
                        </div>

                        {{-- Recorded Audio Player --}}
                        <div id="recordedAudioDiv" style="display: none;">
                            <div style="color: var(--cream); font-size: 12px; margin-bottom: 8px; font-weight: 600;">✅ التسجيل كامل:</div>
                            <audio id="recordedAudio" style="width: 100%; margin-bottom: 12px; height: 40px;" controls></audio>
                            
                            <div style="display: flex; gap: 8px;">
                                <button type="button" id="retakeBtn" style="flex: 1; padding: 10px; background: var(--slate-blue); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px;">
                                    🔄 إعادة التسجيل
                                </button>
                                <button type="button" id="useRecordingBtn" style="flex: 1; padding: 10px; background: #10B981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px;">
                                    ✅ استخدم هذا التسجيل
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload Panel --}}
                <div id="uploadPanel" style="display: none;">
                    <div style="background: var(--gold)/10; padding: 20px; border-radius: 8px; border: 2px dashed var(--gold); text-align: center; cursor: pointer; transition: all 0.3s;" id="dropZone">
                        <div id="dropZoneContent">
                            <div style="font-size: 32px; margin-bottom: 10px;">📁</div>
                            <div style="color: var(--cream); margin-bottom: 4px;">اسحب الملف هنا أو اضغط للاختيار</div>
                            <div style="color: var(--slate-blue); font-size: 12px;">ملفات مدعومة: MP3, WAV, M4A, OGG (حتى 50 MB)</div>
                        </div>
                        <input type="file" id="audioFile" name="audio_file" accept="audio/*" style="display: none;">
                    </div>

                    <div id="uploadProgress" style="display: none; margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px; color: var(--cream);">
                            <span id="fileName"></span>
                            <span id="uploadPercent">0%</span>
                        </div>
                        <div style="background: var(--gold)/20; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div id="uploadBar" style="background: var(--gold); height: 100%; width: 0%; transition: width 0.3s;"></div>
                        </div>
                        <div style="margin-top: 8px; font-size: 12px; color: var(--slate-blue);">
                            السرعة: <span id="uploadSpeed">0</span> KB/s
                        </div>
                    </div>
                </div>

                {{-- Hidden file input for recording --}}
                <input type="hidden" id="audioFileHidden" name="audio_file_hidden">
            </div>

            {{-- Step 5: Notes --}}
            <div style="background: var(--dark-bg); padding: 24px; border-radius: 12px; border: 1px solid var(--gold)/30;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span style="background: var(--gold); color: var(--dark-bg); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700;">5</span>
                    <h2 style="margin: 0; color: var(--gold); font-size: 18px;">ملاحظاتك</h2>
                </div>

                <textarea 
                    name="notes" 
                    placeholder="أضف ملاحظات عن تسجيلك (نقاط ضعف، صعوبات، إلخ)..." 
                    style="width: 100%; padding: 12px; border: 1px solid var(--gold)/30; background: var(--dark-bg); color: var(--cream); border-radius: 8px; font-size: 14px; min-height: 100px; resize: vertical; font-family: inherit;"
                ></textarea>
            </div>

            {{-- Submit Button --}}
            <button type="submit" id="submitBtn" style="width: 100%; padding: 16px; background: linear-gradient(135deg, var(--gold) 0%, var(--slate-blue) 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 16px; transition: all 0.3s; disabled-opacity: 0.5;" disabled>
                ✅ حفظ التسجيل
            </button>
        </div>

    </form>
</div>

<script>
// ========== SURAH SEARCH ==========
let allSurahs = [];

async function loadSurahs() {
    try {
        console.log('جاري تحميل السور...');
        const response = await fetch('/api/recordings/surahs');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log(`تم تحميل ${data.length} سورة بنجاح`);
        allSurahs = data;
        
        // تفعيل البحث
        document.getElementById('surahSearch').disabled = false;
        document.getElementById('surahSearch').placeholder = 'ابحث عن السورة...';
    } catch (error) {
        console.error('خطأ في تحميل السور:', error);
        document.getElementById('surahSearch').placeholder = 'خطأ في التحميل';
    }
}

document.getElementById('surahSearch').addEventListener('input', function(e) {
    const query = e.target.value.trim().toLowerCase();
    const dropdown = document.getElementById('surahDropdown');
    
    if (query.length === 0) {
        dropdown.style.display = 'none';
        return;
    }

    // البحث في جميع الحقول
    const filtered = allSurahs.filter(surah => {
        const nameAr = (surah.name_ar || '').toLowerCase();
        const nameEn = (surah.name_en || '').toLowerCase();
        const number = (surah.number || '').toString();
        
        return nameAr.includes(query) || 
               nameEn.includes(query) || 
               number === query;
    });

    if (filtered.length === 0) {
        dropdown.innerHTML = '<div style="padding: 12px; color: var(--slate-blue); text-align: center;">❌ لا توجد نتائج</div>';
        dropdown.style.display = 'block';
        return;
    }

    dropdown.innerHTML = filtered.map(surah => `
        <div style="padding: 12px; cursor: pointer; border-bottom: 1px solid var(--gold)/10; transition: all 0.2s;" 
             onmouseover="this.style.background='var(--gold)/10'" 
             onmouseout="this.style.background='transparent'"
             data-surah-id="${surah.id}"
             data-surah-name="${surah.name_ar || surah.name_en}"
             data-surah-ayahs="${surah.ayah_count || 0}">
            <div style="color: var(--gold); font-weight: 600; margin-bottom: 4px;">
                ${surah.number} - ${surah.name_ar || surah.name_en}
            </div>
            <div style="color: var(--slate-blue); font-size: 12px;">
                ${surah.name_en || ''} • ${surah.ayah_count || 0} آية
            </div>
        </div>
    `).join('');
    
    dropdown.style.display = 'block';

    // إضافة معالجات الضغط
    dropdown.querySelectorAll('div[data-surah-id]').forEach(el => {
        el.addEventListener('click', function() {
            selectSurah(
                this.dataset.surahId,
                this.dataset.surahName,
                this.dataset.surahAyahs
            );
        });
    });
});

function selectSurah(id, name, ayahs) {
    document.getElementById('surah_id').value = id;
    document.getElementById('surahSearch').value = name;
    document.getElementById('surahDropdown').style.display = 'none';
    
    document.getElementById('surahName').textContent = name;
    document.getElementById('surahAyahs').textContent = ayahs;
    document.getElementById('surahInfo').style.display = 'block';
    
    console.log(`تم اختيار السورة: ${name} (ID: ${id})`);
    
    // تحميل الأجزاء
    loadJuz(id, parseInt(ayahs));
    
    // تفعيل الحقول التالية
    document.getElementById('juz_id').disabled = false;
}

async function loadJuz(surahId, maxAyahs) {
    try {
        console.log(`جاري تحميل أجزاء السورة ${surahId}...`);
        const response = await fetch(`/api/recordings/surah/${surahId}/juz`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const juzzes = await response.json();
        console.log(`تم تحميل ${juzzes.length} أجزاء:`, juzzes);
        
        if (!juzzes || juzzes.length === 0) {
            console.warn('⚠️ لا توجد أجزاء للسورة');
            document.getElementById('juz_id').innerHTML = '<option value="">لا توجد أجزاء متاحة</option>';
            return;
        }

        const select = document.getElementById('juz_id');
        console.log('====== JUZ OPTIONS DEBUG ======');
        const options = juzzes.map(juz => {
            console.log(`Raw juz object:`, juz);
            console.log(`Type of juz.id:`, typeof juz.id, `Value:`, juz.id);
            const juzName = juz.name_ar || `الجزء ${juz.number || juz.id}`;
            const optionHtml = `<option value="${juz.id}" data-id="${juz.id}" data-number="${juz.number}">${juzName}</option>`;
            console.log(`Generated: ${optionHtml}`);
            return optionHtml;
        }).join('');
        console.log('====== END DEBUG ======');
        
        select.innerHTML = '<option value="">اختر الجزء</option>' + options;
        
        select.addEventListener('change', function() {
            if (this.value) {
                // تأكد من تمرير surahId الصحيح
                const selectedText = this.options[this.selectedIndex].text;
                const selectedJuzId = this.value;
                console.log(`====== JUZ SELECTED ======`);
                console.log(`Selected text: ${selectedText}`);
                console.log(`Selected value: ${selectedJuzId}`);
                console.log(`Type of value: ${typeof selectedJuzId}`);
                console.log(`Parsed as int: ${parseInt(selectedJuzId)}`);
                console.log(`Surah ID: ${surahId} (type: ${typeof surahId})`);
                console.log(`Max Ayahs: ${maxAyahs}`);
                console.log(`====== END SELECT =====`);
                loadAyahs(parseInt(surahId), parseInt(selectedJuzId), maxAyahs);
            }
        });
        
        console.log('✅ تم تحميل الأجزاء بنجاح');
    } catch (error) {
        console.error('❌ خطأ في تحميل الأجزاء:', error);
        document.getElementById('juz_id').innerHTML = `<option value="">❌ خطأ في التحميل: ${error.message}</option>`;
    }
}

async function loadAyahs(surahId, juzId, maxAyahs) {
    try {
        console.log(`جاري تحميل الآيات: السورة ${surahId}, الجزء ${juzId}...`);
        const response = await fetch(`/api/recordings/surah/${surahId}/juz/${juzId}/ayahs`);
        
        const responseText = await response.text();
        console.log('Response status:', response.status);
        console.log('Response text:', responseText.substring(0, 200));
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = JSON.parse(responseText);
        console.log('Parsed data:', data);
        
        // تعامل مع البيانات الجديدة
        let fromAyah = data.from || 1;
        let toAyah = data.to || maxAyahs || 286;
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        console.log(`النطاق: من ${fromAyah} إلى ${toAyah}`);
        
        const fromInput = document.getElementById('ayah_from');
        const toInput = document.getElementById('ayah_to');
        
        fromInput.disabled = false;
        fromInput.min = fromAyah;
        fromInput.max = toAyah;
        fromInput.value = fromAyah;
        
        toInput.disabled = false;
        toInput.min = fromAyah;
        toInput.max = toAyah;
        toInput.placeholder = `حتى ${toAyah}`;
        toInput.value = toAyah;
        
        console.log('✅ تم تحميل الآيات بنجاح');
        checkFormValidity();
    } catch (error) {
        console.error('❌ خطأ في تحميل الآيات:', error);
        alert('خطأ في تحميل الآيات: ' + error.message);
    }
}

// ========== RECORDING FUNCTIONALITY ==========
let mediaRecorder;
let audioChunks = [];
let recordingStartTime;
let timerInterval;
let recordedBlob = null;  // حفظ الـ blob هنا

document.getElementById('startRecordBtn').addEventListener('click', async function(e) {
    e.preventDefault();
    
    try {
        console.log('🎙️ جاري طلب الوصول إلى الميكروفون...');
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        console.log('✅ تم الوصول إلى الميكروفون');
        
        mediaRecorder = new MediaRecorder(stream);
        
        audioChunks = [];
        recordingStartTime = Date.now();
        
        mediaRecorder.ondataavailable = (event) => {
            console.log('📊 جزء صوتي تم التقاطه:', event.data.size, 'bytes');
            audioChunks.push(event.data);
        };
        
        mediaRecorder.onstop = () => {
            console.log('⏹️ تم إيقاف التسجيل - عدد الأجزاء:', audioChunks.length);
            
            // إنشاء blob
            recordedBlob = new Blob(audioChunks, { type: 'audio/webm' });
            console.log('📦 حجم الـ blob:', recordedBlob.size, 'bytes');
            
            if (recordedBlob.size === 0) {
                alert('❌ خطأ: التسجيل فارغ! جرب مرة أخرى');
                console.error('❌ الـ blob فارغ!');
                return;
            }
            
            // تعيين إلى الـ audio player
            const audioUrl = URL.createObjectURL(recordedBlob);
            console.log('🔗 URL للتشغيل:', audioUrl);
            
            const audioElement = document.getElementById('recordedAudio');
            audioElement.src = audioUrl;
            
            // تعيين إلى الـ form input
            try {
                const file = new File([recordedBlob], `recording_${Date.now()}.webm`, { type: 'audio/webm' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                
                const fileInput = document.getElementById('audioFile');
                fileInput.files = dataTransfer.files;
                
                console.log('✅ تم تعيين الملف إلى الـ form input');
                console.log('📄 اسم الملف:', file.name);
                console.log('📏 حجم الملف:', file.size, 'bytes');
            } catch (err) {
                console.error('❌ خطأ في تعيين الملف:', err);
            }
            
            document.getElementById('recordedAudioDiv').style.display = 'block';
            document.getElementById('recordingStatus').style.display = 'none';
            
            clearInterval(timerInterval);
        };
        
        mediaRecorder.start();
        console.log('▶️ بدأ التسجيل');
        
        // Update UI
        document.getElementById('startRecordBtn').style.display = 'none';
        document.getElementById('stopRecordBtn').style.display = 'block';
        document.getElementById('recordingStatus').style.display = 'block';
        document.getElementById('recordedAudioDiv').style.display = 'none';
        
        // Start timer
        timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('recordingTime').textContent = 
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }, 100);
        
    } catch (error) {
        console.error('❌ خطأ:', error);
        alert('❌ خطأ في الوصول إلى الميكروفون:\n\n' + error.message + '\n\n🔧 تأكد من:\n1. السماح بالميكروفون في الإعدادات\n2. وجود ميكروفون مُتصل\n3. لا يوجد تطبيق آخر يستخدم الميكروفون');
    }
});

document.getElementById('stopRecordBtn').addEventListener('click', function(e) {
    e.preventDefault();
    if (mediaRecorder) {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
        
        document.getElementById('startRecordBtn').style.display = 'block';
        document.getElementById('stopRecordBtn').style.display = 'none';
    }
});

document.getElementById('retakeBtn').addEventListener('click', function(e) {
    e.preventDefault();
    audioChunks = [];
    document.getElementById('recordedAudioDiv').style.display = 'none';
    document.getElementById('startRecordBtn').style.display = 'block';
    document.getElementById('recordingStatus').style.display = 'none';
});

document.getElementById('useRecordingBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    // التأكد من وجود ملف صوتي
    if (audioChunks.length === 0) {
        alert('❌ لم يتم تسجيل أي صوت! أعد المحاولة');
        return;
    }
    
    // إخفاء panel التسجيل
    document.getElementById('recordPanel').style.display = 'none';
    
    // إظهار رسالة نجاح مؤقتة
    const recordStatus = document.getElementById('recordingStatus');
    recordStatus.innerHTML = '<div style="color: #10B981; font-weight: 700; font-size: 18px;">✅ تم! التسجيل محفوظ ومجهز للإرسال</div>';
    recordStatus.style.display = 'block';
    
    console.log('✅ تم اختيار التسجيل - عدد الـ audio chunks:', audioChunks.length);
    
    // تحديث حالة الـ form
    checkFormValidity();
});

// ========== TAB TOGGLE ==========
document.getElementById('recordTab').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('recordPanel').style.display = 'block';
    document.getElementById('uploadPanel').style.display = 'none';
    
    this.style.background = 'var(--gold)';
    this.style.color = 'var(--dark-bg)';
    document.getElementById('uploadTab').style.background = 'transparent';
    document.getElementById('uploadTab').style.color = 'var(--cream)';
});

document.getElementById('uploadTab').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('recordPanel').style.display = 'none';
    document.getElementById('uploadPanel').style.display = 'block';
    
    this.style.background = 'var(--gold)';
    this.style.color = 'var(--dark-bg)';
    document.getElementById('recordTab').style.background = 'transparent';
    document.getElementById('recordTab').style.color = 'var(--cream)';
});

// ========== FILE UPLOAD ==========
const dropZone = document.getElementById('dropZone');
const audioFile = document.getElementById('audioFile');

dropZone.addEventListener('click', () => audioFile.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.background = 'var(--gold)/20';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.background = 'var(--gold)/10';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        audioFile.files = files;
        handleFileSelect(files[0]);
    }
});

audioFile.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFileSelect(e.target.files[0]);
    }
});

function handleFileSelect(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('dropZoneContent').style.display = 'none';
    document.getElementById('uploadProgress').style.display = 'block';
    
    checkFormValidity();
}

// ========== FORM VALIDATION ==========
function checkFormValidity() {
    const hasAudioFile = document.getElementById('audioFile').files.length > 0;
    const hasSurah = document.getElementById('surah_id').value !== '';
    const hasJuz = document.getElementById('juz_id').value !== '';
    const hasAyahFrom = document.getElementById('ayah_from').value !== '';
    
    console.log('📋 Form Validation:');
    console.log('  ✓ Audio File:', hasAudioFile, '(files:', document.getElementById('audioFile').files.length, ')');
    console.log('  ✓ Surah:', hasSurah, '(value:', document.getElementById('surah_id').value, ')');
    console.log('  ✓ Juz:', hasJuz, '(value:', document.getElementById('juz_id').value, ')');
    console.log('  ✓ Ayah From:', hasAyahFrom, '(value:', document.getElementById('ayah_from').value, ')');
    
    const isValid = hasAudioFile && hasSurah && hasJuz && hasAyahFrom;
    console.log('  → Form Valid:', isValid);
    
    document.getElementById('submitBtn').disabled = !isValid;
}

// تأكد من الـ validation عند التغيير
document.getElementById('audioFile').addEventListener('change', checkFormValidity);
document.getElementById('surah_id').addEventListener('change', checkFormValidity);
document.getElementById('juz_id').addEventListener('change', checkFormValidity);
document.getElementById('ayah_from').addEventListener('change', checkFormValidity);

// ========== FORM SUBMISSION ==========
function handleFormSubmit(event) {
    const audioFile = document.getElementById('audioFile');
    
    console.log('📤 جاري إرسال الـ form...');
    console.log('  ✓ Audio Files Count:', audioFile.files.length);
    
    if (audioFile.files.length > 0) {
        const file = audioFile.files[0];
        console.log('  ✓ File Name:', file.name);
        console.log('  ✓ File Size:', file.size, 'bytes');
        console.log('  ✓ File Type:', file.type);
        
        if (file.size === 0) {
            event.preventDefault();
            alert('❌ خطأ: الملف فارغ! جرب التسجيل مرة أخرى');
            return false;
        }
    } else {
        console.log('  ❌ لا توجد ملفات صوتية!');
        event.preventDefault();
        alert('❌ يجب تسجيل أو رفع ملف صوتي');
        return false;
    }
    
    console.log('✅ الـ form جاهز للإرسال');
    return true;
}

// ========== INIT ==========
console.log('🚀 جاري تحميل الصفحة...');

// تأكد من تحميل الـ DOM قبل تشغيل الـ JS
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('📄 DOM loaded');
        loadSurahs();
        console.log('✅ تم تفعيل البحث');
    });
} else {
    console.log('📄 DOM already loaded');
    loadSurahs();
    console.log('✅ تم تفعيل البحث');
}
</script>

<style>
    input:disabled, select:disabled, textarea:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection
