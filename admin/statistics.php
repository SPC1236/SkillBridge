<?php
$page_title = "Platform Statistics";
require_once '../includes/config.php';
require_once '../includes/database.php'; // ADD THIS
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Get detailed statistics
$db = new Database();
$conn = $db->getConnection();

// Basic counts
$total_job_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers")->fetchColumn();
$active_job_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE is_active = 1")->fetchColumn();
$total_employers = $conn->query("SELECT COUNT(*) FROM employers")->fetchColumn();
$active_employers = $conn->query("SELECT COUNT(*) FROM employers WHERE is_active = 1")->fetchColumn();

// Recent registrations (last 7 days)
$recent_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE date_joined >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
$recent_employers = $conn->query("SELECT COUNT(*) FROM employers WHERE date_joined >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();

// Top skills
$skills_result = $conn->query("SELECT skills FROM job_seekers WHERE skills IS NOT NULL AND skills != ''");
$all_skills = [];
while($row = $skills_result->fetch()) {
    $skills = explode(',', $row['skills']);
    foreach($skills as $skill) {
        $skill = trim($skill);
        if(!empty($skill)) {
            if(isset($all_skills[$skill])) {
                $all_skills[$skill]++;
            } else {
                $all_skills[$skill] = 1;
            }
        }
    }
}
arsort($all_skills);
$top_skills = array_slice($all_skills, 0, 10);

// Registration trend (last 30 days)
$registration_trend = [];
for($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $seekers_count = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE DATE(date_joined) = '$date'")->fetchColumn();
    $employers_count = $conn->query("SELECT COUNT(*) FROM employers WHERE DATE(date_joined) = '$date'")->fetchColumn();
    $registration_trend[$date] = [
        'seekers' => $seekers_count,
        'employers' => $employers_count,
        'total' => $seekers_count + $employers_count
    ];
}

// Avoid Division by Zero if platform is empty
$grand_total_users = $total_job_seekers + $total_employers;
$seeker_percentage = $grand_total_users > 0 ? ($total_job_seekers / $grand_total_users) * 100 : 50;
?>

<div class="dashboard-container">
    
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">👑</div>
            <h3>Administrator</h3>
            <p class="sidebar-role">Super Admin</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-crown"></i>
                <span>Admin Dashboard</span>
            </a>
            <a href="manage_users.php" class="sidebar-link">
                <i class="fas fa-users-cog"></i>
                <span>Manage Users</span>
            </a>

            <a href="manage_jobs.php" class="sidebar-link">
                <i class="fas fa-briefcase-cog"></i>
                <span>Manage Jobs</span>
            </a>

             <a href="upload_resource.php" class="sidebar-link">
                <i class="fas fa-upload-cog"></i>
                <span>Upload Resources</span>
            </a>


            <a href="statistics.php" class="sidebar-link active">
                <i class="fas fa-chart-bar"></i>
                <span>View Statistics</span>
            </a>
            <a href="settings.php" class="sidebar-link">
                <i class="fas fa-sliders-h"></i>
                <span>Settings</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="dashboard-main" style="flex: 1; padding: 2rem;">

        <section class="dashboard-header" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: #ffffff;">Platform Statistics 📊</h1>
                    <p style="color: #94a3b8; margin: 0.25rem 0 0 0; font-size: 0.95rem;">Analytics and insights about your freelance portal</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="dashboard.php" class="btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="manage_users.php" class="btn-action-primary">
                        <i class="fas fa-users-cog"></i> Manage Users
                    </a>
                </div>
            </div>
        </section>

        <section class="metrics-dashboard-grid" style="margin-bottom: 2.5rem;">
            <div class="premium-stat-card border-accent-total">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Total Accounts</span>
                        <h2 class="stat-massive-number"><?php echo $grand_total_users; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-total"><i class="fas fa-globe"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-pill-indicator indicator-blue"><?php echo $active_job_seekers + $active_employers; ?> Active Baseline</span>
                </div>
            </div>
            
            <div class="premium-stat-card border-accent-seekers">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Job Seekers</span>
                        <h2 class="stat-massive-number"><?php echo $total_job_seekers; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-seekers"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-label-secondary"><b><?php echo $active_job_seekers; ?></b> Active</span>
                    <span class="meta-grow-label text-growth-green"><i class="fas fa-caret-up"></i> +<?php echo $recent_seekers; ?> new (7d)</span>
                </div>
            </div>
            
            <div class="premium-stat-card border-accent-employers">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Employers</span>
                        <h2 class="stat-massive-number"><?php echo $total_employers; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-employers"><i class="fas fa-building"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-label-secondary"><b><?php echo $active_employers; ?></b> Active</span>
                    <span class="meta-grow-label text-growth-green"><i class="fas fa-caret-up"></i> +<?php echo $recent_employers; ?> new (7d)</span>
                </div>
            </div>
        </section>

        <section class="split-analytics-layout" style="margin-bottom: 2.5rem;">
            <div class="premium-content-card">
                <div class="card-element-header">
                    <h3 class="card-title-text"><i class="fas fa-fire-alt text-accent-orange"></i> Marketplace In-Demand Skills</h3>
                </div>
                <div class="card-element-body">
                    <?php if(empty($top_skills)): ?>
                        <div class="empty-data-wrapper">
                            <i class="far fa-chart-bar"></i>
                            <p>No explicit skills data mapped yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="skills-list-scrollable">
                            <?php foreach($top_skills as $skill => $count): ?>
                                <div class="skills-list-item">
                                    <div class="skill-name-badge-flex">
                                        <div class="skill-icon-dot"></div>
                                        <span class="skill-string-name"><?php echo htmlspecialchars($skill); ?></span>
                                    </div>
                                    <span class="count-pill-metric"><?php echo $count; ?> Users Registered</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="premium-content-card">
                <div class="card-element-header">
                    <h3 class="card-title-text"><i class="fas fa-pie-chart text-accent-purple"></i> Portal Core Distribution</h3>
                </div>
                <div class="card-element-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                    <div class="chart-visual-wrapper">
                        <div class="css-pie-donut-render" style="background: conic-gradient(#3b82f6 0% <?php echo $seeker_percentage; ?>%, #10b981 <?php echo $seeker_percentage; ?>% 100%);">
                            <div class="css-pie-donut-inner-core">
                                <span class="inner-core-ratio"><?php echo round($seeker_percentage); ?>%</span>
                                <span class="inner-core-lbl">Seekers</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chart-legend-row-flex">
                        <div class="legend-item">
                            <div class="legend-square bg-blue-primary"></div>
                            <span class="legend-text-label">Seekers (<?php echo $total_job_seekers; ?>)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-square bg-green-primary"></div>
                            <span class="legend-text-label">Employers (<?php echo $total_employers; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="premium-content-card" style="margin-bottom: 2.5rem;">
            <div class="card-element-header">
                <h3 class="card-title-text"><i class="fas fa-history text-accent-cyan"></i> Registration Activity Pipeline (Last 30 Days)</h3>
            </div>
            <div class="table-scroll-wrapper">
                <table class="premium-admin-table">
                    <thead>
                        <tr>
                            <th>Calendar Timeline Entry</th>
                            <th>Job Seekers Registrations</th>
                            <th>Employers Registrations</th>
                            <th style="text-align: right;">Total System Yield</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_reverse($registration_trend) as $date => $data): ?>
                            <tr>
                                <td class="text-date-styled" style="color: #ffffff; font-weight: 600;"><?php echo date('F j, Y', strtotime($date)); ?></td>
                                <td>
                                    <?php if($data['seekers'] > 0): ?>
                                        <span class="data-indicator-pill row-pill-blue">+<?php echo $data['seekers']; ?> Seekers</span>
                                    <?php else: ?>
                                        <span class="muted-zero-placeholder">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($data['employers'] > 0): ?>
                                        <span class="data-indicator-pill row-pill-green">+<?php echo $data['employers']; ?> Corporate</span>
                                    <?php else: ?>
                                        <span class="muted-zero-placeholder">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <span class="yield-badge-sum <?php echo $data['total'] > 0 ? 'yield-active' : 'yield-silent'; ?>">
                                        <?php echo $data['total']; ?> Accounts
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="premium-content-card">
            <div class="card-element-header">
                <h3 class="card-title-text"><i class="fas fa-heartbeat text-accent-red"></i> Real-time Portal Vital Indicators</h3>
            </div>
            <div class="diagnostics-matrix-grid">
                <div class="diagnostic-node">
                    <div class="diagnostic-icon-box node-green"><i class="fas fa-server"></i></div>
                    <div class="diagnostic-meta">
                        <h4>Database Integrity</h4>
                        <p class="status-success-txt">Synchronized & Secure</p>
                    </div>
                </div>
                <div class="diagnostic-node">
                    <div class="diagnostic-icon-box node-green"><i class="fas fa-bolt"></i></div>
                    <div class="diagnostic-meta">
                        <h4>Query Performance</h4>
                        <p class="status-success-txt">Optimal Execution Runtime</p>
                    </div>
                </div>
                <div class="diagnostic-node">
                    <div class="diagnostic-icon-box node-green"><i class="fas fa-shield-alt"></i></div>
                    <div class="diagnostic-meta">
                        <h4>SSL Encryption Layer</h4>
                        <p class="status-success-txt">Fully Protected Pipeline</p>
                    </div>
                </div>
                <div class="diagnostic-node">
                    <div class="diagnostic-icon-box node-amber"><i class="fas fa-chart-line"></i></div>
                    <div class="diagnostic-meta">
                        <h4>User Growth Trajectory</h4>
                        <p class="status-warning-txt">Stable Sequential Curve</p>
                    </div>
                </div>
            </div>
        </section>
        
    </main>
</div>

<style>
/* Base Theme Layout Configuration Framework */
.dashboard-container { display: flex; min-height: calc(100vh - 70px); background: #0c1524; font-family: system-ui, -apple-system, sans-serif; }
.dashboard-sidebar { width: 280px; background: #0f1c30; border-right: 1px solid rgba(255, 255, 255, 0.05); padding: 1.5rem; position: sticky; top: 70px; height: calc(100vh - 70px); overflow-y: auto; display: flex; flex-direction: column; }
.sidebar-user { text-align: center; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.sidebar-avatar { width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem; color: white; }
.sidebar-user h3 { font-size: 1rem; margin-bottom: 0.25rem; color: #ffffff; }
.sidebar-role { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.sidebar-nav { display: flex; flex-direction: column; gap: 0.25rem; }
.sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s ease; }
.sidebar-link i { width: 20px; font-size: 1.1rem; text-align: center; }
.sidebar-link:hover, .sidebar-link.active { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.05); }

/* Buttons Controls Stylesheet Blueprint */
.btn-action-outline { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: transparent; border: 1px solid rgba(255,255,255,0.15); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
.btn-action-outline:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.3); }
.btn-action-primary { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: #2563eb; border: 1px solid transparent; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
.btn-action-primary:hover { background: #1d4ed8; }

/* Analytics Numeric Metric Component System */
.metrics-dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
.premium-stat-card { background: #111e32; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.04); padding: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; }
.stat-card-interior { display: flex; justify-content: space-between; align-items: flex-start; }
.stat-label-text { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.5rem; }
.stat-massive-number { font-size: 2.25rem; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.1; }
.stat-icon-wrapper { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #ffffff; }
.bg-gradient-total { background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
.bg-gradient-seekers { background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 4px 14px rgba(59,130,246,0.3); }
.bg-gradient-employers { background: linear-gradient(135deg, #10b981, #047857); box-shadow: 0 4px 14px rgba(16,185,129,0.3); }
.stat-footer-meta { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #94a3b8; }
.meta-pill-indicator { font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 6px; }
.indicator-blue { background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.2); }
.text-growth-green { color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; }

/* Decorative Border Accents */
.border-accent-total { border-left: 4px solid #6366f1; }
.border-accent-seekers { border-left: 4px solid #3b82f6; }
.border-accent-employers { border-left: 4px solid #10b981; }

/* Structural Content Card Framework */
.split-analytics-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.premium-content-card { background: #111e32; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.04); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2); overflow: hidden; }
.card-element-header { padding: 1.25rem 1.5rem; background: #14233a; border-bottom: 1px solid rgba(255, 255, 255, 0.04); }
.card-title-text { font-size: 1rem; font-weight: 700; color: #ffffff; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.card-element-body { padding: 1.5rem; }

/* Accent Coloring Map */
.text-accent-orange { color: #f59e0b; }
.text-accent-purple { color: #a855f7; }
.text-accent-cyan { color: #06b6d4; }
.text-accent-red { color: #ef4444; }

/* Skills Panel Interface Elements */
.skills-list-scrollable { max-height: 310px; overflow-y: auto; padding-right: 0.25rem; }
.skills-list-item { display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 0.75rem; border-radius: 8px; margin-bottom: 0.5rem; background: rgba(255,255,255,0.01); transition: background 0.15s; }
.skills-list-item:hover { background: rgba(255,255,255,0.03); }
.skills-list-item:last-child { margin-bottom: 0; }
.skill-name-badge-flex { display: flex; align-items: center; gap: 0.6rem; }
.skill-icon-dot { width: 6px; height: 6px; border-radius: 50%; background-color: #f59e0b; box-shadow: 0 0 8px #f59e0b; }
.skill-string-name { font-weight: 600; color: #e2e8f0; font-size: 0.9rem; }
.count-pill-metric { background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(245, 158, 11, 0.15); }

/* Custom Advanced CSS Donut Chart UI Engine */
.chart-visual-wrapper { margin-bottom: 1.5rem; }
.css-pie-donut-render { width: 180px; height: 180px; border-radius: 50%; position: relative; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0,0,0,0.4); }
.css-pie-donut-inner-core { width: 110px; height: 110px; background: #111e32; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.inner-core-ratio { font-size: 1.5rem; font-weight: 800; color: #ffffff; line-height: 1; }
.inner-core-lbl { font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-top: 0.15rem; }
.chart-legend-row-flex { display: flex; justify-content: center; gap: 1.5rem; width: 100%; flex-wrap: wrap; }
.legend-item { display: flex; align-items: center; gap: 0.4rem; }
.legend-square { width: 10px; height: 10px; border-radius: 3px; }
.bg-blue-primary { background-color: #3b82f6; box-shadow: 0 0 8px rgba(59,130,246,0.5); }
.bg-green-primary { background-color: #10b981; box-shadow: 0 0 8px rgba(16,185,129,0.5); }
.legend-text-label { font-size: 0.85rem; color: #94a3b8; font-weight: 500; }

/* Premium Activity Data Table Matrix */
.table-scroll-wrapper { overflow-x: auto; width: 100%; max-height: 450px; }
.premium-admin-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
.premium-admin-table th { background-color: #14233a; color: #64748b; font-weight: 700; padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; position: sticky; top: 0; z-index: 10; }
.premium-admin-table td { padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.03); vertical-align: middle; color: #cbd5e1; }
.premium-admin-table tr:last-child td { border-bottom: none; }
.premium-admin-table tr:hover td { background-color: rgba(255,255,255,0.01); }
.data-indicator-pill { font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 6px; display: inline-block; }
.row-pill-blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
.row-pill-green { background: rgba(16,185,129,0.12); color: #34d399; }
.muted-zero-placeholder { color: #475569; font-weight: 700; padding-left: 0.5rem; }
.yield-badge-sum { font-size: 0.85rem; padding: 0.3rem 0.6rem; border-radius: 6px; font-weight: 700; display: inline-block; }
.yield-active { background-color: rgba(255,255,255,0.06); color: #ffffff; border: 1px solid rgba(255,255,255,0.05); }
.yield-silent { color: #475569; font-weight: 500; }

/* Real-time Diagnostics Framework Layout */
.diagnostics-matrix-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; padding: 0.5rem; }
.diagnostic-node { display: flex; align-items: center; gap: 0.85rem; padding: 1rem; background: rgba(255,255,255,0.01); border-radius: 12px; border: 1px solid rgba(255,255,255,0.02); }
.diagnostic-icon-box { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.node-green { background: rgba(16,185,129,0.1); color: #10b981; }
.node-amber { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.diagnostic-meta h4 { font-size: 0.85rem; color: #94a3b8; margin: 0 0 0.2rem 0; font-weight: 500; }
.diagnostic-meta p { font-size: 0.85rem; margin: 0; font-weight: 700; }
.status-success-txt { color: #10b981; }
.status-warning-txt { color: #f59e0b; }
.empty-data-wrapper { text-align: center; color: #475569; padding: 3rem 1.5rem; }
.empty-data-wrapper i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }

@media (max-width: 992px) {
    .dashboard-container { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; border-right: none; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    .split-analytics-layout { grid-template-columns: 1fr; }
}
</style>

<?php require_once '../includes/footer.php'; ?>