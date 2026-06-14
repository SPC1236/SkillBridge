<?php
$page_title = "Admin Dashboard";
require_once '../includes/config.php';
require_once '../includes/database.php'; // ADD THIS
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Get statistics
$db = new Database();
$conn = $db->getConnection();

$total_job_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers")->fetchColumn();
$active_job_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE is_active = 1")->fetchColumn();
$total_employers = $conn->query("SELECT COUNT(*) FROM employers")->fetchColumn();
$active_employers = $conn->query("SELECT COUNT(*) FROM employers WHERE is_active = 1")->fetchColumn();
$new_today_seekers = $conn->query("SELECT COUNT(*) FROM job_seekers WHERE DATE(date_joined) = CURDATE()")->fetchColumn();
$new_today_employers = $conn->query("SELECT COUNT(*) FROM employers WHERE DATE(date_joined) = CURDATE()")->fetchColumn();

$grand_total_users = $total_job_seekers + $total_employers;
?>

<div class="dashboard-container">
    
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">👑</div>
            <h3>Administrator</h3>
            <p class="sidebar-role">Super Admin</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link active">
                <i class="fas fa-crown"></i>
                <span>Admin Dashboard</span>
            </a>

            <a href="manage_users.php" class="sidebar-link">
                <i class="fas fa-users-cog"></i>
                <span>Manage Users</span>
            </a>

             <a href="manage_jobs.php" class="sidebar-link">
                <i class="fas fa-briefcase"></i>
                <span>Manage Jobs</span>
            </a>

             <a href="upload_resource.php" class="sidebar-link">
                <i class="fas fa-upload"></i>
                <span>Upload Resources</span>
            </a>

            <a href="statistics.php" class="sidebar-link">
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
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: #ffffff;">Admin Dashboard 👑</h1>
                    <p style="color: #94a3b8; margin: 0.25rem 0 0 0; font-size: 0.95rem;">Manage your freelance portal platform</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="manage_users.php" class="btn-action-primary">
                        <i class="fas fa-users-cog"></i> Manage Users
                    </a>
                    <a href="statistics.php" class="btn-action-outline">
                        <i class="fas fa-chart-bar"></i> View Statistics
                    </a>
                </div>
            </div>
        </section>

        <section class="metrics-dashboard-grid" style="margin-bottom: 2.5rem;">
            <div class="premium-stat-card border-accent-seekers">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Total Job Seekers</span>
                        <h2 class="stat-massive-number"><?php echo $total_job_seekers; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-seekers"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-label-secondary"><b><?php echo $active_job_seekers; ?></b> Active accounts</span>
                    <span class="meta-grow-label text-growth-green"><i class="fas fa-bolt"></i> +<?php echo $new_today_seekers; ?> today</span>
                </div>
            </div>
            
            <div class="premium-stat-card border-accent-employers">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Total Employers</span>
                        <h2 class="stat-massive-number"><?php echo $total_employers; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-employers"><i class="fas fa-building"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-label-secondary"><b><?php echo $active_employers; ?></b> Active accounts</span>
                    <span class="meta-grow-label text-growth-green"><i class="fas fa-bolt"></i> +<?php echo $new_today_employers; ?> today</span>
                </div>
            </div>
            
            <div class="premium-stat-card border-accent-total">
                <div class="stat-card-interior">
                    <div class="stat-info-wrap">
                        <span class="stat-label-text">Total Platform Users</span>
                        <h2 class="stat-massive-number"><?php echo $grand_total_users; ?></h2>
                    </div>
                    <div class="stat-icon-wrapper bg-gradient-total"><i class="fas fa-globe"></i></div>
                </div>
                <div class="stat-footer-meta">
                    <span class="meta-pill-indicator indicator-blue">Combined System Yield</span>
                </div>
            </div>
        </section>

        <section style="margin-bottom: 2.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #ffffff; margin: 0 0 1.25rem 0;">Quick Management Navigation</h2>
            <div class="metrics-dashboard-grid">
                
                <div class="premium-content-card">
                    <div class="card-element-body navigation-card-body">
                        <div class="nav-card-icon text-accent-blue"><i class="fas fa-users-cog"></i></div>
                        <h3 class="nav-card-title">User Management</h3>
                        <p class="nav-card-desc">View, manage, activate credentials, or handle active moderation penalties for user accounts.</p>
                        <a href="manage_users.php" class="btn-action-primary" style="margin-top: auto; justify-content: center;">Access Directory</a>
                    </div>
                </div>

                <div class="premium-content-card">
                    <div class="card-element-body navigation-card-body">
                        <div class="nav-card-icon text-accent-purple"><i class="fas fa-chart-bar"></i></div>
                        <h3 class="nav-card-title">Advanced Statistics</h3>
                        <p class="nav-card-desc">View market skill demands, core distribution ratios, and registration history trends.</p>
                        <a href="statistics.php" class="btn-action-outline" style="margin-top: auto; justify-content: center;">Open Analytics</a>
                    </div>
                </div>

                <div class="premium-content-card">
                    <div class="card-element-body navigation-card-body">
                        <div class="nav-card-icon text-accent-orange"><i class="fas fa-sliders-h"></i></div>
                        <h3 class="nav-card-title">System Settings</h3>
                        <p class="nav-card-desc">Configure parameters, service rules, security session lifetimes, and maintenance mode status.</p>
                        <a href="settings.php" class="btn-action-outline" style="margin-top: auto; justify-content: center;">Open Configurations</a>
                    </div>
                </div>

            </div>
        </section>

        <section class="split-activity-layout">
            <div class="premium-content-card">
                <div class="card-element-header">
                    <h3 class="card-title-text"><i class="fas fa-history text-accent-cyan"></i> Recent Administrative Action Logs</h3>
                </div>
                <div class="card-element-body" style="text-align: center; padding: 3rem 1.5rem; color: #475569;">
                    <i class="far fa-folder-open" style="font-size: 2rem; margin-bottom: 0.75rem; display: block; color: #334155;"></i>
                    <p style="margin: 0; font-size: 0.95rem; color: #94a3b8;">No diagnostic administrative actions recorded within this session interval.</p>
                </div>
            </div>

            <div class="premium-content-card">
                <div class="card-element-header">
                    <h3 class="card-title-text"><i class="fas fa-server text-accent-green"></i> Secure Operational System Status</h3>
                </div>
                <div class="card-element-body diagnostics-flex-stack">
                    <div class="system-status-row">
                        <div class="status-indicator-dot dot-online"></div>
                        <span class="status-label-name">Database Component Connection</span>
                        <span class="status-badge-txt text-accent-green">Online</span>
                    </div>
                    <div class="system-status-row">
                        <div class="status-indicator-dot dot-online"></div>
                        <span class="status-label-name">Session Key Authentication Pipeline</span>
                        <span class="status-badge-txt text-accent-green">Online</span>
                    </div>
                    <div class="system-status-row">
                        <div class="status-indicator-dot dot-online"></div>
                        <span class="status-label-name">Storage Node Encryption File System</span>
                        <span class="status-badge-txt text-accent-green">Online</span>
                    </div>
                    <div class="system-status-row">
                        <div class="status-indicator-dot dot-online"></div>
                        <span class="status-label-name">Global Platform Core Engine</span>
                        <span class="status-badge-txt text-accent-green">Stable</span>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

<style>
/* Theme Core Layout Rules Base */
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

/* Interface Control Buttons Layout Rules */
.btn-action-outline { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: transparent; border: 1px solid rgba(255,255,255,0.15); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
.btn-action-outline:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.3); }
.btn-action-primary { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: #2563eb; border: 1px solid transparent; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
.btn-action-primary:hover { background: #1d4ed8; }

/* Structural Grid Matrices Rules */
.metrics-dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
.premium-stat-card { background: #111e32; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.04); padding: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; }
.stat-card-interior { display: flex; justify-content: space-between; align-items: flex-start; }
.stat-label-text { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.5rem; }
.stat-massive-number { font-size: 2.25rem; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.1; }
.stat-icon-wrapper { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #ffffff; }

/* Matrix Background Gradient Color Values */
.bg-gradient-total { background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
.bg-gradient-seekers { background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 4px 14px rgba(59,130,246,0.3); }
.bg-gradient-employers { background: linear-gradient(135deg, #10b981, #047857); box-shadow: 0 4px 14px rgba(16,185,129,0.3); }

/* Accent Border Card Assignments */
.border-accent-total { border-left: 4px solid #6366f1; }
.border-accent-seekers { border-left: 4px solid #3b82f6; }
.border-accent-employers { border-left: 4px solid #10b981; }

.stat-footer-meta { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #94a3b8; }
.meta-pill-indicator { font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 6px; }
.indicator-blue { background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.2); }
.text-growth-green { color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; }

/* Management Block Item Elements System */
.premium-content-card { background: #111e32; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.04); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; }
.card-element-header { padding: 1.25rem 1.5rem; background: #14233a; border-bottom: 1px solid rgba(255, 255, 255, 0.04); }
.card-title-text { font-size: 1rem; font-weight: 700; color: #ffffff; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.card-element-body { padding: 1.5rem; }
.navigation-card-body { display: flex; flex-direction: column; height: 100%; box-sizing: border-box; min-height: 250px; }

/* Control Icons Color Definition Blueprint */
.nav-card-icon { font-size: 1.75rem; margin-bottom: 1rem; }
.text-accent-blue { color: #3b82f6; }
.text-accent-purple { color: #a855f7; }
.text-accent-orange { color: #f59e0b; }
.text-accent-cyan { color: #06b6d4; }
.text-accent-green { color: #10b981; }

.nav-card-title { font-size: 1.15rem; font-weight: 700; color: #ffffff; margin: 0 0 0.5rem 0; }
.nav-card-desc { font-size: 0.85rem; color: #94a3b8; line-height: 1.5; margin: 0 0 1.5rem 0; }

/* Bottom Segment Column Structural Framework */
.split-activity-layout { display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem; }
.diagnostics-flex-stack { display: flex; flex-direction: column; gap: 0.75rem; }
.system-status-row { display: flex; align-items: center; padding: 0.75rem 1rem; background: rgba(255,255,255,0.01); border-radius: 10px; border: 1px solid rgba(255,255,255,0.02); }
.status-indicator-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 0.75rem; }
.dot-online { background-color: #10b981; box-shadow: 0 0 8px #10b981; }
.status-label-name { font-size: 0.85rem; color: #cbd5e1; font-weight: 500; }
.status-badge-txt { font-size: 0.85rem; font-weight: 700; margin-left: auto; }

@media (max-width: 992px) {
    .dashboard-container { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; border-right: none; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    .split-activity-layout { grid-template-columns: 1fr; }
}
</style>

<?php require_once '../includes/footer.php'; ?>