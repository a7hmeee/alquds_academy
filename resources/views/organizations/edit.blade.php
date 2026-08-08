@extends('layouts.app')
@section('title', 'تعديل جهة - ' . $organization->name)

@section('header', 'تعديل جهة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                @php
                    $typeIcons = [
                        'mosque' => ['icon' => 'fa-mosque', 'color' => 'from-blue-900/20 to-blue-900/10', 'textColor' => 'text-blue-300'],
                        'school' => ['icon' => 'fa-school', 'color' => 'from-emerald-900/20 to-emerald-900/10', 'textColor' => 'text-emerald-300'],
                        'university' => ['icon' => 'fa-university', 'color' => 'from-purple-900/20 to-purple-900/10', 'textColor' => 'text-purple-300'],
                        'other' => ['icon' => 'fa-building', 'color' => 'from-gray-900/20 to-gray-900/10', 'textColor' => 'text-gray-300']
                    ];
                    $typeData = $typeIcons[$organization->type] ?? $typeIcons['other'];
                @endphp
                
                <div class="w-12 h-12 rounded-lg {{ $typeData['color'] }} border border-[var(--border)] flex items-center justify-center">
                    <i class="fas {{ $typeData['icon'] }} text-xl {{ $typeData['textColor'] }}"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[var(--cream)]">تعديل جهة</h1>
                    <p class="text-[var(--slate-blue)]">{{ $organization->name }}</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3">
                <span class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-calendar ml-1"></i>
                    تم الإنشاء: {{ $organization->created_at->format('Y/m/d') }}
                </span>
                @if($organization->updated_at->ne($organization->created_at))
                <span class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-history ml-1"></i>
                    آخر تعديل: {{ $organization->updated_at->format('Y/m/d') }}
                </span>
                @endif
            </div>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('organizations.show', $organization) }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-eye"></i>
                عرض
            </a>
            
            <a href="{{ route('organizations.index') }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                رجوع للقائمة
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="main-content-section">
        <!-- Current Status -->
        <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-transparent border border-[var(--border)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    @if($organization->is_active)
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-green-900/20 to-emerald-900/10 border border-green-500/20">
                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                        <span class="text-green-300 text-sm font-medium">نشطة</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-red-900/20 to-rose-900/10 border border-red-500/20">
                        <div class="w-2 h-2 rounded-full bg-red-400"></div>
                        <span class="text-red-300 text-sm font-medium">غير نشطة</span>
                    </div>
                    @endif
                    
                    <span class="text-sm text-[var(--slate-blue)]">رقم الجهة: #{{ $organization->id }}</span>
                </div>
                
                <div class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-info-circle ml-1"></i>
                    الحقول المميزة بـ <span class="text-red-400">*</span> إلزامية
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('organizations.update', $organization) }}" method="POST" class="space-y-6" id="editOrganizationForm">
            @csrf
            @method('PUT')

            <!-- Basic Information Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-info-circle text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">المعلومات الأساسية</h3>
                        <p class="text-sm text-[var(--slate-blue)]">قم بتحديث البيانات الأساسية للجهة</p>
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
                                   value="{{ old('name', $organization->name) }}"
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
                                <option value="" disabled>اختر نوع الجهة</option>
                                <option value="mosque" @selected(old('type', $organization->type)==='mosque')>مسجد</option>
                                <option value="school" @selected(old('type', $organization->type)==='school')>مدرسة</option>
                                <option value="university" @selected(old('type', $organization->type)==='university')>جامعة</option>
                                <option value="other" @selected(old('type', $organization->type)==='other')>أخرى</option>
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
                        <p class="text-sm text-[var(--slate-blue)] mb-3">انقر لاختيار نوع الجهة:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="type-option" data-type="mosque">
                                <div class="p-4 rounded-lg border-2 {{ old('type', $organization->type) === 'mosque' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-900/20 to-blue-900/10 flex items-center justify-center">
                                        <i class="fas fa-mosque text-xl text-blue-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">مسجد</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="school">
                                <div class="p-4 rounded-lg border-2 {{ old('type', $organization->type) === 'school' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-emerald-900/20 to-emerald-900/10 flex items-center justify-center">
                                        <i class="fas fa-school text-xl text-emerald-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">مدرسة</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="university">
                                <div class="p-4 rounded-lg border-2 {{ old('type', $organization->type) === 'university' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-900/20 to-purple-900/10 flex items-center justify-center">
                                        <i class="fas fa-university text-xl text-purple-300"></i>
                                    </div>
                                    <span class="text-[var(--cream)] font-medium">جامعة</span>
                                </div>
                            </div>
                            
                            <div class="type-option" data-type="other">
                                <div class="p-4 rounded-lg border-2 {{ old('type', $organization->type) === 'other' ? 'border-[var(--gold)] bg-[var(--deep-green)]/20' : 'border-[var(--border)]' }} bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
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
                        <p class="text-sm text-[var(--slate-blue)]">(اختياري) قم بتحديث المعلومات الإضافية</p>
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
                                      placeholder="أدخل عنوان الجهة">{{ old('address', $organization->address) }}</textarea>
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
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الهاتف
                        </label>
                        <div class="relative">
                            <input type="tel" 
                                   name="phone" 
                                   value="{{ old('phone', $organization->phone) }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="+966 123 456 789">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        @error('phone')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            البريد الإلكتروني
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $organization->email) }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="info@example.com">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        @error('email')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

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
                                       @checked(old('is_active', $organization->is_active))
                                       class="w-4 h-4 text-[var(--gold)] bg-[var(--surface)] border-[var(--border)] focus:ring-[var(--gold)]">
                                <span class="text-[var(--cream)]">نشطة</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="is_active" 
                                       value="0" 
                                       @checked(!old('is_active', $organization->is_active))
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

                    <!-- Website -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الموقع الإلكتروني
                        </label>
                        <div class="relative">
                            <input type="url" 
                                   name="website" 
                                   value="{{ old('website', $organization->website) }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="https://example.com">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-globe"></i>
                            </div>
                        </div>
                        @error('website')
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
                        <p class="text-sm text-[var(--slate-blue)]">(اختياري) قم بتحديث الوصف</p>
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
                                  placeholder="أدخل وصفًا مختصرًا عن الجهة...">{{ old('description', $organization->description) }}</textarea>
                        <div class="absolute left-3 top-3 text-[var(--slate-blue)]">
                            <i class="fas fa-align-right"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-[var(--slate-blue)]">
                        <span id="charCount">{{ Str::length(old('description', $organization->description)) }}</span> / 500 حرف
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
                    <i class="fas fa-history ml-1"></i>
                    يتم حفظ سجل التعديلات تلقائيًا
                </div>
                
                <div class="flex flex-wrap justify-center sm:justify-end gap-3">
                    <a href="{{ route('organizations.index') }}"
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
                        <i class="fas fa-save"></i>
                        حفظ التعديلات
                        <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform duration-200"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Changes Summary -->
    <div class="mt-8 p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
            <i class="fas fa-exchange-alt text-[var(--gold)]"></i>
            ملخص التغييرات
        </h3>
        
        <div class="space-y-4">
            <!-- Name Changes -->
            @if(old('name') && old('name') !== $organization->name)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-900/10 border border-yellow-500/20">
                <i class="fas fa-pen text-yellow-400"></i>
                <div class="flex-1">
                    <div class="text-sm font-medium text-[var(--cream)]">اسم الجهة</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-red-300 line-through">{{ $organization->name }}</span>
                        <i class="fas fa-arrow-left text-xs text-[var(--slate-blue)]"></i>
                        <span class="text-xs text-green-300">{{ old('name') }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Type Changes -->
            @if(old('type') && old('type') !== $organization->type)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-900/10 border border-blue-500/20">
                <i class="fas fa-tag text-blue-400"></i>
                <div class="flex-1">
                    <div class="text-sm font-medium text-[var(--cream)]">نوع الجهة</div>
                    <div class="flex items-center gap-2 mt-1">
                        @php
                            $typeLabels = [
                                'mosque' => 'مسجد',
                                'school' => 'مدرسة',
                                'university' => 'جامعة',
                                'other' => 'أخرى'
                            ];
                        @endphp
                        <span class="text-xs text-red-300 line-through">{{ $typeLabels[$organization->type] ?? $organization->type }}</span>
                        <i class="fas fa-arrow-left text-xs text-[var(--slate-blue)]"></i>
                        <span class="text-xs text-green-300">{{ $typeLabels[old('type')] ?? old('type') }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Status Changes -->
            @if(old('is_active') !== null && old('is_active') != $organization->is_active)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-green-900/10 border border-green-500/20">
                <i class="fas fa-toggle-on text-green-400"></i>
                <div class="flex-1">
                    <div class="text-sm font-medium text-[var(--cream)]">الحالة</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-red-300 line-through">{{ $organization->is_active ? 'نشطة' : 'غير نشطة' }}</span>
                        <i class="fas fa-arrow-left text-xs text-[var(--slate-blue)]"></i>
                        <span class="text-xs text-green-300">{{ old('is_active') ? 'نشطة' : 'غير نشطة' }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            @if(!old('name') && !old('type') && old('is_active') === null)
            <div class="text-center py-4">
                <p class="text-[var(--slate-blue)]">لم تقم بإجراء أي تغييرات بعد</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
    <div class="bg-[var(--surface)] rounded-xl border border-[var(--border)] max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6 border-b border-[var(--border)]">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-[var(--cream)]">معاينة التعديلات</h3>
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
                    تأكيد التعديلات
                </button>
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
            
            // Update changes summary
            updateChangesSummary();
        });
    });
    
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
        });
    }
    
    // Update changes summary on input
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', updateChangesSummary);
        input.addEventListener('input', updateChangesSummary);
    });
    
    // Initialize changes summary
    updateChangesSummary();
});

// Update changes summary function
function updateChangesSummary() {
    // This function would typically compare current values with original values
    // For now, it's a placeholder for actual comparison logic
    console.log('Changes summary updated');
}

// Show preview modal
function showPreview() {
    const form = document.getElementById('editOrganizationForm');
    const formData = new FormData(form);
    
    // Get form values
    const name = formData.get('name') || '{{ $organization->name }}';
    const type = formData.get('type') || '{{ $organization->type }}';
    const address = formData.get('address') || '{{ $organization->address }}';
    const phone = formData.get('phone') || '{{ $organization->phone }}';
    const email = formData.get('email') || '{{ $organization->email }}';
    const isActive = formData.get('is_active') || '{{ $organization->is_active }}';
    const website = formData.get('website') || '{{ $organization->website }}';
    const description = formData.get('description') || '{{ $organization->description }}';
    
    // Type data
    const typeIcons = {
        'mosque': { icon: 'fa-mosque', color: 'from-blue-900/20 to-blue-900/10', textColor: 'text-blue-300', label: 'مسجد' },
        'school': { icon: 'fa-school', color: 'from-emerald-900/20 to-emerald-900/10', textColor: 'text-emerald-300', label: 'مدرسة' },
        'university': { icon: 'fa-university', color: 'from-purple-900/20 to-purple-900/10', textColor: 'text-purple-300', label: 'جامعة' },
        'other': { icon: 'fa-building', color: 'from-gray-900/20 to-gray-900/10', textColor: 'text-gray-300', label: 'أخرى' }
    };
    
    const typeData = typeIcons[type] || typeIcons.other;
    
    // Generate preview content
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="space-y-6">
            <!-- Organization Header -->
            <div class="flex items-center gap-4 p-4 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-transparent border border-[var(--border)]">
                <div class="w-16 h-16 rounded-xl ${typeData.color} border border-[var(--border)] flex items-center justify-center">
                    <i class="fas ${typeData.icon} text-2xl ${typeData.textColor}"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-[var(--cream)]">${name}</h4>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${typeData.color} border border-white/10 ${typeData.textColor}">
                            ${typeData.label}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${isActive === '1' ? 'bg-gradient-to-r from-green-900/20 to-emerald-900/10 text-green-300 border border-green-500/20' : 'bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20'}">
                            ${isActive === '1' ? 'نشطة' : 'غير نشطة'}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${address ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-map-marker-alt text-[var(--slate-blue)]"></i>
                        <span class="text-sm font-medium text-[var(--cream)]">العنوان</span>
                    </div>
                    <p class="text-[var(--slate-blue)] text-sm">${address}</p>
                </div>
                ` : ''}
                
                ${phone ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-phone text-[var(--slate-blue)]"></i>
                        <span class="text-sm font-medium text-[var(--cream)]">الهاتف</span>
                    </div>
                    <p class="text-[var(--slate-blue)] text-sm">${phone}</p>
                </div>
                ` : ''}
                
                ${email ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-envelope text-[var(--slate-blue)]"></i>
                        <span class="text-sm font-medium text-[var(--cream)]">البريد الإلكتروني</span>
                    </div>
                    <p class="text-[var(--slate-blue)] text-sm">${email}</p>
                </div>
                ` : ''}
                
                ${website ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-globe text-[var(--slate-blue)]"></i>
                        <span class="text-sm font-medium text-[var(--cream)]">الموقع الإلكتروني</span>
                    </div>
                    <p class="text-[var(--slate-blue)] text-sm">${website}</p>
                </div>
                ` : ''}
            </div>
            
            <!-- Description -->
            ${description ? `
            <div class="p-4 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-align-right text-[var(--slate-blue)]"></i>
                    <span class="text-sm font-medium text-[var(--cream)]">الوصف</span>
                </div>
                <p class="text-[var(--slate-blue)] text-sm">${description}</p>
            </div>
            ` : ''}
            
            <!-- Changes Summary -->
            <div class="p-4 rounded-lg bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent border border-[var(--border)]">
                <h5 class="text-sm font-bold text-[var(--cream)] mb-3">ملخص التغييرات:</h5>
                <ul class="space-y-2 text-sm text-[var(--slate-blue)]">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-400"></i>
                        سيتم تحديث معلومات الجهة
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-history text-yellow-400"></i>
                        سيتم حفظ نسخة من التعديلات
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-clock text-blue-400"></i>
                        التاريخ المحدث: ${new Date().toLocaleDateString('ar-SA')}
                    </li>
                </ul>
            </div>
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
    document.getElementById('editOrganizationForm').submit();
}

// Form validation
const form = document.getElementById('editOrganizationForm');
if (form) {
    form.addEventListener('submit', function(e) {
        const name = form.querySelector('input[name="name"]').value.trim();
        const type = form.querySelector('select[name="type"]').value;
        
        if (!name || !type) {
            e.preventDefault();
            
            Swal.fire({
                title: 'بيانات ناقصة',
                text: 'يرجى ملء الحقول الإلزامية (*) قبل الحفظ',
                icon: 'warning',
                confirmButtonText: 'حسنًا',
                confirmButtonColor: '#C3A04E',
                reverseButtons: true
            });
        }
    });
}
</script>

<style>
/* Custom styles for edit page */
.type-option.active div {
    border-color: var(--gold) !important;
    background: rgba(29, 79, 49, 0.2) !important;
}

#previewModal {
    backdrop-filter: blur(5px);
}

/* Animation for modal */
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

/* Changes summary animations */
@keyframes highlightChange {
    0%, 100% {
        background-color: transparent;
    }
    50% {
        background-color: rgba(195, 160, 78, 0.1);
    }
}

.highlight-change {
    animation: highlightChange 1s ease;
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