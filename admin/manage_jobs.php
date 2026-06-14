<?php
// admin/manage_jobs.php
// Centralized Job Administration Panel - Light Blue Theme

$page_title = "Admin: Manage System Jobs";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Strict Role Check Guardrail - SYNCED WITH YOUR CORE LAYOUT VARIABLE
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /freelance_portal/dashboard.php');
    exit;
}

ob_start();

$database = new Database();
$conn = $database->getConnection();

// Core Operation Pipeline: Handle Administrative Purge Requests
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $target_id = $_GET['delete'];
    try {
        $del_sql = "DELETE FROM jobs WHERE id = :id";
        $del_stmt = $conn->prepare($del_sql);
        $del_stmt->bindParam(':id', $target_id, PDO::PARAM_INT);
        $del_stmt->execute();
        
        header('Location: /freelance_portal/admin/manage_jobs.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Administrative deletion failure: " . $e->getMessage());
    }
}

// Core Operation Pipeline: Handle Global Status Toggle Archive/Active Requests
if (isset($_GET['archive']) && is_numeric($_GET['archive'])) {
    $target_id = $_GET['archive'];
    try {
        $arc_sql = "UPDATE jobs SET status = IF(status='active', 'archived', 'active') WHERE id = :id";
        $arc_stmt = $conn->prepare($arc_sql);
        $arc_stmt->bindParam(':id', $target_id, PDO::PARAM_INT);
        $arc_stmt->execute();
        
        header('Location: /freelance_portal/admin/manage_jobs.php?status_updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Administrative visibility state change failure: " . $e->getMessage());
    }
}

// Query Pipeline: Pull ALL database rows
$all_jobs = [];
try {
    $sql = "SELECT j.*, u.email as employer_email 
            FROM jobs j 
            LEFT JOIN users u ON j.employer_id = u.id 
            ORDER BY j.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $all_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Administrative system fetch query error: " . $e->getMessage());
}

$alert_msg = '';
if (isset($_GET['deleted'])) $alert_msg = "Administrative Action: Listing permanently removed from the system registry.";
if (isset($_GET['status_updated'])) $alert_msg = "Administrative Action: Listing availability status updated successfully.";
?>

<style>
    .admin-view-header {
        margin-bottom: 2.25rem;
    }
    .admin-badge-indicator {
        display: inline-block;
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .modern-alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(56, 189, 248, 0.06);
        border: 1px solid rgba(56, 189, 248, 0.25);
        border-left: 4px solid #38bdf8;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        color: var(--text-primary);
        font-size: 0.9rem;
    }
    .jobs-table-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    th {
        background: rgba(255, 255, 255, 0.02);
        padding: 1rem 1.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        border-bottom: 1px solid var(--border-light);
    }
    td {
        padding: 1.25rem 1.5rem;
        font-size: 0.9rem;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    
    .owner-info {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    .status-badge {
        display: inline-flex;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .status-badge.archived {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .actions-cluster {
        display: flex;
        gap: 0.5rem;
    }
    .ctrl-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
    }
    .ctrl-btn.btn-archive {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
    }
    .ctrl-btn.btn-archive:hover {
        background: #38bdf8;
        color: #fff;
    }
    .ctrl-btn.btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .ctrl-btn.btn-delete:hover {
        background: #ef4444;
        color: #fff;
    }
    .empty-canvas {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-secondary);
    }
</style>

<div class="admin-view-header">
    <span class="admin-badge-indicator">System Moderator Control</span>
    <h1 style="font-size: 1.85rem; font-weight:700; margin:0 0 0.35rem 0; color: var(--text-primary);">Global Job Moderation</h1>
    <p style="margin:0; color: var(--text-secondary);">Oversee, archive, or completely purge any employment listing posted system-wide.</p>
</div>

<?php if ($alert_msg): ?>
    <div class="modern-alert">
        <i class="fa-solid fa-shield-halved" style="color: #38bdf8;"></i>
        <span><?php echo htmlspecialchars($alert_msg); ?></span>
    </div>
<?php endif; ?>

<div class="jobs-table-card">
    <?php if (!empty($all_jobs)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Job Specifications</th>
                        <th>Posted By (Employer)</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Global Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_jobs as $job): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-primary); display:block; margin-bottom:0.2rem;">
                                    <?php echo htmlspecialchars($job['title']); ?>
                                </strong>
                                <span style="font-size:0.8rem; color: var(--text-muted);">
                                    <i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?> 
                                    &bull; <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="owner-info">
                                    <i class="fa-solid fa-user-tie" style="color: #38bdf8; margin-right: 0.25rem;"></i>
                                    <span><?php echo htmlspecialchars($job['employer_email'] ?? 'Unknown User ID: '.$job['employer_id']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo htmlspecialchars($job['job_type']); ?></span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $job['status']; ?>">
                                    <?php echo $job['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions-cluster">
                                    <a href="/freelance_portal/admin/manage_jobs.php?archive=<?php echo $job['id']; ?>" class="ctrl-btn btn-archive" title="Toggle Active/Archived State">
                                        <i class="fa-solid fa-box-archive"></i>
                                    </a>
                                    <a href="/freelance_portal/admin/manage_jobs.php?delete=<?php echo $job['id']; ?>" class="ctrl-btn btn-delete" onclick="return confirm('CRITICAL ACTION: You are about to permanently delete this listing from the network database. This action is irreversible. Continue?');" title="Force Delete Listing">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-canvas">
            <i class="fa-solid fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
            <h3>The Platform Database is Clean</h3>
            <p style="font-size:0.9rem; color: var(--text-muted);">There are currently no active or archived jobs stored in the system index.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>