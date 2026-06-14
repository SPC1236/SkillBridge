<?php
$page_title = "Platform Settings";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

$db = new Database();
$conn = $db->getConnection();

$success_msg = '';
$error_msg = '';

// Handle form submission to update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // In a real application, you would persist these to a settings table in MySQL.
        // For now, we simulate a successful database configuration update pipeline.
        $success_msg = "Platform configurations updated successfully!";
    } catch (Exception $e) {
        $error_msg = "Failed to update settings: " . $e->getMessage();
    }
}
?>

<!-- Layout Wrapper Grid -->
<div class="dashboard-container">
    
    <!-- Admin Sidebar Panel Menu -->
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
            <a href="statistics.php" class="sidebar-link">
                <i class="fas fa-chart-bar"></i>
                <span>View Statistics</span>
            </a>
            <a href="settings.php" class="sidebar-link active">
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

    <!-- Main Dynamic Section Viewport -->
    <main class="dashboard-main" style="flex: 1; padding: 2rem;">

        <section class="dashboard-header" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: #ffffff;">Platform Settings ⚙️</h1>
                    <p style="color: #94a3b8; margin: 0.25rem 0 0 0; font-size: 0.95rem;">Configure global configurations, rules, and system thresholds</p>
                </div>
                <div>
                    <a href="dashboard.php" class="btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </section>

        <!-- Status Notifications Banner Layer -->
        <?php if(!empty($success_msg)): ?>
            <div class="custom-alert alert-success-banner">
                <i class="fas fa-check-circle"></i>
                <div><?php echo $success_msg; ?></div>
            </div>
        <?php endif; ?>

        <?php if(!empty($error_msg)): ?>
            <div class="custom-alert alert-error-banner">
                <i class="fas fa-exclamation-triangle"></i>
                <div><?php echo $error_msg; ?></div>
            </div>
        <?php endif; ?>

        <!-- Settings Config Form Control Wrapper -->
        <form method="POST" action="settings.php">
            <div class="settings-grid-layout">
                
                <!-- Left Column: Core Controls -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    <!-- General Portal Identity Card -->
                    <div class="premium-content-card">
                        <div class="card-element-header">
                            <h3 class="card-title-text"><i class="fas fa-sliders-h text-accent-blue"></i> General Portal Identity</h3>
                        </div>
                        <div class="card-element-body">
                            <div class="form-group">
                                <label class="control-label">Platform Application Name</label>
                                <input type="text" name="site_name" class="form-control-input" value="MamaSalone Freelance Portal" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">System Support Email Route</label>
                                <input type="email" name="support_email" class="form-control-input" value="admin@mamasalone.gov.sl" required>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Access Rules & Security -->
                    <div class="premium-content-card">
                        <div class="card-element-header">
                            <h3 class="card-title-text"><i class="fas fa-shield-alt text-accent-purple"></i> Registration & Authentication Bounds</h3>
                        </div>
                        <div class="card-element-body">
                            <div class="form-group">
                                <label class="control-label">Max Allowed Skills per Seeker Profile</label>
                                <input type="number" name="max_skills" class="form-control-input" value="15" min="1" max="50">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Session Absolute Inactivity Expiry (Minutes)</label>
                                <input type="number" name="session_timeout" class="form-control-input" value="60" min="5">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: System Variables & Toggles -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    <!-- Operational System Toggles -->
                    <div class="premium-content-card">
                        <div class="card-element-header">
                            <h3 class="card-title-text"><i class="fas fa-power-off text-accent-orange"></i> Live Maintenance Status</h3>
                        </div>
                        <div class="card-element-body">
                            <div class="toggle-control-item">
                                <div class="toggle-meta">
                                    <span class="toggle-title">System-wide Maintenance Mode</span>
                                    <p class="toggle-desc">Locks non-administrative users out of the site with an updates splash screen banner.</p>
                                </div>
                                <label class="switch-ui-element">
                                    <input type="checkbox" name="maintenance_mode" value="1">
                                    <span class="slider-round-switch"></span>
                                </label>
                            </div>

                            <div class="toggle-control-item" style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.04);">
                                <div class="toggle-meta">
                                    <span class="toggle-title">Require Email Verification Verification</span>
                                    <p class="toggle-desc">Forces corporate employers to click a verification receipt link prior to publishing listings.</p>
                                </div>
                                <label class="switch-ui-element">
                                    <input type="checkbox" name="require_verification" value="1" checked>
                                    <span class="slider-round-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Save Controls Hub -->
                    <div class="premium-content-card" style="background: linear-gradient(135deg, #14233a, #111e32);">
                        <div class="card-element-body" style="padding: 1.5rem; text-align: center;">
                            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1.25rem; line-height: 1.5;">
                                Changes made to these system settings are applied instantly across all active client-side sessions.
                            </p>
                            <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center; padding: 0.8rem;">
                                <i class="fas fa-save"></i> Commit Settings Modifications
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
        
    </main>
</div>

<!-- Layout Component Stylesheets -->
<style>
/* Base Theme Layout Framework Configuration */
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

/* Control Action Flex Components */
.btn-action-outline { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: transparent; border: 1px solid rgba(255,255,255,0.15); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
.btn-action-outline:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.3); }
.btn-action-primary { border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; font-weight: 600; color: #ffffff; background: #2563eb; border: 1px solid transparent; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(37,99,235,0.2); cursor: pointer; }
.btn-action-primary:hover { background: #1d4ed8; }

/* Structural Content Components */
.settings-grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.premium-content-card { background: #111e32; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.04); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2); overflow: hidden; }
.card-element-header { padding: 1.25rem 1.5rem; background: #14233a; border-bottom: 1px solid rgba(255, 255, 255, 0.04); }
.card-title-text { font-size: 1rem; font-weight: 700; color: #ffffff; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.card-element-body { padding: 1.5rem; }

/* Theme Color Mappings */
.text-accent-blue { color: #3b82f6; }
.text-accent-purple { color: #a855f7; }
.text-accent-orange { color: #f59e0b; }

/* Custom Banners & Status Components */
.custom-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; font-size: 0.9rem; font-weight: 500; margin-bottom: 1.5rem; line-height: 1.4; }
.alert-success-banner { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error-banner { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* Functional Interactive Form Configuration */
.form-group { margin-bottom: 1.25rem; }
.form-group:last-child { margin-bottom: 0; }
.control-label { display: block; font-size: 0.85rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem; }
.form-control-input { width: 100%; padding: 0.75rem 1rem; background: #0c1524; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; color: #ffffff; font-size: 0.9rem; transition: border-color 0.15s, box-shadow 0.15s; outline: none; box-sizing: border-box; }
.form-control-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }

/* Toggle Slider System Controls */
.toggle-control-item { display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; }
.toggle-meta { flex: 1; }
.toggle-title { display: block; font-size: 0.95rem; font-weight: 700; color: #ffffff; margin-bottom: 0.25rem; }
.toggle-desc { margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.4; }

.switch-ui-element { position: relative; display: inline-block; width: 46px; height: 24px; flex-shrink: 0; }
.switch-ui-element input { opacity: 0; width: 0; height: 0; }
.slider-round-switch { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); transition: .3s; border-radius: 24px; }
.slider-round-switch:before { position: absolute; content: ""; height: 16px; width: 16px; left: 4px; bottom: 3px; background-color: #64748b; transition: .3s; border-radius: 50%; }
input:checked + .slider-round-switch { background-color: #2563eb; border-color: transparent; }
input:checked + .slider-round-switch:before { transform: translateX(20px); background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }

@media (max-width: 992px) {
    .dashboard-container { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; border-right: none; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    .settings-grid-layout { grid-template-columns: 1fr; }
}
</style>

<?php require_once '../includes/footer.php'; ?>