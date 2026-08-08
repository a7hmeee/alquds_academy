@extends('layouts.app')
@section('title', 'إضافة معلم جديد')

@section('header', 'إضافة معلم جديد')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إضافة معلم جديد</h1>
            <p class="text-[var(--slate-blue)]">يمكنك إضافة معلم من مستخدم موجود أو إنشاء مستخدم جديد</p>
        </div>
        
        <a href="{{ route('teachers.index') }}"
           class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            رجوع للقائمة
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
                <div class="absolute top-1/2 left-0 h-0.5 bg-gradient-to-r from-[var(--gold)] to-[#D4B85C] -translate-y-1/2 z-10" style="width: 25%"></div>
                
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
                <div class="relative z-20 flex items-center justify-center w-10 h-10 rounded-full bg-[var(--surface)] border-2 border-[var(--border)] text-[var(--slate-blue)]">
                    4
                </div>
                
                <!-- Step Labels -->
                <div class="absolute top-12 left-0 right-0 flex justify-between gap-1">
                    <span class="text-[9px] sm:text-sm text-[var(--gold)] font-medium whitespace-nowrap">اختيار المستخدم</span>
                    <span class="text-[9px] sm:text-sm text-[var(--slate-blue)] whitespace-nowrap">المعلومات الشخصية</span>
                    <span class="text-[9px] sm:text-sm text-[var(--slate-blue)] whitespace-nowrap">المعلومات الأكاديمية</span>
                    <span class="text-[9px] sm:text-sm text-[var(--slate-blue)] whitespace-nowrap">المراجعة</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="teacherForm">
            @csrf

            <!-- User Selection Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-user-check text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">اختيار المستخدم</h3>
                        <p class="text-sm text-[var(--slate-blue)]">اختر مستخدمًا موجودًا أو أنشئ مستخدمًا جديدًا</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Existing User Selection -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-[var(--cream)]">
                            اختيار مستخدم موجود
                        </label>
                        <div class="relative">
                            <select name="user_id" 
                                    id="userSelect"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none"
                                    onchange="toggleUserFields()">
                                <option value="">— إنشاء مستخدم جديد —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                        {{ $user->name }} — {{ $user->email }}
                                        @if($user->phone)
                                            — {{ $user->phone }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                        
                        <!-- User Preview -->
                        <div id="userPreview" class="hidden p-4 rounded-lg bg-gradient-to-br from-[var(--deep-green)]/5 to-transparent border border-[var(--border)]">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-900/20 to-blue-900/10 border border-[var(--border)] flex items-center justify-center">
                                    <i class="fas fa-user text-lg text-blue-300"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[var(--cream)]" id="previewUserName"></h4>
                                    <p class="text-sm text-[var(--slate-blue)]" id="previewUserEmail"></p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-[var(--slate-blue)]">
                            <i class="fas fa-info-circle ml-1"></i>
                            إذا اخترت مستخدمًا موجودًا، سيتم تجاهل حقول إنشاء المستخدم الجديد
                        </p>
                        @error('user_id')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-[var(--border)]"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-[var(--surface)] text-[var(--slate-blue)]">أو</span>
                        </div>
                    </div>

                    <!-- New User Fields -->
                    <div class="space-y-4" id="newUserFields">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                                <i class="fas fa-user-plus text-xl text-[var(--gold)]"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[var(--cream)]">إنشاء مستخدم جديد</h4>
                                <p class="text-sm text-[var(--slate-blue)]">املأ البيانات لإنشاء مستخدم جديد</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                                    اسم المعلم <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                           placeholder="أدخل اسم المعلم">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                @error('name')
                                    <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                                    البريد الإلكتروني <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                           placeholder="example@email.com">
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

                            <!-- Password -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                                    كلمة المرور <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password" 
                                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                           placeholder="كلمة مرور قوية">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                                    تأكيد كلمة المرور <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                           placeholder="أعد كتابة كلمة المرور">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teacher Information Card -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                        <i class="fas fa-user-graduate text-xl text-[var(--gold)]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--cream)]">بيانات المعلم</h3>
                        <p class="text-sm text-[var(--slate-blue)]">أدخل المعلومات الأكاديمية والوظيفية للمعلم</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Academic Degree -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            الدرجة العلمية <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <select name="academic_degree" 
                                    id="academicDegree"
                                    class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 appearance-none"
                                    required>
                                <option value="">اختر الدرجة العلمية</option>
                                <option value="hafiz" @selected(old('academic_degree')=='hafiz')>حافظ قرآن</option>
                                <option value="ijazah" @selected(old('academic_degree')=='ijazah')>إجازة</option>
                                <option value="bachelor" @selected(old('academic_degree')=='bachelor')>بكالوريوس</option>
                                <option value="master" @selected(old('academic_degree')=='master')>ماجستير</option>
                                <option value="doctorate" @selected(old('academic_degree')=='doctorate')>دكتوراه</option>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="absolute left-10 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-[var(--slate-blue)]"></i>
                            </div>
                        </div>
                        @error('academic_degree')
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
                                       name="status" 
                                       value="active" 
                                       @checked(old('status', 'active') === 'active')
                                       class="w-4 h-4 text-[var(--gold)] bg-[var(--surface)] border-[var(--border)] focus:ring-[var(--gold)]">
                                <span class="text-[var(--cream)]">نشط</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="inactive" 
                                       @checked(old('status') === 'inactive')
                                       class="w-4 h-4 text-[var(--gold)] bg-[var(--surface)] border-[var(--border)] focus:ring-[var(--gold)]">
                                <span class="text-[var(--cream)]">غير نشط</span>
                            </label>
                        </div>
                        @error('status')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            التخصص
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="specialization" 
                                   value="{{ old('specialization') }}"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="مثال: علوم القرآن، الحديث">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        @error('specialization')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Years of Experience -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            سنوات الخبرة
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="years_of_experience" 
                                   value="{{ old('years_of_experience') }}"
                                   min="0"
                                   max="50"
                                   class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200"
                                   placeholder="عدد سنوات الخبرة">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--slate-blue)]">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        @error('years_of_experience')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Teacher Photo -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            صورة المعلم (اختياري)
                        </label>
                        <div class="space-y-4">
                            <!-- Upload Area -->
                            <div class="relative">
                                <input type="file" 
                                       name="photo" 
                                       id="photoInput"
                                       class="hidden"
                                       accept="image/*">
                                <label for="photoInput"
                                       class="block p-6 rounded-lg border-2 border-dashed border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer hover:border-[var(--gold)] transition-all duration-200">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-camera text-2xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <p class="text-[var(--cream)] font-medium mb-1">انقر لرفع صورة المعلم</p>
                                    <p class="text-sm text-[var(--slate-blue)]">JPG, PNG أو GIF - الحد الأقصى 2MB</p>
                                </label>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="hidden">
                                <div class="flex items-center justify-between p-4 rounded-lg bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent border border-[var(--border)]">
                                    <div class="flex items-center gap-3">
                                        <img id="previewImage" 
                                             class="w-16 h-16 rounded-lg object-cover border border-[var(--border)]"
                                             alt="معاينة الصورة">
                                        <div>
                                            <p class="text-[var(--cream)] font-medium" id="fileName"></p>
                                            <p class="text-sm text-[var(--slate-blue)]" id="fileSize"></p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            onclick="removeImage()"
                                            class="p-2 rounded-lg hover:bg-red-900/20 text-red-300 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('photo')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Biography -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                            نبذة عن المعلم
                        </label>
                        <div class="relative">
                            <textarea name="bio" 
                                      rows="4"
                                      class="w-full px-4 py-3 pr-12 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] placeholder-[var(--slate-blue)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="أدخل نبذة مختصرة عن المعلم وخبراته...">{{ old('bio') }}</textarea>
                            <div class="absolute left-3 top-3 text-[var(--slate-blue)]">
                                <i class="fas fa-pen"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-[var(--slate-blue)]">
                            <span id="bioCharCount">0</span> / 1000 حرف
                        </div>
                        @error('bio')
                            <div class="mt-2 p-2 rounded-lg bg-red-900/20 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Degree Selection Cards -->
            <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                <h4 class="text-sm font-medium text-[var(--slate-blue)] mb-4">انقر لاختيار الدرجة العلمية:</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div class="degree-option" data-degree="hafiz">
                        <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-900/20 to-blue-900/10 flex items-center justify-center">
                                <i class="fas fa-book-quran text-xl text-blue-300"></i>
                            </div>
                            <span class="text-[var(--cream)] font-medium">حافظ قرآن</span>
                        </div>
                    </div>
                    
                    <div class="degree-option" data-degree="ijazah">
                        <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-emerald-900/20 to-emerald-900/10 flex items-center justify-center">
                                <i class="fas fa-award text-xl text-emerald-300"></i>
                            </div>
                            <span class="text-[var(--cream)] font-medium">إجازة</span>
                        </div>
                    </div>
                    
                    <div class="degree-option" data-degree="bachelor">
                        <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-900/20 to-purple-900/10 flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-xl text-purple-300"></i>
                            </div>
                            <span class="text-[var(--cream)] font-medium">بكالوريوس</span>
                        </div>
                    </div>
                    
                    <div class="degree-option" data-degree="master">
                        <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-yellow-900/20 to-yellow-900/10 flex items-center justify-center">
                                <i class="fas fa-user-graduate text-xl text-yellow-300"></i>
                            </div>
                            <span class="text-[var(--cream)] font-medium">ماجستير</span>
                        </div>
                    </div>
                    
                    <div class="degree-option" data-degree="doctorate">
                        <div class="p-4 rounded-lg border-2 border-[var(--border)] bg-[var(--surface)] text-center cursor-pointer transition-all duration-200 hover:border-[var(--gold)] hover:bg-[var(--deep-green)]/10">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-red-900/20 to-red-900/10 flex items-center justify-center">
                                <i class="fas fa-user-tie text-xl text-red-300"></i>
                            </div>
                            <span class="text-[var(--cream)] font-medium">دكتوراه</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-[var(--border)]">
                <div class="text-sm text-[var(--slate-blue)]">
                    <i class="fas fa-info-circle ml-1"></i>
                    الحقول المميزة بـ <span class="text-red-400">*</span> إلزامية
                </div>
                
                <div class="flex flex-wrap justify-center sm:justify-end gap-3">
                    <a href="{{ route('teachers.index') }}"
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
                        إضافة المعلم
                        <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform duration-200"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-[var(--cream)] mb-4">معاينة المعلم</h3>
        <div id="previewCard" class="p-6 rounded-xl border-2 border-dashed border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/5 to-transparent">
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                    <i class="fas fa-user-graduate text-2xl text-[var(--slate-blue)]"></i>
                </div>
                <p class="text-[var(--slate-blue)]">ستظهر معاينة المعلم هنا بعد ملء البيانات الأساسية</p>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
    <div class="bg-[var(--surface)] rounded-xl border border-[var(--border)] max-w-2xl w-full max-h-[90vh] overflow-auto">
        <div class="p-6 border-b border-[var(--border)]">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-[var(--cream)]">معاينة المعلم الجديد</h3>
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
    // Initialize user selection
    toggleUserFields();
    
    // User selection change handler
    const userSelect = document.getElementById('userSelect');
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            toggleUserFields();
            updateTeacherPreview();
        });
    }
    
    // Degree selection
    const degreeOptions = document.querySelectorAll('.degree-option');
    const degreeSelect = document.getElementById('academicDegree');
    
    degreeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const degree = this.dataset.degree;
            degreeSelect.value = degree;
            
            // Update active class
            degreeOptions.forEach(opt => {
                opt.querySelector('div').classList.remove('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
                opt.querySelector('div').classList.add('border-[var(--border)]');
            });
            
            this.querySelector('div').classList.remove('border-[var(--border)]');
            this.querySelector('div').classList.add('border-[var(--gold)]', 'bg-[var(--deep-green)]/20');
            
            updateTeacherPreview();
        });
    });
    
    // Image upload
    const photoInput = document.getElementById('photoInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && validateImageFile(file)) {
                updateImagePreview(file);
                updateTeacherPreview();
            }
        });
    }
    
    // Bio character count
    const bioTextarea = document.querySelector('textarea[name="bio"]');
    const bioCharCount = document.getElementById('bioCharCount');
    
    if (bioTextarea && bioCharCount) {
        bioCharCount.textContent = bioTextarea.value.length;
        bioTextarea.addEventListener('input', function() {
            bioCharCount.textContent = this.value.length;
            if (this.value.length > 1000) {
                this.value = this.value.substring(0, 1000);
                bioCharCount.textContent = 1000;
                bioCharCount.classList.add('text-red-400');
            } else {
                bioCharCount.classList.remove('text-red-400');
            }
            updateTeacherPreview();
        });
    }
    
    // Update preview on all inputs
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', updateTeacherPreview);
        input.addEventListener('input', updateTeacherPreview);
    });
    
    // Initialize preview
    updateTeacherPreview();
});

// Toggle user fields based on selection
function toggleUserFields() {
    const userSelect = document.getElementById('userSelect');
    const userPreview = document.getElementById('userPreview');
    const newUserFields = document.getElementById('newUserFields');
    
    if (userSelect && userSelect.value) {
        // Existing user selected
        newUserFields.style.opacity = '0.5';
        newUserFields.style.pointerEvents = 'none';
        
        // Show user preview
        const selectedOption = userSelect.options[userSelect.selectedIndex];
        document.getElementById('previewUserName').textContent = selectedOption.dataset.name;
        document.getElementById('previewUserEmail').textContent = selectedOption.dataset.email;
        userPreview.classList.remove('hidden');
    } else {
        // New user mode
        newUserFields.style.opacity = '1';
        newUserFields.style.pointerEvents = 'all';
        userPreview.classList.add('hidden');
    }
}

// Validate image file
function validateImageFile(file) {
    // Size validation (2MB)
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire({
            title: 'حجم الملف كبير',
            text: 'الحد الأقصى لحجم الصورة هو 2 ميجابايت',
            icon: 'error',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#C3A04E'
        });
        return false;
    }
    
    // Type validation
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        Swal.fire({
            title: 'نوع ملف غير مدعوم',
            text: 'الرجاء رفع صورة بصيغة JPG, PNG أو GIF',
            icon: 'error',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#C3A04E'
        });
        return false;
    }
    
    return true;
}

// Update image preview
function updateImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        imagePreview.classList.remove('hidden');
        imagePreview.classList.add('block');
    };
    reader.readAsDataURL(file);
}

// Remove uploaded image
function removeImage() {
    const photoInput = document.getElementById('photoInput');
    const imagePreview = document.getElementById('imagePreview');
    
    photoInput.value = '';
    imagePreview.classList.add('hidden');
    imagePreview.classList.remove('block');
    updateTeacherPreview();
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Update teacher preview
function updateTeacherPreview() {
    const form = document.getElementById('teacherForm');
    const formData = new FormData(form);
    const userSelect = document.getElementById('userSelect');
    const degreeSelect = document.getElementById('academicDegree');
    const previewCard = document.getElementById('previewCard');
    
    // Get form values
    const isExistingUser = userSelect && userSelect.value;
    const userName = isExistingUser ? 
        userSelect.options[userSelect.selectedIndex]?.dataset.name || '' : 
        formData.get('name') || '';
    const userEmail = isExistingUser ? 
        userSelect.options[userSelect.selectedIndex]?.dataset.email || '' : 
        formData.get('email') || '';
    const academicDegree = degreeSelect?.value || '';
    const bio = formData.get('bio') || '';
    const status = formData.get('status') || 'active';
    const specialization = formData.get('specialization') || '';
    const yearsOfExperience = formData.get('years_of_experience') || '';
    
    if (!userName || !academicDegree) {
        previewCard.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                    <i class="fas fa-user-graduate text-2xl text-[var(--slate-blue)]"></i>
                </div>
                <p class="text-[var(--slate-blue)]">ستظهر معاينة المعلم هنا بعد ملء البيانات الأساسية</p>
            </div>
        `;
        return;
    }
    
    // Degree data
    const degreeData = {
        'hafiz': { icon: 'fa-book-quran', color: 'from-blue-900/20 to-blue-900/10', textColor: 'text-blue-300', label: 'حافظ قرآن' },
        'ijazah': { icon: 'fa-award', color: 'from-emerald-900/20 to-emerald-900/10', textColor: 'text-emerald-300', label: 'إجازة' },
        'bachelor': { icon: 'fa-graduation-cap', color: 'from-purple-900/20 to-purple-900/10', textColor: 'text-purple-300', label: 'بكالوريوس' },
        'master': { icon: 'fa-user-graduate', color: 'from-yellow-900/20 to-yellow-900/10', textColor: 'text-yellow-300', label: 'ماجستير' },
        'doctorate': { icon: 'fa-user-tie', color: 'from-red-900/20 to-red-900/10', textColor: 'text-red-300', label: 'دكتوراه' }
    };
    
    const degree = degreeData[academicDegree] || degreeData.hafiz;
    
    // Generate preview
    previewCard.innerHTML = `
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br ${degree.color} border-2 border-[var(--border)] flex items-center justify-center">
                    <i class="fas ${degree.icon} text-2xl ${degree.textColor}"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-[var(--cream)]">${userName}</h4>
                    <p class="text-[var(--slate-blue)]">${userEmail}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${degree.color} border border-white/10 ${degree.textColor}">
                            ${degree.label}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${status === 'active' ? 'bg-gradient-to-r from-green-900/20 to-emerald-900/10 text-green-300 border border-green-500/20' : 'bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20'}">
                            ${status === 'active' ? 'نشط' : 'غير نشط'}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${specialization ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">التخصص</div>
                    <div class="text-[var(--cream)] font-medium">${specialization}</div>
                </div>
                ` : ''}
                
                ${yearsOfExperience ? `
                <div class="p-3 rounded-lg bg-[var(--surface)] border border-[var(--border)]">
                    <div class="text-sm text-[var(--slate-blue)] mb-1">سنوات الخبرة</div>
                    <div class="text-[var(--cream)] font-medium">${yearsOfExperience} سنة</div>
                </div>
                ` : ''}
            </div>
            
            <!-- Bio -->
            ${bio ? `
            <div class="p-4 rounded-lg bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent border border-[var(--border)]">
                <div class="text-sm font-medium text-[var(--cream)] mb-2">نبذة عن المعلم:</div>
                <p class="text-[var(--slate-blue)] text-sm">${bio.substring(0, 200)}${bio.length > 200 ? '...' : ''}</p>
            </div>
            ` : ''}
            
            <!-- User Type -->
            <div class="text-sm text-[var(--slate-blue)]">
                <i class="fas fa-user-tag ml-1"></i>
                ${isExistingUser ? 'مستخدم موجود' : 'مستخدم جديد سيتم إنشاؤه'}
            </div>
        </div>
    `;
}

// Show preview modal
function showPreview() {
    const form = document.getElementById('teacherForm');
    const formData = new FormData(form);
    const previewContent = document.getElementById('previewContent');
    
    // Validate required fields
    const userSelect = document.getElementById('userSelect');
    const nameInput = form.querySelector('input[name="name"]');
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const degreeSelect = document.getElementById('academicDegree');
    
    const isExistingUser = userSelect && userSelect.value;
    const hasNewUserData = nameInput.value && emailInput.value && passwordInput.value;
    
    if (!isExistingUser && !hasNewUserData) {
        Swal.fire({
            title: 'بيانات ناقصة',
            text: 'يجب إما اختيار مستخدم موجود أو ملء بيانات المستخدم الجديد',
            icon: 'warning',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#C3A04E'
        });
        return;
    }
    
    if (!degreeSelect.value) {
        Swal.fire({
            title: 'الدرجة العلمية مطلوبة',
            text: 'الرجاء اختيار الدرجة العلمية للمعلم',
            icon: 'warning',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#C3A04E'
        });
        degreeSelect.focus();
        return;
    }
    
    // Generate preview content (similar to updateTeacherPreview but more detailed)
    updateTeacherPreview();
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
    document.getElementById('teacherForm').submit();
}
</script>

<style>
/* Custom styles */
.degree-option.active div {
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

/* Form transitions */
#newUserFields {
    transition: all 0.3s ease;
}
</style>
@endsection