<?php
$page_title = "Join as Employer";
require_once '../includes/config.php';
require_once '../includes/header.php';

if(isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/');
    exit;
}
?>

<!-- Modern Registration Page - Two Column Layout -->
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);">
    <div style="max-width: 1200px; width: 100%; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; background: var(--bg-card); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid var(--border-light);">
            
            <!-- Left Side - Branding & Benefits -->
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); padding: 3rem 2rem; display: flex; flex-direction: column; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-building" style="color: var(--accent-primary);"></i>
                    </div>
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">Hire Top Talent</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">Join thousands of companies finding skilled freelancers</p>
                    
                    <!-- Benefits List -->
                    <div style="text-align: left; max-width: 300px; margin: 0 auto;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Access to 10,000+ freelancers</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Post unlimited jobs</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Advanced filtering system</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Direct messaging</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>No upfront fees</span>
                        </div>
                    </div>
                    
                    <!-- Trust Badge -->
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
                        <div style="display: flex; justify-content: center; gap: 1rem;">
                            <i class="fas fa-shield-alt" style="color: var(--text-muted);"></i>
                            <span style="font-size: 0.875rem; color: var(--text-muted);">Secure Registration • GDPR Compliant</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Registration Form -->
            <div style="padding: 3rem 2rem;">
                <div style="margin-bottom: 2rem; text-align: center;">
                    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Create Employer Account</h1>
                    <p style="color: var(--text-muted);">Start hiring top talent today</p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
                        <?php 
                        $errors = [
                            'email_exists' => 'This email is already registered. Please use a different email or <a href="login.php" style="color: var(--accent-primary);">sign in</a>.',
                            'missing' => 'Please fill in all required fields marked with *.',
                            'password' => 'Password must be at least 6 characters long.',
                            'database_error' => 'Registration failed. Please try again later.'
                        ];
                        echo $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="process_register.php" method="POST" id="registerForm">
                    <input type="hidden" name="role" value="employer">
                    
                    <!-- Company Information Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-building"></i> Company Information
                        </h3>
                        
                        <div class="form-group">
                            <label for="company_name" class="form-label">
                                <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
                                Company Name <span class="required">*</span>
                            </label>
                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                   placeholder="Enter your company name" required
                                   value="<?php echo isset($_GET['company']) ? htmlspecialchars($_GET['company']) : ''; ?>">
                            <small class="form-text">This will be visible to freelancers</small>
                        </div>

                        <div class="form-group">
                            <label for="company_phone" class="form-label">
                                <i class="fas fa-phone"></i> Company Phone
                            </label>
                            <input type="tel" id="company_phone" name="company_phone" class="form-control" 
                                   placeholder="+1 234 567 8900"
                                   value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
                            <small class="form-text">Optional but recommended for verification</small>
                        </div>
                    </div>

                    <!-- Contact Person Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-user-tie"></i> Contact Information
                        </h3>
                        
                        <div class="form-group">
                            <label for="contact_person" class="form-label">
                                <i class="fas fa-user"></i>
                                Contact Person <span class="required">*</span>
                            </label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control" 
                                   placeholder="Full name of contact person" required
                                   value="<?php echo isset($_GET['contact']) ? htmlspecialchars($_GET['contact']) : ''; ?>">
                            <small class="form-text">Who should freelancers contact?</small>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   placeholder="company@example.com" required
                                   value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                            <small class="form-text">We'll send verification and notifications to this email</small>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-lock"></i> Security
                        </h3>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i>
                                Password <span class="required">*</span>
                            </label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control" 
                                       placeholder="Create a password (min. 6 characters)" 
                                       minlength="6" required>
                                <button type="button" id="togglePassword" class="password-toggle" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text">Must be at least 6 characters long</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-check-circle"></i>
                                Confirm Password <span class="required">*</span>
                            </label>
                            <div style="position: relative;">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                       placeholder="Confirm your password" required>
                                <span id="passwordMatch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></span>
                            </div>
                            <small class="form-text" id="passwordMatchText"></small>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="form-group" style="margin-top: 1rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="terms" required style="width: auto; margin-right: 0.75rem;">
                            <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                I agree to the <a href="#" style="color: var(--accent-primary);">Terms of Service</a> and 
                                <a href="#" style="color: var(--accent-primary);">Privacy Policy</a> <span class="required">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 1rem;">
                        <i class="fas fa-rocket"></i> Create Employer Account
                    </button>
                </form>

                <!-- Login Links -->
                <div class="auth-footer" style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted);">
                        Already have an account? 
                        <a href="login.php" style="color: var(--accent-primary); text-decoration: none; font-weight: 500;">
                            Sign In
                        </a>
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                        Looking for work? 
                        <a href="register_jobseeker.php" style="color: var(--accent-primary); text-decoration: none;">
                            Join as Freelancer
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm_password');
const passwordMatchText = document.getElementById('passwordMatchText');
const passwordMatchIcon = document.getElementById('passwordMatch');

if (togglePassword && password) {
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
}

// Real-time password matching validation
if (confirmPassword && password) {
    confirmPassword.addEventListener('keyup', function() {
        if (confirmPassword.value.length > 0) {
            if (password.value === confirmPassword.value) {
                passwordMatchIcon.innerHTML = '<i class="fas fa-check-circle" style="color: var(--accent-success);"></i>';
                passwordMatchText.innerHTML = 'Passwords match!';
                passwordMatchText.style.color = 'var(--accent-success)';
                confirmPassword.style.borderColor = 'var(--accent-success)';
            } else {
                passwordMatchIcon.innerHTML = '<i class="fas fa-times-circle" style="color: var(--accent-danger);"></i>';
                passwordMatchText.innerHTML = 'Passwords do not match';
                passwordMatchText.style.color = 'var(--accent-danger)';
                confirmPassword.style.borderColor = 'var(--accent-danger)';
            }
        } else {
            passwordMatchIcon.innerHTML = '';
            passwordMatchText.innerHTML = '';
            confirmPassword.style.borderColor = '';
        }
    });
    
    password.addEventListener('keyup', function() {
        if (confirmPassword.value.length > 0) {
            if (password.value === confirmPassword.value) {
                passwordMatchIcon.innerHTML = '<i class="fas fa-check-circle" style="color: var(--accent-success);"></i>';
                passwordMatchText.innerHTML = 'Passwords match!';
                passwordMatchText.style.color = 'var(--accent-success)';
                confirmPassword.style.borderColor = 'var(--accent-success)';
            } else {
                passwordMatchIcon.innerHTML = '<i class="fas fa-times-circle" style="color: var(--accent-danger);"></i>';
                passwordMatchText.innerHTML = 'Passwords do not match';
                passwordMatchText.style.color = 'var(--accent-danger)';
                confirmPassword.style.borderColor = 'var(--accent-danger)';
            }
        }
    });
}

// Form validation before submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
    // Check if passwords match
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Passwords do not match. Please make sure both passwords are the same.');
        confirmPassword.focus();
        return false;
    }
    
    // Check if terms are accepted
    const terms = document.getElementById('terms');
    if (!terms.checked) {
        e.preventDefault();
        alert('Please accept the Terms of Service and Privacy Policy to continue.');
        terms.focus();
        return false;
    }
    
    // Show loading state on button
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
    submitBtn.disabled = true;
    
    return true;
});
</script>

<style>
/* Registration page specific styles */
.form-section {
    margin-bottom: 1.5rem;
}

.form-section-title {
    font-size: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
}

.form-section-title i {
    color: var(--accent-primary);
    font-size: 1rem;
}

.required {
    color: var(--accent-danger);
    font-weight: bold;
}

.form-text {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
    display: block;
}

/* Password strength indicator (can be enhanced later) */
.password-strength {
    height: 4px;
    background: var(--bg-secondary);
    border-radius: var(--radius-sm);
    margin-top: 0.5rem;
    overflow: hidden;
}

.password-strength-fill {
    height: 100%;
    width: 0%;
    transition: width var(--transition-base);
}

/* Focus states for better UX */
.form-control:focus {
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Animation for form sections */
.form-section {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    [style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    
    .form-section {
        margin-bottom: 1rem;
    }
    
    .password-toggle {
        right: 12px !important;
    }
}

/* Autofill styles */
input:-webkit-autofill,
input:-webkit-autofill:focus {
    transition: background-color 600000s 0s, color 600000s 0s;
}
</style>

<?php require_once '../includes/footer.php'; ?>