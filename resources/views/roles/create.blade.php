@extends('layouts.app')

@section('title', 'إنشاء دور جديد')

@section('content')
<div class="create-role-page">
    <!-- Header Section -->
    <div class="page-header" style="margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 1.8rem; color: var(--cream); margin-bottom: 10px;" class="elegant-text">
                <i class="fas fa-plus-circle" style="color: var(--gold); margin-left: 10px;"></i>
                إنشاء دور جديد
            </h1>
            <p style="color: var(--slate-blue); font-size: 0.9rem;">
                إضافة دور جديد مع تحديد الصلاحيات المناسبة
            </p>
        </div>
    </div>

    <!-- Steps Indicator -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; justify-content: space-between; position: relative;">
            <!-- Progress Line -->
            <div style="position: absolute; top: 50%; left: 50px; right: 50px; height: 2px; background: rgba(195, 160, 78, 0.2); transform: translateY(-50%); z-index: 1;"></div>
            
            <!-- Steps -->
            <div style="display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 2;">
                <div class="step active" style="text-align: center; flex: 1;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 10px; background: linear-gradient(135deg, var(--gold), #E4C875); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        1
                    </div>
                    <div style="color: var(--cream); font-weight: 500;">معلومات الدور</div>
                </div>
                
                <div class="step" style="text-align: center; flex: 1;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 10px; background: rgba(138, 166, 179, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--slate-blue); font-weight: bold; font-size: 1.2rem;">
                        2
                    </div>
                    <div style="color: var(--slate-blue);">الصلاحيات</div>
                </div>
                
                <div class="step" style="text-align: center; flex: 1;">
                    <div style="width: 50px; height: 50px; margin: 0 auto 10px; background: rgba(138, 166, 179, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--slate-blue); font-weight: bold; font-size: 1.2rem;">
                        3
                    </div>
                    <div style="color: var(--slate-blue);">المراجعة</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <form method="POST" action="{{ route('roles.store') }}" id="createRoleForm">
        @csrf

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; overflow: hidden;">
            <!-- Form Header -->
            <div style="padding: 20px; border-bottom: 1px solid var(--border); background: rgba(29, 79, 49, 0.1);">
                <h3 style="color: var(--cream); font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: var(--gold);"></i>
                    معلومات الدور الأساسية
                </h3>
                <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                    أدخل المعلومات الأساسية للدور الجديد
                </p>
            </div>

            <!-- Role Details Section -->
            <div style="padding: 25px;">
                <!-- Role Name -->
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px;">
                        <i class="fas fa-tag" style="color: var(--gold); margin-left: 8px;"></i>
                        اسم الدور
                    </label>
                    <div style="position: relative;">
                        <input type="text" 
                               name="name"
                               id="roleName"
                               required
                               placeholder="أدخل اسم الدور (مثال: مدير قسم، مراقب، محرر...)"
                               style="width: 100%; padding: 15px 45px 15px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 1rem; transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                        <i class="fas fa-user-tag" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
                    </div>
                    <div id="nameFeedback" style="margin-top: 8px; font-size: 0.8rem;"></div>
                </div>

                <!-- Role Description -->
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px;">
                        <i class="fas fa-align-left" style="color: var(--gold); margin-left: 8px;"></i>
                        وصف الدور (اختياري)
                    </label>
                    <textarea name="description"
                              id="roleDescription"
                              rows="3"
                              placeholder="أدخل وصفًا مختصرًا للدور ومسؤولياته..."
                              style="width: 100%; padding: 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.9rem; resize: vertical; transition: all 0.3s ease;"
                              onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"></textarea>
                </div>

                <!-- Role Type Selection -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 15px;">
                        <i class="fas fa-layer-group" style="color: var(--gold); margin-left: 8px;"></i>
                        نوع الدور
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <label class="role-type-option" 
                               style="border: 2px solid var(--border); border-radius: 10px; padding: 15px; cursor: pointer; transition: all 0.3s ease; position: relative;"
                               onclick="selectRoleType('custom')">
                            <input type="radio" 
                                   name="role_type" 
                                   value="custom" 
                                   checked
                                   style="position: absolute; opacity: 0;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8AA6B3, #6c8a99); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: 500; margin-bottom: 3px;">مخصص</div>
                                    <div style="color: var(--slate-blue); font-size: 0.8rem;">دور جديد بحريتك</div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="role-type-option" 
                               style="border: 2px solid var(--border); border-radius: 10px; padding: 15px; cursor: pointer; transition: all 0.3s ease; position: relative;"
                               onclick="selectRoleType('admin')">
                            <input type="radio" 
                                   name="role_type" 
                                   value="admin"
                                   style="position: absolute; opacity: 0;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--deep-green), #27ae60); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: 500; margin-bottom: 3px;">مسؤول</div>
                                    <div style="color: var(--slate-blue); font-size: 0.8rem;">صلاحيات إدارية</div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="role-type-option" 
                               style="border: 2px solid var(--border); border-radius: 10px; padding: 15px; cursor: pointer; transition: all 0.3s ease; position: relative;"
                               onclick="selectRoleType('viewer')">
                            <input type="radio" 
                                   name="role_type" 
                                   value="viewer"
                                   style="position: absolute; opacity: 0;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #9b59b6, #8e44ad); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div>
                                    <div style="color: var(--cream); font-weight: 500; margin-bottom: 3px;">مشاهد</div>
                                    <div style="color: var(--slate-blue); font-size: 0.8rem;">صلاحيات مشاهدة فقط</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div style="padding: 25px; border-top: 1px solid var(--border); background: rgba(29, 79, 49, 0.05);">
                <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <h3 style="color: var(--cream); font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-key" style="color: var(--gold);"></i>
                        إدارة الصلاحيات
                    </h3>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" onclick="selectAllPermissions()" 
                                style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                            <i class="fas fa-check-square"></i>
                            تحديد الكل
                        </button>
                        <button type="button" onclick="deselectAllPermissions()" 
                                style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                            <i class="fas fa-square"></i>
                            إلغاء الكل
                        </button>
                    </div>
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
                <div class="permission-group" style="margin-bottom: 25px; border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
                    <div style="padding: 15px 20px; background: rgba(29, 79, 49, 0.1); border-bottom: 1px solid var(--border);">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @switch($module)
                                    @case('users')
                                        <div style="width: 35px; height: 35px; background: rgba(41, 128, 185, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #3498db;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        @break
                                    @case('roles')
                                        <div style="width: 35px; height: 35px; background: rgba(195, 160, 78, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        @break
                                    @case('permissions')
                                        <div style="width: 35px; height: 35px; background: rgba(46, 204, 113, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #27ae60;">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        @break
                                    @default
                                        <div style="width: 35px; height: 35px; background: rgba(155, 89, 182, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9b59b6;">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                @endswitch
                                <h4 style="color: var(--cream); font-weight: 500; font-size: 1rem;">
                                    {{ ucfirst($module) }}
                                </h4>
                                <span style="background: rgba(138, 166, 179, 0.2); color: var(--slate-blue); padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                                    {{ count($modulePermissions) }}
                                </span>
                            </div>
                            <button type="button" 
                                    onclick="toggleModuleSelection('{{ $module }}')"
                                    style="background: none; border: none; color: var(--slate-blue); cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 5px; transition: all 0.3s ease;"
                                    onmouseover="this.style.backgroundColor='rgba(29, 79, 49, 0.1)';"
                                    onmouseout="this.style.backgroundColor='transparent';">
                                <i class="fas fa-check-square"></i>
                                تحديد الكل
                            </button>
                        </div>
                    </div>

                    <div style="padding: 20px;">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
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
                                           class="permission-checkbox"
                                           style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--gold);">
                                </div>
                                
                                <div style="flex: 1;">
                                    <div style="color: var(--cream); font-weight: 500; font-size: 0.9rem; margin-bottom: 3px;">
                                        {{ $permission->name }}
                                    </div>
                                    <div style="color: var(--slate-blue); font-size: 0.8rem;">
                                        @php
                                            $description = '';
                                            switch(true) {
                                                case str_contains($permission->name, 'view'):
                                                    $description = 'صلاحية عرض';
                                                    break;
                                                case str_contains($permission->name, 'create'):
                                                    $description = 'صلاحية إنشاء';
                                                    break;
                                                case str_contains($permission->name, 'edit'):
                                                    $description = 'صلاحية تعديل';
                                                    break;
                                                case str_contains($permission->name, 'delete'):
                                                    $description = 'صلاحية حذف';
                                                    break;
                                                default:
                                                    $description = 'صلاحية عامة';
                                            }
                                        @endphp
                                        {{ $description }}
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Selected Permissions Summary -->
                <div id="selectedPermissionsSummary" 
                     style="background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 10px; padding: 15px; margin-top: 20px; display: none;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-check-circle" style="color: var(--gold);"></i>
                            <h4 style="color: var(--cream); font-weight: 500;">الصلاحيات المحددة</h4>
                        </div>
                        <span id="selectedCount" style="background: rgba(195, 160, 78, 0.2); color: var(--gold); padding: 2px 10px; border-radius: 10px; font-size: 0.8rem;">
                            0 صلاحية
                        </span>
                    </div>
                    <div id="selectedPermissionsList" 
                         style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 30px; color: var(--slate-blue); font-size: 0.9rem;">
                        <span>لم يتم تحديد أي صلاحيات بعد</span>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div style="padding: 20px; border-top: 1px solid var(--border); display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: center; background: rgba(29, 79, 49, 0.1);">
                <div>
                    <a href="{{ route('roles.index') }}" 
                       style="display: flex; align-items: center; gap: 8px; padding: 10px 15px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 8px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; text-decoration: none;">
                        <i class="fas fa-arrow-right"></i>
                        إلغاء والعودة
                    </a>
                </div>
                
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <button type="button" 
                            onclick="previewRole()"
                            id="previewBtn"
                            style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(41, 128, 185, 0.1); border: 1px solid rgba(41, 128, 185, 0.3); border-radius: 8px; color: #3498db; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-eye"></i>
                        معاينة الدور
                    </button>
                    <button type="submit" 
                            style="display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--deep-green), #27ae60); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 500;"
                            id="submitBtn">
                        <i class="fas fa-plus-circle"></i>
                        إنشاء الدور
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Tips & Guidelines -->
    <div style="margin-top: 30px; background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 25px;">
        <h3 style="color: var(--cream); margin-bottom: 15px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-lightbulb" style="color: var(--gold);"></i>
            نصائح وإرشادات
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="color: var(--slate-blue); font-size: 0.9rem; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="color: var(--gold); margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">اختيار الاسم:</strong> اختر اسمًا واضحًا ودالًا على مسؤوليات الدور
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="color: var(--gold); margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">تحديد الصلاحيات:</strong> حدد فقط الصلاحيات الضرورية لأداء المهام المطلوبة
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <i class="fas fa-check-circle" style="color: var(--gold); margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">مبدأ أقل صلاحية:</strong> امنح الصلاحيات بأقل قدر ممكن لأداء المهام
                    </div>
                </div>
            </div>
            
            <div style="color: var(--slate-blue); font-size: 0.9rem; line-height: 1.6;">
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-exclamation-triangle" style="color: #f39c12; margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">تحذير:</strong> لا تنشئ أدوارًا بصلاحيات كاملة إلا للمسؤولين المختصين
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #3498db; margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">معلومة:</strong> يمكن تعديل الصلاحيات لاحقًا من صفحة إدارة الأدوار
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <i class="fas fa-history" style="color: var(--slate-blue); margin-top: 3px;"></i>
                    <div>
                        <strong style="color: var(--cream);">التسجيل:</strong> يتم تسجيل جميع عمليات إنشاء الأدوار في سجل النظام
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .step.active div:first-child {
        background: linear-gradient(135deg, var(--gold), #E4C875) !important;
        color: white !important;
    }
    
    .step.active div:last-child {
        color: var(--cream) !important;
        font-weight: 500 !important;
    }
    
    .role-type-option:hover {
        border-color: var(--gold) !important;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .role-type-option input[type="radio"]:checked + div {
        border-color: var(--gold) !important;
    }
    
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
    
    #previewBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(41, 128, 185, 0.2);
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
    // Select role type
    function selectRoleType(type) {
        const options = document.querySelectorAll('.role-type-option');
        options.forEach(option => {
            option.style.borderColor = 'var(--border)';
        });
        
        const selectedOption = document.querySelector(`.role-type-option[onclick="selectRoleType('${type}')"]`);
        if (selectedOption) {
            selectedOption.style.borderColor = 'var(--gold)';
        }
        
        // Update form based on selected type
        updateRoleType(type);
    }

    // Update form based on role type
    function updateRoleType(type) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        
        // Deselect all first
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Select based on type
        switch(type) {
            case 'admin':
                // Select admin permissions (view, create, edit for main modules)
                checkboxes.forEach(checkbox => {
                    if (checkbox.value.includes('view') || 
                        checkbox.value.includes('create') || 
                        checkbox.value.includes('edit')) {
                        checkbox.checked = true;
                    }
                });
                break;
                
            case 'viewer':
                // Select view permissions only
                checkboxes.forEach(checkbox => {
                    if (checkbox.value.includes('view')) {
                        checkbox.checked = true;
                    }
                });
                break;
                
            case 'custom':
                // Leave as is (user will manually select)
                break;
        }
        
        updateSelectedPermissions();
        showNotification(`تم تحديد صلاحيات ${type === 'admin' ? 'المسؤول' : type === 'viewer' ? 'المشاهد' : 'المخصص'}`, 'info');
    }

    // Update selected permissions display
    function updateSelectedPermissions() {
        const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
        const summaryDiv = document.getElementById('selectedPermissionsSummary');
        const listDiv = document.getElementById('selectedPermissionsList');
        const countSpan = document.getElementById('selectedCount');
        
        if (checkboxes.length > 0) {
            summaryDiv.style.display = 'block';
            
            let html = '';
            checkboxes.forEach(checkbox => {
                const permissionName = checkbox.value;
                html += `<span style="background: rgba(195, 160, 78, 0.1); color: var(--gold); padding: 4px 10px; border-radius: 15px; font-size: 0.8rem; margin: 2px;">${permissionName}</span>`;
            });
            
            listDiv.innerHTML = html;
            countSpan.textContent = `${checkboxes.length} صلاحية`;
        } else {
            summaryDiv.style.display = 'block';
            listDiv.innerHTML = '<span>لم يتم تحديد أي صلاحيات بعد</span>';
            countSpan.textContent = '0 صلاحية';
        }
    }

    // Select all permissions
    function selectAllPermissions() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedPermissions();
        
        showNotification('تم تحديد جميع الصلاحيات', 'success');
    }

    // Deselect all permissions
    function deselectAllPermissions() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedPermissions();
        
        showNotification('تم إلغاء تحديد جميع الصلاحيات', 'info');
    }

    // Toggle selection for a module
    function toggleModuleSelection(module) {
        const checkboxes = document.querySelectorAll(`.permission-group:has(h4:contains('${module}')) .permission-checkbox`);
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        updateSelectedPermissions();
        
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

    // Role name validation
    document.getElementById('roleName').addEventListener('input', function(e) {
        const name = this.value.trim();
        const feedback = document.getElementById('nameFeedback');
        
        if (name.length === 0) {
            feedback.innerHTML = '';
            feedback.style.color = 'var(--slate-blue)';
        } else if (name.length < 3) {
            feedback.innerHTML = '<i class="fas fa-exclamation-circle"></i> الاسم قصير جداً';
            feedback.style.color = '#e74c3c';
        } else if (name.length > 50) {
            feedback.innerHTML = '<i class="fas fa-exclamation-circle"></i> الاسم طويل جداً';
            feedback.style.color = '#e74c3c';
        } else {
            feedback.innerHTML = '<i class="fas fa-check-circle"></i> الاسم مناسب';
            feedback.style.color = '#27ae60';
        }
    });

    // Preview role
    function previewRole() {
        const roleName = document.getElementById('roleName').value.trim();
        const roleDescription = document.getElementById('roleDescription').value.trim();
        const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
        
        if (!roleName) {
            showNotification('الرجاء إدخال اسم الدور', 'error');
            document.getElementById('roleName').focus();
            return;
        }
        
        let permissionsList = '';
        checkboxes.forEach((checkbox, index) => {
            if (index < 5) {
                permissionsList += `• ${checkbox.value}\n`;
            }
        });
        
        if (checkboxes.length > 5) {
            permissionsList += `• و ${checkboxes.length - 5} صلاحيات أخرى\n`;
        }
        
        const message = `معاينة الدور:\n\n` +
                       `الاسم: ${roleName}\n` +
                       `الوصف: ${roleDescription || 'لا يوجد وصف'}\n` +
                       `عدد الصلاحيات: ${checkboxes.length}\n\n` +
                       `الصلاحيات المحددة:\n${permissionsList}\n` +
                       `هل تريد الاستمرار في إنشاء هذا الدور؟`;
        
        if (confirm(message)) {
            // Auto-scroll to submit button
            document.getElementById('submitBtn').scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Form submission
    document.getElementById('createRoleForm').addEventListener('submit', function(e) {
        const roleName = document.getElementById('roleName').value.trim();
        const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
        
        if (!roleName) {
            e.preventDefault();
            showNotification('الرجاء إدخال اسم الدور', 'error');
            document.getElementById('roleName').focus();
            return;
        }
        
        if (checkboxes.length === 0) {
            if (!confirm('لم يتم تحديد أي صلاحيات. هل تريد إنشاء دور بدون صلاحيات؟')) {
                e.preventDefault();
                return;
            }
        }
        
        // Change button state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds if form submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Update selected permissions when checkboxes change
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedPermissions);
        });
        
        // Select custom role type by default
        selectRoleType('custom');
        
        // Initial update of selected permissions
        updateSelectedPermissions();
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