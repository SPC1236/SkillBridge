<?php
$page_title = "Analytics & Insights";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Fetch profile data
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM job_seekers WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$profile = $stmt->fetch();

// Calculate profile completion
$fields = [
    'full_name'          => $profile['full_name']          ?? '',
    'professional_title' => $profile['professional_title'] ?? '',
    'phone'              => $profile['phone']              ?? '',
    'skills'             => $profile['skills']             ?? '',
    'bio'                => $profile['bio']                ?? '',
    'portfolio_link'     => $profile['portfolio_link']     ?? '',
];

$filled = 0;
foreach($fields as $value) {
    if(!empty(trim($value))) $filled++;
}
$completion_percent = round(($filled / count($fields)) * 100);

// Get profile views
$profile_views = $profile['profile_views'] ?? 0;

// Sample analytics data
$weekly_views = [12, 8, 15, 22, 18, 25, 30]; 
$monthly_views = [45, 52, 68, 89, 105, 134]; 
$applications_by_week = [2, 3, 5, 4, 7, 6, 8];
$profile_completion_impact = [
    '0-20%' => 5,
    '21-40%' => 12,
    '41-60%' => 28,
    '61-80%' => 56,
    '81-100%' => 134
];

// Skill demand in marketplace
$skill_demand = [
    ['skill' => 'React', 'demand' => 89],
    ['skill' => 'Node.js', 'demand' => 76],
    ['skill' => 'Python', 'demand' => 82],
    ['skill' => 'UI/UX', 'demand' => 71],
    ['skill' => 'PHP', 'demand' => 45],
    ['skill' => 'Laravel', 'demand' => 52],
];

// Get user's skills
$user_skills = !empty($profile['skills']) ? array_map('trim', explode(',', $profile['skills'])) : [];

// Sidebar counters
$applied_jobs = 0;
$saved_jobs = 0;
?>

<div class="dashboard-container">
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo substr($_SESSION['full_name'] ?? 'U', 0, 1); ?>
            </div>
            <h3><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></h3>
            <p class="sidebar-role">Freelancer</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="profile.php" class="sidebar-link">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
            <a href="edit_profile.php" class="sidebar-link">
                <i class="fas fa-edit"></i>
                <span>Edit Profile</span>
            </a>
            <a href="applied_jobs.php" class="sidebar-link">
                <i class="fas fa-briefcase"></i>
                <span>Applied Jobs</span>
                <span class="badge"><?php echo $applied_jobs; ?></span>
            </a>
            <a href="analytics.php" class="sidebar-link active">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
            <div class="stat-info" style="padding: 1rem 0.75rem; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="stat-value"><?php echo $saved_jobs; ?></div>
                <div class="stat-label">Saved Jobs</div>
                <a href="saved_jobs.php" style="font-size: 0.7rem; color: var(--accent-primary); text-decoration: none;">View all →</a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="dashboard-main">
        <div class="dashboard-header">
            <div>
                <h1>Analytics & Insights</h1>
                <p class="text-muted" style="margin-top: 0.15rem;">Track your profile performance and market trends</p>
            </div>
            <div class="dashboard-actions">
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-eye"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($profile_views); ?></div>
                    <div class="stat-label">Total Profile Views</div>
                    <small class="trend up"><i class="fas fa-arrow-up"></i> +23% this month</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $profile_views > 0 ? round(($profile_views / max(1, $profile_views)) * 100) : 0; ?></div>
                    <div class="stat-label">Profile Strength</div>
                    <small class="trend up"><i class="fas fa-arrow-up"></i> +8% vs average</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-search"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $profile_views > 0 ? rand(5, 20) : 0; ?></div>
                    <div class="stat-label">Times Discovered</div>
                    <small class="trend neutral"><i class="fas fa-info-circle"></i> In search results</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-percent"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $completion_percent; ?>%</div>
                    <div class="stat-label">Profile Completion</div>
                    <small class="trend <?php echo $completion_percent >= 80 ? 'up' : ($completion_percent >= 50 ? 'neutral' : 'down'); ?>">
                        <?php echo $completion_percent >= 80 ? '<i class="fas fa-check-circle"></i> Excellent' : ($completion_percent >= 50 ? '<i class="fas fa-chart-line"></i> Good' : '<i class="fas fa-exclamation-triangle"></i> Needs work'); ?>
                    </small>
                </div>
            </div>
        </div>

        <div class="dashboard-grid-2">
            <div class="card chart-card">
                <div class="card-header">
                    <div>
                        <h3>Profile Views Trend</h3>
                        <p class="text-muted" style="font-size: 0.75rem; margin: 0.1rem 0 0 0;">Last 7 days</p>
                    </div>
                    <select id="viewPeriod" class="chart-select">
                        <option value="7">Last 7 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                    </select>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>

            <div class="card chart-card">
                <div class="card-header">
                    <div>
                        <h3>Applications Sent</h3>
                        <p class="text-muted" style="font-size: 0.75rem; margin: 0.1rem 0 0 0;">Weekly activity</p>
                    </div>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="dashboard-grid-2">
            <div class="card">
                <div class="card-header">
                    <h3>Profile Completion Impact</h3>
                    <i class="fas fa-info-circle" data-tooltip="Higher profile completion leads to more views" style="color: var(--text-muted); cursor: help;"></i>
                </div>
                <div style="height: 220px; position: relative;">
                    <canvas id="completionChart"></canvas>
                </div>
                <div class="profile-tip" style="margin-top:1rem; display:flex; align-items:center; gap:0.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-lightbulb" style="color: var(--accent-warning);"></i>
                    <span>
                        <?php if($completion_percent < 60): ?>
                            Complete your profile to get <strong><?php echo rand(200, 500); ?>% more views</strong>
                        <?php else: ?>
                            Great job! Your complete profile gets <strong><?php echo rand(50, 150); ?>% more views</strong> than average
                        <?php endif; ?>
                    </span>
                </div>
                <div style="display: flex; gap: 0.75rem; width: 100%;">
                    <a href="edit_profile.php" class="btn btn-outline" style="flex: 1; text-align:center; padding:0.5rem; border:1px solid var(--accent-primary); color:var(--accent-primary); text-decoration:none; border-radius:6px; font-size: 0.9rem;">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                    <a href="portfolio.php" class="btn btn-primary" style="flex: 1; text-align:center; padding:0.5rem; text-decoration:none; border-radius:6px; font-size: 0.9rem;">
                        <i class="fas fa-plus-circle"></i> Create Portfolio
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <h3>In-Demand Skills</h3>
                        <p class="text-muted" style="font-size: 0.75rem; margin: 0.1rem 0 0 0;">Marketplace demand</p>
                    </div>
                </div>
                <div class="skill-demand-list" style="margin-top: 1rem;">
                    <?php foreach($skill_demand as $skill): ?>
                        <div class="skill-demand-item" style="margin-bottom: 1rem;">
                            <div class="skill-demand-info" style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                <span class="skill-demand-name"><?php echo htmlspecialchars($skill['skill']); ?></span>
                                <span class="skill-demand-pct"><?php echo $skill['demand']; ?>%</span>
                            </div>
                            <div class="progress-bar-container" style="background: rgba(255,255,255,0.05); height: 6px; border-radius: 4px; overflow: hidden;">
                                <div class="progress-bar-fill" style="width: <?php echo $skill['demand']; ?>%; height: 100%; background: var(--accent-primary);"></div>
                            </div>
                            <?php if(in_array($skill['skill'], $user_skills)): ?>
                                <span class="skill-has-badge" style="font-size: 0.7rem; color: #10b981; display: block; margin-top: 0.25rem;">
                                    <i class="fas fa-check"></i> You have this skill
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
            <div class="card-header">
                <div>
                    <h3>Monthly Performance Trend</h3>
                    <p class="text-muted" style="font-size: 0.75rem; margin: 0.1rem 0 0 0;">Profile views over time</p>
                </div>
            </div>
            <div style="height: 280px; position: relative;">
                <canvas id="monthlyViewsChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="margin-bottom: 1.25rem;">
                <h3>AI-Powered Insights</h3>
                <i class="fas fa-robot" style="color: var(--accent-primary); font-size: 1.25rem;"></i>
            </div>
            <div class="insights-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <div class="insight-card primary" style="background: rgba(59,130,246,0.05); padding: 1rem; border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <i class="fas fa-chart-line" style="color:#3b82f6; margin-bottom:0.5rem;"></i><br>
                    <strong class="insight-title">Profile Views</strong>
                    <p class="insight-desc" style="font-size:0.8rem; margin-top:0.25rem; color:var(--text-muted);">Your profile is getting <?php echo $profile_views > 50 ? 'above average' : 'average'; ?> views compared to similar freelancers.</p>
                </div>
                <div class="insight-card warning" style="background: rgba(245,158,11,0.05); padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <i class="fas fa-graduation-cap" style="color:#f59e0b; margin-bottom:0.5rem;"></i><br>
                    <strong class="insight-title">Skill Gap</strong>
                    <p class="insight-desc" style="font-size:0.8rem; margin-top:0.25rem; color:var(--text-muted);">Adding React and Node.js to your skills could increase views by 40%.</p>
                </div>
                <div class="insight-card success" style="background: rgba(16,185,129,0.05); padding: 1rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <i class="fas fa-calendar" style="color:#10b981; margin-bottom:0.5rem;"></i><br>
                    <strong class="insight-title">Best Time to Apply</strong>
                    <p class="insight-desc" style="font-size:0.8rem; margin-top:0.25rem; color:var(--text-muted);">Employers are most active on Tuesday and Wednesday mornings.</p>
                </div>
                <div class="insight-card primary" style="background: rgba(59,130,246,0.05); padding: 1rem; border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <i class="fas fa-file-alt" style="color:#3b82f6; margin-bottom:0.5rem;"></i><br>
                    <strong class="insight-title">Profile Quality</strong>
                    <p class="insight-desc" style="font-size:0.8rem; margin-top:0.25rem; color:var(--text-muted);">
                        <?php echo $completion_percent >= 80 ? 'Excellent! Your profile is fully optimized.' : 'Complete your profile to improve visibility by ' . (100 - $completion_percent) . '%.'; ?>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Profile Views Chart (Last 7 days)
const viewsCtx = document.getElementById('viewsChart').getContext('2d');
let gradientViews = viewsCtx.createLinearGradient(0, 0, 0, 250);
gradientViews.addColorStop(0, 'rgba(59, 130, 246, 0.22)');
gradientViews.addColorStop(1, 'rgba(59, 130, 246, 0)');

let viewsChart = new Chart(viewsCtx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Profile Views',
            data: <?php echo json_encode($weekly_views); ?>,
            borderColor: '#3b82f6',
            backgroundColor: gradientViews,
            borderWidth: 2.5,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#1f2937',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(75, 85, 99, 0.1)' }, ticks: { color: '#9ca3af' } },
            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
        }
    }
});

// Applications Sent Chart
const appsCtx = document.getElementById('applicationsChart').getContext('2d');
let gradientApps = appsCtx.createLinearGradient(0, 0, 0, 250);
gradientApps.addColorStop(0, 'rgba(59, 130, 246, 0.85)');
gradientApps.addColorStop(1, 'rgba(59, 130, 246, 0.15)');

new Chart(appsCtx, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Applications Sent',
            data: <?php echo json_encode($applications_by_week); ?>,
            backgroundColor: gradientApps,
            borderColor: '#3b82f6',
            borderWidth: 1.5,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(75, 85, 99, 0.1)' }, ticks: { color: '#9ca3af', stepSize: 1 } },
            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
        }
    }
});

// Profile Completion Impact Chart
const completionCtx = document.getElementById('completionChart').getContext('2d');
new Chart(completionCtx, {
    type: 'doughnut',
    data: {
        labels: ['0-20% Complete', '21-40% Complete', '41-60% Complete', '61-80% Complete', '81-100% Complete'],
        datasets: [{
            data: <?php echo json_encode(array_values($profile_completion_impact)); ?>,
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(59, 130, 246, 0.6)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(37, 99, 235, 0.95)'
            ],
            borderColor: '#1f2937',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { color: '#9ca3af', boxWidth: 12 } }
        }
    }
});

// Monthly Views Chart
const monthlyCtx = document.getElementById('monthlyViewsChart').getContext('2d');
let gradientMonthly = monthlyCtx.createLinearGradient(0, 0, 0, 250);
gradientMonthly.addColorStop(0, 'rgba(59, 130, 246, 0.22)');
gradientMonthly.addColorStop(1, 'rgba(59, 130, 246, 0)');

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Profile Views',
            data: <?php echo json_encode($monthly_views); ?>,
            borderColor: '#3b82f6',
            backgroundColor: gradientMonthly,
            borderWidth: 3,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 5,
            tension: 0.35,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(75, 85, 99, 0.1)' }, ticks: { color: '#9ca3af' } },
            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
        }
    }
});

// Period selector dynamic filter
document.getElementById('viewPeriod').addEventListener('change', function() {
    const period = this.value;
    let newData;
    let newLabels;
    
    if (period === '7') {
        newData = <?php echo json_encode($weekly_views); ?>;
        newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    } else if (period === '30') {
        newData = [45, 52, 48, 61, 58, 67, 72, 68, 75, 82, 79, 85, 88, 92, 89, 95, 98, 102, 108, 112, 115, 118, 120, 125, 128, 132, 135, 138, 140, 142];
        newLabels = Array.from({length: 30}, (_, i) => `Day ${i + 1}`);
    } else {
        newData = [120, 135, 148, 162, 175, 188, 195, 210, 225, 240, 255, 270, 285, 300, 310, 325, 340, 355, 370, 385, 400, 410, 425, 440, 455, 470, 485, 500];
        newLabels = Array.from({length: 50}, (_, i) => `Day ${i + 1}`);
    }
    
    viewsChart.data.labels = newLabels;
    viewsChart.data.datasets[0].data = newData;
    viewsChart.update();
});
</script>

<?php require_once '../includes/footer.php'; ?>