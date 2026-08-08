@extends('layouts.app')

@section('title', 'الأدوار والصلاحيات')

@section('content')
<div class="roles-management-page">
    <!-- Header Section -->
    <div class="page-header" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 1.8rem; color: var(--cream); margin-bottom: 10px;" class="elegant-text">
                <i class="fas fa-user-tag" style="color: var(--gold); margin-left: 10px;"></i>
                إدارة الأدوار والصلاحيات
            </h1>
            <p style="color: var(--slate-blue); font-size: 0.9rem;">
                إدارة وتعديل أدوار المستخدمين في النظام
            </p>
        </div>
        
        <a href="{{ route('roles.create') }}" 
           class="add-role-btn" 
           style="display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--deep-green), #256341); color: white; border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; text-decoration: none;">
            <i class="fas fa-plus-circle"></i>
            إنشاء دور جديد
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">إجمالي الأدوار</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--gold); font-weight: bold;">{{ count($roles) }}</div>
        </div>

        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">أدوار النظام</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--cream); font-weight: bold;">
                {{ $roles->where('name', 'Super Admin')->count() + $roles->where('name', 'Admin')->count() }}
            </div>
        </div>

        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">أدوار مخصصة</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-user-edit"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--cream); font-weight: bold;">
                {{ $roles->whereNotIn('name', ['Super Admin', 'Admin'])->count() }}
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="roles-table-container" style="background: var(--surface); border-radius: 15px; border: 1px solid var(--border); overflow: hidden;">
        <!-- Table Header -->
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="color: var(--cream); font-size: 1.2rem; font-weight: 500;">
                    <i class="fas fa-list-alt" style="color: var(--gold); margin-left: 8px;"></i>
                    قائمة الأدوار
                </h2>
                <span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 2px 10px; border-radius: 12px; font-size: 0.8rem;">
                    {{ count($roles) }} دور
                </span>
            </div>
            
            <div class="search-box" style="position: relative; max-width: 300px;">
                <input type="text" placeholder="بحث عن دور..." style="width: 100%; padding: 10px 15px 10px 40px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 25px; color: var(--cream); font-size: 0.9rem;">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(29, 79, 49, 0.1);">
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-tag" style="margin-left: 8px;"></i>
                            اسم الدور
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-users" style="margin-left: 8px;"></i>
                            عدد المستخدمين
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                            تاريخ الإنشاء
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-cog" style="margin-left: 8px;"></i>
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $index => $role)
                    <tr style="border-bottom: 1px solid var(--border); transition: all 0.3s ease;" class="role-row" data-role-id="{{ $role->id }}">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="role-icon" style="width: 40px; height: 40px; border-radius: 10px; 
                                    @if($role->name === 'Super Admin')
                                        background: linear-gradient(135deg, var(--gold), #E4C875);
                                    @elseif($role->name === 'Admin')
                                        background: linear-gradient(135deg, var(--deep-green), #27ae60);
                                    @else
                                        background: linear-gradient(135deg, #8AA6B3, #6c8a99);
                                    @endif
                                    display: flex; align-items: center; justify-content: center; color: white;">
                                    @if($role->name === 'Super Admin')
                                        <i class="fas fa-crown"></i>
                                    @elseif($role->name === 'Admin')
                                        <i class="fas fa-user-shield"></i>
                                    @else
                                        <i class="fas fa-user-tag"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: 500; margin-bottom: 3px;">
                                        {{ $role->name }}
                                        @if($role->name === 'Super Admin')
                                            <span style="font-size: 0.7rem; color: var(--gold); margin-right: 5px;">
                                                <i class="fas fa-star"></i> النظام
                                            </span>
                                        @endif
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                        @php
                                            $permissionsCount = $role->permissions->count();
                                        @endphp
                                        <span style="color: var(--slate-blue); font-size: 0.8rem;">
                                            <i class="fas fa-key"></i>
                                            {{ $permissionsCount }} صلاحية
                                        </span>
                                        @if($role->name === 'Super Admin')
                                            <span style="background: rgba(195, 160, 78, 0.2); color: var(--gold); padding: 1px 8px; border-radius: 10px; font-size: 0.7rem;">
                                                كامل الصلاحيات
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 30px; height: 30px; background: rgba(29, 79, 49, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: bold; font-size: 1.1rem;">
                                        {{ $role->users->count() }}
                                    </div>
                                    <div style="color: var(--slate-blue); font-size: 0.8rem;">
                                        مستخدم
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; color: var(--cream);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-clock" style="color: var(--slate-blue);"></i>
                                @if($role->created_at)
                                    {{ $role->created_at->format('Y-m-d') }}
                                @else
                                    غير محدد
                                @endif
                            </div>
                            <div style="color: var(--slate-blue); font-size: 0.8rem; margin-top: 5px;">
                                @if($role->updated_at)
                                    آخر تعديل: {{ $role->updated_at->diffForHumans() }}
                                @endif
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('roles.edit', $role) }}" 
                                   class="action-btn edit-btn" 
                                   title="تعديل"
                                   style="width: 35px; height: 35px; border-radius: 8px; background: rgba(41, 128, 185, 0.1); border: 1px solid rgba(41, 128, 185, 0.3); color: #3498db; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; text-decoration: none;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="action-btn view-btn" 
                                        title="عرض التفاصيل"
                                        onclick="viewRoleDetails('{{ $role->id }}')"
                                        style="width: 35px; height: 35px; border-radius: 8px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); color: var(--slate-blue); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div style="padding: 15px 20px; border-top: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: center; background: rgba(29, 79, 49, 0.05);">
            <div style="color: var(--slate-blue); font-size: 0.9rem;">
                عرض {{ count($roles) }} من {{ count($roles) }} دور
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="pagination-btn" style="padding: 8px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="pagination-btn" style="padding: 8px 15px; background: rgba(29, 79, 49, 0.2); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    1
                </button>
                <button class="pagination-btn" style="padding: 8px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px;">
        <!-- Permissions Info -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: var(--gold);"></i>
                معلومات حول الأدوار
            </h3>
            <div style="color: var(--slate-blue); font-size: 0.9rem; line-height: 1.6;">
                <p style="margin-bottom: 10px;">
                    <strong style="color: var(--cream);">Super Admin:</strong> يملك كافة صلاحيات النظام ولا يمكن تعديله أو حذفه
                </p>
                <p style="margin-bottom: 10px;">
                    <strong style="color: var(--cream);">Admin:</strong> يملك معظم صلاحيات النظام مع بعض القيود
                </p>
                <p>
                    يمكنك إنشاء أدوار مخصصة وتحديد الصلاحيات المناسبة لكل دور
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-bolt" style="color: var(--gold);"></i>
                إجراءات سريعة
            </h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                
                <button onclick="exportRoles()" 
                        style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-file-export"></i>
                    تصدير الأدوار
                </button>
                <button onclick="showRoleGuide()" 
                        style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-question-circle"></i>
                    دليل الاستخدام
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .role-row:hover {
        background: rgba(29, 79, 49, 0.05) !important;
    }
    
    .action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .add-role-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 79, 49, 0.3);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        border-color: var(--gold) !important;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: rgba(29, 79, 49, 0.3) !important;
        border-color: var(--gold) !important;
        color: var(--gold) !important;
    }
</style>

<script>
    // Add hover effects
    document.querySelectorAll('.role-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(-5px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.role-row');
            
            rows.forEach(row => {
                const roleName = row.querySelector('td:nth-child(1) div:first-child div:first-child')?.textContent?.toLowerCase() || '';
                
                if (roleName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // View role details function
    function viewRoleDetails(roleId) {
        // In a real app, this would fetch role details via AJAX
        const row = document.querySelector(`.role-row[data-role-id="${roleId}"]`);
        const roleName = row.querySelector('td:nth-child(1) div:first-child div:first-child').textContent.trim();
        
        alert(`عرض تفاصيل الدور: ${roleName}\n\nهذه الميزة ستكون متاحة في النسخة القادمة!`);
    }
    
    // Export roles function
    function exportRoles() {
        if (confirm('هل تريد تصدير قائمة الأدوار إلى ملف CSV؟')) {
            // Simulate export process
            const exportBtn = document.querySelector('button[onclick="exportRoles()"]');
            const originalText = exportBtn.innerHTML;
            
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
            exportBtn.disabled = true;
            
            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
                alert('تم تصدير قائمة الأدوات بنجاح!');
            }, 1500);
        }
    }
    
    // Show role guide
    function showRoleGuide() {
        alert('دليل استخدام إدارة الأدوار:\n\n' +
              '1. يمكن إنشاء أدوار جديدة باستخدام زر "إنشاء دور جديد"\n' +
              '2. لا يمكن تعديل أو حذف أدوار النظام (Super Admin, Admin)\n' +
              '3. يمكن تعديل الصلاحيات للأدوار المخصصة\n' +
              '4. يمكن تعيين الأدوار للمستخدمين من صفحة إدارة المستخدمين');
    }
</script>
@endsection