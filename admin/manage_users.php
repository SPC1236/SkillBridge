<?php
$page_title = "Manage Users";
require_once '../includes/config.php';
require_once '../includes/database.php'; // ADD THIS
require_once '../includes/header.php';
require_once '../includes/auth_check.php';


// Get all users
$db = new Database();
$conn = $db->getConnection();

// Get job seekers
$job_seekers_stmt = $conn->query("SELECT * FROM job_seekers ORDER BY date_joined DESC");
$job_seekers = $job_seekers_stmt->fetchAll();

// Get employers
$employers_stmt = $conn->query("SELECT * FROM employers ORDER BY date_joined DESC");
$employers = $employers_stmt->fetchAll();

// Handle user deletion
if(isset($_GET['delete'])) {
    $user_type = $_GET['type'];
    $user_id = $_GET['id'];
    
    if($user_type == 'jobseeker') {
        $stmt = $conn->prepare("DELETE FROM job_seekers WHERE id = :id");
    } else {
        $stmt = $conn->prepare("DELETE FROM employers WHERE id = :id");
    }
    
    $stmt->bindParam(':id', $user_id);
    
    if($stmt->execute()) {
        header('Location: manage_users.php?success=deleted');
        exit;
    } else {
        header('Location: manage_users.php?error=delete_failed');
        exit;
    }
}

// Handle user activation/deactivation
if(isset($_GET['toggle_status'])) {
    $user_type = $_GET['type'];
    $user_id = $_GET['id'];
    $current_status = $_GET['current_status'];
    $new_status = $current_status ? 0 : 1;
    
    if($user_type == 'jobseeker') {
        $stmt = $conn->prepare("UPDATE job_seekers SET is_active = :status WHERE id = :id");
    } else {
        $stmt = $conn->prepare("UPDATE employers SET is_active = :status WHERE id = :id");
    }
    
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':id', $user_id);
    
    if($stmt->execute()) {
        header('Location: manage_users.php?success=status_updated');
        exit;
    } else {
        header('Location: manage_users.php?error=status_failed');
        exit;
    }
}
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
            <a href="manage_users.php" class="sidebar-link active">
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
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: #ffffff;">User Management 👥</h1>
                    <p style="color: #94a3b8; margin: 0.25rem 0 0 0; font-size: 0.95rem;">Manage all job seekers and employers registered on the platform</p>
                </div>
                <div>
                    <a href="dashboard.php" class="btn btn-outline" style="border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.9rem; color: #ffffff; border-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </section>

        <section>
            <?php if(isset($_GET['success'])): ?>
                <div class="custom-alert alert-success-banner">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <?php 
                        $success_messages = [
                            'deleted' => 'User account deleted successfully!',
                            'status_updated' => 'User authorization status changed successfully!'
                        ];
                        echo $success_messages[$_GET['success']] ?? 'Action completed successfully!';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="custom-alert alert-error-banner">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <?php 
                        $error_messages = [
                            'delete_failed' => 'Failed to delete user. Please try again.',
                            'status_failed' => 'Failed to update user status. Please try again.'
                        ];
                        echo $error_messages[$_GET['error']] ?? 'An error occurred!';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="user-management-card" style="margin-bottom: 2.5rem;">
                <div class="card-section-header">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="section-icon-box seeker-color-theme"><i class="fas fa-user-graduate"></i></div>
                        <div>
                            <h2 class="section-title">Job Seekers</h2>
                            <p class="section-subtitle">Total Registered: <strong><?php echo count($job_seekers); ?></strong></p>
                        </div>
                    </div>
                    <span class="pill-badge pill-active-count"><?php echo count(array_filter($job_seekers, fn($user) => $user['is_active'])); ?> Active Currently</span>
                </div>

                <?php if(empty($job_seekers)): ?>
                    <div class="empty-state-box">
                        <i class="far fa-folder-open"></i>
                        <p>No job seekers registered yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-scroll-wrapper">
                        <table class="premium-admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Basic Info</th>
                                    <th>Professional Title</th>
                                    <th>Key Skills Focus</th>
                                    <th>Registration</th>
                                    <th>Status</th>
                                    <th style="text-align: right; width: 220px;">Administrative Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($job_seekers as $seeker): ?>
                                    <tr>
                                        <td class="text-id-bold">#<?php echo $seeker['id']; ?></td>
                                        <td>
                                            <div class="info-cell-primary"><?php echo htmlspecialchars($seeker['full_name']); ?></div>
                                            <div class="info-cell-secondary"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($seeker['email']); ?></div>
                                        </td>
                                        <td class="text-truncate-custom"><?php echo htmlspecialchars($seeker['professional_title'] ?? 'Not set'); ?></td>
                                        <td>
                                            <?php if($seeker['skills']): ?>
                                                <span class="skills-inline-preview"><?php echo substr(htmlspecialchars($seeker['skills']), 0, 35); ?>...</span>
                                            <?php else: ?>
                                                <span class="text-placeholder-italic">No listed skillset</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-date-styled"><?php echo date('M j, Y', strtotime($seeker['date_joined'])); ?></td>
                                        <td>
                                            <?php if($seeker['is_active']): ?>
                                                <span class="badge-status status-online"><span class="dot-indicator"></span> Active</span>
                                            <?php else: ?>
                                                <span class="badge-status status-offline"><span class="dot-indicator"></span> Suspended</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons-flex">
                                                <a href="javascript:void(0)" 
                                                   onclick="toggleUserStatus('jobseeker', <?php echo $seeker['id']; ?>, <?php echo $seeker['is_active']; ?>)"
                                                   class="action-btn-styled <?php echo $seeker['is_active'] ? 'btn-status-deactivate' : 'btn-status-activate'; ?>">
                                                    <i class="fas <?php echo $seeker['is_active'] ? 'fa-ban' : 'fa-check'; ?>"></i>
                                                    <?php echo $seeker['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                   onclick="confirmDelete('jobseeker', <?php echo $seeker['id']; ?>, '<?php echo htmlspecialchars($seeker['full_name']); ?>')"
                                                   class="action-btn-styled btn-status-delete">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="user-management-card">
                <div class="card-section-header">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="section-icon-box employer-color-theme"><i class="fas fa-building"></i></div>
                        <div>
                            <h2 class="section-title">Employers & Corporate Accounts</h2>
                            <p class="section-subtitle">Total Registered: <strong><?php echo count($employers); ?></strong></p>
                        </div>
                    </div>
                    <span class="pill-badge pill-active-count"><?php echo count(array_filter($employers, fn($user) => $user['is_active'])); ?> Active Currently</span>
                </div>

                <?php if(empty($employers)): ?>
                    <div class="empty-state-box">
                        <i class="far fa-folder-open"></i>
                        <p>No employers registered yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-scroll-wrapper">
                        <table class="premium-admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Company & Brand Details</th>
                                    <th>Point of Contact</th>
                                    <th>Phone line</th>
                                    <th>Registration</th>
                                    <th>Status</th>
                                    <th style="text-align: right; width: 220px;">Administrative Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($employers as $employer): ?>
                                    <tr>
                                        <td class="text-id-bold">#<?php echo $employer['id']; ?></td>
                                        <td>
                                            <div class="info-cell-primary"><?php echo htmlspecialchars($employer['company_name']); ?></div>
                                            <div class="info-cell-secondary"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($employer['email']); ?></div>
                                        </td>
                                        <td style="font-weight: 500; color: var(--text-secondary);"><?php echo htmlspecialchars($employer['contact_person']); ?></td>
                                        <td class="text-phone-styled"><?php echo htmlspecialchars($employer['company_phone'] ?? 'Not set'); ?></td>
                                        <td class="text-date-styled"><?php echo date('M j, Y', strtotime($employer['date_joined'])); ?></td>
                                        <td>
                                            <?php if($employer['is_active']): ?>
                                                <span class="badge-status status-online"><span class="dot-indicator"></span> Active</span>
                                            <?php else: ?>
                                                <span class="badge-status status-offline"><span class="dot-indicator"></span> Suspended</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons-flex">
                                                <a href="javascript:void(0)" 
                                                   onclick="toggleUserStatus('employer', <?php echo $employer['id']; ?>, <?php echo $employer['is_active']; ?>)"
                                                   class="action-btn-styled <?php echo $employer['is_active'] ? 'btn-status-deactivate' : 'btn-status-activate'; ?>">
                                                    <i class="fas <?php echo $employer['is_active'] ? 'fa-ban' : 'fa-check'; ?>"></i>
                                                    <?php echo $employer['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                   onclick="confirmDelete('employer', <?php echo $employer['id']; ?>, '<?php echo htmlspecialchars($employer['company_name']); ?>')"
                                                   class="action-btn-styled btn-status-delete">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
    </main>
</div>

<style>
/* Base Theme Framework Connectors */
.dashboard-container { display: flex; min-height: calc(100vh - 70px); background: #0c1524; } /* CHANGED FROM #f8fafc to remove white rectangle */
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

/* Banners & Notification UI */
.custom-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; font-size: 0.9rem; font-weight: 500; margin-bottom: 1.5rem; line-height: 1.4; }
.alert-success-banner { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error-banner { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* Refined Premium Structural Cards */
.user-management-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02); overflow: hidden; }
.card-section-header { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #fafafa; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 1rem; }
.section-icon-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #ffffff; }
.seeker-color-theme { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.employer-color-theme { background: linear-gradient(135deg, #10b981, #047857); }
.section-title { font-size: 1.2rem; font-weight: 700; color: #1e293b; margin: 0; }
.section-subtitle { margin: 0.15rem 0 0 0; font-size: 0.85rem; color: #64748b; }
.pill-badge { font-size: 0.8rem; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 20px; }
.pill-active-count { background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

/* Enhanced Table Typography and Design Layouts */
.table-scroll-wrapper { overflow-x: auto; width: 100%; }
.premium-admin-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
.premium-admin-table th { background-color: #ffffff; color: #64748b; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 2px solid #edf2f7; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
.premium-admin-table td { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; }
.premium-admin-table tr:last-child td { border-bottom: none; }
.premium-admin-table tr:hover td { background-color: #f8fafc; }

/* Specific Cell Style Enhancements */
.text-id-bold { font-family: monospace; font-weight: 700; color: #94a3b8; font-size: 0.95rem; }
.info-cell-primary { font-weight: 600; color: #1e293b; font-size: 0.95rem; }
.info-cell-secondary { font-size: 0.8rem; color: #64748b; margin-top: 0.15rem; display: flex; align-items: center; gap: 0.35rem; }
.text-truncate-custom { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
.skills-inline-preview { background-color: #f1f5f9; color: #475569; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.8rem; border: 1px solid #e2e8f0; }
.text-date-styled { font-size: 0.85rem; color: #64748b; font-weight: 500; }
.text-phone-styled { font-size: 0.85rem; font-family: monospace; color: #475569; }
.text-placeholder-italic { color: #94a3b8; font-style: italic; font-size: 0.85rem; }

/* Status Labels Framework */
.badge-status { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 6px; }
.dot-indicator { width: 6px; height: 6px; border-radius: 50%; }
.status-online { background-color: #dcfce7; color: #15803d; }
.status-online .dot-indicator { background-color: #16a34a; }
.status-offline { background-color: #fef3c7; color: #b45309; }
.status-offline .dot-indicator { background-color: #d97706; }

/* Table System Control Action Flex Buttons */
.action-buttons-flex { display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center; }
.action-btn-styled { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; font-weight: 600; padding: 0.4rem 0.75rem; border-radius: 8px; text-decoration: none; border: 1px solid transparent; transition: all 0.15s ease-in-out; cursor: pointer; white-space: nowrap; }
.btn-status-activate { background-color: #ffffff; color: #2563eb; border-color: #bfdbfe; }
.btn-status-activate:hover { background-color: #eff6ff; border-color: #2563eb; }
.btn-status-deactivate { background-color: #ffffff; color: #64748b; border-color: #cbd5e1; }
.btn-status-deactivate:hover { background-color: #f8fafc; color: #1e293b; border-color: #94a3b8; }
.btn-status-delete { background-color: #ffffff; color: #dc2626; border-color: #fecaca; }
.btn-status-delete:hover { background-color: #fef2f2; border-color: #dc2626; }

.empty-state-box { text-align: center; color: #94a3b8; padding: 3rem 1.5rem; }
.empty-state-box i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
.empty-state-box p { margin: 0; font-size: 0.95rem; }

@media (max-width: 992px) {
    .dashboard-container { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; border-right: none; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
}
</style>

<script>
function confirmDelete(userType, userId, userName) {
    if(confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone.`)) {
        window.location.href = `manage_users.php?delete=1&type=${userType}&id=${userId}`;
    }
}

function toggleUserStatus(userType, userId, currentStatus) {
    const action = currentStatus ? 'deactivate' : 'activate';
    if(confirm(`Are you sure you want to ${action} this user?`)) {
        window.location.href = `manage_users.php?toggle_status=1&type=${userType}&id=${userId}&current_status=${currentStatus}`;
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>