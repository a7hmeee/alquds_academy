<section>
    <header style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-lock"></i>
            </div>
            <div>
                <h2 class="elegant-text" style="font-size: 1.5rem; color: var(--cream); font-weight: 600;">
                    {{ __('Update Password') }}
                </h2>
                <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </div>
        </div>
        
        <!-- Password Strength Indicator -->
        <div id="passwordStrength" style="display: none; margin-top: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: var(--slate-blue); font-size: 0.85rem;">قوة كلمة المرور:</span>
                <span id="strengthText" style="font-size: 0.85rem; font-weight: 500;"></span>
            </div>
            <div style="height: 6px; background: rgba(138, 166, 179, 0.1); border-radius: 3px; overflow: hidden;">
                <div id="strengthBar" style="height: 100%; width: 0%; transition: width 0.3s ease; border-radius: 3px;"></div>
            </div>
            <div id="passwordRequirements" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 15px;">
                <div class="requirement" style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                    <i class="fas fa-circle" style="color: #e74c3c; font-size: 0.5rem;"></i>
                    <span style="color: var(--slate-blue);">8 أحرف على الأقل</span>
                </div>
                <div class="requirement" style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                    <i class="fas fa-circle" style="color: #e74c3c; font-size: 0.5rem;"></i>
                    <span style="color: var(--slate-blue);">حرف كبير وصغير</span>
                </div>
                <div class="requirement" style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                    <i class="fas fa-circle" style="color: #e74c3c; font-size: 0.5rem;"></i>
                    <span style="color: var(--slate-blue);">رقم واحد على الأقل</span>
                </div>
                <div class="requirement" style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem;">
                    <i class="fas fa-circle" style="color: #e74c3c; font-size: 0.5rem;"></i>
                    <span style="color: var(--slate-blue);">رمز خاص واحد على الأقل</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Password Update Form -->
    <form method="post" action="{{ route('password.update') }}" id="passwordForm" class="mt-6">
        @csrf
        @method('put')

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 30px; margin-bottom: 30px;">
            <!-- Form Header -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                <div style="width: 40px; height: 40px; background: rgba(231, 76, 60, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #e74c3c;">
                    <i class="fas fa-key"></i>
                </div>
                <h3 style="color: var(--cream); font-size: 1.1rem; font-weight: 500;">
                    {{ __('Change Password') }}
                </h3>
            </div>

            <!-- Current Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-unlock" style="color: var(--gold);"></i>
                    {{ __('Current Password') }}
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           id="update_password_current_password" 
                           name="current_password" 
                           autocomplete="current-password"
                           style="width: 100%; padding: 15px 45px 15px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                    <i class="fas fa-eye toggle-password" 
                       style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue); cursor: pointer;"
                       onclick="togglePasswordVisibility('update_password_current_password', this)"></i>
                </div>
                @error('current_password', 'updatePassword')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-lock" style="color: #27ae60;"></i>
                    {{ __('New Password') }}
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           id="update_password_password" 
                           name="password" 
                           autocomplete="new-password"
                           style="width: 100%; padding: 15px 45px 15px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"
                           oninput="checkPasswordStrength(this.value)">
                    <i class="fas fa-eye toggle-password" 
                       style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue); cursor: pointer;"
                       onclick="togglePasswordVisibility('update_password_password', this)"></i>
                </div>
                @error('password', 'updatePassword')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-lock" style="color: #3498db;"></i>
                    {{ __('Confirm Password') }}
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           id="update_password_password_confirmation" 
                           name="password_confirmation" 
                           autocomplete="new-password"
                           style="width: 100%; padding: 15px 45px 15px 15px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"
                           oninput="checkPasswordMatch()">
                    <i class="fas fa-eye toggle-password" 
                       style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue); cursor: pointer;"
                       onclick="togglePasswordVisibility('update_password_password_confirmation', this)"></i>
                </div>
                <div id="passwordMatch" style="display: flex; align-items: center; gap: 8px; margin-top: 8px; font-size: 0.85rem; display: none;">
                    <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                    <span style="color: #27ae60;">كلمات المرور متطابقة</span>
                </div>
                @error('password_confirmation', 'updatePassword')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Password Tips -->
            <div style="background: rgba(29, 79, 49, 0.1); border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-top: 20px;">
                <h4 style="color: var(--cream); font-size: 1rem; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-lightbulb" style="color: var(--gold);"></i>
                    {{ __('Password Tips') }}
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60; margin-top: 3px;"></i>
                        <div>
                            <div style="color: var(--cream); font-size: 0.9rem; margin-bottom: 2px;">استخدم كلمات مرور طويلة</div>
                            <div style="color: var(--slate-blue); font-size: 0.8rem;">12 حرفًا على الأقل</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #27ae60; margin-top: 3px;"></i>
                        <div>
                            <div style="color: var(--cream); font-size: 0.9rem; margin-bottom: 2px;">اختلط بين الأنواع</div>
                            <div style="color: var(--slate-blue); font-size: 0.8rem;">أحرف، أرقام، رموز</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-times-circle" style="color: #e74c3c; margin-top: 3px;"></i>
                        <div>
                            <div style="color: var(--cream); font-size: 0.9rem; margin-bottom: 2px;">لا تستخدم معلومات شخصية</div>
                            <div style="color: var(--slate-blue); font-size: 0.8rem;">مثل الأسماء أو التواريخ</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-sync-alt" style="color: var(--gold); margin-top: 3px;"></i>
                        <div>
                            <div style="color: var(--cream); font-size: 0.9rem; margin-bottom: 2px;">غيّر كلمة المرور بانتظام</div>
                            <div style="color: var(--slate-blue); font-size: 0.8rem;">كل 3-6 أشهر</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <button type="button" 
                        onclick="generateStrongPassword()"
                        style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(155, 89, 182, 0.1); border: 1px solid rgba(155, 89, 182, 0.3); border-radius: 10px; color: #9b59b6; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-magic"></i>
                    {{ __('Generate Strong Password') }}
                </button>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <!-- Success Message -->
                @if (session('status') === 'password-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     style="display: flex; align-items: center; gap: 8px; background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.2); color: #27ae60; padding: 10px 15px; border-radius: 10px; font-size: 0.9rem; animation: slideIn 0.3s ease;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('Password updated successfully!') }}</span>
                </div>
                @endif
                
                <!-- Save Button -->
                <button type="submit" 
                        style="display: flex; align-items: center; gap: 10px; background: linear-gradient(135deg, var(--deep-green), #27ae60); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 500;"
                        id="savePasswordBtn">
                    <i class="fas fa-key"></i>
                    {{ __('Update Password') }}
                </button>
            </div>
        </div>
    </form>
</section>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes requirementCheck {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .requirement.met i {
        color: #27ae60 !important;
        animation: requirementCheck 0.3s ease;
    }
    
    .requirement.met span {
        color: #27ae60 !important;
    }
    
    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 79, 49, 0.3);
    }
    
    button[type="button"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .toggle-password:hover {
        color: var(--gold) !important;
    }
    
    /* Password strength colors */
    .strength-weak {
        background: linear-gradient(90deg, #e74c3c, #e67e22) !important;
    }
    
    .strength-fair {
        background: linear-gradient(90deg, #e67e22, #f39c12) !important;
    }
    
    .strength-good {
        background: linear-gradient(90deg, #f39c12, #27ae60) !important;
    }
    
    .strength-strong {
        background: linear-gradient(90deg, #27ae60, #2ecc71) !important;
    }
    
    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group input {
            padding: 12px 12px 12px 40px !important;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .form-actions > div {
            width: 100%;
            justify-content: space-between;
        }
        
        #passwordRequirements {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
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
    
    // Check password strength
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const requirements = document.querySelectorAll('.requirement');
        
        if (password.length > 0) {
            strengthDiv.style.display = 'block';
            
            let strength = 0;
            let messages = [];
            
            // Check length
            if (password.length >= 8) {
                strength += 25;
                requirements[0].classList.add('met');
                requirements[0].querySelector('i').className = 'fas fa-check-circle';
            } else {
                requirements[0].classList.remove('met');
                requirements[0].querySelector('i').className = 'fas fa-circle';
            }
            
            // Check uppercase and lowercase
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                strength += 25;
                requirements[1].classList.add('met');
                requirements[1].querySelector('i').className = 'fas fa-check-circle';
            } else {
                requirements[1].classList.remove('met');
                requirements[1].querySelector('i').className = 'fas fa-circle';
            }
            
            // Check numbers
            if (/\d/.test(password)) {
                strength += 25;
                requirements[2].classList.add('met');
                requirements[2].querySelector('i').className = 'fas fa-check-circle';
            } else {
                requirements[2].classList.remove('met');
                requirements[2].querySelector('i').className = 'fas fa-circle';
            }
            
            // Check special characters
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                strength += 25;
                requirements[3].classList.add('met');
                requirements[3].querySelector('i').className = 'fas fa-check-circle';
            } else {
                requirements[3].classList.remove('met');
                requirements[3].querySelector('i').className = 'fas fa-circle';
            }
            
            // Update strength bar and text
            strengthBar.style.width = strength + '%';
            
            // Remove all classes
            strengthBar.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');
            
            if (strength <= 25) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'ضعيفة';
                strengthText.style.color = '#e74c3c';
            } else if (strength <= 50) {
                strengthBar.classList.add('strength-fair');
                strengthText.textContent = 'متوسطة';
                strengthText.style.color = '#e67e22';
            } else if (strength <= 75) {
                strengthBar.classList.add('strength-good');
                strengthText.textContent = 'جيدة';
                strengthText.style.color = '#f39c12';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'قوية جداً';
                strengthText.style.color = '#27ae60';
            }
        } else {
            strengthDiv.style.display = 'none';
            
            // Reset requirements
            requirements.forEach(req => {
                req.classList.remove('met');
                req.querySelector('i').className = 'fas fa-circle';
                req.querySelector('i').style.color = '#e74c3c';
            });
        }
        
        // Check password match
        checkPasswordMatch();
    }
    
    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('update_password_password').value;
        const confirmPassword = document.getElementById('update_password_password_confirmation').value;
        const matchDiv = document.getElementById('passwordMatch');
        
        if (confirmPassword.length > 0 && password === confirmPassword) {
            matchDiv.style.display = 'flex';
            document.getElementById('update_password_password_confirmation').style.borderColor = '#27ae60';
        } else {
            matchDiv.style.display = 'none';
            document.getElementById('update_password_password_confirmation').style.borderColor = 'var(--border)';
        }
    }
    
    // Generate strong password
    function generateStrongPassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        let password = '';
        
        // Ensure at least one of each type
        password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
        password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
        password += '0123456789'[Math.floor(Math.random() * 10)];
        password += '!@#$%^&*()_+-=[]{}|;:,.<>?'[Math.floor(Math.random() * 30)];
        
        // Add random characters to reach 12 characters
        for (let i = 4; i < 12; i++) {
            password += chars[Math.floor(Math.random() * chars.length)];
        }
        
        // Shuffle the password
        password = password.split('').sort(() => Math.random() - 0.5).join('');
        
        // Set the password
        const passwordInput = document.getElementById('update_password_password');
        const confirmInput = document.getElementById('update_password_password_confirmation');
        
        passwordInput.value = password;
        confirmInput.value = password;
        passwordInput.type = 'text';
        confirmInput.type = 'text';
        
        // Update UI
        checkPasswordStrength(password);
        checkPasswordMatch();
        
        // Show success message
        showNotification('تم إنشاء كلمة مرور قوية بنجاح', 'success');
        
        // Auto-hide passwords after 5 seconds
        setTimeout(() => {
            passwordInput.type = 'password';
            confirmInput.type = 'password';
        }, 5000);
    }
    
    // Form submission handling
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('update_password_current_password').value;
        const newPassword = document.getElementById('update_password_password').value;
        const confirmPassword = document.getElementById('update_password_password_confirmation').value;
        const saveBtn = document.getElementById('savePasswordBtn');
        
        // Basic validation
        if (!currentPassword) {
            e.preventDefault();
            showNotification('الرجاء إدخال كلمة المرور الحالية', 'error');
            document.getElementById('update_password_current_password').focus();
            return;
        }
        
        if (!newPassword) {
            e.preventDefault();
            showNotification('الرجاء إدخال كلمة المرور الجديدة', 'error');
            document.getElementById('update_password_password').focus();
            return;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            showNotification('كلمات المرور غير متطابقة', 'error');
            document.getElementById('update_password_password_confirmation').focus();
            return;
        }
        
        // Password strength check
        if (newPassword.length < 8) {
            if (!confirm('كلمة المرور الجديدة قصيرة جدًا. هل تريد المتابعة على أي حال؟')) {
                e.preventDefault();
                return;
            }
        }
        
        // Change button state
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Updating...") }}';
        saveBtn.disabled = true;
        saveBtn.style.opacity = '0.8';
        
        // Re-enable after 5 seconds if form submission fails
        setTimeout(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            saveBtn.style.opacity = '1';
        }, 5000);
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
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus current password field
        document.getElementById('update_password_current_password').focus();
        
        // Show success message with animation
        @if (session('status') === 'password-updated')
            setTimeout(() => {
                const successMessage = document.querySelector('[x-data="{ show: true }"]');
                if (successMessage) {
                    successMessage.style.animation = 'slideIn 0.3s ease';
                }
            }, 100);
        @endif
    });
</script>