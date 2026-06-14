<?php
$page_title = "Advanced Search";
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';
?>

<!-- Modern Dashboard Layout with Sidebar -->
<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo substr($_SESSION['company_name'] ?? 'E', 0, 1); ?>
            </div>
            <h3><?php echo htmlspecialchars($_SESSION['company_name'] ?? 'Employer'); ?></h3>
            <p class="sidebar-role">Employer</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="browse_seekers.php" class="sidebar-link">
                <i class="fas fa-search"></i>
                <span>Find Talent</span>
            </a>
            <a href="search.php" class="sidebar-link active">
                <i class="fas fa-filter"></i>
                <span>Advanced Search</span>
            </a>
            <a href="profile.php" class="sidebar-link">
                <i class="fas fa-building"></i>
                <span>Company Profile</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-file-alt"></i>
                <span>Posted Jobs</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Applicants</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Page Header -->
        <div class="dashboard-header">
            <div>
                <h1>Advanced Search</h1>
                <p class="text-muted">Find the perfect freelancer for your project</p>
            </div>
            <div class="dashboard-actions">
                <a href="browse_seekers.php" class="btn btn-outline">Simple Browse</a>
                <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
            </div>
        </div>

        <!-- Search Form Card -->
        <div class="card">
            <form method="GET" action="browse_seekers.php">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2rem;">
                    <!-- Left Column: Basic Information -->
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-user-tag" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                            <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">Basic Information</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="search" class="form-label">Name or Title</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search by name or professional title">
                        </div>

                        <div class="form-group">
                            <label for="skills" class="form-label">Skills Required</label>
                            <input type="text" id="skills" name="skills" class="form-control" placeholder="e.g., JavaScript, PHP, UI/UX Design">
                            <small class="form-text">Separate multiple skills with commas</small>
                        </div>
                    </div>

                    <!-- Right Column: Additional Filters -->
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-sliders-h" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                            <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">Additional Filters</h3>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Availability</label>
                            <div class="custom-segmented-control">
                                <label class="segment-item">
                                    <input type="radio" name="availability" value="fulltime">
                                    <span class="segment-label">Full-time</span>
                                </label>
                                <label class="segment-item">
                                    <input type="radio" name="availability" value="parttime">
                                    <span class="segment-label">Part-time</span>
                                </label>
                                <label class="segment-item">
                                    <input type="radio" name="availability" value="both" checked>
                                    <span class="segment-label">Both</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Experience Level</label>
                            <div style="position: relative;">
                                <select name="experience" class="form-control" style="appearance: none; -webkit-appearance: none; padding-right: 2.5rem;">
                                    <option value="">Any experience level</option>
                                    <option value="entry">Entry Level (0-2 years)</option>
                                    <option value="mid">Mid Level (2-5 years)</option>
                                    <option value="senior">Senior Level (5+ years)</option>
                                </select>
                                <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 0.8rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="text-align: center; display: flex; gap: 1rem; justify-content: center;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search Freelancers
                    </button>
                    <button type="reset" class="btn btn-secondary">Clear Filters</button>
                </div>
            </form>
        </div>

        <!-- Search Tips Card -->
        <div class="card" style="margin-top: 1.5rem;">
            <h3 style="margin-bottom: 1rem; color: var(--text-primary);">
                <i class="fas fa-lightbulb" style="color: var(--accent-primary);"></i> Search Tips
            </h3>
            <div class="tips-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="tip-item">
                    <h4 style="margin-bottom: 0.5rem; color: var(--accent-primary);">Use Specific Skills</h4>
                    <p class="text-muted" style="font-size: 0.9rem;">Instead of "design", try "UI/UX Design" or "Graphic Design"</p>
                </div>
                <div class="tip-item">
                    <h4 style="margin-bottom: 0.5rem; color: var(--accent-primary);">Combine Keywords</h4>
                    <p class="text-muted" style="font-size: 0.9rem;">Search for "Web Developer JavaScript React" to find specific expertise</p>
                </div>
                <div class="tip-item">
                    <h4 style="margin-bottom: 0.5rem; color: var(--accent-primary);">Review Profiles</h4>
                    <p class="text-muted" style="font-size: 0.9rem;">Check portfolios and bios to find the best match for your project</p>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Mobile Sidebar Toggle -->
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
/* Custom Layout Pill-segmented options for selector inputs */
.custom-segmented-control {
    display: flex;
    background: var(--bg-input);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    padding: 0.25rem;
    gap: 0.25rem;
}

.segment-item {
    flex: 1;
    text-align: center;
    cursor: pointer;
    position: relative;
}

.segment-item input[type="radio"] {
    position: absolute;
    visibility: hidden;
    opacity: 0;
}

.segment-label {
    display: block;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.2s ease;
}

.segment-item input[type="radio"]:checked + .segment-label {
    background: var(--accent-primary);
    color: white;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
}

/* Dashboard Layout Styles */
.dashboard-container {
    display: flex;
    min-height: calc(100vh - 70px);
    background: var(--bg-primary);
}

.dashboard-sidebar {
    width: 280px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border-light);
    padding: 1.5rem;
    position: sticky;
    top: 70px;
    height: calc(100vh - 70px);
    overflow-y: auto;
    transition: left 0.3s ease;
}

.sidebar-user {
    text-align: center;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.sidebar-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin: 0 auto 1rem;
    color: white;
}

.sidebar-user h3 {
    font-size: 1rem;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.sidebar-role {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar-link i {
    width: 20px;
    font-size: 1.125rem;
}

.sidebar-link:hover {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-primary);
}

.sidebar-link.active {
    background: rgba(16, 185, 129, 0.15);
    color: var(--accent-primary);
    border-left: 3px solid var(--accent-primary);
}

.sidebar-footer {
    margin-top: auto;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.dashboard-main {
    flex: 1;
    padding: 1.5rem;
    overflow-x: auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-header h1 {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.text-muted {
    color: var(--text-muted);
}

.dashboard-actions {
    display: flex;
    gap: 0.75rem;
}

.card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    border-color: var(--accent-primary);
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 0.875rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--bg-input);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: var(--font-primary);
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-text {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
    display: block;
}

.radio-group label {
    cursor: pointer;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: var(--accent-primary);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-hover);
    transform: translateY(-1px);
}

.btn-secondary {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
}

.btn-outline {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border-light);
}

.btn-outline:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
}

.mobile-sidebar-toggle {
    display: none;
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    z-index: 1000;
    background: var(--accent-primary);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.tip-item {
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.tip-item:hover {
    transform: translateY(-2px);
    background: var(--bg-card);
    border: 1px solid var(--accent-primary);
}

.form-actions {
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dashboard-actions {
        width: 100%;
    }
    
    .dashboard-actions .btn {
        flex: 1;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .dashboard-sidebar {
        position: fixed;
        left: -280px;
        z-index: 999;
        top: 0;
        height: 100vh;
    }
    
    .dashboard-sidebar.active {
        left: 0;
    }
    
    .mobile-sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .dashboard-main {
        padding: 1rem;
    }
    
    .card {
        padding: 1rem;
    }
    
    [style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .tips-grid {
        grid-template-columns: 1fr !important;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .dashboard-header h1 {
        font-size: 1.25rem;
    }
}
</style>

<script>
// Mobile sidebar toggle
const mobileToggle = document.getElementById('mobileSidebarToggle');
const sidebar = document.querySelector('.dashboard-sidebar');

if (mobileToggle && sidebar) {
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
}

// Clear filters button functionality
const resetBtn = document.querySelector('button[type="reset"]');
if (resetBtn) {
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('input[type="text"], select').forEach(field => {
            field.value = '';
        });
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            if (radio.value === 'both') {
                radio.checked = true;
            } else {
                radio.checked = false;
            }
        });
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>