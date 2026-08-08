@extends('layouts.app')

@section('title', 'المستخدمون')

@section('content')
<div class="user-management-page">
    <!-- Header Section -->
    <div class="page-header" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 1.8rem; color: var(--cream); margin-bottom: 10px;" class="elegant-text">
                <i class="fas fa-users" style="color: var(--gold); margin-left: 10px;"></i>
                إدارة المستخدمين
            </h1>
            <p style="color: var(--slate-blue); font-size: 0.9rem;">
                إدارة صلاحيات وأدوار المستخدمين في النظام
            </p>
        </div>
        
        <button class="add-user-btn" style="display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--deep-green), #256341); color: white; border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease;">
            <i class="fas fa-user-plus"></i>
            إضافة مستخدم جديد
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">إجمالي المستخدمين</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--gold); font-weight: bold;">{{ count($users) }}</div>
        </div>

        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">المسؤولون</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--cream); font-weight: bold;">
                {{ $users->filter(function($user) { return $user->hasRole('Super Admin') || $user->hasRole('Admin'); })->count() }}
            </div>
        </div>

        <div class="stats-card" style="background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(195, 160, 78, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <h3 style="color: var(--slate-blue); font-size: 0.9rem;">المستخدمون النشطون</h3>
                <div style="width: 40px; height: 40px; background: rgba(29, 79, 49, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div style="font-size: 2rem; color: var(--cream); font-weight: bold;">{{ count($users) }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container" style="background: var(--surface); border-radius: 15px; border: 1px solid var(--border); overflow: hidden;">
        <!-- Table Header -->
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="color: var(--cream); font-size: 1.2rem; font-weight: 500;">
                    <i class="fas fa-list" style="color: var(--gold); margin-left: 8px;"></i>
                    قائمة المستخدمين
                </h2>
                <span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 2px 10px; border-radius: 12px; font-size: 0.8rem;">
                    {{ count($users) }} مستخدم
                </span>
            </div>
            
            <div class="search-box" style="position: relative; max-width: 300px;">
                <input type="text" placeholder="بحث عن مستخدم..." style="width: 100%; padding: 10px 15px 10px 40px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 25px; color: var(--cream); font-size: 0.9rem;">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(29, 79, 49, 0.1);">
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-user" style="margin-left: 8px;"></i>
                            المستخدم
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-envelope" style="margin-left: 8px;"></i>
                            البريد الإلكتروني
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-user-tag" style="margin-left: 8px;"></i>
                            الدور
                        </th>
                        <th style="padding: 15px; color: var(--slate-blue); text-align: right; font-weight: 500; border-bottom: 1px solid var(--border);">
                            <i class="fas fa-cog" style="margin-left: 8px;"></i>
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr style="border-bottom: 1px solid var(--border); transition: all 0.3s ease;" class="user-row" data-user-id="{{ $user->id }}">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--deep-green), #256341); display: flex; align-items: center; justify-content: center; color: var(--gold); font-weight: bold;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: 500; margin-bottom: 3px;">{{ $user->name }}</div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <span style="color: var(--slate-blue); font-size: 0.8rem;">
                                            ID: {{ $user->id }}
                                        </span>
                                        @if($user->email_verified_at)
                                        <span style="color: #27ae60; font-size: 0.7rem;">
                                            <i class="fas fa-check-circle"></i>
                                            مفعل
                                        </span>
                                        @else
                                        <span style="color: #e74c3c; font-size: 0.7rem;">
                                            <i class="fas fa-exclamation-circle"></i>
                                            غير مفعل
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; color: var(--cream);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-envelope" style="color: var(--slate-blue);"></i>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <form method="POST" action="{{ route('users.changeRole', $user) }}" class="role-form">
                                @csrf
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <select name="role" 
                                            onchange="this.form.submit()"
                                            style="padding: 8px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); min-width: 150px; cursor: pointer; transition: all 0.3s ease;"
                                            onmouseover="this.style.borderColor='var(--gold)';"
                                            onmouseout="this.style.borderColor='var(--border)';">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}
                                                style="background: var(--surface); color: var(--cream);">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="role-badge" style="padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; 
                                        @if($user->hasRole('Super Admin'))
                                            background: rgba(195, 160, 78, 0.2); color: var(--gold);
                                        @elseif($user->hasRole('Admin'))
                                            background: rgba(29, 79, 49, 0.2); color: #27ae60;
                                        @else
                                            background: rgba(138, 166, 179, 0.2); color: var(--slate-blue);
                                        @endif">
                                        @if($user->hasRole('Super Admin'))
                                            <i class="fas fa-crown"></i> Super Admin
                                        @elseif($user->hasRole('Admin'))
                                            <i class="fas fa-user-shield"></i> Admin
                                        @else
                                            <i class="fas fa-user"></i> User
                                        @endif
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <button class="action-btn" title="تعديل" style="width: 35px; height: 35px; border-radius: 8px; background: rgba(41, 128, 185, 0.1); border: 1px solid rgba(41, 128, 185, 0.3); color: #3498db; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn" title="حذف" style="width: 35px; height: 35px; border-radius: 8px; background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.3); color: #e74c3c; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="action-btn" title="تفاصيل" style="width: 35px; height: 35px; border-radius: 8px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); color: var(--slate-blue); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;">
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
                عرض {{ count($users) }} من {{ count($users) }} مستخدم
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

    <!-- Quick Stats -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px;">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-pie" style="color: var(--gold);"></i>
                توزيع الأدوار
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @php
                    $roleCounts = [];
                    foreach ($users as $user) {
                        foreach ($roles as $role) {
                            if ($user->hasRole($role->name)) {
                                $roleCounts[$role->name] = ($roleCounts[$role->name] ?? 0) + 1;
                            }
                        }
                    }
                @endphp
                
                @foreach($roles as $role)
                    @if(isset($roleCounts[$role->name]))
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="color: var(--cream); font-size: 0.9rem;">{{ $role->name }}</span>
                            <span style="color: var(--gold); font-weight: bold;">{{ $roleCounts[$role->name] }}</span>
                        </div>
                        <div style="height: 6px; background: rgba(138, 166, 179, 0.1); border-radius: 3px; overflow: hidden;">
                            <div style="height: 100%; background: linear-gradient(90deg, var(--gold), #E4C875); border-radius: 3px; width: {{ ($roleCounts[$role->name] / count($users)) * 100 }}%;"></div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-bolt" style="color: var(--gold);"></i>
                إجراءات سريعة
            </h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <button style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-user-plus"></i>
                    إضافة مستخدم
                </button>
                <button style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-file-export"></i>
                    تصدير البيانات
                </button>
                <button style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-sync-alt"></i>
                    تحديث الصلاحيات
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .user-row:hover {
        background: rgba(29, 79, 49, 0.05) !important;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .add-user-btn:hover {
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
    
    select:focus {
        outline: none;
        border-color: var(--gold) !important;
        box-shadow: 0 0 0 2px rgba(195, 160, 78, 0.1) !important;
    }
</style>

<script>
    // Add hover effects
    document.querySelectorAll('.user-row').forEach(row => {
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
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const userName = row.querySelector('td:nth-child(1) .user-name')?.textContent?.toLowerCase() || '';
                const userEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Add confirmation for role change
    document.querySelectorAll('.role-form select').forEach(select => {
        select.addEventListener('change', function(e) {
            const userName = this.closest('.user-row').querySelector('.user-name').textContent;
            const newRole = this.options[this.selectedIndex].text;
            
            if (confirm(`هل أنت متأكد من تغيير دور ${userName} إلى ${newRole}؟`)) {
                this.form.submit();
            } else {
                this.value = this.dataset.originalValue;
            }
        });
        
        // Store original value
        select.dataset.originalValue = select.value;
    });
</script>
@endsection