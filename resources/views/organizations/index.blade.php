@extends('layouts.app')
@section('title', 'الجهات')

@section('header', 'إدارة الجهات (مساجد - مدارس - جامعات)')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)] mb-2">الجهات التعليمية والدينية</h1>
            <p class="text-[var(--slate-blue)]">إدارة المساجد والمدارس والجامعات والجهات المرتبطة بالنظام</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 w-full sm:w-auto">
                <input type="text" 
                       placeholder="بحث عن جهة..." 
                       class="pr-10 pl-4 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-lg text-[var(--cream)] w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-[var(--gold)] focus:border-transparent">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[var(--slate-blue)]">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <a href="{{ route('organizations.create') }}"
               class="px-4 py-2.5 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                إضافة جهة جديدة
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-mosque text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">المساجد</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $organizations->where('type', 'mosque')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جهة دينية</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-school text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">المدارس</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $organizations->where('type', 'school')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جهة تعليمية</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-university text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">الجامعات</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $organizations->where('type', 'university')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جهة أكاديمية</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--deep-green)] to-teal-900">
                    <i class="fas fa-building text-xl text-[var(--gold)]"></i>
                </div>
                <span class="text-sm text-[var(--slate-blue)]">أخرى</span>
            </div>
            <div class="text-2xl font-bold text-[var(--gold)]">
                {{ $organizations->where('type', 'other')->count() }}
            </div>
            <div class="mt-2 text-xs text-[var(--slate-blue)]">جهات متنوعة</div>
        </div>
    </div>

    <!-- Organizations Table -->
    <div class="main-content-section">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[var(--gold)] mb-2 md:mb-0">قائمة الجهات</h2>
            <div class="flex items-center gap-2 text-sm text-[var(--slate-blue)]">
                <span>إجمالي الجهات: {{ $organizations->total() }}</span>
                <span class="text-[var(--border)]">•</span>
                <span>عرض {{ $organizations->firstItem() ?? 0 }} - {{ $organizations->lastItem() ?? 0 }} من {{ $organizations->total() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 border-b border-[var(--border)]">
                    <tr>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">#</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">اسم الجهة</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">النوع</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">التاريخ</th>
                        <th class="py-4 px-6 text-right text-[var(--gold)] font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($organizations as $org)
                        <tr class="hover:bg-[var(--deep-green)]/5 transition-colors duration-150">
                            <td class="py-4 px-6 text-[var(--cream)] font-medium">
                                {{ $org->id }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--deep-green)] to-teal-900 flex items-center justify-center text-[var(--gold)]">
                                        @php
                                            $icons = [
                                                'mosque' => 'fa-mosque',
                                                'school' => 'fa-school',
                                                'university' => 'fa-university',
                                                'other' => 'fa-building'
                                            ];
                                        @endphp
                                        <i class="fas {{ $icons[$org->type] ?? 'fa-building' }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[var(--cream)]">{{ $org->name }}</div>
                                        @if($org->address)
                                            <div class="text-xs text-[var(--slate-blue)] mt-1">
                                                <i class="fas fa-map-marker-alt ml-1"></i>
                                                {{ Str::limit($org->address, 40) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $typeLabels = [
                                        'mosque' => ['label' => 'مسجد', 'color' => 'from-blue-900/20 to-blue-900/10', 'text' => 'text-blue-300'],
                                        'school' => ['label' => 'مدرسة', 'color' => 'from-emerald-900/20 to-emerald-900/10', 'text' => 'text-emerald-300'],
                                        'university' => ['label' => 'جامعة', 'color' => 'from-purple-900/20 to-purple-900/10', 'text' => 'text-purple-300'],
                                        'other' => ['label' => 'أخرى', 'color' => 'from-gray-900/20 to-gray-900/10', 'text' => 'text-gray-300'],
                                    ];
                                    $type = $typeLabels[$org->type] ?? $typeLabels['other'];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r {{ $type['color'] }} border border-white/10 {{ $type['text'] }}">
                                    {{ $type['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[var(--slate-blue)]">
                                {{ $org->created_at->format('Y/m/d') }}
                            </td>
                          
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                   
                                    
                                    <a href="{{ route('organizations.edit', $org) }}"
                                       class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 transition-all duration-200 flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </a>

                                    <form action="{{ route('organizations.destroy', $org) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirmDeletion(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20 hover:border-red-500/40 transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-trash-alt"></i>
                                            <span>حذف</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--deep-green)]/10 to-[var(--deep-green)]/5 border border-[var(--border)] flex items-center justify-center">
                                        <i class="fas fa-building text-3xl text-[var(--slate-blue)]"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-[var(--cream)] mb-2">لا توجد جهات مسجلة</h3>
                                    <p class="text-[var(--slate-blue)] mb-6">ابدأ بإضافة جهة جديدة لتظهر هنا</p>
                                    <a href="{{ route('organizations.create') }}"
                                       class="px-6 py-3 rounded-lg bg-gradient-to-l from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] font-bold hover:opacity-90 transition-all duration-200 inline-flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        إضافة أول جهة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($organizations->hasPages())
            <div class="mt-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-sm text-[var(--slate-blue)]">
                        عرض {{ $organizations->firstItem() ?? 0 }} - {{ $organizations->lastItem() ?? 0 }} من {{ $organizations->total() }} جهة
                    </div>
                    
                    <div class="flex items-center gap-1">
                        {{ $organizations->onEachSide(1)->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-download text-[var(--gold)]"></i>
                تصدير البيانات
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-4">تصدير قائمة الجهات بصيغة Excel أو PDF</p>
            <div class="flex gap-2">
                <button class="px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900/20 to-blue-900/10 text-blue-300 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 flex-1 text-center">
                    <i class="fas fa-file-excel ml-1"></i>
                    Excel
                </button>
                <button class="px-3 py-2 rounded-lg bg-gradient-to-r from-red-900/20 to-rose-900/10 text-red-300 border border-red-500/20 hover:border-red-500/40 transition-all duration-200 flex-1 text-center">
                    <i class="fas fa-file-pdf ml-1"></i>
                    PDF
                </button>
            </div>
        </div>

        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-filter text-[var(--gold)]"></i>
                تصفية سريعة
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-4">تصفية الجهات حسب النوع أو الحالة</p>
            <div class="flex gap-2">
                <select class="px-3 py-2 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)] flex-1 focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    <option value="">جميع الأنواع</option>
                    <option value="mosque">مساجد</option>
                    <option value="school">مدارس</option>
                    <option value="university">جامعات</option>
                    <option value="other">أخرى</option>
                </select>
                <button class="px-4 py-2 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50">
                    تطبيق
                </button>
            </div>
        </div>

        <div class="p-5 rounded-xl border border-[var(--border)] bg-gradient-to-br from-[var(--deep-green)]/10 to-transparent">
            <h3 class="text-lg font-bold text-[var(--cream)] mb-3 flex items-center gap-2">
                <i class="fas fa-chart-bar text-[var(--gold)]"></i>
                إحصائيات سريعة
            </h3>
            <p class="text-sm text-[var(--slate-blue)] mb-2">النشطة: {{ $organizations->where('is_active', true)->count() }}</p>
            <p class="text-sm text-[var(--slate-blue)] mb-4">غير النشطة: {{ $organizations->where('is_active', false)->count() }}</p>
            <button class="px-4 py-2 rounded-lg bg-gradient-to-r from-[var(--deep-green)]/20 to-[var(--deep-green)]/10 text-[var(--gold)] border border-[var(--border)] hover:border-[var(--gold)]/50 w-full">
                <i class="fas fa-chart-line ml-1"></i>
                عرض تقرير مفصل
            </button>
        </div>
    </div>
</div>

<script>
function confirmDeletion(event) {
    event.preventDefault();
    
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لن تتمكن من استعادة هذه الجهة بعد الحذف!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذفها',
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

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                // Implement search logic here
                console.log('Searching for:', this.value);
            }
        });
    }
    
    // Filter functionality
    const filterSelect = document.querySelector('select');
    const filterButton = document.querySelector('button:contains("تطبيق")');
    if (filterSelect && filterButton) {
        filterButton.addEventListener('click', function() {
            const filterValue = filterSelect.value;
            // Implement filter logic here
            console.log('Filtering by:', filterValue);
        });
    }
});
</script>

<style>
/* Custom styles for this page */
.stats-card {
    @apply p-4 rounded-xl border border-[var(--border)] bg-[var(--surface)] hover:border-[var(--gold)] transition-all duration-200;
}

.stats-card:hover {
    @apply transform -translate-y-1 shadow-lg;
}

/* Custom pagination styles */
.pagination {
    @apply flex items-center gap-1;
}

.pagination .page-item {
    @apply mx-1;
}

.pagination .page-link {
    @apply px-3 py-2 rounded-lg border border-[var(--border)] bg-[var(--surface)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20 hover:border-[var(--gold)] transition-all duration-200;
}

.pagination .page-item.active .page-link {
    @apply bg-gradient-to-r from-[var(--gold)] to-[#D4B85C] text-[var(--dark-bg)] border-[var(--gold)] font-bold;
}

.pagination .page-item.disabled .page-link {
    @apply opacity-50 cursor-not-allowed hover:bg-[var(--surface)] hover:border-[var(--border)];
}
</style>
@endsection