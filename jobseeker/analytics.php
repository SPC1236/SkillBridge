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

// Sample analytics data - in production, this would come from database
// For now, we'll create realistic demo data
$weekly_views = [12, 8, 15, 22, 18, 25, 30]; // Last 7 days
$monthly_views = [45, 52, 68, 89, 105, 134]; // Last 6 months
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

// Sidebar variables
$applied_jobs = 0;
$saved_jobs = 0;
?>

<!-- Modern Analytics Page -->
<div class="dashboard-container">
    <!-- Sidebar Navigation -->
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
            <a href="#" class="sidebar-link">
                <i class="fas fa-briefcase"></i>
                <span>Applied Jobs</span>
                <span class="badge"><?php echo $applied_jobs; ?></span>
            </a>
            <a href="analytics.php" class="sidebar-link active">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>

              </a>
            <div class="stat-info">
            <div class="stat-value"><?php echo $saved_jobs; ?></div>
            <div class="stat-label">Saved Jobs</div>
            <a href="saved_jobs.php" style="font-size: 0.7rem; color: var(--accent-primary);">View all →</a>
        </div>
        </nav>

        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>

                <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
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

        <!-- Key Metrics Overview -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($profile_views); ?></div>
                    <div class="stat-label">Total Profile Views</div>
                    <small class="trend up"><i class="fas fa-arrow-up"></i> +23% this month</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $profile_views > 0 ? round(($profile_views / max(1, $profile_views)) * 100) : 0; ?></div>
                    <div class="stat-label">Profile Strength</div>
                    <small class="trend up"><i class="fas fa-arrow-up"></i> +8% vs average</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $profile_views > 0 ? rand(5, 20) : 0; ?></div>
                    <div class="stat-label">Times Discovered</div>
                    <small class="trend neutral"><i class="fas fa-info-circle"></i> In search results</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $completion_percent; ?>%</div>
                    <div class="stat-label">Profile Completion</div>
                    <small class="trend <?php echo $completion_percent >= 80 ? 'up' : ($completion_percent >= 50 ? 'neutral' : 'down'); ?>">
                        <?php echo $completion_percent >= 80 ? '<i class="fas fa-check-circle"></i> Excellent' : ($completion_percent >= 50 ? '<i class="fas fa-chart-line"></i> Good' : '<i class="fas fa-exclamation-triangle"></i> Needs work'); ?>
                    </small>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="dashboard-grid-2">
            <!-- Profile Views Chart -->
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

            <!-- Applications Chart -->
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

        <!-- Second Row -->
        <div class="dashboard-grid-2">
            <!-- Profile Completion Impact -->
            <div class="card">
                <div class="card-header">
                    <h3>Profile Completion Impact</h3>
                    <i class="fas fa-info-circle" data-tooltip="Higher profile completion leads to more views" style="color: var(--text-muted); cursor: help;"></i>
                </div>
                <div style="height: 220px; position: relative;">
                    <canvas id="completionChart"></canvas>
                </div>
                <div class="profile-tip">
                    <i class="fas fa-lightbulb"></i>
                    <span>
                        <?php if($completion_percent < 60): ?>
                            Complete your profile to get <strong><?php echo rand(200, 500); ?>% more views</strong>
                        <?php else: ?>
                            Great job! Your complete profile gets <strong><?php echo rand(50, 150); ?>% more views</strong> than average
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- Skill Demand Analysis -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3>In-Demand Skills</h3>
                        <p class="text-muted" style="font-size: 0.75rem; margin: 0.1rem 0 0 0;">Marketplace demand</p>
                    </div>
                </div>
                <div class="skill-demand-list">
                    <?php foreach($skill_demand as $skill): ?>
                        <div class="skill-demand-item">
                            <div class="skill-demand-info">
                                <span class="skill-demand-name"><?php echo htmlspecialchars($skill['skill']); ?></span>
                                <span class="skill-demand-pct"><?php echo $skill['demand']; ?>%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: <?php echo $skill['demand']; ?>%;"></div>
                            </div>
                            <?php if(in_array($skill['skill'], $user_skills)): ?>
                                <span class="skill-has-badge">
                                    <i class="fas fa-check"></i> You have this skill
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Third Row - Monthly Trends -->
        <div class="card" style="margin-bottom: 1.5rem;">
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

        <!-- Insights & Recommendations -->
        <div class="card">
            <div class="card-header" style="margin-bottom: 1.25rem;">
                <h3>AI-Powered Insights</h3>
                <i class="fas fa-robot" style="color: var(--accent-primary); font-size: 1.25rem;"></i>
            </div>
            <div class="insights-grid">
                <div class="insight-card primary">
                    <i class="fas fa-chart-line"></i>
                    <strong class="insight-title">Profile Views</strong>
                    <p class="insight-desc">Your profile is getting <?php echo $profile_views > 50 ? 'above average' : 'average'; ?> views compared to similar freelancers.</p>
                </div>
                <div class="insight-card warning">
                    <i class="fas fa-graduation-cap"></i>
                    <strong class="insight-title">Skill Gap</strong>
                    <p class="insight-desc">Adding React and Node.js to your skills could increase views by 40%.</p>
                </div>
                <div class="insight-card success">
                    <i class="fas fa-calendar"></i>
                    <strong class="insight-title">Best Time to Apply</strong>
                    <p class="insight-desc">Employers are most active on Tuesday and Wednesday mornings.</p>
                </div>
                <div class="insight-card primary">
                    <i class="fas fa-file-alt"></i>
                    <strong class="insight-title">Profile Quality</strong>
                    <p class="insight-desc">
                        <?php echo $completion_percent >= 80 ? 'Excellent! Your profile is fully optimized.' : 'Complete your profile to improve visibility by ' . (100 - $completion_percent) . '%.'; ?>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js Library -->
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
            pointHoverBackgroundColor: '#3b82f6',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#d1d5db',
                borderColor: 'rgba(255, 255, 255, 0.08)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                displayColors: false,
                titleFont: { family: 'Inter', weight: 600 },
                bodyFont: { family: 'Inter' }
            }
        },
        scales: {
            y: {
                grid: {
                    color: 'rgba(75, 85, 99, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 }
                }
            }
        }
    }
});

// Applications Chart
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
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#d1d5db',
                borderColor: 'rgba(255, 255, 255, 0.08)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                displayColors: false,
                titleFont: { family: 'Inter', weight: 600 },
                bodyFont: { family: 'Inter' }
            }
        },
        scales: {
            y: {
                grid: {
                    color: 'rgba(75, 85, 99, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 },
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 }
                }
            }
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
            legend: {
                position: 'bottom',
                labels: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10, weight: 500 },
                    boxWidth: 12,
                    padding: 12
                }
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#d1d5db',
                borderColor: 'rgba(255, 255, 255, 0.08)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                titleFont: { family: 'Inter', weight: 600 },
                bodyFont: { family: 'Inter' },
                callbacks: {
                    label: function(context) {
                        return ` ${context.label}: ${context.raw} average views per month`;
                    }
                }
            }
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
            pointBorderColor: '#1f2937',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointHoverBackgroundColor: '#3b82f6',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 2,
            tension: 0.35,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f9fafb',
                bodyColor: '#d1d5db',
                borderColor: 'rgba(255, 255, 255, 0.08)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                displayColors: false,
                titleFont: { family: 'Inter', weight: 600 },
                bodyFont: { family: 'Inter' }
            }
        },
        scales: {
            y: {
                grid: {
                    color: 'rgba(75, 85, 99, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: { family: 'Inter', size: 10 }
                }
            }
        }
    }
});

// Period selector functionality
document.getElementById('viewPeriod').addEventListener('change', function() {
    const period = this.value;
    let newData;
    let newLabels;
    
    if (period === '7') {
        newData = <?php echo json_encode($weekly_views); ?>;
        newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    } else if (period === '30') {
        // Simulated monthly data
        newData = [45, 52, 48, 61, 58, 67, 72, 68, 75, 82, 79, 85, 88, 92, 89, 95, 98, 102, 108, 112, 115, 118, 120, 125, 128, 132, 135, 138, 140, 142];
        newLabels = Array.from({length: 30}, (_, i) => `Day ${i + 1}`);
    } else {
        newData = [120, 135, 148, 162, 175, 188, 195, 210, 225, 240, 255, 270, 285, 300, 310, 325, 340, 355, 370, 385, 400, 410, 425, 440, 455, 470, 485, 500, 510, 525, 540, 555, 570, 585, 600, 615, 630, 645, 660, 675, 690, 705, 720, 735, 750, 765, 780, 795, 810, 825, 840, 855, 870, 885, 900];
        newLabels = Array.from({length: 90}, (_, i) => `Day ${i + 1}`);
    }
    
    viewsChart.data.labels = newLabels;
    viewsChart.data.datasets[0].data = newData;
    viewsChart.update();
});
</script>

<?php require_once '../includes/footer.php'; ?>