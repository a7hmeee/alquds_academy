@extends('layouts.app')

@section('title', 'استيراد الطلاب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <!-- الرأس -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-slate-900">
                    <i class="fas fa-file-import text-lg"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300">
                    استيراد الطلاب
                </h1>
            </div>
            <p class="text-slate-400 text-lg">استوردّ عدة طلاب دفعة واحدة من ملف Excel أو CSV</p>
        </div>

        <!-- التنبيهات -->
        <div id="alert" class="mb-6 hidden"></div>

        <!-- محتوى الصفحة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- العمود الأيسر: المساعدة -->
            <div class="md:col-span-1 space-y-6">
                
                <!-- البطاقة 1: التعليمات -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <h2 class="text-lg font-bold text-yellow-400">التعليمات</h2>
                    </div>
                    
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li class="flex gap-2">
                            <span class="text-yellow-400 font-bold">1.</span>
                            <span>حمّل نموذج Excel</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-yellow-400 font-bold">2.</span>
                            <span>أملأ بيانات الطلاب</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-yellow-400 font-bold">3.</span>
                            <span>رفع الملف هنا</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-yellow-400 font-bold">4.</span>
                            <span>تحقق من النتائج</span>
                        </li>
                    </ul>
                </div>

                <!-- البطاقة 2: الأعمدة المطلوبة -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                            <i class="fas fa-columns text-yellow-400"></i>
                        </div>
                        <h2 class="text-lg font-bold text-yellow-400">أعمدة الملف</h2>
                    </div>
                    
                    <div class="space-y-2 text-xs">
                        <div class="bg-slate-700/30 rounded p-2">
                            <p class="text-yellow-400 font-bold">الاسم *</p>
                            <p class="text-slate-400">إلزامي</p>
                        </div>
                        <div class="bg-slate-700/30 rounded p-2">
                            <p class="text-slate-300 font-bold">البريد الإلكتروني</p>
                            <p class="text-slate-400">اختياري</p>
                        </div>
                        <div class="bg-slate-700/30 rounded p-2">
                            <p class="text-slate-300 font-bold">السورة / الجزء / الآية</p>
                            <p class="text-slate-400">اختياري</p>
                        </div>
                    </div>
                </div>

                <!-- البطاقة 3: تحميل النموذج -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl">
                    <a href="{{ route('students.import.template') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-bold hover:shadow-lg hover:shadow-green-500/50 transition duration-300">
                        <i class="fas fa-download"></i>
                        تحميل النموذج
                    </a>
                </div>
            </div>

            <!-- العمود الأيمن: نموذج الرفع -->
            <div class="md:col-span-2">
                <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                            <i class="fas fa-upload text-yellow-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-yellow-400">رفع الملف</h2>
                    </div>

                    <form id="importForm" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- منطقة الرفع -->
                        <div id="dropZone" class="border-2 border-dashed border-yellow-400/50 rounded-xl p-8 text-center cursor-pointer hover:border-yellow-400 hover:bg-slate-700/30 transition duration-300">
                            <input type="file" id="fileInput" name="import_file" accept=".xlsx,.xls,.csv" class="hidden">
                            
                            <div class="space-y-3">
                                <div class="text-4xl text-yellow-400">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <p class="text-slate-100 font-semibold">
                                    اسحب الملف هنا أو انقر للاختيار
                                </p>
                                <p class="text-slate-400 text-sm">
                                    ملفات مدعومة: Excel (.xlsx, .xls) أو CSV
                                </p>
                                <p class="text-yellow-400 text-xs">
                                    الحد الأقصى: 5MB
                                </p>
                            </div>
                        </div>

                        <!-- اسم الملف المختار -->
                        <div id="selectedFile" class="hidden bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                            <p class="text-slate-400 text-sm mb-1">الملف المختار:</p>
                            <p id="fileName" class="text-yellow-400 font-bold"></p>
                        </div>

                        <!-- الأزرار -->
                        <div class="flex gap-4">
                            <button type="button" id="clearBtn" class="flex-1 px-6 py-3 bg-slate-700/50 border border-slate-600 text-slate-300 rounded-lg font-bold hover:border-red-400 hover:text-red-400 transition duration-300 hidden">
                                <i class="fas fa-times mr-2"></i>إلغاء
                            </button>
                            <button type="submit" id="submitBtn" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-slate-900 rounded-lg font-bold hover:shadow-lg hover:shadow-yellow-400/50 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-check mr-2"></i>رفع واستيراد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- نتائج الاستيراد -->
        <div id="resultsContainer" class="mt-8 hidden">
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-tasks text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">نتائج الاستيراد</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-green-500/30">
                        <p class="text-slate-400 text-sm">نجح</p>
                        <p id="successCount" class="text-green-400 font-bold text-3xl">0</p>
                    </div>
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-red-500/30">
                        <p class="text-slate-400 text-sm">فشل</p>
                        <p id="failedCount" class="text-red-400 font-bold text-3xl">0</p>
                    </div>
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-yellow-400/30">
                        <p class="text-slate-400 text-sm">إجمالي</p>
                        <p id="totalCount" class="text-yellow-400 font-bold text-3xl">0</p>
                    </div>
                </div>

                <!-- التفاصيل -->
                <div id="detailsContainer" class="space-y-3 mb-6">
                    <!-- سيتم ملؤه من جانب JavaScript -->
                </div>

                <!-- الأخطاء -->
                <div id="errorsContainer" class="space-y-3"></div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const selectedFileDiv = document.getElementById('selectedFile');
    const fileNameSpan = document.getElementById('fileName');
    const clearBtn = document.getElementById('clearBtn');
    const submitBtn = document.getElementById('submitBtn');
    const resultsContainer = document.getElementById('resultsContainer');
    const alertDiv = document.getElementById('alert');

    // معالجات السحب والإفلات
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-yellow-400', 'bg-slate-700/30');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-yellow-400', 'bg-slate-700/30');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-yellow-400', 'bg-slate-700/30');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            displaySelectedFile(files[0]);
        }
    });

    // النقر على منطقة الرفع
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // تغيير الملف
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            displaySelectedFile(e.target.files[0]);
        }
    });

    // عرض الملف المختار
    function displaySelectedFile(file) {
        fileNameSpan.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
        selectedFileDiv.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
    }

    // مسح الملف
    clearBtn.addEventListener('click', () => {
        fileInput.value = '';
        selectedFileDiv.classList.add('hidden');
        clearBtn.classList.add('hidden');
        resultsContainer.classList.add('hidden');
    });

    // رفع الملف
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!fileInput.files.length) {
            showAlert('يرجى اختيار ملف أولاً', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الاستيراد...';

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("students.import") }}', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                displayResults(data.data);
                fileInput.value = '';
                selectedFileDiv.classList.add('hidden');
            } else {
                showAlert(data.message, 'error');
            }
        } catch (error) {
            showAlert('حدث خطأ: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>رفع واستيراد';
        }
    });

    // عرض النتائج
    function displayResults(result) {
        document.getElementById('successCount').textContent = result.success;
        document.getElementById('failedCount').textContent = result.failed;
        document.getElementById('totalCount').textContent = result.success + result.failed;

        // عرض التفاصيل الناجحة
        const detailsContainer = document.getElementById('detailsContainer');
        detailsContainer.innerHTML = '';

        result.details.forEach(detail => {
            const div = document.createElement('div');
            div.className = 'bg-green-900/20 border border-green-500/30 rounded-lg p-3';
            div.innerHTML = `
                <p class="text-green-400 text-sm">✓ السطر ${detail.row}: ${detail.name}</p>
            `;
            detailsContainer.appendChild(div);
        });

        // عرض الأخطاء
        const errorsContainer = document.getElementById('errorsContainer');
        errorsContainer.innerHTML = '';

        if (result.errors.length > 0) {
            const errorsTitle = document.createElement('h3');
            errorsTitle.className = 'text-red-400 font-bold mb-3 mt-6';
            errorsTitle.textContent = 'الأخطاء:';
            errorsContainer.appendChild(errorsTitle);

            result.errors.forEach(error => {
                const div = document.createElement('div');
                div.className = 'bg-red-900/20 border border-red-500/30 rounded-lg p-3';
                div.innerHTML = `
                    <p class="text-red-400 text-sm">✗ السطر ${error.row}: ${error.error}</p>
                `;
                errorsContainer.appendChild(div);
            });
        }

        resultsContainer.classList.remove('hidden');
    }

    // عرض التنبيه
    function showAlert(message, type) {
        let bgColor = 'bg-green-900/30 border-green-500/50';
        let icon = 'fa-check-circle text-green-400';
        let textColor = 'text-green-300';

        if (type === 'error') {
            bgColor = 'bg-red-900/30 border-red-500/50';
            icon = 'fa-exclamation-circle text-red-400';
            textColor = 'text-red-300';
        }

        const html = `
            <div class="flex items-center gap-3 ${bgColor} border rounded-lg p-4">
                <i class="fas ${icon}"></i>
                <p class="${textColor}">${message}</p>
                <button type="button" class="ml-auto text-gray-400 hover:text-gray-200" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        alertDiv.innerHTML = html;
        alertDiv.classList.remove('hidden');
    }
});
</script>
@endsection
