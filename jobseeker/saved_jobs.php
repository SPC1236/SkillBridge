<?php
// saved_jobs.php
// Modernized Saved Jobs Dashboard Grid View - Light Blue Accent Scheme

$page_title = "Saved Jobs";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Start output buffering for dashboard layout structure
ob_start();

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];

// Handle asynchronous-like target deletion mechanics cleanly
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $job_id = $_GET['remove'];
    try {
        $delete_sql = "DELETE FROM saved_jobs WHERE user_id = :user_id AND job_id = :job_id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':user_id', $user_id);
        $delete_stmt->bindParam(':job_id', $job_id);
        $delete_stmt->execute();
        header('Location: saved_jobs.php?removed=1');
        exit;
    } catch(PDOException $e) {
        error_log("Error removing saved job: " . $e->getMessage());
    }
}

$saved_jobs = [];
$total_saved = 0;
try {
    // 1. Fetch Saved Jobs
    $sql_saved = "SELECT sj.job_id, j.title, j.company_name, j.location, j.job_type, j.salary_min, j.salary_max, j.skills_required, 'saved' as type 
                  FROM saved_jobs sj 
                  LEFT JOIN jobs j ON sj.job_id = j.id 
                  WHERE sj.user_id = :user_id";
    
    // 2. Fetch Applied Jobs
    $sql_applied = "SELECT ja.job_id, j.title, j.company_name, j.location, j.job_type, j.salary_min, j.salary_max, j.skills_required, 'applied' as type 
                    FROM job_applications ja 
                    LEFT JOIN jobs j ON ja.job_id = j.id 
                    WHERE ja.user_id = :user_id";

    $stmt = $conn->prepare($sql_saved . " UNION " . $sql_applied);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_items = count($jobs);
} catch(PDOException $e) {
    error_log("Error fetching jobs: " . $e->getMessage());
}

$success_message = '';
if (isset($_GET['removed'])) {
    $success_message = 'Job removed from saved list successfully!';
}
?>

<style>
    .dashboard-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.25rem;
    }

    .dashboard-view-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.85rem;
        font-weight: 700;
        margin: 0 0 0.35rem 0;
        color: var(--text-primary);
    }

    .dashboard-view-header p {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .header-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #38bdf8; /* Vibrant light blue / sky blue */
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
        background: #0ea5e9; /* Deepened sky blue */
        transform: translateY(-2px);
    }
    

    /* System Status Alerts styling upgrades - Sky Blue theme */
    .modern-alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(56, 189, 248, 0.06) !important;
        border: 1px solid rgba(56, 189, 248, 0.25) !important;
        border-left: 4px solid #38bdf8 !important;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .modern-alert i {
        color: #38bdf8;
        font-size: 1.1rem;
    }

    /* Analytical Overview Widget Metrics Row */
    .metric-overview-strip {
        margin-bottom: 2rem;
    }

    .metric-glass-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 1.25rem 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 1.25rem;
        min-width: 240px;
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        background: rgba(56, 189, 248, 0.1);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-icon-box i {
        color: #38bdf8;
        font-size: 1.25rem;
    }

    .metric-data-readout {
        display: flex;
        flex-direction: column;
    }

    .metric-val {
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .metric-lbl {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Modern Responsive Layout Display Pipeline */
    .saved-jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .modern-job-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: var(--transition-smooth);
    }

    .modern-job-card:hover {
        transform: translateY(-4px);
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2), 0 0 15px rgba(56, 189, 248, 0.03);
    }

    .card-top-deck {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        margin-bottom: 1.25rem;
    }

    .badge-status {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        background: rgba(56, 189, 248, 0.15);
        color: #38bdf8;
        margin-bottom: 0.5rem;
        display: inline-block;

    .badge-status {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        background: rgba(56, 189, 248, 0.15);
        color: #38bdf8;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    .dynamic-avatar-logo {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.15);
    }

    .deck-details-box {
        flex: 1;
        min-width: 0;
    }

    .deck-details-box h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .company-anchor-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-decoration: none;
    }

    .company-anchor-link i {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Core Dynamic Action Interface Buttons Layout */
    .trash-action-anchor {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15);
        width: 36px;
        height: 36px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: var(--transition-smooth);
        flex-shrink: 0;
    }

    .trash-action-anchor i {
        color: #ef4444;
        font-size: 0.95rem;
    }

    .trash-action-anchor:hover {
        background: #ef4444;
        border-color: #ef4444;
        transform: scale(1.05);
    }
    
    .trash-action-anchor:hover i {
        color: #ffffff;
    }

    /* Micro Meta Tags Array */
    .meta-tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px dashed var(--border-light);
    }

    .cloud-tag-item {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .cloud-tag-item i {
        color: #38bdf8;
        font-size: 0.85rem;
    }

    .cloud-tag-item.salary-highlight {
        color: #38bdf8;
        font-weight: 600;
    }

    /* Developer Skill badge pills array viewports */
    .skills-capsules-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1.5rem;
    }

    .skill-pill {
        background: rgba(56, 189, 248, 0.06);
        border: 1px solid rgba(56, 189, 248, 0.15);
        color: var(--text-secondary);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Structural Core Actions Footer Platform Row */
    .card-footer-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }

    .card-footer-actions .btn-workspace {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-lg);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition-smooth);
        cursor: pointer;
    }
    /* Ensure buttons match your color scheme */
.btn-view-details {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-primary);
}

.btn-trigger-apply {
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.2);
    color: #38bdf8;
}

.btn-trigger-apply:hover {
    background: #38bdf8;
    color: #ffffff;
}

    .btn-workspace.btn-view-details {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .btn-workspace.btn-view-details:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--text-secondary);
    }

    .btn-workspace.btn-trigger-apply {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: #38bdf8;
    }

    .btn-workspace.btn-trigger-apply:hover {
        background: #38bdf8;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);
    }

    /* Premium Clean Application Empty Engine System Template State */
    .empty-canvas-state {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        text-align: center;
        padding: 4.5rem 2rem;
        max-width: 500px;
        margin: 2rem auto;
    }

    .empty-canvas-state i {
        font-size: 3.5rem;
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        opacity: 0.4;
    }

    .empty-canvas-state h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        color: var(--text-primary);
    }

    .empty-canvas-state p {
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin: 0 0 1.75rem 0;
        line-height: 1.5;
    }

    /* Core Media Boundary Optimization adjustments queries */
    @media (max-width: 576px) {
        .dashboard-view-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .header-action-btn {
            width: 100%;
            justify-content: center;
        }
        .saved-jobs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-view-header">
    <div>
        <h1>My Career Dashboard</h1>
        <p>Manage your saved bookmarks and application status</p>
    </div>
</div>

<div class="saved-jobs-grid">
    <?php foreach ($jobs as $job): 
        $skills = !empty($job['skills_required']) ? array_map('trim', explode(',', $job['skills_required'])) : [];
    ?>
        <article class="modern-job-card">
            <div>
                <div class="card-top-deck">
                    <div class="dynamic-avatar-logo">
                        <?php echo htmlspecialchars(substr($job['company_name'] ?? 'C', 0, 1)); ?>
                    </div>
                    
                    <div class="deck-details-box">
                        <!-- ADDED BADGE HERE -->
                        <span class="badge-status"><?php echo ucfirst($job['type']); ?></span>
                        
                        <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                        <span class="company-anchor-link">
                            <i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?>
                        </span>
                    </div>
                </div>
                <!-- ... rest of your existing card HTML ... -->
                <div class="card-footer-actions">
    <!-- View Specs is always available -->
    <a href="job_details.php?id=<?php echo $job['job_id']; ?>" class="btn-workspace btn-view-details">
        <i class="fas fa-expand"></i> View Specs
    </a>
    
    <!-- Only show "Apply Now" if the job is just Saved, not already Applied -->
    <?php if ($job['type'] === 'saved'): ?>
        <a href="apply.php?id=<?php echo $job['job_id']; ?>" class="btn-workspace btn-trigger-apply">
            <i class="fas fa-paper-plane"></i> 
        </a>
    <?php else: ?>
        <!-- Optional: Show a disabled button or "Status" for applied jobs -->
        <button class="btn-workspace" disabled style="background:#f1f5f9; cursor:default;">
            <i class="fas fa-check"></i> Applied
        </button>
    <?php endif; ?>
</div>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>