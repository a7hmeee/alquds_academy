@extends('layouts.app')
@section('title', 'الحلقات الدراسية')

@section('header', 'إدارة الحلقات الدراسية')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">إدارة الحلقات الدراسية</h1>
            <p class="text-[var(--slate-blue)]">إدارة الحلقات الدراسية والمجموعات التعليمية</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <!-- Search Box -->
            <div class="relative flex-1 w-full sm:w-auto">
                <input type="text" 
                       placeholder="بحث عن حلقة..." 
                       class="pr-10 pl-4 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-lg text-[var(--cream)] w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[var(--slate-blue)]">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <!-- Add Circle Button -->
            <a href="{{ route('circles.create') }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                إضافة حلقة جديدة
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
                    <i class="fas fa-circle text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">إجمالي الحلقات</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $circles->total() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جميع الحلقات</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-user-check text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">نشطة</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $circles->where('status', 'active')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">حلقات نشطة</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-laptop text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">أونلاين</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $circles->where('type', 'online')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">حلقات عن بُعد</div>
        </div>
        
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-users text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">حضوري</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $circles->where('type', 'onsite')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">حلقات مباشرة</div>
        </div>
    </div>

    <!-- Circles Table -->
    <div class="main-content-section">
        <!-- Table Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-[var(--gold)] mb-2 md:mb-0">قائمة الحلقات</h2>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter -->
                <div class="flex flex-wrap gap-2">
                    <div class="relative flex-1 min-w-0 sm:flex-none">
                        <select id="statusFilter" class="px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشطة</option>
                            <option value="paused">موقوفة</option>
                            <option value="archived">مؤرشفة</option>
                        </select>
                    </div>
                    
                    <div class="relative flex-1 min-w-0 sm:flex-none">
                        <select id="typeFilter" class="px-4 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                            <option value="">جميع الأنواع</option>
                            <option value="onsite">حضوري</option>
                            <option value="online">أونلاين</option>
                            <option value="hybrid">هجين</option>
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
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحلقة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التفاصيل</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">النوع</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">السعة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الحالة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($circles as $circle)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150 group" 
                            data-status="{{ $circle->status }}"
                            data-type="{{ $circle->type }}">
                            <!-- Circle Column -->
                            <td class="py-4 px-6" data-label="الحلقة">
                                <div class="flex items-center gap-3">
                                    <!-- Circle Icon -->
                                    <div class="relative">
                                        @php
                                            $typeIcons = [
                                                'onsite' => ['icon' => 'fa-users', 'color' => 'from-blue-900/20 to-blue-900/10', 'textColor' => 'text-blue-300'],
                                                'online' => ['icon' => 'fa-laptop', 'color' => 'from-purple-900/20 to-purple-900/10', 'textColor' => 'text-purple-300'],
                                                'hybrid' => ['icon' => 'fa-blender-phone', 'color' => 'from-green-900/20 to-green-900/10', 'textColor' => 'text-green-300']
                                            ];
                                            $type = $typeIcons[$circle->type] ?? $typeIcons['onsite'];
                                        @endphp
                                        
                                        <div class="w-12 h-12 rounded-lg {{ $type['color'] }} border border-[var(--border)] flex items-center justify-center">
                                            <i class="fas {{ $type['icon'] }} text-lg {{ $type['textColor'] }}"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Circle Info -->
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $circle->name }}</div>
                                        @if($circle->organization)
                                            <div class="text-xs text-[var(--slate-blue)] mt-1 flex items-center gap-1">
                                                <i class="fas fa-building"></i>
                                                {{ $circle->organization->name }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-[var(--slate-blue)] mt-1">
                                            <i class="fas fa-calendar-alt ml-1"></i>
                                            أنشئت: {{ $circle->created_at->format('Y/m/d') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Details Column -->
                            <td class="py-4 px-6" data-label="التفاصيل">
                                <div class="space-y-1">
                                    @if($circle->level)
                                        <div class="text-sm text-[var(--cream)] flex items-center gap-1">
                                            <i class="fas fa-chart-line text-[var(--slate-blue)]"></i>
                                            {{ $circle->level }}
                                        </div>
                                    @endif
                                    
                                    @if($circle->description)
                                        <div class="text-sm text-[var(--slate-blue)] truncate max-w-xs">
                                            {{ Str::limit($circle->description, 50) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Type Column -->
                            <td class="py-4 px-6" data-label="النوع">
                                @php
                                    $typeLabels = [
                                        'onsite' => ['label' => 'حضوري', 'color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300'],
                                        'online' => ['label' => 'أونلاين', 'color' => 'from-purple-900/20 to-purple-900/10', 'text' => 'text-purple-300'],
                                        'hybrid' => ['label' => 'هجين', 'color' => 'from-green-900/20 to-green-900/10', 'text' => 'text-green-300']
                                    ];
                                    $typeLabel = $typeLabels[$circle->type] ?? $typeLabels['onsite'];
                                @endphp
                                
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $typeLabel['color'] }} border border-white/10 {{ $typeLabel['text'] }}">
                                    {{ $typeLabel['label'] }}
                                </span>
                            </td>
                            
                            <!-- Capacity Column -->
                            <td class="py-4 px-6" data-label="السعة">
                                <div class="space-y-2">
                                    @if($circle->capacity)
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-[var(--border)] rounded-full h-2 overflow-hidden">
                                                @php
                                                    $percentage = $circle->students_count ? min(100, ($circle->students_count / $circle->capacity) * 100) : 0;
                                                @endphp
                                                <div class="h-full bg-gradient-to-r from-[var(--gold)] to-[#D4B85C]" 
                                                     style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm text-[var(--cream)]">
                                                {{ $circle->students_count ?? 0 }}/{{ $circle->capacity }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-[var(--slate-blue)]">
                                            {{ number_format($percentage, 0) }}% من السعة
                                        </div>
                                    @else
                                        <span class="text-[var(--slate-blue)]">غير محددة</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="py-4 px-6" data-label="الحالة">
                                @php
                                    $statusData = [
                                        'active' => ['color' => 'from-green-900/20 to-emerald-900/10', 'text' => 'text-green-300', 'label' => 'نشطة', 'icon' => 'fa-check-circle'],
                                        'paused' => ['color' => 'from-yellow-900/20 to-yellow-900/10', 'text' => 'text-yellow-300', 'label' => 'موقوفة', 'icon' => 'fa-pause-circle'],
                                        'archived' => ['color' => 'from-gray-900/20 to-gray-900/10', 'text' => 'text-gray-300', 'label' => 'مؤرشفة', 'icon' => 'fa-archive']
                                    ];
                                    $status = $statusData[$circle->status] ?? $statusData['active'];
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
                                    <a href="{{ route('circles.show', $circle) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">عرض</span>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('circles.edit', $circle) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">تعديل</span>
                                    </a>
                                    
                                    <!-- Archive Button -->
                                    @if($circle->status !== 'archived')
                                    <form action="{{ route('circles.destroy', $circle) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirmArchive('{{ $circle->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-yellow-900/20 to-yellow-900/10 text-yellow-300 border border-yellow-500/20 hover:border-yellow-500/40 transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-archive"></i>
                                            <span class="hidden sm:inline">أرشفة</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-circle text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد حلقات حالياً</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">ابدأ بإضافة حلقة جديدة لتظهر هنا</p>
                                    <a href="{{ route('circles.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus-circle"></i>
                                        إضافة أول حلقة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary -->
        @if($circles->hasPages() || $circles->count() > 0)
            <div class="mt-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Summary -->
                    <div class="text-sm text-[var(--slate-blue)]">
                        <i class="fas fa-info-circle ml-1"></i>
                        عرض {{ $circles->firstItem() ?? 0 }} - {{ $circles->lastItem() ?? 0 }} من {{ $circles->total() }} حلقة
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex items-center gap-1">
                        @if($circles->hasPages())
                            {{ $circles->onEachSide(1)->links('vendor.pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        @endif
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
                    <span>تفعيل جميع الحلقات</span>
                    <i class="fas fa-toggle-on text-green-400"></i>
                </button>
                <button class="w-full px-4 py-2.5 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 transition-all duration-200 text-right flex items-center justify-between">
                    <span>تعطيل جميع الحلقات</span>
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
                    <span class="text-sm text-[var(--slate-blue)]">متوسط السعة:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ number_format($circles->avg('capacity') ?? 0, 1) }} طالب
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">نسبة الحضوري:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $circles->count() > 0 ? round(($circles->where('type', 'onsite')->count() / $circles->count()) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--slate-blue)]">الحلقات المؤرشفة:</span>
                    <span class="text-sm font-medium text-[var(--gold)]">
                        {{ $circles->where('status', 'archived')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmArchive(circleName) {
    return Swal.fire({
        title: 'تأكيد الأرشفة',
        html: `سيتم أرشفة الحلقة: <strong>${circleName}</strong><br><br>
              <small class="text-gray-400">يمكن استعادة الحلقات المؤرشفة لاحقاً</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، أرشفة',
        cancelButtonText: 'إلغاء',
        reverseButtons: true,
        customClass: {
            confirmButton: 'bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg',
            cancelButton: 'bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg'
        }
    }).then((result) => {
        return result.isConfirmed;
    });
}

// Initialize filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const searchInput = document.querySelector('input[type="text"]');
    const tableRows = document.querySelectorAll('tbody tr');
    
    // Filter function
    function filterTable() {
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        
        tableRows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowType = row.dataset.type;
            const rowText = row.textContent.toLowerCase();
            
            const statusMatch = !statusValue || rowStatus === statusValue;
            const typeMatch = !typeValue || rowType === typeValue;
            const searchMatch = !searchValue || rowText.includes(searchValue);
            
            if (statusMatch && typeMatch && searchMatch) {
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
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterTable);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
    
    // Bulk actions
    document.querySelectorAll('.bulk-action').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            Swal.fire({
                title: 'تنفيذ إجراء جماعي',
                text: `سيتم ${action === 'activate' ? 'تفعيل' : 'تعطيل'} جميع الحلقات`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'متابعة',
                cancelButtonText: 'إلغاء'
            });
        });
    });
    
    // Export functionality
    const exportButton = document.querySelector('button:contains("تصدير")');
    if (exportButton) {
        exportButton.addEventListener('click', function() {
            Swal.fire({
                title: 'تصدير البيانات',
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
                                    <span class="text-sm">الحقول الأساسية</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="rounded">
                                    <span class="text-sm">الإحصائيات</span>
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
});
</script>

<style>
/* Custom styles for circles page */
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

/* Progress bar styles */
.w-16.bg-\[\#8AA6B3\]\/10 {
    background: rgba(138, 166, 179, 0.1);
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

/* Animation for filters */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

tbody tr {
    animation: fadeIn 0.3s ease;
}
</style>
@endsection