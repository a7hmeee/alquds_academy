@extends('layouts.app')
@section('title', 'إضافة حلقة جديدة')

@section('header', 'إضافة حلقة جديدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إضافة حلقة جديدة</h1>
            <p class="text-[var(--slate-blue)]">أضف حلقة دراسية جديدة للنظام التعليمي</p>
        </div>
        
        <a href="{{ route('circles.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع للحلقات
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 animate-slide-in">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200 animate-slide-in">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                <span class="font-medium">يوجد أخطاء في النموذج:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="main-content-section">
        <!-- Form Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-[var(--border)] -translate-y-1/2 z-0"></div>
                <div class="absolute top-1/2 left-0 h-0.5 bg-gradient-to-r from-[var(--gold)] to-[#D4B85C] -translate-y-1/2 z-10" style="width: 33%"></div>
                
                <!-- Steps -->
                <div class="relative z-20 flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold">
                    1
                </div>
                <div class="relative z-20 flex items-center justify-center w-10 h-10 rounded-full bg-[var(--surface)] border-2 border-[var(--border)] text-[var(--slate-blue)]">
                    2
                </div>
                <div class="relative z-20 flex items-center justify-center w-10 h-10 rounded-full bg-[var(--surface)] border-2 border-[var(--border)] text-[var(--slate-blue)]">
                    3
                </div>
                
                <!-- Step Labels -->
                <div class="absolute top-12 left-0 right-0 flex justify-between gap-1">
                    <span class="text-[10px] sm:text-sm text-[var(--gold)] font-medium whitespace-nowrap">المعلومات الأساسية</span>
                    <span class="text-[10px] sm:text-sm text-[var(--slate-blue)] whitespace-nowrap">الإعدادات</span>
                    <span class="text-[10px] sm:text-sm text-[var(--slate-blue)] whitespace-nowrap">المراجعة</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('circles.store') }}" class="space-y-6" id="circleForm">
            @csrf

            <!-- Basic Information Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-circle text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">المعلومات الأساسية</h3>
                        <p class="text-sm text-[var(--slate-blue)]">أدخل المعلومات الأساسية للحلقة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Circle Name -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            اسم الحلقة <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="أدخل اسم الحلقة">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-heading"></i>
                            </div>
                        </div>
                        @error('name')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Organization -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الجهة (اختياري)
                        </label>
                        <div class="relative">
                            <select name="organization_id"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none">
                                <option value="">— بدون جهة —</option>
                                @foreach($organizations ?? [] as $org)
                                    <option value="{{ $org->id }}" @selected(old('organization_id') == $org->id)>
                                        {{ $org->name }}
                                        @if($org->type)
                                            <span class="text-xs text-[var(--slate-blue)]">
                                                ({{ [
                                                    'mosque' => 'مسجد',
                                                    'school' => 'مدرسة',
                                                    'university' => 'جامعة',
                                                    'other' => 'أخرى'
                                                ][$org->type] ?? $org->type }})
                                            </span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Level -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            المستوى (اختياري)
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="level"
                                   value="{{ old('level') }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="مبتدئ / متوسط / متقدم">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            السعة القصوى للطلاب
                        </label>
                        <div class="relative">
                            <input type="number"
                                   name="capacity"
                                   value="{{ old('capacity') }}"
                                   min="1"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="عدد الطلاب">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        @error('capacity')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Juz (الجزء المطلوب) -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الجزء المطلوب
                        </label>
                        <div class="relative">
                            <select name="juz_id"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200">
                                <option value="">— اختياري —</option>
                                @foreach(\App\Models\Juz::orderBy('id')->get() as $juz)
                                    <option value="{{ $juz->id }}" {{ old('juz_id') == $juz->id ? 'selected' : '' }}>
                                        {{ $juz->name }} (الجزء {{ $juz->id }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-book-quran"></i>
                            </div>
                        </div>
                        @error('juz_id')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-cog text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">إعدادات الحلقة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">حدد نوع الحلقة والحالة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            نوع الحلقة
                        </label>
                        <div class="relative">
                            <select name="type"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none">
                                <option value="onsite" @selected(old('type', 'onsite')=='onsite')>حضوري</option>
                                <option value="online" @selected(old('type')=='online')>أونلاين</option>
                                <option value="hybrid" @selected(old('type')=='hybrid')>هجين</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-laptop-house"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            حالة الحلقة
                        </label>
                        <div class="relative">
                            <select name="status"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none">
                                <option value="active" @selected(old('status', 'active')=='active')>نشطة</option>
                                <option value="paused" @selected(old('status')=='paused')>موقوفة</option>
                                <option value="archived" @selected(old('status')=='archived')>مؤرشفة</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Type Options -->
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-[var(--slate-blue)] mb-4">اختر نوع الحلقة:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="type-option" data-type="onsite">
                                <div class="p-4 rounded-lg border-2 {{ old('type', 'onsite') === 'onsite' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-900/20 to-blue-900/10 flex items-center justify-center">
                                        <i class="fas fa-users text-xl text-blue-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium block mb-1">حضوري</span>
                                    <p class="text-xs text-[var(--slate-blue)]">دروس مباشرة في المكان</p>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="online">
                                <div class="p-4 rounded-lg border-2 {{ old('type') === 'online' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-900/20 to-purple-900/10 flex items-center justify-center">
                                        <i class="fas fa-laptop text-xl text-purple-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium block mb-1">أونلاين</span>
                                    <p class="text-xs text-[var(--slate-blue)]">دروس عن بُعد</p>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="hybrid">
                                <div class="p-4 rounded-lg border-2 {{ old('type') === 'hybrid' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-green-900/20 to-green-900/10 flex items-center justify-center">
                                        <i class="fas fa-blender-phone text-xl text-green-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium block mb-1">هجين</span>
                                    <p class="text-xs text-[var(--slate-blue)]">مزيج من الحضوري والأونلاين</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-file-alt text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">وصف الحلقة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">(اختياري) وصف مختصر للحلقة وأهدافها</p>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                        الوصف
                    </label>
                    <div class="relative">
                        <textarea name="description"
                                  rows="4"
                                  class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 resize-none"
                                  placeholder="أدخل وصفًا مختصرًا للحلقة وأهدافها...">{{ old('description') }}</textarea>
                        <div class="absolute left-3 top-3 text-[var(--slate-blue)]">
                            <i class="fas fa-align-right"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-[var(--slate-blue)]">
                        <span id="descCharCount">0</span> / 500 حرف
                    </div>
                    @error('description')
                        <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-info-circle ml-1"></i>
                    الحقول المميزة بـ <span class="text-red-400">*</span> إلزامية
                </div>
                
                <div class="flex flex-wrap justify-center sm:justify-end gap-3">
                    <a href="{{ route('circles.index') }}"
                       class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    
                    <button type="button"
                            onclick="showPreview()"
                            class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        معاينة
                    </button>
                    
                    <button type="submit"
                            class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2 group">
                        <i class="fas fa-plus-circle"></i>
                        إضافة الحلقة
                        <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform duration-200"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4">معاينة الحلقة</h3>
        <div id="previewCard" class="p-6 rounded-xl border-2 border-dashed border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/5 to-transparent">
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                    <i class="fas fa-circle text-2xl text-[var(--slate-blue)]"></i>
                </div>
                <p class="text-[var(--slate-blue)]">ستظهر معاينة الحلقة هنا بعد ملء الحقول الأساسية</p>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
    <div class="bg-[var(--surface)] rounded-xl border border-[var(--border)] max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6 border-b border-[var(--border)]">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-[var(--cream)]">معاينة الحلقة الجديدة</h3>
                <button onclick="closePreview()"
                        class="p-2 rounded-lg hover:bg-[var(--deep-green)]/20 transition-colors duration-200">
                    <i class="fas fa-times text-[var(--slate-blue)]"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div id="previewContent"></div>
            
            <div class="mt-6 pt-6 border-t border-[var(--border)] flex justify-end gap-3">
                <button onclick="closePreview()"
                        class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                    إغلاق
                </button>
                <button onclick="submitForm()"
                        class="px-4 py-2 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold">
                    تأكيد الإضافة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Type selection
    const typeOptions = document.querySelectorAll('.type-option');
    const typeSelect = document.querySelector('select[name="type"]');
    
    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const type = this.dataset.type;
            typeSelect.value = type;
            
            // Update active class
            typeOptions.forEach(opt => {
                opt.querySelector('div').classList.remove('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
                opt.querySelector('div').classList.add('border-[var(--border)]');
            });
            
            this.querySelector('div').classList.remove('border-[var(--border)]');
            this.querySelector('div').classList.add('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
            
            updateCirclePreview();
        });
    });
    
    // Description character count
    const descTextarea = document.querySelector('textarea[name="description"]');
    const descCharCount = document.getElementById('descCharCount');
    
    if (descTextarea && descCharCount) {
        descCharCount.textContent = descTextarea.value.length;
        descTextarea.addEventListener('input', function() {
            descCharCount.textContent = this.value.length;
            if (this.value.length > 500) {
                this.value = this.value.substring(0, 500);
                descCharCount.textContent = 500;
                descCharCount.classList.add('text-red-400');
            } else {
                descCharCount.classList.remove('text-red-400');
            }
            updateCirclePreview();
        });
    }
    
    // Update preview on all inputs
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', updateCirclePreview);
        input.addEventListener('input', updateCirclePreview);
    });
    
    // Initialize preview
    updateCirclePreview();
});

// Update circle preview
function updateCirclePreview() {
    const form = document.getElementById('circleForm');
    const formData = new FormData(form);
    const previewCard = document.getElementById('previewCard');
    
    // Get form values
    const name = formData.get('name') || '';
    const organizationId = formData.get('organization_id') || '';
    const type = formData.get('type') || 'onsite';
    const level = formData.get('level') || '';
    const capacity = formData.get('capacity') || '';
    const status = formData.get('status') || 'active';
    const description = formData.get('description') || '';
    
    if (!name) {
        previewCard.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                    <i class="fas fa-circle text-2xl text-[var(--slate-blue)]"></i>
                </div>
                <p class="text-[var(--slate-blue)]">ستظهر معاينة الحلقة هنا بعد ملء الحقول الأساسية</p>
            </div>
        `;
        return;
    }
    
    // Type data
    const typeData = {
        'onsite': { icon: 'fa-users', color: 'from-blue-900/20 to-blue-900/10', textColor: 'text-blue-300', label: 'حضوري' },
        'online': { icon: 'fa-laptop', color: 'from-purple-900/20 to-purple-900/10', textColor: 'text-purple-300', label: 'أونلاين' },
        'hybrid': { icon: 'fa-blender-phone', color: 'from-green-900/20 to-green-900/10', textColor: 'text-green-300', label: 'هجين' }
    };
    
    const typeInfo = typeData[type] || typeData.onsite;
    
    // Status data
    const statusData = {
        'active': { color: 'from-green-900/20 to-emerald-900/10', textColor: 'text-green-300', label: 'نشطة' },
        'paused': { color: 'from-yellow-900/20 to-yellow-900/10', textColor: 'text-yellow-300', label: 'موقوفة' },
        'archived': { color: 'from-gray-900/20 to-gray-900/10', textColor: 'text-gray-300', label: 'مؤرشفة' }
    };
    
    const statusInfo = statusData[status] || statusData.active;
    
    // Organization name (if selected)
    let orgName = '';
    if (organizationId) {
        const orgSelect = document.querySelector('select[name="organization_id"]');
        const selectedOption = orgSelect?.options[orgSelect.selectedIndex];
        orgName = selectedOption?.text || '';
    }
    
    // Generate preview
    previewCard.innerHTML = `
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl ${typeInfo.color} border-2 border-[var(--border)] flex items-center justify-center">
                    <i class="fas ${typeInfo.icon} text-2xl ${typeInfo.textColor}"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-[var(--cream)]">${name}</h4>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${typeInfo.color} border border-white/10 ${typeInfo.textColor}">
                            ${typeInfo.label}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${statusInfo.color} border border-white/10 ${statusInfo.textColor}">
                            ${statusInfo.label}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${orgName ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">الجهة</div>
                    <div class="text-[var(--cream)] font-medium">${orgName}</div>
                </div>
                ` : ''}
                
                ${level ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">المستوى</div>
                    <div class="text-[var(--cream)] font-medium">${level}</div>
                </div>
                ` : ''}
                
                ${capacity ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">السعة القصوى</div>
                    <div class="text-[var(--cream)] font-medium">${capacity} طالب</div>
                </div>
                ` : ''}
            </div>
            
            <!-- Description -->
            ${description ? `
            <div class="p-4 rounded-lg bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent border border-[var(--border)]">
                <div class="text-sm font-medium text-[var(--cream)] mb-2">وصف الحلقة:</div>
                <p class="text-[var(--slate-blue)] text-sm">${description.substring(0, 200)}${description.length > 200 ? '...' : ''}</p>
            </div>
            ` : ''}
        </div>
    `;
}

// Show preview modal
function showPreview() {
    const form = document.getElementById('circleForm');
    const formData = new FormData(form);
    const previewContent = document.getElementById('previewContent');
    
    // Validate required fields
    const nameInput = form.querySelector('input[name="name"]');
    
    if (!nameInput.value.trim()) {
        Swal.fire({
            title: 'اسم الحلقة مطلوب',
            text: 'الرجاء إدخال اسم الحلقة',
            icon: 'warning',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#C3A04E'
        });
        nameInput.focus();
        return;
    }
    
    // Generate preview content
    updateCirclePreview();
    const previewCard = document.getElementById('previewCard').innerHTML;
    
    previewContent.innerHTML = `
        <div class="space-y-4">
            <h4 class="text-lg font-bold text-[var(--cream)] mb-2">معاينة نهائية</h4>
            ${previewCard}
        </div>
    `;
    
    // Show modal
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}

// Close preview modal
function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
}

// Submit form from preview
function submitForm() {
    document.getElementById('circleForm').submit();
}
</script>

<style>
/* Custom styles for circles form */
.type-option.active div {
    border-color: var(--gold) !important;
    background: rgba(29, 79, 49, 0.2) !important;
}

#previewModal {
    backdrop-filter: blur(5px);
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#previewModal > div {
    animation: modalFadeIn 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .type-option > div {
        padding: 0.75rem;
    }
    
    .type-option i {
        font-size: 1rem;
    }
}
</style>
@endsection