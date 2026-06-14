<?php
$page_title = "Login";
require_once '../includes/config.php';
require_once '../includes/header.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    switch($_SESSION['user_role']) {
        case 'jobseeker':
            header('Location: ' . SITE_URL . '/jobseeker/dashboard.php');
            exit;
        case 'employer':
            header('Location: ' . SITE_URL . '/employer/dashboard.php');
            exit;
        case 'admin':
            header('Location: ' . SITE_URL . '/admin/dashboard.php');
            exit;
    }
}
?>

<!-- Modern Login Page - Two Column Layout -->
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);">
    <div style="max-width: 1200px; width: 100%; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; background: var(--bg-card); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl); border: 1px solid var(--border-light);">
            
            <!-- Left Side - Branding & Illustration -->
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); padding: 3rem 2rem; display: flex; flex-direction: column; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-rocket" style="color: var(--accent-primary);"></i>
                    </div>
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">Welcome Back!</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">Sign in to access your dashboard and continue your freelance journey.</p>
                    
                    <!-- Features List -->
                    <div style="text-align: left; max-width: 300px; margin: 0 auto;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Access your personalized dashboard</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Manage your profile and portfolio</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Connect with opportunities</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--accent-primary);"></i>
                            <span>Track your progress</span>
                        </div>
                    </div>
                    
                    <!-- Trust Badge -->
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
                        <div style="display: flex; justify-content: center; gap: 1rem;">
                            <i class="fas fa-lock" style="color: var(--text-muted);"></i>
                            <span style="font-size: 0.875rem; color: var(--text-muted);">Secure Login • 256-bit SSL</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div style="padding: 3rem 2rem;">
                <div style="margin-bottom: 2rem; text-align: center;">
                    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Sign In</h1>
                    <p style="color: var(--text-muted);">Enter your credentials to continue</p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
                        <?php 
                        $errors = [
                            'invalid' => 'Invalid email/username or password. Please try again.',
                            'missing' => 'Please fill in all required fields.',
                            'inactive' => 'Your account has been deactivated. Please contact support.'
                        ];
                        echo $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                        ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                        <?php 
                        $success = [
                            'registered' => 'Registration successful! Please login with your credentials.',
                            'logout' => 'You have been logged out successfully.',
                            'profile_updated' => 'Profile updated successfully! Please login again.'
                        ];
                        echo $success[$_GET['success']] ?? 'Action completed successfully!';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="process_login.php" method="POST" id="loginForm" autocomplete="off">
                    <!-- Role Selection -->
                    <div class="form-group">
                        <label for="role" class="form-label">
                            <i class="fas fa-user-tag" style="margin-right: 0.5rem;"></i>
                            Account Type <span class="required">*</span>
                        </label>
                        <select id="role" name="role" class="form-control" required style="cursor: pointer;">
                            <option value="">Select your role</option>
                            <option value="jobseeker" <?php echo isset($_GET['role']) && $_GET['role'] == 'jobseeker' ? 'selected' : ''; ?>>💼 Job Seeker (Freelancer)</option>
                            <option value="employer" <?php echo isset($_GET['role']) && $_GET['role'] == 'employer' ? 'selected' : ''; ?>>🏢 Employer</option>
                            <option value="admin" <?php echo isset($_GET['role']) && $_GET['role'] == 'admin' ? 'selected' : ''; ?>>👑 Administrator</option>
                        </select>
                        <small class="form-text">Select your account type to continue</small>
                    </div>

                    <!-- Email Field (for jobseeker/employer) -->
                    <div class="form-group" id="emailField" style="display: none;">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="Enter your registered email address"
                               autocomplete="off"
                               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                        <small class="form-text">We'll never share your email with anyone else</small>
                    </div>

                    <!-- Username Field (for admin only) -->
                    <div class="form-group" id="usernameField" style="display: none;">
                        <label for="username" class="form-label">
                            <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                            Username <span class="required">*</span>
                        </label>
                        <input type="text" id="username" name="username" class="form-control" 
                               placeholder="Enter administrator username"
                               autocomplete="off"
                               value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                        <small class="form-text">Administrators use username instead of email</small>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>
                            Password <span class="required">*</span>
                        </label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Enter your password" required
                                   autocomplete="new-password"
                                   minlength="6">
                            <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-text">Password must be at least 6 characters long</small>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="remember" style="width: auto; margin-right: 0.5rem;">
                            <span style="font-size: 0.875rem; color: var(--text-secondary);">Remember me</span>
                        </label>
                        <a href="<?php echo SITE_URL; ?>/public/contact.php" style="font-size: 0.875rem; color: var(--accent-primary); text-decoration: none;">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" style="width: 100%;" aria-busy="false">
                            <span id="loginText"><i class="fas fa-sign-in-alt"></i> Sign In</span>
                            <span id="loadingSpinner" class="auth-loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Signing in...</span>
                        </button>
                    </div>
                </form>

                <!-- Social Login Placeholders -->
                <div style="margin-top: 2rem;">
                    <div style="position: relative; text-align: center; margin-bottom: 1.5rem;">
                        <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border-light);"></div>
                        <span style="position: relative; background: var(--bg-card); padding: 0 1rem; color: var(--text-muted); font-size: 0.875rem;">Or continue with</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button class="btn btn-outline" style="width: 100%;" disabled>
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button class="btn btn-outline" style="width: 100%;" disabled>
                            <i class="fab fa-github"></i> GitHub
                        </button>
                    </div>
                </div>

                <!-- Sign Up Links -->
                <div class="auth-footer" style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted);">
                        Don't have an account? 
                        <a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" class="auth-link" style="color: var(--accent-primary);">Join as Freelancer</a> or 
                        <a href="<?php echo SITE_URL; ?>/auth/register_employer.php" class="auth-link" style="color: var(--accent-primary);">Hire Talent</a>
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                        <a href="<?php echo SITE_URL; ?>/public/contact.php" style="color: var(--text-muted); text-decoration: none;">
                            <i class="fas fa-headset"></i> Need help signing in?
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

if (togglePassword && password) {
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
}

function toggleLoginField() {
    const role = document.getElementById('role').value;
    const emailField = document.getElementById('emailField');
    const usernameField = document.getElementById('usernameField');
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    
    // Reset both fields first
    emailField.style.display = 'none';
    usernameField.style.display = 'none';
    emailInput.removeAttribute('required');
    usernameInput.removeAttribute('required');

    // Clear values when switching roles
    emailInput.value = '';
    usernameInput.value = '';
    document.getElementById('password').value = '';
    
    if (role === 'admin') {
        usernameField.style.display = 'block';
        usernameInput.setAttribute('required', 'required');
    } else if (role === 'jobseeker' || role === 'employer') {
        emailField.style.display = 'block';
        emailInput.setAttribute('required', 'required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Hide both fields initially
    document.getElementById('emailField').style.display = 'none';
    document.getElementById('usernameField').style.display = 'none';

    // Clear all fields on page load to prevent cached values showing
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    
    if (emailInput) emailInput.value = '';
    if (usernameInput) usernameInput.value = '';
    if (passwordInput) passwordInput.value = '';
    
    // Set up event listener for role change
    document.getElementById('role').addEventListener('change', toggleLoginField);
    
    // If role is pre-selected via URL parameter, trigger the change
    const urlParams = new URLSearchParams(window.location.search);
    const roleParam = urlParams.get('role');
    if (roleParam) {
        document.getElementById('role').value = roleParam;
        toggleLoginField();
    }
    
    // Form submission handler
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const role = document.getElementById('role').value;
        const loginText = document.getElementById('loginText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        
        if (!role) {
            e.preventDefault();
            alert('Please select your role');
            return;
        }
        
        // Show loading state
        loginText.style.display = 'none';
        loadingSpinner.style.display = 'inline';
        
        // Basic validation
        if (role === 'admin') {
            const username = document.getElementById('username').value;
            if (!username.trim()) {
                e.preventDefault();
                alert('Please enter admin username');
                loginText.style.display = 'inline';
                loadingSpinner.style.display = 'none';
                return;
            }
        } else {
            const email = document.getElementById('email').value;
            if (!email.trim()) {
                e.preventDefault();
                alert('Please enter your email address');
                loginText.style.display = 'inline';
                loadingSpinner.style.display = 'none';
                return;
            }
        }
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .auth-card {
        animation: slideInRight 0.5s ease-out;
    }
    
    .btn-block {
        width: 100%;
    }
    
    .form-control:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
`;
document.head.appendChild(style);
</script>

<?php require_once '../includes/footer.php'; ?>