@extends('layouts.student')

@section('page-title', 'استيراد التسجيلات بكميات كبيرة')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 30px;">
        <a href="{{ route('recordings.dashboard') }}" style="color: var(--gold); text-decoration: none; font-size: 14px; margin-bottom: 16px; display: inline-block;">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
        <h1 style="color: var(--cream); font-size: 28px; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-upload" style="color: var(--gold);"></i> استيراد تسجيلات جماعية
        </h1>
        <p style="color: var(--slate-blue);">رفع عدة تسجيلات دفعة واحدة باستخدام ملف CSV</p>
    </div>

    {{-- Info Card --}}
    <div class="card" style="background: var(--gold)/10; border-left: 4px solid var(--gold); margin-bottom: 24px;">
        <div style="color: var(--gold); font-weight: 600; margin-bottom: 12px;">
            <i class="fas fa-info-circle"></i> معلومات هامة
        </div>
        <div style="color: var(--cream); font-size: 14px; line-height: 1.6;">
            <ul>
                <li>✓ يجب أن يكون الملف بصيغة CSV</li>
                <li>✓ الحد الأقصى لحجم الملف: 5 ميجابايت</li>
                <li>✓ يجب تتبع نفس صيغة النموذج</li>
                <li>✓ جميع التسجيلات ستُضاف إلى حلقتك الحالية</li>
            </ul>
        </div>
    </div>

    {{-- Main Form --}}
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-title">
            <i class="fas fa-cloud-upload-alt" style="color: var(--gold);"></i>
            رفع الملف
        </div>

        <form id="bulkImportForm" enctype="multipart/form-data">
            @csrf

            {{-- Drop Zone --}}
            <div 
                id="dropZone" 
                style="border: 2px dashed var(--border); border-radius: 8px; padding: 48px 24px; text-align: center; background: var(--dark-bg)/30; transition: all 0.3s ease; cursor: pointer; margin-bottom: 24px;"
            >
                <div style="color: var(--slate-blue); margin-bottom: 16px;">
                    <i class="fas fa-file-csv" style="font-size: 48px;"></i>
                </div>
                <input type="file" name="file" id="fileInput" accept=".csv,.xlsx,.xls" style="display: none;">
                <label for="fileInput" style="cursor: pointer; color: var(--gold); font-weight: 500; display: block; margin-bottom: 8px;">
                    اختر الملف أو اسحبه هنا
                </label>
                <div style="color: var(--slate-blue); font-size: 12px;">
                    CSV أو Excel (.csv, .xlsx, .xls)
                </div>
            </div>

            {{-- Selected File Info --}}
            <div id="fileInfo" style="display: none; background: #10B981/10; border-left: 4px solid #10B981; padding: 16px; border-radius: 4px; margin-bottom: 24px;">
                <div style="color: #10B981; font-weight: 600; margin-bottom: 4px;">
                    <i class="fas fa-check-circle"></i> الملف المختار
                </div>
                <div style="color: var(--cream); font-size: 14px; overflow-wrap: break-word;">
                    <span id="selectedFileName"></span> - <span id="selectedFileSize"></span>
                </div>
            </div>

            {{-- Submit Button --}}
            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <button type="submit" id="submitBtn" class="btn btn-primary" style="padding: 12px 24px; flex: 1; min-width: 160px; opacity: 0.5; cursor: not-allowed;" disabled>
                    <i class="fas fa-upload"></i> استيراد التسجيلات
                </button>
                <a href="{{ route('recordings.bulkImport.template') }}" class="btn btn-secondary" style="padding: 12px 24px; text-align: center; flex: 1; min-width: 160px;">
                    <i class="fas fa-download"></i> تحميل النموذج
                </a>
            </div>
        </form>
    </div>

    {{-- Results Section --}}
    <div id="resultsSection" style="display: none;">
        {{-- Progress Card --}}
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-title">
                <i class="fas fa-chart-bar" style="color: var(--gold);"></i>
                نتائج الاستيراد
            </div>

            {{-- Progress Bar --}}
            <div id="progressBar" style="display: none; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <div style="color: var(--slate-blue); font-size: 12px;">جارٍ المعالجة...</div>
                    <div id="progressText" style="color: var(--gold); font-size: 12px;"></div>
                </div>
                <div style="width: 100%; height: 8px; background: var(--dark-bg)/30; border-radius: 4px; overflow: hidden;">
                    <div id="progressFill" style="height: 100%; background: linear-gradient(90deg, var(--gold), #10B981); width: 0%; transition: width 0.3s ease;"></div>
                </div>
            </div>

            {{-- Statistics --}}
            <div id="stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px; margin-bottom: 20px;">
                <div style="background: #10B981/10; padding: 12px; border-radius: 4px; text-align: center;">
                    <div style="color: #10B981; font-size: 20px; font-weight: bold;" id="totalCount">0</div>
                    <div style="color: var(--slate-blue); font-size: 12px;">إجمالي</div>
                </div>
                <div style="background: #10B981/10; padding: 12px; border-radius: 4px; text-align: center;">
                    <div style="color: #10B981; font-size: 20px; font-weight: bold;" id="successCount">0</div>
                    <div style="color: var(--slate-blue); font-size: 12px;">نجح</div>
                </div>
                <div style="background: #EF4444/10; padding: 12px; border-radius: 4px; text-align: center;">
                    <div style="color: #EF4444; font-size: 20px; font-weight: bold;" id="failureCount">0</div>
                    <div style="color: var(--slate-blue); font-size: 12px;">فشل</div>
                </div>
            </div>

            {{-- Error Messages --}}
            <div id="errorsList" style="display: none;">
                <div style="color: #EF4444; font-weight: 600; margin-bottom: 12px;">🔴 الأخطاء:</div>
                <div id="errorsContent" style="background: #EF4444/10; border-left: 4px solid #EF4444; padding: 12px; border-radius: 4px; max-height: 300px; overflow-y: auto;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <!-- Will be populated -->
                    </ul>
                </div>
            </div>

            {{-- Success Message --}}
            <div id="successMessage" style="display: none; background: #10B981/10; border-left: 4px solid #10B981; padding: 16px; border-radius: 4px; text-align: center;">
                <div style="color: #10B981; font-size: 16px; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> تم الاستيراد بنجاح!
                </div>
                <div style="color: var(--slate-blue); font-size: 12px; margin-top: 4px;">
                    تحقق من لوحة التسجيلات لعرض التسجيلات الجديدة
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('recordings.dashboard') }}" class="btn btn-primary" style="padding: 12px 24px; flex: 1; text-align: center;">
                <i class="fas fa-arrow-left"></i> العودة إلى التسجيلات
            </a>
            <button type="button" id="tryAgainBtn" class="btn btn-secondary" style="padding: 12px 24px; flex: 1;">
                <i class="fas fa-repeat"></i> استيراد ملف آخر
            </button>
        </div>
    </div>

    {{-- Template Info --}}
    <div class="card" style="margin-top: 24px;">
        <div class="card-title">
            <i class="fas fa-book" style="color: var(--gold);"></i>
            صيغة الملف
        </div>

        <div style="overflow-x: auto; background: var(--dark-bg)/30; padding: 12px; border-radius: 6px;">
            <table style="width: 100%; color: var(--cream); font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="text-align: right; padding: 8px;">العمود</th>
                        <th style="text-align: right; padding: 8px;">النوع</th>
                        <th style="text-align: right; padding: 8px;">المثال</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="text-align: right; padding: 8px;">اسم السورة</td>
                        <td style="text-align: right; padding: 8px;">نص أو رقم</td>
                        <td style="text-align: right; padding: 8px;">البقرة أو 2</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="text-align: right; padding: 8px;">رقم الجزء</td>
                        <td style="text-align: right; padding: 8px;">رقم</td>
                        <td style="text-align: right; padding: 8px;">1 أو 15</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="text-align: right; padding: 8px;">من الآية</td>
                        <td style="text-align: right; padding: 8px;">رقم</td>
                        <td style="text-align: right; padding: 8px;">1 أو 50</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="text-align: right; padding: 8px;">إلى الآية</td>
                        <td style="text-align: right; padding: 8px;">رقم (اختياري)</td>
                        <td style="text-align: right; padding: 8px;">100 أو فارغ</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="text-align: right; padding: 8px;">الملاحظات</td>
                        <td style="text-align: right; padding: 8px;">نص (اختياري)</td>
                        <td style="text-align: right; padding: 8px;">تسجيل تجريبي</td>
                    </tr>
                    <tr>
                        <td style="text-align: right; padding: 8px;">مسار الملف</td>
                        <td style="text-align: right; padding: 8px;">مسار (اختياري)</td>
                        <td style="text-align: right; padding: 8px;">recordings/audio/file.mp3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('bulkImportForm');
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    const resultsSection = document.getElementById('resultsSection');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const stats = document.getElementById('stats');
    const totalCount = document.getElementById('totalCount');
    const successCount = document.getElementById('successCount');
    const failureCount = document.getElementById('failureCount');
    const errorsList = document.getElementById('errorsList');
    const errorsContent = document.getElementById('errorsContent');
    const successMessage = document.getElementById('successMessage');
    const tryAgainBtn = document.getElementById('tryAgainBtn');

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
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect();
        }
        dropZone.style.background = 'var(--dark-bg)/30';
        dropZone.style.borderColor = 'var(--border)';
    });

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            document.getElementById('selectedFileName').textContent = file.name;
            document.getElementById('selectedFileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
            fileInfo.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!fileInput.files.length) {
            alert('يرجى اختيار ملف');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('file', fileInput.files[0]);

        submitBtn.disabled = true;
        progressBar.style.display = 'block';

        try {
            const response = await fetch('{{ route("recordings.bulkImport") }}', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                totalCount.textContent = data.total;
                successCount.textContent = data.imported;
                failureCount.textContent = data.failed;

                if (data.errors.length > 0) {
                    const errorsList = document.querySelector('#errorsContent ul');
                    errorsList.innerHTML = data.errors
                        .map(e => `<li style="color: var(--cream); margin-bottom: 4px; overflow-wrap: break-word;">السطر ${e.row}: ${e.message}</li>`)
                        .join('');
                    document.getElementById('errorsList').style.display = 'block';
                }

                if (data.failed === 0) {
                    successMessage.style.display = 'block';
                }

                progressBar.style.display = 'none';
                resultsSection.style.display = 'block';
                form.style.display = 'none';

            } else {
                alert('خطأ: ' + (data.error || 'فشل الاستيراد'));
                progressBar.style.display = 'none';
                submitBtn.disabled = false;
            }

        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
            progressBar.style.display = 'none';
            submitBtn.disabled = false;
        }
    });

    tryAgainBtn.addEventListener('click', () => {
        fileInput.value = '';
        form.style.display = 'block';
        resultsSection.style.display = 'none';
        fileInfo.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
    });
</script>

<style>
    table {
        border-collapse: collapse;
    }

    table th, table td {
        border: 1px solid var(--border);
    }
</style>
@endsection
