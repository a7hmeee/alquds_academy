@extends('layouts.app')
@section('title', 'الطلاب')

@section('header', 'إدارة الطلاب')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إدارة الطلاب</h1>
            <p class="text-[var(--slate-blue)]">إدارة وتتبع الطلاب في النظام التعليمي</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <!-- Search Box -->
            <div class="relative flex-1 w-full sm:w-auto">
                <input type="text" 
                       placeholder="بحث عن طالب..." 
                       class="pr-10 pl-4 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-lg text-[var(--cream)] w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[var(--slate-blue)]">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <!-- Add Student Button -->
            <a href="{{ route('students.create') }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                إضافة طالب جديد
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
                <span class="text-sm text-[var(--slate-blue)]">إجمالي الطلاب</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جميع الطلاب</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-check text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">نشطين</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $students->where('status', 'active')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">طلاب نشطون</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-graduation-cap text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">حفظة</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $students->where('memorization_level', 'hafiz')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">حفظة القرآن</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-clock text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">جدد</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $students->where('created_at', '>=', now()->subDays(30))->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">مسجلين هذا الشهر</div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="main-content-section">
        <!-- Table Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-[var(--gold)] mb-2 md:mb-0">قائمة الطلاب</h2>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter -->
                <div class="flex flex-wrap gap-2">
                    <div class="relative flex-1 min-w-0 sm:flex-none">
                        <select id="statusFilter" class="px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                            <option value="transferred">منقول</option>
                            <option value="graduated">متخرج</option>
                        </select>
                    </div>
                    
                    <div class="relative flex-1 min-w-0 sm:flex-none">
                        <select id="levelFilter" class="px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">جميع المستويات</option>
                            <option value="beginner">مبتدئ</option>
                            <option value="intermediate">متوسط</option>
                            <option value="advanced">متقدم</option>
                            <option value="hafiz">حافظ</option>
                        </select>
                    </div>
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
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الطالب</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">المعلومات</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">المعلم</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($students as $student)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group" 
                            data-status="{{ $student->status }}"
                            data-level="{{ $student->memorization_level ?? 'beginner' }}">
                            <!-- Student Column -->
                            <td class="py-4 px-6" data-label="الطالب">
                                <div class="flex items-center gap-3">
                                    <!-- Profile Image -->
                                    <div class="relative">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/'.$student->photo) }}"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-[var(--border)]">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[var(--deep-green)] to-teal-900 border-2 border-[var(--border)] flex items-center justify-center">
                                                <i class="fas fa-user text-lg text-[var(--gold)]"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Level Badge -->
                                        @php
                                            $levelBadges = [
                                                'beginner' => ['color' => 'from-blue-400 to-blue-600', 'label' => 'مبتدئ'],
                                                'intermediate' => ['color' => 'from-green-400 to-green-600', 'label' => 'متوسط'],
                                                'advanced' => ['color' => 'from-purple-400 to-purple-600', 'label' => 'متقدم'],
                                                'hafiz' => ['color' => 'from-yellow-400 to-yellow-600', 'label' => 'حافظ']
                                            ];
                                            $level = $levelBadges[$student->memorization_level ?? 'beginner'] ?? $levelBadges['beginner'];
                                        @endphp
                                        
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-gradient-to-br {{ $level['color'] }} border border-[var(--surface)] flex items-center justify-center">
                                            <i class="fas fa-star text-xs text-white"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Student Info -->
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $student->full_name }}</div>
                                        <div class="text-xs text-[var(--slate-blue)] mt-1">
                                            <i class="fas fa-id-card ml-1"></i>
                                            ID: {{ $student->id }}
                                        </div>
                                        <div class="text-xs text-[var(--slate-blue)] mt-1">
                                            <i class="fas fa-calendar-alt ml-1"></i>
                                            انضم: {{ $student->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Information Column -->
                            <td class="py-4 px-6" data-label="المعلومات">
                                <div class="space-y-1">
                                    @if($student->age)
                                        <div class="text-sm text-[var(--cream)]">
                                            <i class="fas fa-birthday-cake ml-1 text-[var(--slate-blue)]"></i>
                                            {{ $student->age }} سنة
                                        </div>
                                    @endif
                                    
                                    @if($student->phone)
                                        <div class="text-sm text-[var(--slate-blue)]">
                                            <i class="fas fa-phone ml-1"></i>
                                            {{ $student->phone }}
                                        </div>
                                    @endif
                                    
                                    @if($student->email)
                                        <div class="text-sm text-[var(--slate-blue)] truncate max-w-xs">
                                            <i class="fas fa-envelope ml-1"></i>
                                            {{ $student->email }}
                                        </div>
                                    @endif
                                    
                                    @if($student->memorization_level)
                                        <div class="text-sm text-[var(--slate-blue)]">
                                            <i class="fas fa-book-quran ml-1"></i>
                                            {{ $level['label'] }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Teacher Column -->
                            <td class="py-4 px-6" data-label="المعلم">
                                @if($student->teacher)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border border-[var(--border)] flex items-center justify-center">
                                            <i class="fas fa-chalkboard-teacher text-xs text-[var(--slate-blue)]"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-[var(--cream)]">{{ $student->teacher->full_name }}</div>
                                            <div class="text-xs text-[var(--slate-blue)]">
                                                @if($student->teacher->academic_degree)
                                                    {{ [
                                                        'hafiz' => 'حافظ',
                                                        'ijazah' => 'إجازة',
                                                        'bachelor' => 'بكالوريوس',
                                                        'master' => 'ماجستير',
                                                        'doctorate' => 'دكتوراه'
                                                    ][$student->teacher->academic_degree] ?? $student->teacher->academic_degree }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[var(--slate-blue)] italic">غير معين</span>
                                @endif
                            </td>
                            
                            <!-- Status Column -->
                            <td class="py-4 px-6" data-label="الحالة">
                                @php
                                    $statusData = [
                                        'active' => ['color' => 'from-green-900/20 to-emerald-900/10', 'text' => 'text-green-300', 'label' => 'نشط', 'icon' => 'fa-check-circle'],
                                        'inactive' => ['color' => 'from-red-900/20 to-rose-900/10', 'text' => 'text-red-300', 'label' => 'غير نشط', 'icon' => 'fa-times-circle'],
                                        'transferred' => ['color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300', 'label' => 'منقول', 'icon' => 'fa-exchange-alt'],
                                        'graduated' => ['color' => 'from-yellow-900/20 to-yellow-900/10', 'text' => 'text-yellow-300', 'label' => 'متخرج', 'icon' => 'fa-graduation-cap']
                                    ];
                                    $status = $statusData[$student->status] ?? $statusData['active'];
                                @endphp
                                
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $status['color'] }} border border-white/10 {{ $status['text'] }} flex items-center gap-2">
                                    <i class="fas {{ $status['icon'] }}"></i>
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="py-4 px-6" data-label="الإجراءات">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <!-- View Button -->
                                    <a href="{{ route('students.show', $student) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">تعديل</span>
                                    </a>
                                    
                                    <!-- Transfer Button -->
                                    <button onclick="showTransferModal('{{ $student->id }}', '{{ $student->full_name }}')"
                                            class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-purple-900/20 to-purple-900/10 text-purple-300 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-exchange-alt"></i>
                                        <span class="hidden sm:inline">تحويل</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-users text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا يوجد طلاب مسجلون</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">ابدأ بإضافة طالب جديد لتظهر هنا</p>
                                    <a href="{{ route('students.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-user-plus"></i>
                                        إضافة أول طالب
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

   
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
                <button onclick="bulkAction('activate')"
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تفعيل جميع الطلاب</span>
                    <i class="fas fa-toggle-on text-green-400"></i>
                </button>
                <button onclick="bulkAction('inactivate')"
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تعطيل جميع الطلاب</span>
                    <i class="fas fa-toggle-off text-red-400"></i>
                </button>
                <button onclick="bulkAction('assign_teacher')"
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تعيين معلم للجميع</span>
                    <i class="fas fa-chalkboard-teacher text-blue-400"></i>
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
                    <span class="text-sm text-[var(--slate-blue)]">متوسط العمر:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $students->avg('age') ?? 0 }} سنة
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">طلاب بدون معلم:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $students->where('teacher_id', null)->count() }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">نسبة النشطين:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $students->count() > 0 ? round(($students->where('status', 'active')->count() / $students->count()) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Student Modal -->
<div id="transferModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
    <div class="bg-[var(--surface)] rounded-xl border border-[var(--border)] max-w-md w-full">
        <div class="p-6 border-b border-[var(--border)]">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-[var(--cream)]">تحويل طالب</h3>
                <button onclick="closeTransferModal()"
                        class="p-2 rounded-lg hover:bg-[var(--deep-green)]/20 transition-colors duration-200">
                    <i class="fas fa-times text-[var(--slate-blue)]"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <p class="text-[var(--slate-blue)] mb-4" id="transferStudentName"></p>
            
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                        اختر المعلم الجديد
                    </label>
                    <select id="newTeacher" class="w-full px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                        <option value="">اختر معلم...</option>
                        <!-- Teachers will be populated dynamically -->
                    </select>
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-[var(--cream)]">
                        سبب التحويل (اختياري)
                    </label>
                    <textarea id="transferReason" 
                              rows="3"
                              class="w-full px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] resize-none"
                              placeholder="أدخل سبب التحويل..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-[var(--border)] flex justify-end gap-3">
                <button onclick="closeTransferModal()"
                        class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                    إلغاء
                </button>
                <button onclick="processTransfer()"
                        class="px-4 py-2 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold">
                    تأكيد التحويل
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentStudentId = null;

function confirmTransfer(studentName) {
    return Swal.fire({
        title: 'تأكيد التحويل',
        html: `سيتم تحويل الطالب: <strong>${studentName}</strong><br><br>
              <small class="text-gray-400">سيتم إرسال إشعار للمعلم الجديد والطالب</small>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، تحويل',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        return result.isConfirmed;
    });
}

// Initialize filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const levelFilter = document.getElementById('levelFilter');
    const searchInput = document.querySelector('input[type="text"]');
    const tableRows = document.querySelectorAll('tbody tr');
    
    // Filter function
    function filterTable() {
        const statusValue = statusFilter.value;
        const levelValue = levelFilter.value;
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        
        tableRows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowLevel = row.dataset.level;
            const rowText = row.textContent.toLowerCase();
            
            const statusMatch = !statusValue || rowStatus === statusValue;
            const levelMatch = !levelValue || rowLevel === levelValue;
            const searchMatch = !searchValue || rowText.includes(searchValue);
            
            if (statusMatch && levelMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listeners
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTable);
    }
    
    if (levelFilter) {
        levelFilter.addEventListener('change', filterTable);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
});

// Transfer modal functions
function showTransferModal(studentId, studentName) {
    currentStudentId = studentId;
    document.getElementById('transferStudentName').textContent = `تحويل الطالب: ${studentName}`;
    
    // Here you would typically fetch teachers list via AJAX
    // For demo purposes, we'll add some dummy data
    const teacherSelect = document.getElementById('newTeacher');
    teacherSelect.innerHTML = `
        <option value="">اختر معلم...</option>
        <option value="1">أحمد محمد - حافظ</option>
        <option value="2">محمد علي - إجازة</option>
        <option value="3">سالم خالد - بكالوريوس</option>
        <option value="4">خالد سعيد - ماجستير</option>
    `;
    
    document.getElementById('transferModal').classList.remove('hidden');
    document.getElementById('transferModal').classList.add('flex');
}

function closeTransferModal() {
    document.getElementById('transferModal').classList.add('hidden');
    document.getElementById('transferModal').classList.remove('flex');
    currentStudentId = null;
}

function processTransfer() {
    const teacherId = document.getElementById('newTeacher').value;
    const reason = document.getElementById('transferReason').value;
    
    if (!teacherId) {
        Swal.fire({
            title: 'خطأ',
            text: 'الرجاء اختيار معلم',
            icon: 'error',
            confirmButtonText: 'حسنًا'
        });
        return;
    }
    
    // Here you would typically make an AJAX request to transfer the student
    Swal.fire({
        title: 'جاري التحويل',
        text: 'يتم تحويل الطالب إلى المعلم الجديد...',
        icon: 'info',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        Swal.fire({
            title: 'تم التحويل',
            text: 'تم تحويل الطالب بنجاح',
            icon: 'success',
            confirmButtonText: 'حسنًا'
        });
        closeTransferModal();
    });
}

// Bulk actions
function bulkAction(action) {
    let title, text, icon, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'تفعيل جميع الطلاب';
            text = 'سيتم تفعيل جميع الطلاب في القائمة';
            icon = 'info';
            confirmText = 'تفعيل';
            break;
        case 'inactivate':
            title = 'تعطيل جميع الطلاب';
            text = 'سيتم تعطيل جميع الطلاب في القائمة';
            icon = 'warning';
            confirmText = 'تعطيل';
            break;
        case 'assign_teacher':
            title = 'تعيين معلم للجميع';
            text = 'سيتم تعيين معلم موحد لجميع الطلاب';
            icon = 'info';
            confirmText = 'تعيين';
            break;
        default:
            return;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would typically make an AJAX request
            Swal.fire({
                title: 'جاري التنفيذ',
                text: 'يتم تنفيذ الإجراء...',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// Export functionality
const exportButton = document.querySelector('button:contains("تصدير")');
if (exportButton) {
    exportButton.addEventListener('click', function() {
        Swal.fire({
            title: 'تصدير بيانات الطلاب',
            html: `
                <div class="text-right space-y-3">
                    <div>
                        <label class="block text-sm mb-1">نوع الملف:</label>
                        <select id="exportType" class="w-full rounded-lg border border-[var(--border)] bg-[var(--surface)] text-[var(--cream)] p-2">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">تضمين:</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" checked class="rounded">
                                <span class="text-sm">المعلومات الأساسية</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span class="text-sm">معلومات المعلم</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="rounded">
                                <span class="text-sm">مستوى الحفظ</span>
                            </label>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'تصدير',
            cancelButtonText: 'إلغاء',
            preConfirm: () => {
                const type = document.getElementById('exportType').value;
                return { type };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'جاري التصدير',
                    text: 'يتم تجهيز الملف، الرجاء الانتظار...',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
}
</script>

<style>
/* Custom styles for students page */
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

/* Modal animations */
#transferModal {
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

#transferModal > div {
    animation: modalFadeIn 0.3s ease;
}

/* Level badge animations */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.w-5.h-5.rounded-full {
    animation: pulse 2s infinite;
}
</style>
@endsection