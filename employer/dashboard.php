<?php
$page_title = "Employer Dashboard";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Get stats for dashboard
$db = new Database();
$conn = $db->getConnection();

$total_freelancers = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE is_active = 1")->fetchColumn();
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
            <a href="dashboard.php" class="sidebar-link active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="browse_seekers.php" class="sidebar-link">
                <i class="fas fa-search"></i>
                <span>Find Talent</span>
            </a>
            <a href="search.php" class="sidebar-link">
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
            <a href="analytics.php" class="sidebar-link">
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
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div>
                <h1>Welcome, <?php echo $_SESSION['company_name']; ?>! 🏢</h1>
                <p class="text-muted">Find and connect with talented freelancers</p>
            </div>
            <div class="dashboard-actions">
                <a href="browse_seekers.php" class="btn btn-primary">Browse Freelancers</a>
                <a href="search.php" class="btn btn-outline">Advanced Search</a>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $total_freelancers; ?></div>
                    <div class="stat-label">Available Freelancers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Contacts Made</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-smile"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-grid-2">
            <div class="card">
                <div class="card-header">
                    <h3>🔍 Find Talent</h3>
                </div>
                <p class="text-muted">Browse through our pool of qualified freelancers.</p>
                <div class="card-actions" style="margin-top: 1rem;">
                    <a href="browse_seekers.php" class="btn btn-primary">Browse All</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>🎯 Advanced Search</h3>
                </div>
                <p class="text-muted">Find specific skills and expertise.</p>
                <div class="card-actions" style="margin-top: 1rem;">
                    <a href="search.php" class="btn btn-outline">Search</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>⭐ Recent Views</h3>
                </div>
                <p class="text-muted">View your recently seen profiles.</p>
                <div class="card-actions" style="margin-top: 1rem;">
                    <a href="browse_seekers.php" class="btn btn-outline">View History</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>Recent Activity</h3>
            </div>
            <div class="activity-placeholder" style="padding: 2rem; text-align: center;">
                <p class="text-muted">
                    No recent activity yet. Start by <a href="browse_seekers.php" style="color: var(--accent-primary);">browsing freelancers</a>.
                </p>
            </div>
        </div>
    </main>
</div>

<!-- Mobile Sidebar Toggle -->
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
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

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--accent-primary);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(16, 185, 129, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent-primary);
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.dashboard-grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
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

.card-header {
    margin-bottom: 1rem;
}

.card-header h3 {
    font-size: 1.125rem;
    margin: 0;
    color: var(--text-primary);
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

/* Responsive */
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
    
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dashboard-stats {
        grid-template-columns: 1fr;
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
</script>

<?php require_once '../includes/footer.php'; ?>