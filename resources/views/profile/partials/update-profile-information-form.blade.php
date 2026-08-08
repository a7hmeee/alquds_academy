<section>
    <header style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--gold), #E4C875); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-user-circle"></i>
            </div>
            <div>
                <h2 class="elegant-text" style="font-size: 1.5rem; color: var(--cream); font-weight: 600;">
                    {{ __('Profile Information') }}
                </h2>
                <p style="color: var(--slate-blue); font-size: 0.9rem; margin-top: 5px;">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
            </div>
        </div>
        
        <!-- User Status Badge -->
        <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(29, 79, 49, 0.1); padding: 6px 15px; border-radius: 20px; margin-top: 10px;">
            <i class="fas fa-shield-alt" style="color: var(--gold); font-size: 0.8rem;"></i>
            <span style="color: var(--cream); font-size: 0.85rem;">
                {{ $user->roles->first()->name ?? __('User') }}
            </span>
            @if($user->email_verified_at)
            <span style="display: flex; align-items: center; gap: 4px; background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">
                <i class="fas fa-check-circle"></i>
                {{ __('Verified') }}
            </span>
            @endif
        </div>
    </header>

    <!-- Verification Form (Hidden) -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Profile Form -->
    <form method="post" action="{{ route('profile.update') }}" id="profileForm">
        @csrf
        @method('patch')

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 30px; margin-bottom: 30px;">
            <!-- Form Header -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                <div style="width: 40px; height: 40px; background: rgba(195, 160, 78, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold);">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 style="color: var(--cream); font-size: 1.1rem; font-weight: 500;">
                    {{ __('Edit Profile') }}
                </h3>
            </div>

            <!-- Name Field -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user" style="color: var(--gold);"></i>
                    {{ __('Name') }}
                </label>
                <div style="position: relative;">
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           required 
                           autofocus 
                           autocomplete="name"
                           style="width: 100%; padding: 15px 15px 15px 45px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                    <i class="fas fa-signature" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
                </div>
                @error('name')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; color: var(--cream); font-weight: 500; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-envelope" style="color: var(--gold);"></i>
                    {{ __('Email') }}
                </label>
                <div style="position: relative;">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           required 
                           autocomplete="username"
                           style="width: 100%; padding: 15px 15px 15px 45px; background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); font-size: 0.95rem; transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 2px rgba(195, 160, 78, 0.1)';"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                    <i class="fas fa-at" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--slate-blue);"></i>
                </div>
                @error('email')
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px; color: #e74c3c; font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror

                <!-- Email Verification -->
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="background: rgba(243, 156, 18, 0.1); border: 1px solid rgba(243, 156, 18, 0.2); border-radius: 10px; padding: 15px; margin-top: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i>
                            <p style="color: var(--cream); font-size: 0.9rem; margin: 0;">
                                {{ __('Your email address is unverified.') }}
                            </p>
                        </div>
                        <button form="send-verification" 
                                style="display: inline-flex; align-items: center; gap: 8px; background: rgba(243, 156, 18, 0.2); border: 1px solid rgba(243, 156, 18, 0.3); color: #f39c12; padding: 8px 15px; border-radius: 8px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease;"
                                onmouseover="this.style.backgroundColor='rgba(243, 156, 18, 0.3)';"
                                onmouseout="this.style.backgroundColor='rgba(243, 156, 18, 0.2)';">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div style="display: flex; align-items: center; gap: 10px; background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.2); border-radius: 10px; padding: 12px 15px; margin-top: 15px;">
                            <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                            <p style="color: #27ae60; font-size: 0.9rem; margin: 0;">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        </div>
                    @endif
                @elseif($user->hasVerifiedEmail())
                    <div style="display: flex; align-items: center; gap: 10px; background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.2); border-radius: 10px; padding: 12px 15px; margin-top: 15px;">
                        <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                        <p style="color: #27ae60; font-size: 0.9rem; margin: 0;">
                            {{ __('Your email address is verified.') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Additional Info Section -->
            <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px;">
                <h4 style="color: var(--cream); font-size: 1rem; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: var(--gold);"></i>
                    {{ __('Account Information') }}
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; padding: 15px;">
                        <div style="color: var(--slate-blue); font-size: 0.85rem; margin-bottom: 5px;">
                            {{ __('Member Since') }}
                        </div>
                        <div style="color: var(--cream); font-weight: 500; font-size: 0.95rem;">
                            {{ $user->created_at->format('Y-m-d') }}
                        </div>
                    </div>
                    
                    <div style="background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; padding: 15px;">
                        <div style="color: var(--slate-blue); font-size: 0.85rem; margin-bottom: 5px;">
                            {{ __('Last Updated') }}
                        </div>
                        <div style="color: var(--cream); font-weight: 500; font-size: 0.95rem;">
                            {{ $user->updated_at->format('Y-m-d') }}
                        </div>
                    </div>
                    
                    <div style="background: rgba(138, 166, 179, 0.05); border: 1px solid var(--border); border-radius: 10px; padding: 15px;">
                        <div style="color: var(--slate-blue); font-size: 0.85rem; margin-bottom: 5px;">
                            {{ __('User ID') }}
                        </div>
                        <div style="color: var(--cream); font-weight: 500; font-size: 0.95rem;">
                            #{{ $user->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <a href="{{ url()->previous() }}" 
                   style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(138, 166, 179, 0.1); border: 1px solid var(--border); border-radius: 10px; color: var(--cream); cursor: pointer; transition: all 0.3s ease; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back') }}
                </a>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <!-- Success Message -->
                @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     style="display: flex; align-items: center; gap: 8px; background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.2); color: #27ae60; padding: 10px 15px; border-radius: 10px; font-size: 0.9rem; animation: slideIn 0.3s ease;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('Saved successfully!') }}</span>
                </div>
                @endif
                
                <!-- Save Button -->
                <button type="submit" 
                        style="display: flex; align-items: center; gap: 10px; background: linear-gradient(135deg, var(--deep-green), #27ae60); color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 500;"
                        id="saveBtn">
                    <i class="fas fa-save"></i>
                    {{ __('Save Changes') }}
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
    
    input:focus {
        outline: none;
    }
    
    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 79, 49, 0.3);
    }
    
    .form-group input:focus {
        background: rgba(138, 166, 179, 0.08) !important;
    }
    
    /* Success message animation */
    [x-show="show"] {
        animation: slideIn 0.3s ease;
    }
    
    /* Responsive adjustments */
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
    }
</style>

<script>
    // Form submission handling
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const saveBtn = document.getElementById('saveBtn');
        const originalText = saveBtn.innerHTML;
        
        // Change button state
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Saving...") }}';
        saveBtn.disabled = true;
        saveBtn.style.opacity = '0.8';
        
        // Re-enable after 5 seconds if form submission fails
        setTimeout(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            saveBtn.style.opacity = '1';
        }, 5000);
    });

    // Input validation
    document.getElementById('name').addEventListener('input', function(e) {
        validateName(this.value);
    });
    
    document.getElementById('email').addEventListener('input', function(e) {
        validateEmail(this.value);
    });

    function validateName(name) {
        const nameInput = document.getElementById('name');
        if (name.length < 2) {
            nameInput.style.borderColor = '#e74c3c';
        } else {
            nameInput.style.borderColor = 'var(--border)';
        }
    }

    function validateEmail(email) {
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            emailInput.style.borderColor = '#e74c3c';
        } else {
            emailInput.style.borderColor = 'var(--border)';
        }
    }

    // Initialize validation on page load
    document.addEventListener('DOMContentLoaded', function() {
        validateName(document.getElementById('name').value);
        validateEmail(document.getElementById('email').value);
        
        // Auto-focus name field if empty
        if (!document.getElementById('name').value.trim()) {
            document.getElementById('name').focus();
        }
    });

    // Show success message with animation
    @if (session('status') === 'profile-updated')
        setTimeout(() => {
            const successMessage = document.querySelector('[x-data="{ show: true }"]');
            if (successMessage) {
                successMessage.style.animation = 'slideIn 0.3s ease';
            }
        }, 100);
    @endif

    // Enhanced form feedback
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = '#e74c3c';
                showFieldError(this.id, 'هذا الحقل مطلوب');
            } else {
                this.style.borderColor = 'var(--border)';
                hideFieldError(this.id);
            }
        });
    });

    function showFieldError(fieldId, message) {
        let errorDiv = document.getElementById(`${fieldId}-error`);
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = `${fieldId}-error`;
            errorDiv.className = 'field-error';
            errorDiv.style.cssText = `
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 8px;
                color: #e74c3c;
                font-size: 0.85rem;
            `;
            
            const input = document.getElementById(fieldId);
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
        
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            ${message}
        `;
    }

    function hideFieldError(fieldId) {
        const errorDiv = document.getElementById(`${fieldId}-error`);
        if (errorDiv) {
            errorDiv.remove();
        }
    }
</script>