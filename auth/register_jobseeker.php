<?php
$page_title = "Join as Freelancer";
require_once '../includes/config.php';
require_once '../includes/header.php';

if(isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/');
    exit;
}
?>

<!-- Modern Registration Page for Freelancers -->
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);">
    <div style="max-width: 1200px; width: 100%; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; background: var(--bg-card); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid var(--border-light);">
            
            <!-- Left Side - Branding & Benefits -->
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); padding: 3rem 2rem; display: flex; flex-direction: column; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-rocket" style="color: var(--accent-primary);"></i>
                    </div>
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">Start Freelancing</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">Join thousands of freelancers finding great opportunities</p>
                    
                    <div style="text-align: left; max-width: 300px; margin: 0 auto;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Showcase your skills</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Get discovered by employers</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Build your portfolio</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Grow your career</span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
                        <div style="display: flex; justify-content: center; gap: 1rem;">
                            <i class="fas fa-shield-alt" style="color: var(--text-muted);"></i>
                            <span style="font-size: 0.875rem; color: var(--text-muted);">Free to join • No hidden fees</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Registration Form -->
            <div style="padding: 3rem 2rem;">
                <div style="margin-bottom: 2rem; text-align: center;">
                    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Create Freelancer Account</h1>
                    <p style="color: var(--text-muted);">Start your freelance journey today</p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
                        <?php 
                        $errors = [
                            'email_exists' => 'This email is already registered. Please use a different email or <a href="login.php" style="color: var(--accent-primary);">sign in</a>.',
                            'missing' => 'Please fill in all required fields marked with *.',
                            'password' => 'Password must be at least 6 characters long.'
                        ];
                        echo $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="process_register.php" method="POST" id="registerForm">
                    <input type="hidden" name="role" value="jobseeker">
                    
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            <i class="fas fa-user"></i>
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               placeholder="Enter your full name" required
                               value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="you@example.com" required
                               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                        <small class="form-text">We'll send verification and job alerts to this email</small>
                    </div>

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

                    <div class="form-group" style="margin-top: 1rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="terms" required style="width: auto; margin-right: 0.75rem;">
                            <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                I agree to the <a href="#" style="color: var(--accent-primary);">Terms of Service</a> and 
                                <a href="#" style="color: var(--accent-primary);">Privacy Policy</a> <span class="required">*</span>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 1rem;">
                        <i class="fas fa-rocket"></i> Create Freelancer Account
                    </button>
                </form>

                <div class="auth-footer" style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted);">
                        Already have an account? 
                        <a href="login.php" style="color: var(--accent-primary); text-decoration: none; font-weight: 500;">
                            Sign In
                        </a>
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                        Looking to hire? 
                        <a href="register_employer.php" style="color: var(--accent-primary); text-decoration: none;">
                            Post a Job
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle and matching validation (same as employer version)
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

if (confirmPassword && password) {
    confirmPassword.addEventListener('keyup', validatePasswords);
    password.addEventListener('keyup', validatePasswords);
}

function validatePasswords() {
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
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Passwords do not match. Please make sure both passwords are the same.');
        confirmPassword.focus();
        return false;
    }
    
    const terms = document.getElementById('terms');
    if (!terms.checked) {
        e.preventDefault();
        alert('Please accept the Terms of Service and Privacy Policy to continue.');
        terms.focus();
        return false;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
    submitBtn.disabled = true;
    
    return true;
});
</script>

<style>
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

.form-control:focus {
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

@media (max-width: 768px) {
    [style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>