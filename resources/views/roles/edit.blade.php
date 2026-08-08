@extends('layouts.app')

@section('title', 'تعديل دور: ' . $role->name)

@section('content')
<div class="edit-role-page">
    <!-- Header Section -->
    <div class="page-header" style="margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 1.8rem; color: var(--cream); margin-bottom: 10px;" class="elegant-text">
                <i class="fas fa-edit" style="color: var(--gold); margin-left: 10px;"></i>
                تعديل دور: <span style="color: var(--gold);">{{ $role->name }}</span>
            </h1>
            <p style="color: var(--slate-blue); font-size: 0.9rem;">
                إدارة وتعديل صلاحيات الدور: {{ $role->name }}
            </p>
        </div>
    </div>

    <!-- Role Info Card -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
            <div class="role-icon" style="width: 70px; height: 70px; border-radius: 15px; 
                @if($role->name === 'Super Admin')
                    background: linear-gradient(135deg, var(--gold), #E4C875);
                @elseif($role->name === 'Admin')
                    background: linear-gradient(135deg, var(--deep-green), #27ae60);
                @else
                    background: linear-gradient(135deg, #8AA6B3, #6c8a99);
                @endif
                display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                @if($role->name === 'Super Admin')
                    <i class="fas fa-crown"></i>
                @elseif($role->name === 'Admin')
                    <i class="fas fa-user-shield"></i>
                @else
                    <i class="fas fa-user-tag"></i>
                @endif
            </div>
            
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <h2 style="font-size: 1.5rem; color: var(--cream); font-weight: 500;">
                        {{ $role->name }}
                    </h2>
                    <span style="padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; 
                        @if($role->name === 'Super Admin')
                            background: rgba(195, 160, 78, 0.2); color: var(--gold);
                        @elseif($role->name === 'Admin')
                            background: rgba(29, 79, 49, 0.2); color: #27ae60;
                        @else
                            background: rgba(138, 166, 179, 0.2); color: var(--slate-blue);
                        @endif">
                        @if($role->name === 'Super Admin')
                            <i class="fas fa-star"></i> دور النظام
                        @elseif($role->name === 'Admin')
                            <i class="fas fa-shield-alt"></i> دور مسؤول
                        @else
                            <i class="fas fa-user"></i> دور مخصص
                        @endif
                    </span>
                </div>
                
                <div style="display: flex; flex-wrap: wrap; gap: 10px; color: var(--slate-blue); font-size: 0.9rem;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-user-friends"></i>
                        <span>{{ $role->users->count() }} مستخدم</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-key"></i>
                        <span>{{ $role->permissions->count() }} صلاحية</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-calendar"></i>
                        <span>أنشئ في: {{ $role->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($role->name === 'Super Admin')
        <div style="background: rgba(195, 160, 78, 0.1); border: 1px solid rgba(195, 160, 78, 0.2); border-radius: 10px; padding: 15px; color: var(--gold); font-size: 0.9rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle"></i>
            دور Super Admin يملك كافة صلاحيات النظام ولا يمكن تعديل صلاحياته
        </div>
        @endif
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('roles.update', $role) }}" id="editRoleForm">
        @csrf
        @method('PUT')

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; overflow: hidden;">
            <!-- Form Header -->
            <div style="padding: 20px; border-bottom: 1px solid var(--border); background: rgba(29, 79, 49, 0.1);">
                <h3 style="color: var(--cream); font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-key" style="color: var(--gold);"></i>
                    إدارة الصلاحيات
                </h3>
                <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                    اختر الصلاحيات المناسبة لهذا الدور
                </p>
            </div>

            <!-- Permissions Section -->
            <div style="padding: 25px;">
                <!-- Quick Actions -->
                <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                    <button type="button" onclick="selectAllPermissions()" 
                            style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-check-square"></i>
                        تحديد الكل
                    </button>
                    <button type="button" onclick="deselectAllPermissions()" 
                            style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-square"></i>
                        إلغاء تحديد الكل
                    </button>
                    @if($role->name === 'Admin')
                    <button type="button" onclick="selectAdminPermissions()" 
                            style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(41, 128, 185, 0.1); border: 1px solid rgba(41, 128, 185, 0.3); border-radius: 8px; color: #3498db; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-user-shield"></i>
                        صلاحيات الادمن القياسية
                    </button>
                    @endif
                </div>

                <!-- Search Permissions -->
                <div class="search-box" style="position: relative; max-width: 400px; margin-bottom: 25px;">
                    <input type="text" 
                           id="permissionSearch"
                           placeholder="بحث عن صلاحية..."
                           style="width: 100%; padding: 12px 15px 12px 45px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 25px; color: var(--cream); font-size: 0.9rem;">
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
                </div>

                <!-- Permissions Grid -->
                @php
                    // Group permissions by module/prefix
                    $groupedPermissions = [];
                    foreach ($permissions as $permission) {
                        $parts = explode('.', $permission->name);
                        $module = count($parts) > 1 ? $parts[0] : 'عام';
                        $groupedPermissions[$module][] = $permission;
                    }
                @endphp

                @foreach($groupedPermissions as $module => $modulePermissions)
                <div class="permission-group" style="margin-bottom: 30px; border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
                    <div style="padding: 15px 20px; background: rgba(29, 79, 49, 0.1); border-bottom: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h4 style="color: var(--cream); font-weight: 500; display: flex; align-items: center; gap: 8px;">
                                @switch($module)
                                    @case('users')
                                        <i class="fas fa-users" style="color: var(--gold);"></i>
                                        @break
                                    @case('roles')
                                        <i class="fas fa-user-tag" style="color: var(--gold);"></i>
                                        @break
                                    @case('permissions')
                                        <i class="fas fa-key" style="color: var(--gold);"></i>
                                        @break
                                    @default
                                        <i class="fas fa-cog" style="color: var(--gold);"></i>
                                @endswitch
                                {{ ucfirst($module) }}
                            </h4>
                            <button type="button" 
                                    onclick="toggleModuleSelection('{{ $module }}')"
                                    style="background: none; border: none; color: var(--slate-blue); cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check-square"></i>
                                تحديد الكل
                            </button>
                        </div>
                        <p style="color: var(--slate-blue); font-size: 0.8rem; margin-top: 5px;">
                            {{ count($modulePermissions) }} صلاحية
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" style="padding: 20px;">
                        @foreach($modulePermissions as $permission)
                        <label class="permission-item" 
                               style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.3s ease;"
                               onmouseover="this.style.borderColor='var(--gold)'; this.style.transform='translateX(-3px)';"
                               onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateX(0)';">
                            <div class="checkbox-container" style="position: relative;">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="permission_{{ $permission->id }}"
                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                       {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                                       style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--gold);">
                            </div>
                            
                            <div style="flex: 1;">
                                <div style="color: var(--cream); font-weight: 500; font-size: 0.9rem; margin-bottom: 3px;">
                                    {{ $permission->name }}
                                </div>
                                <div style="color: var(--slate-blue); font-size: 0.8rem;">
                                    @php
                                        $description = '';
                                        switch($permission->name) {
                                            case str_contains($permission->name, 'view'):
                                                $description = 'عرض';
                                                break;
                                            case str_contains($permission->name, 'create'):
                                                $description = 'إنشاء';
                                                break;
                                            case str_contains($permission->name, 'edit'):
                                                $description = 'تعديل';
                                                break;
                                            case str_contains($permission->name, 'delete'):
                                                $description = 'حذف';
                                                break;
                                            default:
                                                $description = 'صلاحية';
                                        }
                                    @endphp
                                    {{ $description }}
                                </div>
                            </div>
                            
                            @if(in_array($permission->name, $rolePermissions))
                            <div style="color: var(--gold); font-size: 0.8rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Current Selection -->
                <div style="background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 10px; padding: 15px; margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--gold);"></i>
                        <h4 style="color: var(--cream); font-weight: 500;">الصلاحيات المحددة</h4>
                    </div>
                    <div id="selectedPermissions" 
                         style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 30px; color: var(--slate-blue); font-size: 0.9rem;">
                        @if(count($rolePermissions) > 0)
                            @foreach($rolePermissions as $permission)
                            <span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 4px 10px; border-radius: 15px; font-size: 0.8rem;">
                                {{ $permission }}
                            </span>
                            @endforeach
                        @else
                            <span>لم يتم تحديد أي صلاحيات</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div style="padding: 20px; border-top: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: center; background: rgba(29, 79, 49, 0.05);">
                <div>
                    <a href="{{ route('roles.index') }}" 
                       style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; text-decoration: none;">
                        <i class="fas fa-arrow-right"></i>
                        رجوع للقائمة
                    </a>
                </div>
                
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <button type="button" 
                            onclick="resetForm()"
                            style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(149, 165, 166, 0.1); border: 1px solid rgba(149, 165, 166, 0.3); border-radius: 8px; color: #95a5a6; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    <button type="submit" 
                            style="display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--deep-green), #256341); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 500;"
                            id="submitBtn">
                        <i class="fas fa-save"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Statistics -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px;">
        <!-- Summary Card -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-bar" style="color: var(--gold);"></i>
                ملخص الصلاحيات
            </h3>
            <div style="color: var(--slate-blue); font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>إجمالي الصلاحيات:</span>
                    <span style="color: var(--cream);">{{ count($permissions) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>الصلاحيات المحددة:</span>
                    <span style="color: var(--gold);" id="selectedCount">{{ count($rolePermissions) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>النسبة المئوية:</span>
                    <span style="color: var(--cream);">{{ round((count($rolePermissions) / count($permissions)) * 100) }}%</span>
                </div>
                <div style="height: 6px; background: rgba(138, 166, 179, 0.1); border-radius: 3px; overflow: hidden; margin-top: 10px;">
                    <div style="height: 100%; background: linear-gradient(90deg, var(--gold), #E4C875); border-radius: 3px; width: {{ (count($rolePermissions) / count($permissions)) * 100 }}%;"></div>
                </div>
            </div>
        </div>

        <!-- User Impact -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 20px;">
            <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-users" style="color: var(--gold);"></i>
                تأثير التعديل
            </h3>
            <div style="color: var(--slate-blue); font-size: 0.9rem;">
                <p style="margin-bottom: 10px;">
                    هذا التعديل سيؤثر على <strong style="color: var(--cream);">{{ $role->users->count() }}</strong> مستخدم
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;">
                    @foreach($role->users->take(3) as $user)
                    <span style="background: rgba(138, 166, 179, 0.1); padding: 4px 10px; border-radius: 15px; font-size: 0.8rem;">
                        {{ $user->name }}
                    </span>
                    @endforeach
                    @if($role->users->count() > 3)
                    <span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 4px 10px; border-radius: 15px; font-size: 0.8rem;">
                        +{{ $role->users->count() - 3 }} آخرين
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .permission-item:hover {
        border-color: var(--gold) !important;
        transform: translateX(-3px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    input[type="checkbox"]:checked + .checkmark {
        background: var(--gold);
        border-color: var(--gold);
    }
    
    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 79, 49, 0.3);
    }
    
    .permission-group {
        transition: all 0.3s ease;
    }
    
    .permission-group:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    @media (max-width: 768px) {
        .grid-cols-3 {
            grid-template-columns: 1fr !important;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    // Update selected permissions display
    function updateSelectedPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
        const selectedDiv = document.getElementById('selectedPermissions');
        const selectedCount = document.getElementById('selectedCount');
        
        let html = '';
        checkboxes.forEach(checkbox => {
            const permissionName = checkbox.value;
            html += `<span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 4px 10px; border-radius: 15px; font-size: 0.8rem; margin: 2px;">${permissionName}</span>`;
        });
        
        selectedDiv.innerHTML = html || '<span>لم يتم تحديد أي صلاحيات</span>';
        selectedCount.textContent = checkboxes.length;
        
        // Update progress bar
        const totalPermissions = {{ count($permissions) }};
        const percentage = Math.round((checkboxes.length / totalPermissions) * 100);
        const progressBar = document.querySelector('.progress-bar div');
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
    }

    // Select all permissions
    function selectAllPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = true;
            }
        });
        updateSelectedPermissions();
        
        // Show notification
        showNotification('تم تحديد جميع الصلاحيات', 'success');
    }

    // Deselect all permissions
    function deselectAllPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = false;
            }
        });
        updateSelectedPermissions();
        
        // Show notification
        showNotification('تم إلغاء تحديد جميع الصلاحيات', 'info');
    }

    // Select admin standard permissions
    function selectAdminPermissions() {
        const adminPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'roles.view',
            'permissions.view'
        ];
        
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled && adminPermissions.some(perm => checkbox.value.includes(perm))) {
                checkbox.checked = true;
            }
        });
        updateSelectedPermissions();
        
        // Show notification
        showNotification('تم تحديد صلاحيات الادمن القياسية', 'success');
    }

    // Toggle selection for a module
    function toggleModuleSelection(module) {
        const checkboxes = document.querySelectorAll(`.permission-group:has(h4:contains('${module}')) input[name="permissions[]"]`);
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked || checkbox.disabled);
        
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = !allChecked;
            }
        });
        updateSelectedPermissions();
        
        // Show notification
        showNotification(`${allChecked ? 'تم إلغاء تحديد' : 'تم تحديد'} جميع صلاحيات ${module}`, 'info');
    }

    // Search permissions
    document.getElementById('permissionSearch').addEventListener('input', function(e) {
        const searchTerm = this.value.toLowerCase();
        const permissionItems = document.querySelectorAll('.permission-item');
        
        permissionItems.forEach(item => {
            const permissionName = item.querySelector('div:nth-child(2) > div:first-child').textContent.toLowerCase();
            if (permissionName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Reset form
    function resetForm() {
        if (confirm('هل أنت متأكد من إعادة تعيين التعديلات؟')) {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const originalPermissions = @json($rolePermissions);
            
            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = originalPermissions.includes(checkbox.value);
                }
            });
            updateSelectedPermissions();
            
            showNotification('تم إعادة تعيين التعديلات', 'info');
        }
    }

    // Form submission
    document.getElementById('editRoleForm').addEventListener('submit', function(e) {
        if ('{{ $role->name }}' === 'Super Admin') {
            e.preventDefault();
            showNotification('لا يمكن تعديل صلاحيات دور Super Admin', 'error');
            return;
        }
        
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
        if (checkboxes.length === 0) {
            if (!confirm('لم يتم تحديد أي صلاحيات. هل تريد الاستمرار؟')) {
                e.preventDefault();
                return;
            }
        }
        
        // Change button state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Update selected permissions when checkboxes change
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedPermissions);
        });
        
        // Initial update
        updateSelectedPermissions();
        
        // Disable form for Super Admin
        if ('{{ $role->name }}' === 'Super Admin') {
            const form = document.getElementById('editRoleForm');
            const submitBtn = document.getElementById('submitBtn');
            
            checkboxes.forEach(checkbox => {
                checkbox.disabled = true;
            });
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> غير قابل للتعديل';
            submitBtn.style.background = 'rgba(149, 165, 166, 0.3)';
            submitBtn.style.cursor = 'not-allowed';
            
            const quickActionBtns = document.querySelectorAll('button[type="button"]');
            quickActionBtns.forEach(btn => {
                btn.disabled = true;
                btn.style.cursor = 'not-allowed';
                btn.style.opacity = '0.6';
            });
        }
    });

    // Show notification function
    function showNotification(message, type = 'info') {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            info: '#3498db',
            warning: '#f39c12'
        };
        
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--surface);
            color: var(--cream);
            padding: 15px 25px;
            border-radius: 10px;
            border-left: 4px solid ${colors[type]};
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Tajawal', sans-serif;
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" 
               style="color: ${colors[type]}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(-50%) translateY(-20px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>
@endsection