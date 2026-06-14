<?php
$page_title = "Track Applied Contracts";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Connect to database node pipeline
$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

// Securely fetch application history mapping to jobs schema
$query = "SELECT ja.id as application_id, ja.status, ja.date_applied, 
                 j.title as job_title, j.company_name, j.salary, j.location
          FROM job_applications ja
          JOIN jobs j ON ja.job_id = j.id
          WHERE ja.user_id = :user_id
          ORDER BY ja.date_applied DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch saved jobs count dynamic aggregate for sidebar menu element module
$saved_stmt = $conn->prepare("SELECT COUNT(*) FROM saved_jobs WHERE user_id = :user_id");
$saved_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$saved_stmt->execute();
$saved_jobs = $saved_stmt->fetchColumn();

// Real-time status metric aggregates
$metrics = ['Pending' => 0, 'Interviewing' => 0, 'Hired' => 0, 'Rejected' => 0];
foreach ($applications as $app) {
    if (array_key_exists($app['status'], $metrics)) {
        $metrics[$app['status']]++;
    }
}
?>

<div class="dashboard-container">
    
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo substr(htmlspecialchars($_SESSION['full_name'] ?? 'U'), 0, 1); ?>
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
            <a href="applied_jobs.php" class="sidebar-link active">
                <i class="fas fa-briefcase"></i>
                <span>Applied Jobs</span>
                <span class="badge badge-success"><?php echo count($applications); ?></span>
            </a>
            <a href="analytics.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
            
            <div class="stat-info" style="margin: var(--spacing-md) var(--spacing-sm); padding: var(--spacing-sm); background: rgba(255,255,255,0.03); border-radius: var(--radius-md); border: 1px solid var(--border-light);">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2px;">
                    <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--accent-warning); line-height: 1;"><?php echo $saved_jobs; ?></div>
                    <a href="saved_jobs.php" style="text-decoration: none; color: var(--accent-primary); font-size: 0.8rem; font-weight: 600;">View all →</a>
                </div>
                <div class="stat-label" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">Saved Opportunities</div>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link" style="color: var(--accent-danger);">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="dashboard-main">
        
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--spacing-md);">
            <div>
                <h1>Application Hub Tracking Matrix</h1>
                <p class="text-muted">Monitor active job pipeline entries, recruitment cycles, and contract delivery logs.</p>
            </div>
            <div>
                <a href="<?php echo SITE_URL; ?>/public/jobs.php" class="btn btn-primary btn-large">
                    <i class="fas fa-search"></i> Discover Jobs
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Submitted</h3>
                <div class="stat-value" style="color: var(--text-primary);"><?php echo count($applications); ?></div>
                <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Active pipeline volume</p>
            </div>
            <div class="stat-card">
                <h3>In Verification</h3>
                <div class="stat-value" style="color: var(--accent-warning);"><?php echo $metrics['Pending']; ?></div>
                <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Awaiting client triage</p>
            </div>
            <div class="stat-card">
                <h3>Active Interviews</h3>
                <div class="stat-value" style="color: var(--accent-primary);"><?php echo $metrics['Interviewing']; ?></div>
                <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Interactive review rounds</p>
            </div>
            <div class="stat-card">
                <h3>Contracts Secured</h3>
                <div class="stat-value" style="color: var(--accent-success);"><?php echo $metrics['Hired']; ?></div>
                <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Successful placements</p>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: var(--spacing-sm);">Application Progress Real-time Log</h3>
            <p class="text-muted" style="margin-bottom: var(--spacing-lg);">Tabular audit map of historical profile routing events.</p>
            
            <?php if (empty($applications)): ?>
                <div style="text-align: center; padding: var(--spacing-xl) 0;">
                    <i class="fas fa-briefcase" style="font-size: 3rem; color: var(--text-muted); margin-bottom: var(--spacing-md); display: block;"></i>
                    <p>No contract transactions found mapping to your profile ID database indices.</p>
                    <a href="<?php echo SITE_URL; ?>/public/jobs.php" class="btn btn-outline">Query Remote Post Directory</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Target Position Role</th>
                                <th>Client / Entity</th>
                                <th>Offered Salary Rate</th>
                                <th>Timestamp Entry</th>
                                <th>System Status Code</th>
                                <th style="text-align: right;">Action Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $row): ?>
                                <tr>
                                    <td style="font-weight: 600;">
                                        <?php echo htmlspecialchars($row['job_title']); ?>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 400; margin-top: 2px;">
                                            <i class="fas fa-map-marker-alt" style="font-size: 0.7rem;"></i> <?php echo htmlspecialchars($row['location']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                    <td style="font-weight: 600; color: var(--accent-success);">
                                        <?php echo htmlspecialchars($row['salary'] ?? 'Not Listed'); ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($row['date_applied'])); ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_class = 'badge-warning';
                                        if ($row['status'] === 'Hired') $status_class = 'badge-success';
                                        if ($row['status'] === 'Rejected') $status_class = 'badge-danger';
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="view_application.php?id=<?php echo $row['application_id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
                                            <i class="fas fa-search-plus"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>