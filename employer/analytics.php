<?php
$page_title = "Employer Analytics";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

$db = new Database();
$conn = $db->getConnection();

// Mock data logic - Replace these with your actual SQL queries
// Example: Get count of jobs posted by this employer
// $employer_id = $_SESSION['user_id'];
// $job_count = $conn->query("SELECT COUNT(*) FROM jobs WHERE employer_id = $employer_id")->fetchColumn();

$stats = [
    'total_posts' => 12,
    'total_applications' => 48,
    'avg_response_time' => '2.4 Days',
    'conversion_rate' => '15%'
];
?>

<div class="dashboard-container">
    <!-- Reuse your Sidebar -->
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
        <div class="dashboard-header">
            <div>
                <h1>Recruitment Analytics 📊</h1>
                <p class="text-muted">Track your hiring performance and applicant trends.</p>
            </div>
            <div class="dashboard-actions">
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_posts']; ?></div>
                    <div class="stat-label">Active Job Posts</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['total_applications']; ?></div>
                    <div class="stat-label">Total Applications</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['avg_response_time']; ?></div>
                    <div class="stat-label">Avg. Response Time</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $stats['conversion_rate']; ?></div>
                    <div class="stat-label">Hire Rate</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="dashboard-grid-2">
            <div class="card">
                <div class="card-header">
                    <h3>Application Trends</h3>
                </div>
                <div class="chart-container">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Top Categories Hired</h3>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Mobile Sidebar Toggle -->
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Inheriting your dashboard styles... plus extras for charts */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

@media print {
    .dashboard-sidebar, .mobile-sidebar-toggle, .dashboard-actions { display: none; }
    .dashboard-main { width: 100%; padding: 0; }
}
</style>

<script>
// Mobile toggle logic (same as your dashboard)
document.getElementById('mobileSidebarToggle')?.addEventListener('click', () => {
    document.querySelector('.dashboard-sidebar').classList.toggle('active');
});

// Chart 1: Line Chart for Trends
const ctx1 = document.getElementById('applicationsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Applications Received',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: '#10b981', // Your var(--accent-primary)
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});

// Chart 2: Doughnut for Categories
const ctx2 = document.getElementById('categoryChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Web Dev', 'Design', 'Marketing', 'Writing'],
        datasets: [{
            data: [40, 25, 20, 15],
            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
        }
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>