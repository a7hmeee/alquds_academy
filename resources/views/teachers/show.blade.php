@extends('layouts.app')
@section('title', 'تفاصيل المعلم - ' . $teacher->user->name)

@section('header', 'تفاصيل المعلم')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">تفاصيل المعلم</h1>
            <p class="text-[var(--slate-blue)]">عرض كافة المعلومات والتفاصيل المتعلقة بالمعلم</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('teachers.edit', $teacher) }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            
            <a href="{{ route('teachers.index') }}"
               class="px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                رجوع للقائمة
            </a>
        </div>
    </div>

    <!-- Teacher Profile Card -->
    <div class="main-content-section">
        <!-- Profile Header -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
            <!-- Profile Image -->
            <div class="relative">
                @if($teacher->photo)
                    <img src="{{ asset('storage/'.$teacher->photo) }}"
                         class="w-32 h-32 rounded-2xl object-cover border-4 border-[var(--border)] shadow-lg">
                @else
                    <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-[var(--deep-green)] to-teal-900 border-4 border-[var(--border)] flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-graduate text-4xl text-[var(--gold)]"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute -bottom-2 -right-2">
                    @if($teacher->status === 'active')
                        <div class="px-3 py-1.5 rounded-full bg-gradient-to-r from-green-900/20 to-emerald-900/10 text-green-300 border border-green-500/20 flex items-center gap-2 shadow-lg">
                            <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-sm font-medium">نشط</span>
                        </div>
                    @else
                        <div class="px-3 py-1.5 rounded-full bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20 flex items-center gap-2 shadow-lg">
                            <div class="w-2 h-2 rounded-full bg-red-400"></div>
                            <span class="text-sm font-medium">غير نشط</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Teacher Info -->
            <div class="flex-1 text-center md:text-right">
                <h2 class="text-2xl md:text-3xl font-bold text-[var(--cream)] mb-2">{{ $teacher->user->name }}</h2>
                
                <!-- Academic Degree -->
                @php
                    $degreeData = [
                        'hafiz' => ['icon' => 'fa-book-quran', 'color' => 'from-blue-900/20 to-blue-900/10', 'textColor' => 'text-blue-300', 'label' => 'حافظ قرآن'],
                        'ijazah' => ['icon' => 'fa-award', 'color' => 'from-emerald-900/20 to-emerald-900/10', 'textColor' => 'text-emerald-300', 'label' => 'إجازة'],
                        'bachelor' => ['icon' => 'fa-graduation-cap', 'color' => 'from-purple-900/20 to-purple-900/10', 'textColor' => 'text-purple-300', 'label' => 'بكالوريوس'],
                        'master' => ['icon' => 'fa-user-graduate', 'color' => 'from-yellow-900/20 to-yellow-900/10', 'textColor' => 'text-yellow-300', 'label' => 'ماجستير'],
                        'doctorate' => ['icon' => 'fa-user-tie', 'color' => 'from-red-900/20 to-red-900/10', 'textColor' => 'text-red-300', 'label' => 'دكتوراه']
                    ];
                    $degree = $degreeData[$teacher->academic_degree] ?? $degreeData['hafiz'];
                @endphp
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r {{ $degree['color'] }} border border-white/10 mb-4">
                    <i class="fas {{ $degree['icon'] }} {{ $degree['textColor'] }}"></i>
                    <span class="text-[var(--cream)] font-medium">{{ $degree['label'] }}</span>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <div class="text-center">
                        <div class="text-sm text-[var(--slate-blue)]">رقم المعرف</div>
                        <div class="font-bold text-[var(--gold)]">#{{ $teacher->id }}</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-sm text-[var(--slate-blue)]">تاريخ الإنشاء</div>
                        <div class="font-bold text-[var(--cream)]">{{ $teacher->created_at->format('Y/m/d') }}</div>
                    </div>
                    
                    @if($teacher->years_of_experience)
                    <div class="text-center">
                        <div class="text-sm text-[var(--slate-blue)]">سنوات الخبرة</div>
                        <div class="font-bold text-[var(--cream)]">{{ $teacher->years_of_experience }} سنة</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-[var(--border)] mb-6 overflow-x-auto">
            <div class="flex space-x-6 md:space-x-8 whitespace-nowrap" id="tabs">
                <button class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-transparent text-[var(--slate-blue)] hover:text-[var(--gold)] transition-all duration-200"
                        data-tab="personal">
                    <i class="fas fa-user ml-2"></i>
                    المعلومات الشخصية
                </button>
                <button class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-transparent text-[var(--slate-blue)] hover:text-[var(--gold)] transition-all duration-200"
                        data-tab="academic">
                    <i class="fas fa-graduation-cap ml-2"></i>
                    المعلومات الأكاديمية
                </button>
                <button class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-transparent text-[var(--slate-blue)] hover:text-[var(--gold)] transition-all duration-200"
                        data-tab="courses">
                    <i class="fas fa-book ml-2"></i>
                    الدروس (0)
                </button>
            </div>
        </div>

        <!-- Tabs Content -->
        <div id="tabs-content">
            <!-- Personal Information Tab -->
            <div class="tab-content active" id="personal-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info Card -->
                    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                            <i class="fas fa-id-card text-[var(--gold)]"></i>
                            المعلومات الشخصية
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-[var(--border)]">
                                <span class="text-sm text-[var(--slate-blue)]">الاسم الكامل</span>
                                <span class="font-medium text-[var(--cream)]">{{ $teacher->user->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b border-[var(--border)]">
                                <span class="text-sm text-[var(--slate-blue)]">البريد الإلكتروني</span>
                                <span class="font-medium text-[var(--cream)]">{{ $teacher->user->email }}</span>
                            </div>
                            
                            @if($teacher->user->phone)
                            <div class="flex items-center justify-between py-2 border-b border-[var(--border)]">
                                <span class="text-sm text-[var(--slate-blue)]">رقم الهاتف</span>
                                <span class="font-medium text-[var(--cream)]">{{ $teacher->user->phone }}</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-[var(--slate-blue)]">تاريخ التسجيل</span>
                                <span class="font-medium text-[var(--cream)]">{{ $teacher->user->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Activity Card -->
                    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-[var(--gold)]"></i>
                            الحالة والنشاط
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">حالة الحساب</div>
                                @if($teacher->status === 'active')
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-green-900/20 to-emerald-900/10 text-green-300 border border-green-500/20">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="font-medium">نشط - يمكنه تسجيل الدخول</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20">
                                        <i class="fas fa-times-circle"></i>
                                        <span class="font-medium">غير نشط - لا يمكنه تسجيل الدخول</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">آخر تحديث</div>
                                <div class="font-medium text-[var(--cream)]">
                                    {{ $teacher->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">المدة في النظام</div>
                                <div class="font-medium text-[var(--cream)]">
                                    {{ $teacher->created_at->diffInDays() }} يوم
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biography Card -->
                @if($teacher->bio)
                <div class="mt-6 p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                    <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                        <i class="fas fa-file-alt text-[var(--gold)]"></i>
                        نبذة عن المعلم
                    </h3>
                    <div class="prose prose-invert max-w-none">
                        <p class="text-[var(--slate-blue)] leading-relaxed">{{ $teacher->bio }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Academic Information Tab -->
            <div class="tab-content hidden" id="academic-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Academic Details Card -->
                    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-[var(--gold)]"></i>
                            التفاصيل الأكاديمية
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">الدرجة العلمية</div>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg {{ $degree['color'] }} border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas {{ $degree['icon'] }} {{ $degree['textColor'] }} text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $degree['label'] }}</div>
                                        <div class="text-sm text-[var(--slate-blue)] capitalize">{{ $teacher->academic_degree }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($teacher->specialization)
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">التخصص</div>
                                <div class="font-medium text-[var(--cream)] flex items-center gap-2">
                                    <i class="fas fa-star text-[var(--slate-blue)]"></i>
                                    {{ $teacher->specialization }}
                                </div>
                            </div>
                            @endif
                            
                            @if($teacher->years_of_experience)
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">سنوات الخبرة</div>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-900/20 to-yellow-900/10 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-lg text-yellow-300"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $teacher->years_of_experience }} سنة</div>
                                        <div class="text-sm text-[var(--slate-blue)]">خبرة تدريسية</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Info Card -->
                    <div class="p-6 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
                        <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[var(--gold)]"></i>
                            معلومات إضافية
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">تاريخ بدء العمل</div>
                                <div class="font-medium text-[var(--cream)]">
                                    {{ $teacher->created_at->format('Y/m/d') }}
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">آخر تحديث للمعلومات</div>
                                <div class="font-medium text-[var(--cream)]">
                                    {{ $teacher->updated_at->format('Y/m/d') }}
                                    <span class="text-sm text-[var(--slate-blue)]">({{ $teacher->updated_at->diffForHumans() }})</span>
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-[var(--slate-blue)] mb-2">حساب المعلم</div>
                                <div class="font-medium text-[var(--cream)]">
                                    {{ $teacher->user->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Tab -->
            <div class="tab-content hidden" id="courses-tab">
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl text-[var(--slate-blue)]"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد دروس حالياً</h3>
                    <p class="text-[var(--slate-blue)] mb-6">لم يتم تعيين أي دروس لهذا المعلم بعد</p>
                    <a href="#"
                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        إضافة درس للمعلم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Print Card -->
        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-print text-[var(--gold)]"></i>
                طباعة وتصدير
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-4">طباعة معلومات المعلم أو تصديرها</p>
            <div class="flex gap-2">
                <button class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex-1 text-center">
                    <i class="fas fa-print ml-1"></i>
                    طباعة
                </button>
                <button class="px-3 py-2 rounded-lg bg-gradient-to-r from-emerald-900/20 to-emerald-900/10 text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 transition-all duration-200 flex-1 text-center">
                    <i class="fas fa-file-pdf ml-1"></i>
                    PDF
                </button>
            </div>
        </div>

        <!-- Contact Card -->
        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-envelope text-[var(--gold)]"></i>
                التواصل
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-4">تواصل مع المعلم مباشرة</p>
            <div class="flex flex-col gap-2">
                <a href="mailto:{{ $teacher->user->email }}"
                   class="px-3 py-2 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 text-center">
                    <i class="fas fa-envelope ml-1"></i>
                    إرسال بريد إلكتروني
                </a>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="p-5 rounded-xl border border-red-500/30 bg-gradient-to-br from-red-900/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                منطقة الخطر
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-4">إجراءات لا يمكن التراجع عنها</p>
            <div class="space-y-2">
                
                
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirmDeletion('{{ $teacher->user->name }}')"
                            class="w-full px-3 py-2 rounded-lg bg-gradient-to-r from-red-900/20 to-red-900/10 text-red-300 border border-red-500/20 hover:border-red-500/40 transition-all duration-200 text-right flex items-center justify-between">
                        <span>حذف المعلم نهائياً</span>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeletion(teacherName) {
    return Swal.fire({
        title: 'هل أنت متأكد؟',
        html: `سيتم حذف المعلم: <strong>${teacherName}</strong><br><br>
              <div class="text-red-400 text-sm">
                <i class="fas fa-exclamation-triangle ml-1"></i>
                هذا الإجراء لا يمكن التراجع عنه
              </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذفه',
        cancelButtonText: 'إلغاء',
        reverseButtons: true,
        customClass: {
            confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg',
            cancelButton: 'bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg'
        }
    }).then((result) => {
        return result.isConfirmed;
    });
}

// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('text-[var(--gold)]', 'border-[var(--gold)]');
                btn.classList.add('text-[var(--slate-blue)]', 'border-transparent');
            });
            
            // Add active class to clicked button
            this.classList.remove('text-[var(--slate-blue)]', 'border-transparent');
            this.classList.add('text-[var(--gold)]', 'border-[var(--gold)]');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
    
    // Initialize first tab as active
    if (tabButtons.length > 0) {
        tabButtons[0].classList.add('text-[var(--gold)]', 'border-[var(--gold)]');
        tabButtons[0].classList.remove('text-[var(--slate-blue)]', 'border-transparent');
    }
});

// Print functionality
document.querySelectorAll('button:contains("طباعة")').forEach(button => {
    button.addEventListener('click', function() {
        window.print();
    });
});
</script>

<style>
/* Custom styles for teacher details */
.prose {
    max-width: 100% !important;
}

.prose p {
    margin: 0;
    line-height: 1.8;
}

/* Tab styles */
.tab-btn {
    border-bottom-width: 2px;
    transition: all 0.2s ease;
}

.tab-content {
    transition: all 0.3s ease;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .main-content-section {
        border: none !important;
        box-shadow: none !important;
    }
    
    .tab-content {
        display: block !important;
    }
    
    .tab-content.hidden {
        display: none !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tab-btn {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
    }
    
    .tab-btn i {
        display: none;
    }
}
</style>
@endsection