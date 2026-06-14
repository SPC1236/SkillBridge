<?php
// employer/manage_jobs.php
$page_title = "Manage Posted Jobs";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

ob_start();

$database = new Database();
$conn = $database->getConnection();
$employer_id = $_SESSION['user_id'];

// Handle dynamic deletion requests safely
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $target_id = $_GET['delete'];
    try {
        $del_sql = "DELETE FROM jobs WHERE id = :id AND employer_id = :employer_id";
        $del_stmt = $conn->prepare($del_sql);
        $del_stmt->bindParam(':id', $target_id);
        $del_stmt->bindParam(':employer_id', $employer_id);
        $del_stmt->execute();
        header('Location: manage_jobs.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        error_log("Failed to purge job index: " . $e->getMessage());
    }
}

// Handle dynamic archive toggle requests safely
if (isset($_GET['archive']) && is_numeric($_GET['archive'])) {
    $target_id = $_GET['archive'];
    try {
        $arc_sql = "UPDATE jobs SET status = IF(status='active', 'archived', 'active') WHERE id = :id AND employer_id = :employer_id";
        $arc_stmt = $conn->prepare($arc_sql);
        $arc_stmt->bindParam(':id', $target_id);
        $arc_stmt->bindParam(':employer_id', $employer_id);
        $arc_stmt->execute();
        header('Location: manage_jobs.php?status_updated=1');
        exit;
    } catch (PDOException $e) {
        error_log("Failed to toggle listing status state: " . $e->getMessage());
    }
}

// Fetch all listings linked explicitly to this authenticated manager account
$posted_jobs = [];
try {
    $sql = "SELECT * FROM jobs WHERE employer_id = :employer_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':employer_id', $employer_id);
    $stmt->execute();
    $posted_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error pulling active manager index: " . $e->getMessage());
}

$alert_msg = '';
if (isset($_GET['created'])) $alert_msg = "Market listing has been broadcast successfully!";
if (isset($_GET['deleted'])) $alert_msg = "Listing profile completely removed from platform index.";
if (isset($_GET['status_updated'])) $alert_msg = "Listing availability status state successfully configured.";
?>

<style>
    .dashboard-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.25rem;
    }
    .header-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #38bdf8;
        color: #ffffff;
        text-decoration: none;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(56, 189, 248, 0.25);
        transition: var(--transition-smooth);
    }
    .header-action-btn:hover {
        background: #0ea5e9;
        transform: translateY(-2px);
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
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }
</style>

<div class="dashboard-view-header">
    <div>
        <h1>Manage Jobs</h1>
        <p>Monitor platform parameters, metrics, or toggle listing status visibilities</p>
    </div>
    <a href="post_job.php" class="header-action-btn">
        <i class="fas fa-plus"></i> Post a New Job
    </a>
</div>

<?php if ($alert_msg): ?>
    <div class="modern-alert">
        <i class="fas fa-circle-check" style="color: #38bdf8;"></i>
        <span><?php echo htmlspecialchars($alert_msg); ?></span>
    </div>
<?php endif; ?>

<div class="jobs-table-card">
    <?php if (!empty($posted_jobs)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Job Specifications</th>
                        <th>Type</th>
                        <th>Compensation Details</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posted_jobs as $job): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-primary); display:block; margin-bottom:0.2rem;">
                                    <?php echo htmlspecialchars($job['title']); ?>
                                </strong>
                                <span style="font-size:0.8rem; color: var(--text-muted);">
                                    <i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?> 
                                    &bull; <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo htmlspecialchars($job['job_type']); ?></span>
                            </td>
                            <td>
                                <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                    <span style="color: #38bdf8; font-weight: 500;">
                                        $<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size:0.85rem;">Not disclosed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $job['status']; ?>">
                                    <?php echo $job['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions-cluster">
                                    <a href="?archive=<?php echo $job['id']; ?>" class="ctrl-btn btn-archive" title="Toggle Active/Archive Status">
                                        <i class="fas fa-box-archive"></i>
                                    </a>
                                    <a href="?delete=<?php echo $job['id']; ?>" class="ctrl-btn btn-delete" onclick="return confirm('Are you sure you want to permanently delete this listing? All applicant links to it will break.');" title="Delete Listing">
                                        <i class="fas fa-trash-can"></i>
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
            <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
            <h3>No Jobs Posted Yet</h3>
            <p style="margin-bottom: 1.5rem; font-size:0.9rem; color: var(--text-muted);">You haven't added any listings to the database hub yet.</p>
            <a href="post_job.php" class="header-action-btn" style="box-shadow:none;">Create Your First Post</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>