@extends('layouts.app')
@section('title', 'إضافة جهة جديدة')

@section('header', 'إضافة جهة جديدة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-1">إضافة جهة جديدة</h1>
            <p class="text-[var(--slate-blue)]">أضف مسجدًا أو مدرسة أو جامعة جديدة للنظام</p>
        </div>
        
        <a href="{{ route('organizations.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع للقائمة
        </a>
    </div>

    <!-- Form Container -->
    <div class="main-content-section">
        <!-- Progress Steps -->
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
                <div class="absolute top-12 left-0 right-0 flex justify-between">
                    <span class="text-sm text-[var(--gold)] font-medium">المعلومات الأساسية</span>
                    <span class="text-sm text-[var(--slate-blue)]">التفاصيل</span>
                    <span class="text-sm text-[var(--slate-blue)]">المراجعة</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('organizations.store') }}" method="POST" class="space-y-6" id="organizationForm">
            @csrf

            <!-- Basic Information Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-info-circle text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">المعلومات الأساسية</h3>
                        <p class="text-sm text-[var(--slate-blue)]">أدخل البيانات الأساسية للجهة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Field -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            اسم الجهة <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="أدخل اسم الجهة"
                                   required>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        @error('name')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Type Field -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            نوع الجهة <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <select name="type"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none"
                                    required>
                                <option value="" disabled selected>اختر نوع الجهة</option>
                                <option value="mosque" @selected(old('type')==='mosque')>مسجد</option>
                                <option value="school" @selected(old('type')==='school')>مدرسة</option>
                                <option value="university" @selected(old('type')==='university')>جامعة</option>
                                <option value="other" @selected(old('type')==='other')>أخرى</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                        @error('type')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Type Preview -->
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                            <div class="type-option" data-type="mosque">
                                <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-900/20 to-blue-900/10 flex items-center justify-center">
                                        <i class="fas fa-mosque text-xl text-blue-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">مسجد</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="school">
                                <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-emerald-900/20 to-emerald-900/10 flex items-center justify-center">
                                        <i class="fas fa-school text-xl text-emerald-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">مدرسة</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="university">
                                <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-900/20 to-purple-900/10 flex items-center justify-center">
                                        <i class="fas fa-university text-xl text-purple-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">جامعة</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="other">
                                <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-gray-900/20 to-gray-900/10 flex items-center justify-center">
                                        <i class="fas fa-building text-xl text-gray-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">أخرى</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-address-card text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">معلومات إضافية</h3>
                        <p class="text-sm text-[var(--slate-blue)]">(اختياري) معلومات إضافية عن الجهة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            العنوان
                        </label>
                        <div class="relative">
                            <textarea name="address" 
                                      rows="3"
                                      class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="أدعن عنوان الجهة">{{ old('address') }}</textarea>
                            <div class="absolute left-3 top-3 text-[var(--slate-blue)]">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        @error('address')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Phone -->
                   

                    <!-- Email -->
                 

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الحالة
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="is_active" 
                                       value="1" 
                                       @checked(old('is_active', true))
                                       class="w-4 h-4 text-[var(--gold)] bg-[var(--surface)] border-[var(--border)] focus:ring-[var(--gold)]">
                                <span class="text-[var(--cream)]">نشطة</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="is_active" 
                                       value="0" 
                                       @checked(old('is_active') === '0')
                                       class="w-4 h-4 text-[var(--gold)] bg-[var(--surface)] border-[var(--border)] focus:ring-[var(--gold)]">
                                <span class="text-[var(--cream)]">غير نشطة</span>
                            </label>
                        </div>
                        @error('is_active')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
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
                        <h3 class="text-lg font-bold text-[var(--cream)]">وصف الجهة</h3>
                        <p class="text-sm text-[var(--slate-blue)]">(اختياري) وصف مختصر عن الجهة ونشاطاتها</p>
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
                                  placeholder="أدخل وصفًا مختصرًا عن الجهة...">{{ old('description') }}</textarea>
                        <div class="absolute left-3 top-3 text-[var(--slate-blue)]">
                            <i class="fas fa-align-right"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-[var(--slate-blue)]">
                        <span id="charCount">0</span> / 500 حرف
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
                
                <div class="flex gap-3">
                    <a href="{{ route('organizations.index') }}"
                       class="px-6 py-3 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    
                    <button type="submit"
                            class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2 group">
                        <i class="fas fa-save"></i>
                        حفظ الجهة
                        <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform duration-200"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Card -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4">معاينة الجهة</h3>
        <div id="previewCard" class="p-6 rounded-xl border-2 border-dashed border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/5 to-transparent">
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                    <i class="fas fa-eye text-2xl text-[var(--slate-blue)]"></i>
                </div>
                <p class="text-[var(--slate-blue)]">ستظهر معاينة الجهة هنا بعد ملء الحقول</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Type selection with preview
    const typeOptions = document.querySelectorAll('.type-option');
    const typeSelect = document.querySelector('select[name="type"]');
    const nameInput = document.querySelector('input[name="name"]');
    const descriptionTextarea = document.querySelector('textarea[name="description"]');
    const charCount = document.getElementById('charCount');
    const previewCard = document.getElementById('previewCard');
    
    // Type selection
    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Update select
            typeSelect.value = type;
            
            // Remove active class from all options
            typeOptions.forEach(opt => {
                opt.querySelector('div').classList.remove('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
                opt.querySelector('div').classList.add('border-[var(--border)]');
            });
            
            // Add active class to selected option
            this.querySelector('div').classList.remove('border-[var(--border)]');
            this.querySelector('div').classList.add('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
            
            // Trigger change event
            typeSelect.dispatchEvent(new Event('change'));
        });
    });
    
    // Initialize active type
    if (typeSelect.value) {
        const activeOption = document.querySelector(`.type-option[data-type="${typeSelect.value}"]`);
        if (activeOption) {
            activeOption.querySelector('div').classList.remove('border-[var(--border)]');
            activeOption.querySelector('div').classList.add('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
        }
    }
    
    // Character count for description
    if (descriptionTextarea && charCount) {
        charCount.textContent = descriptionTextarea.value.length;
        
        descriptionTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            // Limit to 500 characters
            if (this.value.length > 500) {
                this.value = this.value.substring(0, 500);
                charCount.textContent = 500;
                charCount.classList.add('text-red-400');
            } else {
                charCount.classList.remove('text-red-400');
            }
            
            updatePreview();
        });
    }
    
    // Update preview on input
    if (nameInput) {
        nameInput.addEventListener('input', updatePreview);
    }
    
    if (typeSelect) {
        typeSelect.addEventListener('change', updatePreview);
    }
    
    // Preview update function
    function updatePreview() {
        const name = nameInput ? nameInput.value.trim() : '';
        const type = typeSelect ? typeSelect.value : '';
        const description = descriptionTextarea ? descriptionTextarea.value.trim() : '';
        
        if (!name && !type) {
            previewCard.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                        <i class="fas fa-eye text-2xl text-[var(--slate-blue)]"></i>
                    </div>
                    <p class="text-[var(--slate-blue)]">ستظهر معاينة الجهة هنا بعد ملء الحقول</p>
                </div>
            `;
            return;
        }
        
        // Type icon mapping
        const typeIcons = {
            'mosque': { icon: 'fa-mosque', color: 'from-blue-900/20 to-blue-900/10', textColor: 'text-blue-300', label: 'مسجد' },
            'school': { icon: 'fa-school', color: 'from-emerald-900/20 to-emerald-900/10', textColor: 'text-emerald-300', label: 'مدرسة' },
            'university': { icon: 'fa-university', color: 'from-purple-900/20 to-purple-900/10', textColor: 'text-purple-300', label: 'جامعة' },
            'other': { icon: 'fa-building', color: 'from-gray-900/20 to-gray-900/10', textColor: 'text-gray-300', label: 'أخرى' }
        };
        
        const typeData = typeIcons[type] || typeIcons.other;
        
        // Generate preview
        previewCard.innerHTML = `
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-xl ${typeData.color} border border-[var(--border)] flex items-center justify-center">
                        <i class="fas ${typeData.icon} text-3xl ${typeData.textColor}"></i>
                    </div>
                </div>
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                        <h4 class="text-xl font-bold text-[var(--cream)]">${name || 'اسم الجهة'}</h4>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${typeData.color} border border-white/10 ${typeData.textColor}">
                            ${typeData.label}
                        </span>
                    </div>
                    
                    ${description ? `
                        <p class="text-[var(--slate-blue)] mb-3">${description.substring(0, 150)}${description.length > 150 ? '...' : ''}</p>
                    ` : ''}
                    
                    <div class="flex items-center gap-4 text-sm text-[var(--slate-blue)]">
                        <span><i class="fas fa-calendar ml-1"></i> ${new Date().toLocaleDateString('ar-SA')}</span>
                        <span><i class="fas fa-check-circle ml-1 text-green-400"></i> سيتم إنشاؤها</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Form validation
    const form = document.getElementById('organizationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = nameInput ? nameInput.value.trim() : '';
            const type = typeSelect ? typeSelect.value : '';
            
            if (!name || !type) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'بيانات ناقصة',
                    text: 'يرجى ملء الحقول الإلزامية (*)',
                    icon: 'warning',
                    confirmButtonText: 'حسنًا',
                    confirmButtonColor: '#C3A04E',
                    reverseButtons: true
                });
                
                // Scroll to first error
                if (!name) {
                    nameInput.focus();
                } else if (!type) {
                    typeSelect.focus();
                }
            }
        });
    }
    
    // Update preview on page load
    updatePreview();
});
</script>

<style>
/* Custom styles for form */
.type-option.active div {
    border-color: var(--gold) !important;
    background: rgba(29, 79, 49, 0.2) !important;
}

/* Custom scrollbar for textarea */
textarea::-webkit-scrollbar {
    width: 6px;
}

textarea::-webkit-scrollbar-track {
    background: var(--surface);
    border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: var(--gold);
}

/* Focus styles */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 2px rgba(195, 160, 78, 0.1);
}

/* Radio button styles */
input[type="radio"] {
    appearance: none;
    -webkit-appearance: none;
    border: 2px solid var(--border);
    border-radius: 50%;
    outline: none;
    transition: all 0.2s ease;
}

input[type="radio"]:checked {
    border-color: var(--gold);
    background-color: var(--gold);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230A1410'%3E%3Ccircle cx='12' cy='12' r='4'/%3E%3C/svg%3E");
    background-size: 60%;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
@endsection