<section style="margin-top: 40px;">
    <header style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-trash-alt"></i>
            </div>
            <div>
                <h2 class="elegant-text" style="font-size: 1.5rem; color: var(--cream); font-weight: 600;">
                    {{ __('Delete Account') }}
                </h2>
                <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
            </div>
        </div>
        
        <!-- Warning Box -->
        <div style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.1), rgba(192, 57, 43, 0.05)); border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 12px; padding: 20px; margin-top: 20px;">
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <div style="min-width: 40px; height: 40px; background: rgba(231, 76, 60, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #e74c3c;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h4 style="color: var(--cream); font-weight: 500; margin-bottom: 8px; font-size: 1.1rem;">
                        {{ __('Important Warning') }}
                    </h4>
                    <div style="color: var(--slate-blue); font-size: 0.9rem; line-height: 1.6;">
                        <p style="margin-bottom: 8px;">
                            <strong style="color: #e74c3c;">هذا الإجراء لا يمكن التراجع عنه!</strong>
                        </p>
                        <ul style="padding-right: 20px; list-style-type: disc;">
                            <li>سيتم حذف جميع بياناتك الشخصية نهائيًا</li>
                            <li>سيتم إزالة جميع الملفات والصور المحفوظة</li>
                            <li>سيتم فقدان جميع الإحصائيات والتقارير</li>
                            <li>لا يمكن استعادة الحساب بعد الحذف</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Delete Account Button -->
    <button id="deleteAccountBtn"
            x-data=""
            x-on:click="openDeleteModal()"
            style="display: flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; padding: 15px 25px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 500; font-size: 1rem; margin-top: 10px;"
            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(231, 76, 60, 0.3)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <i class="fas fa-trash-alt"></i>
        {{ __('Delete Account') }}
    </button>

    <!-- Download Data Button -->
    <div style="margin-top: 20px;">
        <button onclick="exportUserData()"
                style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); cursor: pointer; transition: all 0.3s ease;">
            <i class="fas fa-download"></i>
            {{ __('Download My Data') }}
        </button>
        <p style="color: var(--slate-blue); font-size: 0.85rem; margin-top: 8px; margin-right: 10px;">
            {{ __('Download all your personal data before deleting your account.') }}
        </p>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" 
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10, 20, 16, 0.9); backdrop-filter: blur(5px); z-index: 10000; align-items: center; justify-content: center; animation: fadeIn 0.3s ease;">
    <div style="background: var(--surface); border-radius: 20px; width: 90%; max-width: 500px; border: 1px solid rgba(231, 76, 60, 0.3); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4); overflow: hidden; animation: slideUp 0.4s ease;">
        <!-- Modal Header -->
        <div style="padding: 25px; border-bottom: 1px solid rgba(231, 76, 60, 0.2); background: linear-gradient(135deg, rgba(231, 76, 60, 0.1), transparent);">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.4rem; color: var(--cream); font-weight: 600; margin: 0;">
                        {{ __('Delete Account') }}
                    </h3>
                    <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                        {{ __('Final Confirmation') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Content -->
        <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm" style="padding: 25px;">
            @csrf
            @method('delete')

            <!-- Warning Message -->
            <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <i class="fas fa-skull-crossbones" style="color: #e74c3c; font-size: 1.2rem; margin-top: 2px;"></i>
                    <div>
                        <h4 style="color: #e74c3c; font-weight: 600; margin-bottom: 8px; font-size: 1.1rem;">
                            {{ __('Irreversible Action') }}
                        </h4>
                        <p style="color: var(--cream); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- What Will Be Deleted -->
            <div style="margin-bottom: 25px;">
                <h4 style="color: var(--cream); font-weight: 500; margin-bottom: 15px; font-size: 1rem;">
                    <i class="fas fa-list" style="color: var(--gold); margin-left: 8px;"></i>
                    {{ __('The following will be deleted:') }}
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(138, 166, 179, 0.05); border-radius: 8px;">
                        <i class="fas fa-user" style="color: #e74c3c;"></i>
                        <span style="color: var(--cream); font-size: 0.9rem;">الحساب الشخصي</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(138, 166, 179, 0.05); border-radius: 8px;">
                        <i class="fas fa-history" style="color: #e74c3c;"></i>
                        <span style="color: var(--cream); font-size: 0.9rem;">سجل النشاط</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(138, 166, 179, 0.05); border-radius: 8px;">
                        <i class="fas fa-chart-bar" style="color: #e74c3c;"></i>
                        <span style="color: var(--cream); font-size: 0.9rem;">الإحصائيات</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(138, 166, 179, 0.05); border-radius: 8px;">
                        <i class="fas fa-file" style="color: #e74c3c;"></i>
                        <span style="color: var(--cream); font-size: 0.9rem;">الملفات المحفوظة</span>
                    </div>
                </div>
            </div>

            <!-- Password Input -->
            <div style="margin-bottom: 30px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-key" style="color: #e74c3c;"></i>
                    {{ __('Password') }}
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           id="delete_password"
                           name="password"
                           placeholder="{{ __('Enter your password to confirm') }}"
                           style="width: 100%; padding: 15px 45px 15px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid rgba(231, 76, 60, 0.3); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#e74c3c'; this.style.boxShadow='0 0 0 2px rgba(231, 76, 60, 0.1)';"
                           onblur="this.style.borderColor='rgba(231, 76, 60, 0.3)'; this.style.boxShadow='none';">
                    <i class="fas fa-eye toggle-password" 
                       style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue); cursor: pointer;"
                       onclick="togglePasswordVisibility('delete_password', this)"></i>
                </div>
                @error('password', 'userDeletion')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Confirmation Checkbox -->
            <div style="margin-bottom: 25px;">
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                    <input type="checkbox" 
                           id="confirmDelete"
                           style="width: 18px; height: 18px; margin-top: 3px; accent-color: #e74c3c; cursor: pointer;">
                    <div style="color: var(--cream); font-size: 0.9rem; line-height: 1.5;">
                        <strong style="color: #e74c3c;">أؤكد فهمي التام لعواقب هذا الإجراء.</strong>
                        <br>
                        <span style="color: var(--slate-blue);">أدرك أن جميع بياناتي ستُحذف نهائيًا ولا يمكن استعادتها.</span>
                    </div>
                </label>
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 20px;">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        style="display: flex; align-items: center; gap: 10px; padding: 12px 25px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; font-weight: 500;"
                        onmouseover="this.style.backgroundColor='rgba(138, 166, 179, 0.2)';"
                        onmouseout="this.style.backgroundColor='rgba(138, 166, 179, 0.1)';">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                
                <button type="submit" 
                        id="confirmDeleteBtn"
                        disabled
                        style="display: flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; padding: 12px 30px; border-radius: 10px; cursor: not-allowed; transition: all 0.3s ease; font-weight: 500; opacity: 0.5;"
                        onmouseover="this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.transform='translateY(0)';">
                    <i class="fas fa-trash-alt"></i>
                    {{ __('Delete Account Permanently') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .shake {
        animation: shake 0.5s ease-in-out;
    }
    
    .modal-open {
        overflow: hidden;
    }
    
    #deleteAccountBtn:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3) !important;
    }
    
    button[type="button"]:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    #confirmDeleteBtn:enabled {
        cursor: pointer !important;
        opacity: 1 !important;
    }
    
    #confirmDeleteBtn:enabled:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4) !important;
    }
    
    .toggle-password:hover {
        color: var(--gold) !important;
    }
    
    @media (max-width: 768px) {
        #deleteModal > div {
            width: 95% !important;
            margin: 20px;
        }
        
        .modal-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .modal-actions button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Open delete modal
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        document.body.classList.add('modal-open');
        modal.style.display = 'flex';
        
        // Reset form
        document.getElementById('delete_password').value = '';
        document.getElementById('confirmDelete').checked = false;
        document.getElementById('confirmDeleteBtn').disabled = true;
        document.getElementById('confirmDeleteBtn').style.opacity = '0.5';
        document.getElementById('confirmDeleteBtn').style.cursor = 'not-allowed';
        
        // Focus on password field after animation
        setTimeout(() => {
            document.getElementById('delete_password').focus();
        }, 300);
    }
    
    // Close delete modal
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }, 300);
    }
    
    // Toggle password visibility
    function togglePasswordVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Check delete confirmation
    document.getElementById('confirmDelete').addEventListener('change', function() {
        const password = document.getElementById('delete_password').value;
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        if (this.checked && password.length > 0) {
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = '1';
            confirmBtn.style.cursor = 'pointer';
        } else {
            confirmBtn.disabled = true;
            confirmBtn.style.opacity = '0.5';
            confirmBtn.style.cursor = 'not-allowed';
        }
    });
    
    // Check password input
    document.getElementById('delete_password').addEventListener('input', function() {
        const confirmCheckbox = document.getElementById('confirmDelete');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        if (confirmCheckbox.checked && this.value.length > 0) {
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = '1';
            confirmBtn.style.cursor = 'pointer';
        } else {
            confirmBtn.disabled = true;
            confirmBtn.style.opacity = '0.5';
            confirmBtn.style.cursor = 'not-allowed';
        }
    });
    
    // Form submission
    document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = document.getElementById('delete_password').value;
        const confirmCheckbox = document.getElementById('confirmDelete');
        
        if (!confirmCheckbox.checked) {
            showNotification('يجب الموافقة على التأكيد قبل المتابعة', 'error');
            confirmCheckbox.parentElement.classList.add('shake');
            setTimeout(() => {
                confirmCheckbox.parentElement.classList.remove('shake');
            }, 500);
            return;
        }
        
        if (!password) {
            showNotification('الرجاء إدخال كلمة المرور', 'error');
            document.getElementById('delete_password').focus();
            return;
        }
        
        // Final warning
        if (!confirm('⚠️  تحذير نهائي!\n\nهل أنت متأكد تمامًا من حذف حسابك؟\n\nهذا الإجراء لا يمكن التراجع عنه وسيتم حذف جميع بياناتك نهائيًا.')) {
            return;
        }
        
        // Change button state
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحذف...';
        confirmBtn.disabled = true;
        
        // Show processing message
        showNotification('جاري حذف حسابك... قد تستغرق العملية بضع لحظات', 'warning');
        
        // Submit form after delay
        setTimeout(() => {
            this.submit();
        }, 2000);
    });
    
    // Export user data
    function exportUserData() {
        const exportBtn = document.querySelector('button[onclick="exportUserData()"]');
        const originalText = exportBtn.innerHTML;
        
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
        exportBtn.disabled = true;
        
        // Simulate data export (in real app, this would be an API call)
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
            
            showNotification('تم تجهيز بياناتك للتحميل. سيبدأ التحميل تلقائيًا', 'success');
            
            // Simulate download
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = '#';
                link.download = 'user-data-export.json';
                link.click();
                
                showNotification('تم تحميل بياناتك بنجاح', 'success');
            }, 1500);
        }, 3000);
    }
    
    // Show notification
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
            z-index: 10001;
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
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
            closeDeleteModal();
        }
    });
    
    // Close modal on outside click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Initialize Alpine.js behavior
    function initAlpine() {
        window.openDeleteModal = openDeleteModal;
    }
    
    // Call initialization
    document.addEventListener('DOMContentLoaded', initAlpine);
    
    // For Alpine.js compatibility
    document.addEventListener('alpine:init', () => {
        Alpine.data('deleteAccount', () => ({
            openDeleteModal() {
                openDeleteModal();
            }
        }));
    });
</script>