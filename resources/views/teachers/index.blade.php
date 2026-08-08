@extends('layouts.app')
@section('title', 'المعلمين')

@section('header', 'إدارة المعلمين')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إدارة المعلمين</h1>
            <p class="text-[var(--slate-blue)]">إدارة وإضافة المعلمين في النظام التعليمي</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <!-- Search Box -->
            <div class="relative flex-1 w-full sm:w-auto">
                <input type="text" 
                       placeholder="بحث عن معلم..." 
                       class="pr-10 pl-4 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-lg text-[var(--cream)] w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[var(--slate-blue)]">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <!-- Add Teacher Button -->
            <a href="{{ route('teachers.create') }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                إضافة معلم جديد
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-green-900/30 to-emerald-900/20 border border-green-500/30 text-green-200 animate-slide-in">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg bg-gradient-to-r from-red-900/30 to-rose-900/20 border border-red-500/30 text-red-200 animate-slide-in">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-users text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">إجمالي المعلمين</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
               
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جميع المعلمين</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-check text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">نشطين</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $teachers->where('status', 'active')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">معلمون نشطون</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-graduate text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">حفظة</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $teachers->where('academic_degree', 'hafiz')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">حفظة القرآن</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-tie text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">حاملي الدكتوراه</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $teachers->where('academic_degree', 'doctorate')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">دكتوراه</div>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="main-content-section">
        <!-- Table Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-[var(--gold)] mb-2 md:mb-0">قائمة المعلمين</h2>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter -->
                <div class="relative flex-1 min-w-0 sm:flex-none">
                    <select class="px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                
                <!-- Export -->
                <button class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    تصدير
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                    <tr>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">المعلم</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">المعلومات</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الدرجة العلمية</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group">
                            <!-- Teacher Column -->
                            <td class="py-4 px-6" data-label="المعلم">
                                <div class="flex items-center gap-3">
                                    <!-- Profile Image -->
                                    <div class="relative">
                                        @if($teacher->photo)
                                            <img src="{{ asset('storage/'.$teacher->photo) }}"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-[var(--border)]">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[var(--deep-green)] to-teal-900 border-2 border-[var(--border)] flex items-center justify-center">
                                                <i class="fas fa-user text-lg text-[var(--gold)]"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Online Status -->
                                        @if($teacher->status === 'active')
                                            <div class="absolute bottom-0 left-0 w-3 h-3 rounded-full bg-green-400 border border-[var(--surface)]"></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Teacher Info -->
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $teacher->user->name }}</div>
                                        <div class="text-xs text-[var(--slate-blue)] mt-1">
                                            <i class="fas fa-calendar-alt ml-1"></i>
                                            انضم: {{ $teacher->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Information Column -->
                            <td class="py-4 px-6" data-label="المعلومات">
                                <div class="space-y-1">
                                    <div class="text-sm text-[var(--cream)]">
                                        <i class="fas fa-envelope ml-1 text-[var(--slate-blue)]"></i>
                                        {{ $teacher->user->email ?? 'غير محدد' }}
                                    </div>
                                    @if($teacher->specialization)
                                        <div class="text-sm text-[var(--slate-blue)]">
                                            <i class="fas fa-star ml-1"></i>
                                            {{ $teacher->specialization }}
                                        </div>
                                    @endif
                                    @if($teacher->years_of_experience)
                                        <div class="text-sm text-[var(--slate-blue)]">
                                            <i class="fas fa-clock ml-1"></i>
                                            {{ $teacher->years_of_experience }} سنة خبرة
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Academic Degree Column -->
                            <td class="py-4 px-6" data-label="الدرجة العلمية">
                                @php
                                    $degreeData = [
                                        'hafiz' => ['icon' => 'fa-book-quran', 'color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300', 'label' => 'حافظ'],
                                        'ijazah' => ['icon' => 'fa-award', 'color' => 'from-emerald-900/20 to-emerald-900/10', 'text' => 'text-emerald-300', 'label' => 'إجازة'],
                                        'bachelor' => ['icon' => 'fa-graduation-cap', 'color' => 'from-purple-900/20 to-purple-900/10', 'text' => 'text-purple-300', 'label' => 'بكالوريوس'],
                                        'master' => ['icon' => 'fa-user-graduate', 'color' => 'from-yellow-900/20 to-yellow-900/10', 'text' => 'text-yellow-300', 'label' => 'ماجستير'],
                                        'doctorate' => ['icon' => 'fa-user-tie', 'color' => 'from-red-900/20 to-red-900/10', 'text' => 'text-red-300', 'label' => 'دكتوراه']
                                    ];
                                    $degree = $degreeData[$teacher->academic_degree] ?? $degreeData['hafiz'];
                                @endphp
                                
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg {{ $degree['color'] }} border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas {{ $degree['icon'] }} {{ $degree['text'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-[var(--cream)]">{{ $degree['label'] }}</div>
                                        <div class="text-xs text-[var(--slate-blue)] capitalize">{{ $teacher->academic_degree }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="py-4 px-6" data-label="الحالة">
                                @if($teacher->status === 'active')
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-green-900/20 to-emerald-900/10 text-green-300 border border-green-500/20 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                        نشط
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="py-4 px-6" data-label="الإجراءات">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <!-- View Button -->
                                    <a href="{{ route('teachers.show', $teacher) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('teachers.edit', $teacher) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">تعديل</span>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('teachers.destroy', $teacher) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirmDeletion(event, '{{ $teacher->user->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20 hover:border-red-500/40 transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="hidden sm:inline">حذف</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا يوجد معلمون مسجلون</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">ابدأ بإضافة معلم جديد لتظهر هنا</p>
                                    <a href="{{ route('teachers.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-user-plus"></i>
                                        إضافة أول معلم
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary -->
        
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Bulk Actions -->
        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                <i class="fas fa-tasks text-[var(--gold)]"></i>
                إجراءات جماعية
            </h3>
            <div class="space-y-3">
                <button class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تفعيل جميع المعلمين</span>
                    <i class="fas fa-toggle-on text-green-400"></i>
                </button>
                <button class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تعطيل جميع المعلمين</span>
                    <i class="fas fa-toggle-off text-red-400"></i>
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-[var(--gold)]"></i>
                إحصائيات سريعة
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">معدل الحفظة:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $teachers->count() > 0 ? round(($teachers->where('academic_degree', 'hafiz')->count() / $teachers->count()) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">متوسط الخبرة:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $teachers->whereNotNull('years_of_experience')->avg('years_of_experience') ?? 0 }} سنة
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">النسبة النشطة:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $teachers->count() > 0 ? round(($teachers->where('status', 'active')->count() / $teachers->count()) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeletion(event, teacherName) {
    event.preventDefault();
    
    Swal.fire({
        title: 'هل أنت متأكد؟',
        html: `سيتم حذف المعلم: <strong>${teacherName}</strong><br><br>
              <small class="text-gray-400">لن تتمكن من استعادة هذه البيانات بعد الحذف</small>`,
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
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    
    return false;
}

// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[type="text"]');
    const filterSelect = document.querySelector('select');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    // Implement search logic here
                    console.log('Searching for teacher:', searchTerm);
                    // You would typically submit a form or make an AJAX request
                }
            }
        });
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const filterValue = this.value;
            // Implement filter logic here
            console.log('Filtering by status:', filterValue);
        });
    }
    
    // Bulk actions
    document.querySelectorAll('.bulk-action').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            Swal.fire({
                title: 'تنفيذ إجراء جماعي',
                text: `سيتم ${action === 'activate' ? 'تفعيل' : 'تعطيل'} جميع المعلمين`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'متابعة',
                cancelButtonText: 'إلغاء'
            });
        });
    });
});
</script>

<style>
/* Custom styles for teachers page */
.stats-card {
    @apply p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] hover:border-[var(--gold)] transition-all duration-200;
}

.stats-card:hover {
    @apply transform -translate-y-1 shadow-lg;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: var(--surface);
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: var(--gold);
}

/* Responsive table */
@media (max-width: 768px) {
    table {
        display: block;
    }
    
    thead {
        display: none;
    }
    
    tbody tr {
        @apply block mb-4 p-4 rounded-lg border border-[var(--border)] bg-[var(--surface)];
    }
    
    tbody td {
        @apply block pb-2 border-b border-[var(--border)] last:border-b-0 last:pb-0;
    }
    
    tbody td:before {
        content: attr(data-label);
        @apply block text-xs font-medium text-[var(--slate-blue)] mb-1;
    }
    
    .group-hover\:opacity-100 {
        opacity: 1 !important;
    }
}
</style>
@endsection